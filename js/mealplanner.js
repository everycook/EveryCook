/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

var glob = glob || {};

jQuery(function($){
	var fixProzentValues_loop = false;
	var fixProzentValues_new = false;
	
	//Initialize Links
	function initSliders(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		var sliders = contentParent.find("input[type=range]");
		if (sliders.length > 0){
			if (sliders.get(0).type == 'text'){
				//browser don't know 'range' input type, do jQuery fallback.
				jQuery("input[type=range]").each(function(){
					var elem = jQuery(this);
					elem.hide();
					if (!elem.next().is('.jqui_slider')){
						jQuery('<div class="jqui_slider"></div>').insertAfter(elem);
					}
				});
				jQuery(".jqui_slider").slider({create: function( event, ui ) {
					var elem = jQuery(event.target);
					elem = elem.parent().parent().find('.input_range:first');
					elem.next().slider('value', elem.val());
				}});
			}
		}
	}
	
	
	$('#page').bind('newContent.mealplanner', function(e, type, contentParent) {
		initSliders(type, contentParent);
	});
	initSliders('initial', jQuery('#page'));
	
	
	function selectNearestMeal(){
		var currentDate = new Date();
		var day = ('0' + currentDate.getDate()).substr(-2);
		var month = ('0' + (currentDate.getMonth() + 1)).substr(-2);
		var year = currentDate.getFullYear();
		
		var hours = currentDate.getHours();
		var minutes = currentDate.getMinutes();
		
		var type;
		if (hours>=16 || hours<4){
			type=3;
		} else if (hours>=4 && hours<11){
			type=1;
		} else if (hours>=11 && hours<16){
			type=2;
		}
		jQuery('#Meals_MTY_ID :selected').removeAttr('selected');
		jQuery('#Meals_MTY_ID option[value=' + type + ']').attr('selected','selected');
		
		
		minutes = (Math.floor(minutes / 15)+1) * 15;
		if (minutes>=60){
			minutes = minutes - 60;
			hours++;
		}
		
		hours = ('0' + hours).substr(-2);
		minutes = ('0' + minutes).substr(-2);
		
		jQuery('#Meals_date').val(year + '-' + month + '-' + day);
		
		jQuery('#Meals_hour :selected').removeAttr('selected');
		jQuery('#Meals_hour option[value=' + hours + ']').attr('selected','selected');
		
		jQuery('#Meals_minute :selected').removeAttr('selected');
		jQuery('#Meals_minute option[value=' + minutes + ']').attr('selected','selected');
	}
	
	jQuery('body').undelegate('#NextMeal','click').delegate('#NextMeal','click',function(){
		var elem = jQuery(this);
		selectNearestMeal();
		jQuery('#peopleDetails').show();
		elem.parent().hide();
		
		updateMealOverview();
		jQuery('#backToCourses').show();
		jQuery('#gotoPeople').hide();
	});
	
	jQuery('body').undelegate('#OtherDay','click').delegate('#OtherDay','click',function(){
		var elem = jQuery(this);
		selectNearestMeal();
		jQuery('#mealOptions').show();
		elem.parent().hide();
	});
	
	jQuery('body').undelegate('#gotoPeople','click').delegate('#gotoPeople','click',function(){
		updateMealOverview();
		
		jQuery('#peopleDetails').show();
		jQuery('#mealOptions').hide();
		jQuery('#backToCourses').show();
		jQuery('#gotoPeople').hide();
	});
	
	jQuery('body').undelegate('#backToCourses','click').delegate('#backToCourses','click',function(){
		updateMealOverview();
		
		jQuery('#courseDetails').show();
		jQuery('#mealOptions').hide();
	});
	
	function updateMealOverview(){
		var mealOverview = jQuery('.mealOverview div');
		jQuery(mealOverview[0]).text(jQuery('#Meals_MTY_ID :selected').text());
		jQuery(mealOverview[1]).text(jQuery('#Meals_date').val());
		jQuery(mealOverview[2]).text(jQuery('#Meals_hour :selected').val() + ':' + jQuery('#Meals_minute :selected').val());
	}
	
	jQuery('body').undelegate('#meals-form .mealOverview .button','click').delegate('#meals-form .mealOverview .button','click',function(){
		jQuery('#mealOptions').show();
		jQuery('#courseDetails').hide();
	});
	
	jQuery('body').undelegate('#cancelPeople','click').delegate('#cancelPeople','click',function(){
		jQuery.fancybox.close();
	});
	
	jQuery('body').undelegate('#useForCourse','click').delegate('#useForCourse','click',function(){
		var elem = jQuery('.activeFancyField');
		var courseSettings;
		if (elem.length != 0){
			courseSettings = elem.parents('.meal_course:first');
		} else {
			courseSettings = jQuery('.meal_course:first');
		}
		calculatePeopleValues(courseSettings);
		
		jQuery.fancybox.close();
	});
	
	jQuery('body').undelegate('#gotoCourses','click').delegate('#gotoCourses','click',function(){
		jQuery('#cancelPeople').show();
		jQuery('#useForCourse').show();
		jQuery('#gotoCourses').hide();	
		
		var courseSettings = jQuery('.meal_course');
		calculatePeopleValues(courseSettings);
		
		jQuery('#courseDetails').show();
		jQuery('#peopleDetails').hide();
	});
	
	function calculatePeopleValues(courseSettings){
		var eat_people = '';
		var kcal_day_total = 0;
		var adult = 0;
		var child = 0;
		
		var json = '[';
		
		var peopleRows = jQuery('#peopleDetailsContent .people .addRowContainer tr:not(#newLine)');
		peopleRows.each(function(index){
			var elem = jQuery(this);
			if (index != 0){
				eat_people += ';';
			}
			var amount = parseInt(elem.find('[id$=amount]').val());
			if (isNaN(amount)){
				amount = 0;
			}
			var gender = elem.find('[id$=gender]').val();
			var gda = elem.find('[id$=gda_id_kcal_GDA_' + gender + '] option:selected');
			var gda_index = gda.index();
			if (gda_index > 6){
				adult += amount;
			} else {
				child += amount;
			}
			gda = gda.val();
			var pos = gda.indexOf('_');
			var gdaid = gda.substr(1,pos-1);
			var gdaval = gda.substr(pos+1);
			kcal_day_total += amount*gdaval;
			eat_people += amount + 'x' + gender + ':' + gda;
			if (json != '['){
				json += ',';
			}
			json += '{"amount":"' + amount + '","gender":"' + gender + '","gda_id_kcal":"' + gda + '","gda_id":"' + gdaid + '","kcal":"' + gdaval + '"}';
		});
		json += ']';
		
		courseSettings.find('[id$=MTC_EAT_PERS]').val(eat_people);
		courseSettings.find('[id$=MTC_KCAL_DAY_TOTAL]').val(kcal_day_total);
		courseSettings.find('[id$=MTC_EAT_ADULTS]').val(adult);
		courseSettings.find('[id$=MTC_EAT_CHILDREN]').val(child);
		
		var eatingText_both = '%d Adults + %d Child eating';
		var eatingText_adult = '%d Adults eating';
		var eatingText_child = '%d Child eating';
		
		if (child>0){
			if (adult>0){
				eatingText = eatingText_both;
				var pos = eatingText.indexOf('%d');
				var pos2 = eatingText.indexOf('%d', pos+2);
				eatingText = eatingText.substr(0,pos-1) + adult + eatingText.substr(pos+2,pos2-pos-2) + child +  eatingText.substr(pos2+2,eatingText.length-pos2-2);
			} else {
				eatingText = eatingText_child;
				var pos = eatingText.indexOf('%d');
				eatingText = eatingText.substr(0,pos-1) + child +  eatingText.substr(pos+2,eatingText.length-pos-2);
			}
		} else {
			eatingText = eatingText_adult;
			var pos = eatingText.indexOf('%d');
			eatingText = eatingText.substr(0,pos-1) + adult +  eatingText.substr(pos+2,eatingText.length-pos-2);
		}
		
		courseSettings.find('.PeopleSelect').text(eatingText);
	}
	
	jQuery('body').undelegate('.PeopleSelect','click').delegate('.PeopleSelect','click',function(){
		var elem = jQuery(this);
		jQuery('.activeFancyField').removeClass('activeFancyField');
		elem.siblings('input.fancyValue:first').addClass('activeFancyField');
		
		var courseSettings = elem.parents('.meal_course:first');
		var eat_people = courseSettings.find('[id$=MTC_EAT_PERS]').val();
		var rowsJSON = eatPeopleToJSON(eat_people);
		
		var container = jQuery('.people .addRowContainer');
		glob.rowContainer.clear(container);
		glob.rowContainer.MealplannerPeopleInit(container, rowsJSON, '[]');
		
		jQuery.fancybox({
			'href':elem.attr('href'),
			'autoScale':true,
			'autoDimensions':true,
			'centerOnScroll':true,
			'onComplete': function(){
				jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
			}
		});
		return false;
	});
	
	function eatPeopleToJSON(eat_people){
		var values = eat_people.split(';');
		var json = '[';
		if (eat_people != ''){
			for(var i=0; i<values.length; ++i){
				parts = values[i].split('x',2);
				var amount = parts[0];
				parts = parts[1].split(':',2);
				var gender = parts[0];
				var gda = parts[1];
				parts = gda.split('_',2);
				var gdaid = parts[0];
				var gdaval = parts[1];
				
				if (json != '['){
					json += ',';
				}
				json += '{"amount":"' + amount + '","gender":"' + gender + '","gda_id_kcal":"' + gda + '","gda_id":"' + gdaid + '","kcal":"' + gdaval + '"}';
			}
		}
		json += ']';
		return json;
	}
	
	jQuery('body').undelegate('.RecipeRemove','click').delegate('.RecipeRemove','click',function(){
		var elem = jQuery(this);
		jQuery('.activeFancyField').removeClass('activeFancyField');
		elem.siblings('input.fancyValue:first').addClass('activeFancyField');
		
		var recipes = elem.parents('.cou_recipes:first').find('div.cou_recipe .title');
		var select = jQuery('#removeRecipeContent select');
		select.find('option').remove();
		for(var i=0;i<recipes.length; ++i){
			var option = '<option value="' + i + '">' + jQuery(recipes.get(i)).text() + '</option>';
			select.append(jQuery(option));
		}
		
		jQuery.fancybox({
			'href':elem.attr('href'),
			'autoScale':true,
			'autoDimensions':true,
			'centerOnScroll':true,
			'onComplete': function(){
				jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
			}
		});
		return false;
	});
	
	
	jQuery('body').undelegate('#cancelDelete','click').delegate('#cancelDelete','click',function(){
		jQuery.fancybox.close();
	});
	
	jQuery('body').undelegate('#deleteRecipe','click').delegate('#deleteRecipe','click',function(){
		var elem = jQuery('.activeFancyField');
		var course;
		if (elem.length != 0){
			course = elem.parents('.cou_recipes:first');
		} else {
			course = jQuery('.cou_recipes:first');
		}
		var recipes = course.find('div.cou_recipe');
		var indexToDelete = jQuery(this).parent().parent().find('select :selected').val();
		if (indexToDelete != ''){
			if (recipes.length > indexToDelete){
				jQuery(recipes.get(indexToDelete)).remove();
				
				if(course.find('div.cou_recipe').length > 0){
					var ranges = course.find('.input_range');
					fixProzentValues(null, ranges);
				} else {
					var courses = course.parents('.meal_courses:first');
					course.parent().remove();
					
					var ranges = courses.find('input[id$=_MTC_PERC_MEAL]');
					fixProzentValues(null, ranges);
				}
			}
		}
		
		jQuery.fancybox.close();
	});
	
	
	jQuery('body').undelegate('#useForCourse','click').delegate('#useForCourse','click',function(){
		var elem = jQuery('.activeFancyField');
		var courseSettings;
		if (elem.length != 0){
			courseSettings = elem.parents('.meal_course:first');
		} else {
			courseSettings = jQuery('.meal_course:first');
		}
		calculatePeopleValues(courseSettings);
		
		jQuery.fancybox.close();
	});
	
	
	
	
	
	jQuery('body').undelegate('.mealView input[id$=_MEA_PERC_GDA]','change').delegate('.mealView input[id$=_MEA_PERC_GDA]','change',function(){
		var elem = jQuery(this);
		elem.parent().parent().find('.value:first').text(elem.val());
	});
	
	jQuery('body').undelegate('.cou_recipes [type=range]','change').delegate('.cou_recipes [type=range]','change',function(){
		var elem = jQuery(this);
		elem.parent().parent().find('.value:first').text(elem.val());
		
		var ranges = elem.parents('.cou_recipes:first').find('.input_range');
		fixProzentValues(elem, ranges);
	});
	
	jQuery('body').undelegate('.cou_recipes .jqui_slider','slide').delegate('.cou_recipes .jqui_slider','slide',function(event, ui){
		var elem = jQuery(ui.handle);
		elem = elem.parent().parent().find('.input_range:first');
		elem.val(ui.value);
		elem.parent().parent().find('.value:first').text(ui.value);
		
		var ranges = elem.parents('.cou_recipes:first').find('.input_range');
		fixProzentValues(elem, ranges);
	});
	
	jQuery('body').undelegate('.cou_recipes .jqui_slider','slidestop').delegate('.cou_recipes .jqui_slider','slidestop',function(event, ui){
		var elem = jQuery(ui.handle);
		elem = elem.parent().parent().find('.input_range:first');
		var ranges = elem.parents('.cou_recipes:first').find('.input_range');
		if (ranges.length == 1 && ui.value != 100){
			elem.next().slider('value', 100);
		}
	});
	
	jQuery('body').undelegate('.meal_courses input[id$=_MTC_PERC_MEAL]','change').delegate('.meal_courses input[id$=_MTC_PERC_MEAL]','change',function(){
		var elem = jQuery(this);
		elem.parent().parent().find('.value:first').text(elem.val());
		
		var ranges = elem.parents('.meal_courses:first').find('input[id$=_MTC_PERC_MEAL]');
		fixProzentValues(elem, ranges);
	});
	
	jQuery('body').undelegate('.meal_courses .cou_header .jqui_slider','slide').delegate('.meal_courses .cou_header .jqui_slider','slide',function(event, ui){
		var elem = jQuery(ui.handle);
		elem = elem.parent().parent().find('.input_range:first');
		elem.val(ui.value);
		elem.parent().parent().find('.value:first').text(ui.value);
		
		var ranges = elem.parents('.meal_courses:first').find('input[id$=_MTC_PERC_MEAL]');
		fixProzentValues(elem, ranges);
	});
	
	jQuery('body').undelegate('.meal_courses .cou_header .jqui_slider','slidestop').delegate('.meal_courses .cou_header .jqui_slider','slidestop',function(event, ui){
		var elem = jQuery(ui.handle);
		elem = elem.parent().parent().find('.input_range:first');
		var ranges = elem.parents('.meal_courses:first').find('input[id$=_MTC_PERC_MEAL]');
		if (ranges.length == 1 && ui.value != 100){
			elem.next().slider('value', 100);
		}
	});
	
	function updateValues(fields, amount){
		var fieldAmounts = fields.length;
		do {
			var fieldsNew = fields;
			tooMany=0;
			for (var i=0; i<fields.length; ++i){
				var changeElem = jQuery(fields[i]);
				var value = parseInt(changeElem.val())
				value+= amount;
				if (value<0){
					tooMany+=value;
					value = 0;
					fieldAmounts--;
					fieldsNew = fieldsNew.not(changeElem);
				} else if (value>100){
					tooMany+=value-100;
					value = 100;
					fieldAmounts--;
					fieldsNew = fieldsNew.not(changeElem);
				}
				changeElem.attr('value',value);
				changeElem.parent().parent().find('.value:first').text(value);
			}
			if (fieldAmounts>0){
				amount = tooMany / fieldAmounts;
				fields = fieldsNew;
			}
		} while (tooMany!=0 && fieldAmounts>0);
	}
	
	function fixProzentValues(elem, ranges){
		if (fixProzentValues_loop) return;
		var amount = ranges.length;
		if (amount == 0) return;
		if (amount == 1){
			if (elem == null){
				elem = jQuery(ranges.get(0));
			}
			if (elem.val() != 100){
				elem.attr('value',100);
				/*
				if (elem.is(':hidden') && elem.next().is('.jslider')){
					elem.slider('value', 100);
				}
				*/
				if (elem.is(':hidden') && elem.next().is('.jqui_slider')){
					elem.slider('value', 100);
					elem.next().slider('value', 100);
				}
				elem.parent().parent().find('.value:first').text(100);
			}
			return;
		}
		var sum=0;
		for (var i=0; i<amount; ++i){
			sum += parseInt(jQuery(ranges[i]).val());
		}
		if (sum>100){
			var avg = sum / amount;
			if (avg == 100){
				var value = Math.round(100 / amount);
				for (var i=0; i<amount; ++i){
					var changeElem = jQuery(ranges[i]);
					changeElem.attr('value',value);
					changeElem.parent().parent().find('.value:first').text(changeElem.val());
				}
			} else if (fixProzentValues_new && elem != null && elem.val() == 100 && ranges.index(elem) == amount-1){
				var newValue = Math.round(100 / amount);
				elem.val(newValue);
				elem.parent().parent().find('.value:first').text(newValue);
				sum = sum-100+newValue;
				var tooMany = -(sum-100);
				
				var substractRanges = ranges.not(elem);
				var fieldAmounts = substractRanges.length;
				updateValues(substractRanges, Math.round(tooMany / fieldAmounts));
			} else {
				var tooMany = -(sum-100);
				var substractRanges = ranges.not(elem);
				var fieldAmounts = substractRanges.length;
				updateValues(substractRanges, Math.round(tooMany / fieldAmounts));
			}
		} else if (sum<100){
			var rest = 100-sum;
			var addRanges = ranges.not(elem);
			var fieldAmounts = addRanges.length;
			updateValues(addRanges, Math.round(rest / fieldAmounts));
		}
		fixProzentValues_loop = true;
		if (ranges.get(0).type == 'text'){
			ranges.each(function(){
				var elem = jQuery(this);
				elem.parent().find('.jqui_slider').slider('value', elem.val());
			});
		}
		fixProzentValues_loop = false;
	}
	
	
	
	
	
	jQuery('body').undelegate('.fancyForm .button.RecipeSelect','click').delegate('.fancyForm .button.RecipeSelect','click', function(){
		var caller = jQuery(this);
		var elem = jQuery('.activeFancyField');
		if (elem.length == 0){
			elem = jQuery('.fancyChoose.RecipeSelect').siblings('input.fancyValue');
		}
		var recipeId = jQuery(caller).attr('href');
		var recipeName = jQuery(caller).parent().find('.name').text().trim();
		var recipePic = jQuery(caller).parent().find('.recipe');
		var recipePicUrl = recipePic.attr('src');
		var recipePicAuthor = recipePic.attr('title');
		
		var courseIndex = elem.parent().parent().parent().index();
		var recipeIndex = elem.parent().index();
		var newRecipe = jQuery('<div class="cou_recipe"><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_course_couToRecs_' + recipeIndex + '_recipe_REC_ID" name="Meals[meaToCous][' + courseIndex + '][course][couToRecs][' + recipeIndex + '][REC_ID]" value="' + recipeId + '"><img src="' + recipePicUrl + '" title="' + recipePicAuthor + '" alt="" class="cou_recipe"><br><span class="title">' + recipeName + '</span><br><div class="slider_holder"><input type="range" id="Meals_meaToCous_' + courseIndex + '_course_couToRecs_' + recipeIndex + '_CTR_REC_PROC" name="Meals[meaToCous][' + courseIndex + '][course][couToRecs][' + recipeIndex + '][CTR_REC_PROC]" value="100" class="input_range" max="100" min="0"></div><span class="prozValue"><span class="value">100</span>%</span></div>');
		newRecipe.insertBefore(elem.parent());
		
		fixProzentValues_new = true;
		initSliders('html', newRecipe);
		newRecipe.find('.input_range').change();
		fixProzentValues_new = false;
		
		jQuery.fancybox.close();
		return false;
	});
	
	jQuery('body').undelegate('#addCourse','click').delegate('#addCourse','click', function(){
		var elem = jQuery(this);
		var courseIndex = elem.index();
		var addRecipeText = glob.trans.MEALPLANNER_ADD_RECIPE; //'Add recipe';
		var gdaMealText = glob.trans.MEALPLANNER_COURSE_GDA; //'<span class="value">100</span>% meal GDA.';
		//printf($this->trans->MEALPLANNER_COURSE_GDA,'<span class="value">' . $meaToCou->MTC_PERC_MEAL . '</span>');
		var peopleEatingText = glob.trans.MEALPLANNER_EATING_PEOPLE_ADULT; //'0 adults eating';
		var courseNameText = 'Course Description';
		var removeRecipeText = glob.trans.MEALPLANNER_REMOVE_RECIPE;//'Remove Recipe';
		//' + courseNameText + ': <input type="text" id="Meals_meaToCous_' + courseIndex + '_course_COU_DESC" name="Meals[meaToCous][' + courseIndex + '][course][COU_DESC]" value="" style="width: 20em;"><br>
		var newCourse = jQuery('<div class="meal_course"><div class="cou_header"><div class="slider_holder"><input type="range" id="Meals_meaToCous_' + courseIndex + '_MTC_PERC_MEAL" name="Meals[meaToCous][' + courseIndex + '][MTC_PERC_MEAL]" value="100" class="input_range" max="100" min="0"></div><span class="prozValue">' + gdaMealText + '</span><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_course_COU_ID" name="Meals[meaToCous][' + courseIndex + '][course][COU_ID]" value=""><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_MTC_EAT_PERS" name="Meals[meaToCous][' + courseIndex + '][MTC_EAT_PERS]" value="1xF:15_2000"><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_MTC_KCAL_DAY_TOTAL" name="Meals[meaToCous][' + courseIndex + '][MTC_KCAL_DAY_TOTAL]" value="0"><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_MTC_EAT_ADULTS" name="Meals[meaToCous][' + courseIndex + '][MTC_EAT_ADULTS]" value="0"><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_MTC_EAT_CHILDREN" name="Meals[meaToCous][' + courseIndex + '][MTC_EAT_CHILDREN]" value="0"></div><div class="cou_recipes"><div style="display: table-cell; vertical-align: top;"><a href="#peopleDetailsContent" class="button PeopleSelect bbq-current">' + peopleEatingText + '</a><a href="' + glob.prefix + 'recipes/chooseRecipe" class="button fancyChoose RecipeSelect">' + addRecipeText + '</a><a href="#removeRecipeContent" class="button RecipeRemove">' + removeRecipeText + '</a><input type="hidden" class="fancyValue"></div></div></div>');
		newCourse.insertBefore(elem);
		
		fixProzentValues_new = true;
		initSliders('html', newCourse);
		newCourse.find('.input_range').change();
		fixProzentValues_new = false;
		
		newCourse.find('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input.fancyValue:first').addClass('activeFancyField');
		});
		newCourse.find('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
		
		//Load people data from previous course:
		var prevCourse = newCourse.prev();
		if (prevCourse.length > 0){
			newCourse.find('[id$=MTC_EAT_PERS]').val(prevCourse.find('[id$=MTC_EAT_PERS]').val());
			newCourse.find('[id$=MTC_KCAL_DAY_TOTAL]').val(prevCourse.find('[id$=MTC_KCAL_DAY_TOTAL]').val());
			newCourse.find('[id$=MTC_EAT_ADULTS]').val(prevCourse.find('[id$=MTC_EAT_ADULTS]').val());
			newCourse.find('[id$=MTC_EAT_CHILDREN]').val(prevCourse.find('[id$=MTC_EAT_CHILDREN]').val());
			newCourse.find('.PeopleSelect').text(prevCourse.find('.PeopleSelect').text());
		}
		
		return false;
	});
	
});