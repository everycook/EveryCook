var glob = glob || {};

jQuery(function($){
	//Initialize Links
	function initMealplaner(){
	/*
		jQuery("#MeaToCou_MTC_PERC_GDA").slider({
			from: 0, 
			to: 100, 
			step: 1,
			dimension: '%',
			skin: 'plastic'
		});
	*/
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initMealplaner();
	});
	initMealplaner();
	
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
		jQuery('#Meals_MEA_TYPE :selected').removeAttr('selected');
		jQuery('#Meals_MEA_TYPE option[value=' + type + ']').attr('selected','selected');
		
		
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
	});
	
	jQuery('body').undelegate('#OtherDay','click').delegate('#OtherDay','click',function(){
		var elem = jQuery(this);
		selectNearestMeal();
		jQuery('#mealOptions').show();
		elem.parent().hide();
	});
	
	jQuery('body').undelegate('#gotoPeople','click').delegate('#gotoPeople','click',function(){
		jQuery('#peopleDetails').show();
		jQuery('#mealOptions').hide();
	});
	
	jQuery('body').undelegate('#gotoCourses','click').delegate('#gotoCourses','click',function(){
		var mealOverview = jQuery('.mealOverview div');
		jQuery(mealOverview[0]).text(jQuery('#Meals_MEA_TYPE :selected').text());
		jQuery(mealOverview[1]).text(jQuery('#Meals_date').val());
		jQuery(mealOverview[2]).text(jQuery('#Meals_hour :selected').val() + ':' + jQuery('#Meals_minute :selected').val());
		
		var eat_person = '';
		var kcal_day_total = 0;
		
		var peopleRows = jQuery('#peopleDetails .people .addRowContainer tr:not(#newLine)');
		peopleRows.each(function(index){
			var elem = jQuery(this);
			if (index != 0){
				eat_person += ';';
			}
			var amount = elem.find('[id$=amount]').val();
			var gender = elem.find('[id$=gender]').val();
			var gda = elem.find('[id$=gda_id_kcal_GDA_' + gender + '] option:selected').val();
			var pos = gda.indexOf('_');
			var gdaval = gda.substr(pos+1);
			kcal_day_total += amount*gdaval;
			eat_person += amount + 'x' + gender + ':' + gda;
		});
		
		var mealOverview = jQuery('.meal_courses');
		mealOverview.find('[id$=MTC_EAT_PERS]').val(eat_person);
		mealOverview.find('[id$=MTC_KCAL_DAY_TOTAL]').val(kcal_day_total);
		
		jQuery('#courseDetails').show();
		jQuery('#peopleDetails').hide();
	});
	
	jQuery('body').undelegate('.mealView input[id$=_MEA_PERC_GDA]','change').delegate('.mealView input[id$=_MEA_PERC_GDA]','change',function(){
		var elem = jQuery(this);
		elem.parent().find('.value:first').text(elem.val());
	});
	
	jQuery('body').undelegate('.cou_recipes [type=range]','change').delegate('.cou_recipes [type=range]','change',function(){
		var elem = jQuery(this);
		elem.parent().find('.value:first').text(elem.val());
		
		var ranges = elem.parents('.cou_recipes:first').find('.input_range');
		fixProzentValues(elem, ranges);
	});
	
	jQuery('body').undelegate('.meal_course input[id$=_MTC_PERC_MEAL]','change').delegate('.meal_course input[id$=_MTC_PERC_MEAL]','change',function(){
		var elem = jQuery(this);
		elem.parent().find('.value:first').text(elem.val());
		
		var ranges = elem.parents('.meal_courses:first').find('input[id$=_MTC_PERC_MEAL]');
		fixProzentValues(elem, ranges);
	});
	
	function fixProzentValues(elem, ranges){
		var amount = ranges.length;
		if (amount == 1){
			if (elem.val() != 100){
				elem.val(100);
				elem.parent().find('.value:first').text(100);
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
					changeElem.val(value);
					changeElem.parent().find('.value:first').text(changeElem.val());
				}
			} else if (elem.val() == 100){
				var newValue = Math.round(100 / amount);
				elem.val(newValue);
				elem.parent().find('.value:first').text(newValue);
				sum = sum-100+newValue;
				var tooMany = sum-100;
				var substract_each = Math.round(tooMany / (amount-1));
				for (var i=0; i<amount; ++i){
					var changeElem = jQuery(ranges[i]);
					if (ranges[i] != elem.get(0)){
						changeElem.val(parseInt(changeElem.val())-substract_each);
						changeElem.parent().find('.value:first').text(changeElem.val());
					}
				}
			} else {
				var tooMany = sum-100;
				var substract_each = Math.round(tooMany / (amount-1));
				for (var i=0; i<amount; ++i){
					var changeElem = jQuery(ranges[i]);
					if (ranges[i] != elem.get(0)){
						changeElem.val(parseInt(changeElem.val())-substract_each);
						changeElem.parent().find('.value:first').text(changeElem.val());
					}
				}
			}
		} else if (sum<100){
			var rest = 100-sum;
			var rest_each = Math.round(rest / (amount-1));
			for (var i=0; i<amount; ++i){
				var changeElem = jQuery(ranges[i]);
				if (ranges[i] != elem.get(0)){
					changeElem.val(parseInt(changeElem.val())+rest_each);
					changeElem.parent().find('.value:first').text(changeElem.val());
				}
			}
		}
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
		var newRecipe = jQuery('<div class="cou_recipe"><input type="hidden" id="Meals_meaToCous_' + courseIndex + '_course_couToRecs_' + recipeIndex + '_recipe_REC_ID" name="Meals[meaToCous][' + courseIndex + '][course][couToRecs][' + recipeIndex + '][REC_ID]" value="' + recipeId + '"><img src="' + recipePicUrl + '" title="' + recipePicAuthor + '" alt="" class="cou_recipe"><br><span class="title">' + recipeName + '</span><br><input type="range" id="Meals_meaToCous_' + courseIndex + '_course_couToRecs_' + recipeIndex + '_CTR_REC_PROC" name="Meals[meaToCous][' + courseIndex + '][course][couToRecs][' + recipeIndex + '][CTR_REC_PROC]" value="100" class="input_range" max="100" min="0"><span class="value">100</span>%</div>');
		newRecipe.insertBefore(elem.parent());
		
		jQuery.fancybox.close();
		newRecipe.find('.input_range').change();
		return false;
	});
	
	jQuery('body').undelegate('#addCourse','click').delegate('#addCourse','click', function(){
		var elem = jQuery(this);
		var courseIndex = elem.index();
		var addRecipeText = 'Rezept Hinzufügen';
		var newCourse = jQuery('<div class="meal_course"><div><input type="range" id="Meals_meaToCous_' + courseIndex + '_MTC_PERC_MEAL" name="Meals[meaToCous][' + courseIndex + '][MTC_PERC_MEAL]" value="100" class="input_range" max="100" min="0"><span class="value">100</span>% der GDA der Mahlzeit.<input type="hidden" id="Meals_meaToCous_' + courseIndex + '_course_COU_ID" name="Meals[meaToCous][' + courseIndex + '][course][COU_ID]" value=""></div><div class="cou_recipes"><div style="display: table-cell; vertical-align: top;"><a href="/EveryCook/recipes/chooseRecipe" class="button fancyChoose RecipeSelect">' + addRecipeText + '</a><input type="hidden" class="fancyValue"></div></div></div>');
		newCourse.insertBefore(elem);
		newCourse.find('.input_range').change();
		
		newCourse.find('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input.fancyValue:first').addClass('activeFancyField');
		});
		newCourse.find('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
		
		return false;
	});
	
});