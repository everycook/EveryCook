/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

var glob = glob || {};

glob.recipeCreator = {};

jQuery(function($){
	
	var fieldToCssClass = $([]);
	var currentStep = $([]);
	var currentStepIndexField = $([]);
	var currentStepJsonField = $([]);
	var currentStepJsonValues = {};
	var ingredientsList = {};
	var ingredientsAuthList = {};
	var currentIngredientField = $([]);
	var currentIngredientCooseLink = $([]);
	var currentIngredientCooseLinkDefaultText = '';
	var currentIngredientImg = $([]);
	var currentIngredientAmountField = $([]);
	var currentIngredientAmountParam = $([]);
	var suggestionList = $([]);
	var actionList = $([]);
	var globalParamDefaults = {'STE_CELSIUS':0, 'STE_KPA':0, 'STE_RPM':0};
	
	var selecting = false;
	var isStepAdding = false;
	var ingredientDragHover = $([]);

	//##################################### init / drag, drop, sort #####################################
	function initIngredientDraggable(ingredients){
		ingredients.draggable({
			appendTo: '#recipeCreator',
			helper: 'clone',
			revert: 'invalid',
			scope: 'ingredient',
			cursor: 'crosshair',
			//opacity: 0.35,
		});
	}
	
	function initStepDroppable(steps){
		steps.droppable({
			tolerance: 'pointer',
			hoverClass: 'drop-hover',
			scope: 'ingredient',
			drop: function(event, ui) {
				var elem = $(ui.draggable);
				var ing_id = elem.find('[id$="_ING_ID"]').val();
				var stepElem = $(this);
				stepElem.click();
				
				currentIngredientField.val(ing_id);
				changeValue(currentIngredientField, ing_id, ingredientsList[ing_id]);
				currentIngredientCooseLink.text(ingredientsList[ing_id]);
				
				if (currentIngredientAmountParam.is(':visible')){
					var amount = parseInt(elem.find('.amount').text());
					if (isNaN(amount)){
						amount = 0;
					}
					//if (!isNaN(amount)){
						currentIngredientAmountField.val(amount);
						currentIngredientAmountField.change();
					//}
				}
				elem.click();
			},
			over: function( event, ui ) {
				ingredientDragHover = ui.helper;
				ingredientDragHover.addClass('dropOK');
			},
			out: function( event, ui ) {
				ingredientDragHover.removeClass('dropOK');
			},
		});
	}

	function initRemoveDroppable(recycleBin){
		recycleBin.droppable({
			tolerance: 'pointer',
			hoverClass: 'drop-hover',
			scope: 'step',
			drop: function(event, ui) {
				var elem = $(ui.draggable);
				var index = elem.index();
				var parent = elem.parent();
				elem.remove();
				stepRemoved(parent, index);
			},
			over: function( event, ui ) {
				ui.draggable.addClass('recycle');
			},
			out: function( event, ui ) {
				ui.draggable.removeClass('recycle');
			},
		});
	}
	
	function initStepSortable(stepList){
		stepList.sortable({
			//items: '> .step',
			axis: 'y',
			placeholder: 'addstep',
			scope: 'step',
			activate: function( event, ui ) {
				if (ui.item.is('.step')){
					$('.recycleBin').show();
				}
			},
			deactivate: function( event, ui ) {
				$('.recycleBin').hide();
			},
			stop: function( event, ui ) {
				var sender = ui.sender;
				var item = $(ui.item);
				if(jQuery.contains(document, item[0])){
					var newIndex = item.index();
					if (item.hasClass('action')){
						var id = item.data('id');
						var actionValues = {};
						try {
							if (typeof(item.data('json')) === 'string'){
								actionValues = $.parseJSON(item.data('json'));
							} else if (item.data('json')){
								actionValues = item.data('json');
							}
						} catch(ex){}
						var newItem = createStepFormActionId(id, newIndex, actionValues);
						
						newItem.insertAfter(item);
						item.remove();
						initStepDroppable(newItem.has('.ingredient'));
						newItem.addClass('needSave');
						stepAdded(newItem, newIndex);
					} else {
						var stepNo = item.find('.stepNo');
						var oldIndex = parseInt(stepNo.text());
						stepNo.text(newIndex+1);
						stepMoved(item, newIndex, oldIndex-1);
					}
				//} else {
				//	//removed by recycleBin drop point
				}
			},
		});
	}

	function initActionDraggable(actions){
		actions.draggable({
			connectToSortable: "#stepList .items",
			//appendTo: "#stepList",
			//helper: "clone",
			helper: function(event){
				var id = $(event.currentTarget).data('id');
				return $('<div class="step"><span class="stepNo">?</span> <span class="actionText">' + $(event.currentTarget).text() + '</span></div>'); 
			},
			revert: "invalid"
		});
		actions.disableSelection();
	}
	
	glob.recipeCreator.init = function(){
		fieldToCssClass = $.parseJSON($('#fieldToCssJSON').val());
		currentStepIndexField = $('#Steps_STE_STEP_NO');
		ingredientsList = $.parseJSON($('#ingredientsJSON').val());
		currentIngredientField =  $('#Steps_ING_ID');
		currentIngredientCooseLink =  $('#Steps_ING_ID_DESC');
		currentIngredientCooseLinkDefaultText = currentIngredientCooseLink.text();
		currentIngredientImg =  $('#Steps_ING_ID_IMG');
		currentIngredientAmountField =  $('#Steps_STE_GRAMS');
		currentIngredientAmountParam = $('#param_STE_GRAMS');
		suggestionList = $('.actionListList:first');
		actionList = $('#actionList');

		initStepSortable($('#stepList .items'));
		//$('.placeholderStep').remove(); 
		$('.step').disableSelection();
		initActionDraggable($('#actionList > .actionListList > .action'));
		$('#propertyList').find('.param').hide();

		var inputs = $('#propertyList').find('.dataField');
		inputs.each(function (){
			var elem = $(this);
			elem.attr('data-defaultValue', elem.get(0).defaultValue);
		});
		$('.step:first').click();
		
		initIngredientDraggable($('.ingredientList .ingredientEntry:not(.newEntry):not(#ingNew)'));
		initStepDroppable($('.step:has(.ingredient)'));
		
		initRemoveDroppable($('.recycleBin'));
		$('#recipeCreator').addClass('initialized');
	}

	//##################################### Recipe logic #####################################
	
	jQuery('body').undelegate('.recipeDetailsTitle','click').delegate('.recipeDetailsTitle','click', function(){
		jQuery(this).siblings('.recipeDetails').toggle();
		return true;
	});
	
	function updateCusineValues(field, id, idName, url){
		var subValues = field.data(idName);
		if (subValues == id){
			if(field.children(':not(:first)').length>0){
				field.show();
			}
		} else {
			glob.ShowActivity = false;
			jQuery.ajax({
				'type':'get',
				'url':url + '?' + idName + '=' + id,
				'dataType': 'json',
				'async':true,
				'cache':true, //TODO cache cusine sub type response?
				'success':function(result){
					field.val('');
					field.children(':not(:first)').remove();
					if (Object.keys(result).length>0){
						for (var key in result){
							field.append($('<option value="' + key + '">' + result[key] + '</option>'));
						}
						field.show();
						field.focus();
					} else {
						field.hide();
					}
					field.data(idName, id);
				},
				'error':function(xhr){
					//alert('error');
				},
			});
			glob.ShowActivity = true;
		}
	}
	
	jQuery('body').undelegate('#Recipes_CUT_ID','change').delegate('#Recipes_CUT_ID','change', function(){
		var elem = jQuery(this);
		var cut_id = elem.val();
		if (cut_id != ''){
			updateCusineValues($('#Recipes_CST_ID'), cut_id, 'cut_id', $('#cusineSubTypeLink').val());
			$('#Recipes_CSS_ID').val('').hide();
		} else {
			$('#Recipes_CST_ID').val('').hide();
			$('#Recipes_CSS_ID').val('').hide();
		}
		return true;
	});

	jQuery('body').undelegate('#Recipes_CST_ID','change').delegate('#Recipes_CST_ID','change', function(){
		var elem = jQuery(this);
		var cut_id = elem.val();
		if (cut_id != ''){
			updateCusineValues($('#Recipes_CSS_ID'), cut_id, 'cst_id', $('#cusineSubSubTypeLink').val());
		} else {
			$('#Recipes_CSS_ID').val('').hide();
		}
		return true;
	});	

	//##################################### Ingredient logic #####################################
	jQuery('body').undelegate('#recipes-form .ingredientList .ingredientEntry','click').delegate('#recipes-form .ingredientList .ingredientEntry','click',function(){
		var elem = $(this);
		var ing_id = elem.find('[id$="_ING_ID"]').val()
		if (ing_id){
			$('.ingredientList .selected').removeClass('selected');
			elem.addClass('selected');
			updateActionSuggestion(ing_id);
		}
	});

	function addIngredient(ing_id, name, img_auth){
		var insertPos = $('.ingredientEntry.newEntry');
		var content = $('#newIngredientMarkup').html();
		content = content.replace(/newIndex/gi, (insertPos.index()-1));
		content = content.replace(/newIng\.png/gi, ing_id + '.png');
		var newElem = $(content);
		newElem.attr('id', 'ing' + ing_id);
		newElem.find('.name').attr('title', name).text(name);
		newElem.find('img').attr('title', name).attr('alt', name);
		newElem.find('.img_auth').attr('title', img_auth).text(img_auth);
		newElem.find('[id$="_ING_ID"]').val(ing_id);
		newElem.insertBefore(insertPos);
		initIngredientDraggable(newElem);
		return newElem;
	};

	
	glob.recipeCreator.ingredientSelect = function(caller){
		var ing_id = caller.attr('href');
		var name = caller.parent().find('.name').text().trim();
		var img_auth = caller.closest('.resultArea').find('.img_auth').text().trim();
		
		var activeField = $('.activeFancyField');
		if (typeof(ingredientsList[ing_id]) === 'undefined'){
			ingredientsList[ing_id] = name;
			var newElem = addIngredient(ing_id, name, img_auth);
		} else {
			var newElem = $('#ing'+ing_id);
		}
		if (!activeField.parent().parent().is('.newEntry')){
			//if it's not the "add ingredient" button, add value to selected action too.
			currentIngredientField.val(ing_id);
			changeValue(currentIngredientField, ing_id, ingredientsList[ing_id]);
			currentIngredientCooseLink.text(ingredientsList[ing_id]);
		} else {
			newElem.click();
			
			actionList.addClass('inFancy');
			jQuery.fancybox({
				'href': '#actionList',
				'modal': true,
				'transitionOut': 'none', //otherwise it is stil in "closing" mode wenn param fancy is start to open. 
				'onComplete': function(){
					actionList.find('.actionListList.selected > .action').draggable("destroy");
				},
				'onCleanup':function(){
					actionList.removeClass('inFancy');
					initActionDraggable(actionList.find('.actionListList.selected > .action'));
				},/*
				'onClosed':function(){
					actionList.removeClass('inFancy');
					actionList.find('.actionListList.selected > .action').draggable("enable");
				},*/
			});
		}
		
		activeField.removeClass('activeFancyField');
		jQuery.fancybox.close();
		return false;
	};
	

	//##################################### Step logic #####################################
	function createStepFormActionId(id, newIndex, actionValues){
		var ain_desc = $('#ain_desc_'+id);
		var actionText = ain_desc.find('.actionHtml').val();
		var jsonValues = {};
		var defaults = [];
		var required = [];
		try {
			defaults = $.parseJSON(ain_desc.find('.actionDefaults').val());
			jsonValues = $.parseJSON($('#emptyStepsJSON').val());
			required = $.parseJSON(ain_desc.find('.actionRequireds').val());
			//eval('defaults = '+ain_desc.find('.actionDefaults').val()+';');
			//eval('jsonValues = '+$('#emptyStepsJSON').val()+';');
			//eval('required = '+ain_desc.find('.actionRequireds').val()+';');
		} catch(ex){}
		jsonValues['AIN_ID'] = id;
		for(var index in required){
			var key = required[index];
			if (typeof(globalParamDefaults[key]) !== 'undefined'){
				jsonValues[key] = globalParamDefaults[key]
			}
		}
		
		for(var key in defaults){
			jsonValues[key] = defaults[key];
		}
		for(var key in actionValues){
			jsonValues[key] = actionValues[key];
		}
		if (actionValues['ING_ID']){
			var ing_id = actionValues['ING_ID'];
			if(ing_id != 0 && $('#ing'+ing_id).length == 0){
				var name = ingredientsList[ing_id];
				var img_auth = ingredientsAuthList[ing_id];
				var newElem = addIngredient(ing_id, name, img_auth);	
			}
		}
		delete jsonValues['CREATED_BY'];
		delete jsonValues['CREATED_ON'];
		delete jsonValues['CHANGED_BY'];
		delete jsonValues['CHANGED_ON'];
		var newItem = $('<div class="step"><span class="stepNo">' + (newIndex+1) + '</span> <span class="actionText">' + actionText + '</span><input type="hidden" id="Steps_' + (newIndex+1) + '_json" class="json" name="Steps[' + (newIndex+1) + '][json]" value="' + JSON.stringify(jsonValues).replace(/"/g, '&quot;') + '"> <span class="remove">' + glob.trans.GENERAL_REMOVE + '</span></div>');
		//if (actionValues){
			//updateStepText(newItem, jsonValues);
			updateStepText(newItem, actionValues);
		//}
		return newItem;
	}
	
	jQuery('body').undelegate('#recipes-form .step','click').delegate('#recipes-form .step','click',function(){
		var elem = $(this);
		selecting = true;
		try {
			elem.parent().find('.selected').removeClass('selected');
			elem.addClass('selected');
			currentStep = elem;
			console.log('selected:' + elem.index());
			currentStepJsonField = elem.find('.json');
			var jsonValues = $.parseJSON(currentStepJsonField.val());
			delete jsonValues['CREATED_BY'];
			delete jsonValues['CREATED_ON'];
			delete jsonValues['CHANGED_BY'];
			delete jsonValues['CHANGED_ON'];
			currentStepJsonValues = jsonValues;
			var ain_desc = $('#ain_desc_'+jsonValues['AIN_ID']);
			var required = [];
			try {
				required = $.parseJSON(ain_desc.find('.actionRequireds').val());
				//eval('required = '+ain_desc.find('.actionRequireds').val()+';');
			} catch(ex){}
			$('#propertyList .param').hide();
			for(var key in jsonValues){
				var param = $('#param_'+key);
				var inputField = param.find('[id$="' + key + '"]');
				inputField.val((jsonValues[key] == null)?'':jsonValues[key]);
				if (inputField.hasClass('withUnit') || inputField.hasClass('input_range')){
					inputField.change();
				}
			}
			for(var index in required){
				var key = required[index];
				$('#param_'+key).show();
			}
			$('.ingredientList .selected').removeClass('selected');
			if (jsonValues['ING_ID']){
				if (ingredientsList[jsonValues['ING_ID']]){
					currentIngredientCooseLink.text(ingredientsList[jsonValues['ING_ID']]);
					var imgsrc = glob.prefix + 'ingredients/displaySavedImage/' + jsonValues['ING_ID'] + '.png'
					currentIngredientImg.attr('src', imgsrc);
					$('#ing' + jsonValues['ING_ID']).addClass('selected');
					if (!isStepAdding){
						updateActionSuggestion(jsonValues['ING_ID']);
					}
				} else {
					currentIngredientCooseLink.text(currentIngredientCooseLinkDefaultText);
				}
			} else {
				currentIngredientCooseLink.text(currentIngredientCooseLinkDefaultText);
			}
			$('#Steps_STE_STEP_NO').val(elem.index());
		} catch(ex){}
		selecting = false;
	});
	
	jQuery('body').undelegate('#recipes-form .step','dblclick').delegate('#recipes-form .step','dblclick',function(){
		var elem = $(this);
		$('#propertyList').addClass('inFancy');
		jQuery.fancybox({
			'href': '#propertyList',
			'modal': true,
//			'onComplete': function(){
//				$(steps.get(index)).click();
//			},
			'onClosed':function(){
				updateStepText(elem);
				$('#propertyList').removeClass('inFancy').find('.error').removeClass('error');
				if (elem.hasClass('needSave')){
					elem.removeClass('needSave');
					SendDataToBackend('&add='+elem.index());
				}
			},
		});
	});
	
	jQuery('body').undelegate('#recipes-form .step .remove','click').delegate('#recipes-form .step .remove','click',function(){
		var elem = $(this).closest('.step');
		var index = elem.index();
		var parent = elem.parent();
		elem.remove();
		stepRemoved(parent, index);
		return false;
	});
	
	function updateStepText(item, jsonValues){
		if (typeof(jsonValues) === 'undefined'){
			jsonValues = $.parseJSON(item.find('.json').val());
		}
		for(var fieldName in jsonValues){
			var cssClass = fieldToCssClass[fieldName];
			if (cssClass){
				if (jsonValues[fieldName] == 0){
					continue;
				}
				if (fieldName == 'STE_STEP_DURATION'){
					var shownValue = secondsToTime(parseInt(jsonValues[fieldName]));
				} else if (fieldName == 'ING_ID'){
					var shownValue = ingredientsList[jsonValues[fieldName]];
					if (!shownValue){
						shownValue = 'ingredient:' + jsonValues[fieldName];	
					}
				} else {
					var shownValue = jsonValues[fieldName];
				}
				item.find('.' + cssClass).text(shownValue);
			}
		}
		item.find('.cookin').text(getCookinValue());
	}
	
	function stepRemoved(parent, index) {
		checkForceUpdate();
		checkUpdateDelayedRows();
		checkUpdateRecipe();
		
		//console.log('remove:' + index);
		var steps = parent.children('.step');
		steps.each(function(i){
			if (i>=index){
				var step = $(this);
				var stepNo = step.find('.stepNo');
				stepNo.text(i+1);
				var jsonValueField = step.find('.json');
				jsonValueField.attr('id', 'Steps_' + (i+1) + '_json');
				jsonValueField.attr('name', 'Steps[' + (i+1) + '][json]');
			}
		});
		
		var newSelection = $('.step:nth-child('+(index+1)+')');
		if (newSelection.length == 0){
			newSelection = $('.step:nth-child('+index+')');	
		}
		if (newSelection.length == 0){
			//hide all param values
			$('#propertyList .param').hide();
		} else {
			newSelection.click();
		}
		
		SendDataToBackend('&remove='+index);
	}
	
	function stepAdded(item, index) {
		isStepAdding = true;
		try {
			checkForceUpdate();
			checkUpdateDelayedRows();
			checkUpdateRecipe();
			
			//console.log('new:' + index);
			var steps = item.parent().children();
			steps.each(function(i){
				if (i>index){
					var step = $(this);
					var stepNo = step.find('.stepNo');
					stepNo.text(i+1);
					var jsonValueField = step.find('.json');
					jsonValueField.attr('id', 'Steps_' + (i+1) + '_json');
					jsonValueField.attr('name', 'Steps[' + (i+1) + '][json]');
				}
			});
			/*
			var inputs = $('#propertyList').find('.dataField');
			inputs.each(function (){
				var elem = $(this);
				//elem.val(elem.get(0).defaultValue);
				elem.val(elem.attr('data-defaultValue'));
			});
			*/
			item.click();
			item.dblclick();
		} catch (ex){}
		isStepAdding = false;
	}
	
	function stepMoved(item, newIndex, oldIndex){
		checkForceUpdate();
		checkUpdateDelayedRows();
		checkUpdateRecipe();
		
		//console.log('moved:' + newIndex + ', ' + oldIndex);
		var steps = item.parent().children();
		if (newIndex>oldIndex){
			var from = oldIndex;
			var to = newIndex;
//		} else if (newIndex == oldIndex){
//			var from = newIndex;
//			var to = oldIndex;
		} else {
			var from = newIndex;
			var to = oldIndex;
		}
		steps.each(function(i){
			//i is 0 based
			if (i>=from && i<=to){
				var step = $(this);
				var stepNo = step.find('.stepNo');
				stepNo.text(i+1);
				var jsonValueField = step.find('.json');
				jsonValueField.attr('id', 'Steps_' + (i+1) + '_json');
				jsonValueField.attr('name', 'Steps[' + (i+1) + '][json]');
			}
		});
		currentStepIndexField.val($('.step.selected').index());
		
		SendDataToBackend('&move='+(oldIndex+1)+'&to='+(newIndex+1));
		//SendDataToBackendRowSetTimeout(currentStepJsonField.val(), newIndex, 'MOVE', oldIndex);
	}

	function updateActionSuggestion(ing_id){
		glob.ShowActivity = false;
		jQuery.ajax({
			'type':'get',
			'url':$('#actionSuggestionLink').val() + '?ing_id=' + ing_id,
			'dataType': 'json',
			'async':true,
			'cache':true, //TODO cache step suggestion response?
			'success':function(result){
				var ingredients = result['ingredients'];
				for(var ing_id in ingredients){
					if (!ingredientsList[ing_id]){
						var ingredientDetails= ingredients[ing_id];
						ingredientsList[ing_id] = ingredientDetails['ING_NAME'];
						ingredientsAuthList[ing_id] = ingredientDetails['ING_IMG_AUTH'];
					}
				}
				var actions = result['suggestions'];
				suggestionList.children().remove();
				for(var index in actions){
					var action = actions[index];
					var id = action['AIN_ID'];

					var ain_desc = $('#ain_desc_'+id);
					if (ain_desc.length){
						/*
						//if i do it with "data()" I cannot access the values on stop function of sortable after drop new action....
						var newAction = $('<div class="action"></div>');
						newAction.data('id', id);
						newAction.data('json', action);
						*/
						var newAction = $('<div class="action" data-id="' + id + '" data-json=""></div>');
						newAction.attr('data-json', JSON.stringify(action));
						
						var actionText = ain_desc.find('.actionHtml').val();
						newAction.html(actionText);
						updateStepText(newAction, action);
						suggestionList.append(newAction);
					}
				}
				//initActionDraggable(suggestionList.find('.action'));
				initActionDraggable(suggestionList.children());
			},
			'error':function(xhr){
				//alert('error');
			},
		});
		glob.ShowActivity = true;
	}
	
	function getCookinValue(){
		var elem = $('#cookInDisplay');
		if (elem.val()){
			var value = elem.children('option:selected').text().trim();
		} else {
			var value = '#cookin#';
		}
		return value;
	}
	
	jQuery('body').undelegate('#cookInDisplay','change').delegate('#cookInDisplay','change',function(){
		$('#stepList .step .cookin').text(getCookinValue());
	});

	
	//##################################### ActionList logic #####################################
	jQuery('body').undelegate('#actionList .actionListType','click').delegate('#actionList .actionListType','click', function(){
		$('#actionList .actionListType').removeClass('selected');
		$(this).addClass('selected');
		var listIndex = $('#actionList').children('.actionListType').index(this);
		var actionLists = $('#actionList').children('.actionListList');
		actionLists.filter('.selected').removeClass('selected');
		$(actionLists.get(listIndex)).addClass('selected');
	});

	jQuery('body').undelegate('#actionList.inFancy .selected .action','click').delegate('#actionList.inFancy .selected .action','click', function(){
		var item = $(this);
		var id = item.data('id');
		var actionValues = {};
		try {
			if (typeof(item.data('json')) === 'string'){
				actionValues = $.parseJSON(item.data('json'));
			} else if (item.data('json')){
				actionValues = item.data('json');
			}
		} catch(ex){}
		var stepList = $('#stepList .items');
		var newIndex = stepList.children().length; 
		var newItem = createStepFormActionId(id, newIndex, actionValues);
		
		stepList.append(newItem);
		initStepDroppable(newItem.has('.ingredient'));
		jQuery.fancybox.close();
		stepAdded(newItem, newIndex);
	});
	
	jQuery('body').undelegate('#actionList #addAction','click').delegate('#actionList #addAction','click', function(){
		var id = $('#actionSelect').val();
		var stepList = $('#stepList .items');
		var newIndex = stepList.children().length; 
		var newItem = createStepFormActionId(id, newIndex, {});
		
		stepList.append(newItem);
		initStepDroppable(newItem.has('.ingredient'));
		stepAdded(newItem, newIndex);
	});

	//##################################### Param logic #####################################
	function changeValue(field, value, shownValue){
		if (selecting){
			return;
		}
		var fieldName = field.attr('id');
		fieldName = fieldName.substr(fieldName.indexOf('_')+1);
		if (currentStepJsonValues[fieldName] != value){
			var oldValue = currentStepJsonValues[fieldName];
			currentStepJsonValues[fieldName] = value;
			currentStepJsonField.val(JSON.stringify(currentStepJsonValues));
			
			//var stepIndex = field.closest('.propertayList').find('[id$="STE_STEP_NO"').val();
			var stepIndex = currentStepIndexField.val();
			var cssClass = fieldToCssClass[fieldName];
			currentStep.find('.' + cssClass).text(shownValue);
			historyUpdateChange(fieldName, oldValue, value);
		}
	}

	function secondsToTime(seconds){
		return intToTime(seconds*1000 -3600000); //value is in miliseconds, start value is 1 std
	}
	function intToTime(time){
		var timeStr = "";
		if (time<-3600000){
			timeStr += "-";
			time = Math.abs(time+3600000)-3600000;
		}
		//time = Math.round(time/1000)*1000;
		//time = Math.ceil(time/1000)*1000;
		var timeToShow = new Date(time);
		
		var val = timeToShow.getHours();
		//if (val>0){
			if (val<10){
				timeStr += "0";
			}
			timeStr += val+":"
		//}
		
		val = timeToShow.getMinutes();
		if (val<10){
			timeStr += "0";
		}
		timeStr += val+":"
		
		val = timeToShow.getSeconds();
		if (val<10){
			timeStr += "0";
		}
		timeStr += val;
		//timeStr += "." + timeToShow.getMilliseconds();
		
		return timeStr;
	}
	
	function changeTimeValue(dataField){
		if (selecting){
			return;
		}
		var value = dataField.val();
		var shownValue = secondsToTime(parseInt(value));
		changeValue(dataField, value, shownValue);
		
	}
	
	jQuery('body').undelegate('#propertyList .viewWithUnit','change').delegate('#propertyList .viewWithUnit','change',function(){
		var inputField = jQuery(this);
		var unitField = inputField.next();
		var multiplier = unitField.val();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
			if (dataField.is('.type_time')){
				changeTimeValue(dataField);
			} else {
				changeValue(dataField, dataField.val(), dataField.val());
			}
		}
	});
	
	jQuery('body').undelegate('#propertyList .unit','change').delegate('#propertyList .unit','change',function(){
		var unitField = jQuery(this);
		var multiplier = unitField.val();
		var inputField = unitField.prev();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
			if (dataField.is('.type_time')){
				changeTimeValue(dataField);
			} else {
				changeValue(dataField, dataField.val(), dataField.val());
			}
		}
	});

	jQuery('body').undelegate('#propertyList .withUnit','change').delegate('#propertyList .withUnit','change',function(){
		var dataField = jQuery(this);
		var unitField = dataField.prev();
		var multiplier = unitField.val();
		var inputField = unitField.prev();
		var value = dataField.val();
		if (value !== ''){
			inputField.val(value / multiplier);
			if (dataField.is('.type_time')){
				changeTimeValue(dataField);
			} else {
				changeValue(dataField, dataField.val(), dataField.val());
			}
		}
	});

	jQuery('body').undelegate('#propertyList .input_range','change').delegate('#propertyList .input_range','change',function(){
		var dataField = jQuery(this);
		var valueField = dataField.next();
		var rangeField = dataField;
		var value = dataField.val();
		if (value !== ''){
			valueField.val(value);
			changeValue(rangeField, rangeField.val(), rangeField.val());
		}
	});

	jQuery('body').undelegate('#propertyList .slider_value','change').delegate('#propertyList .slider_value','change',function(){
		var dataField = jQuery(this);
		var valueField = dataField;
		var rangeField = dataField.prev();
		var value = dataField.val();
		if (value !== ''){
			rangeField.val(value);
			changeValue(rangeField, rangeField.val(), rangeField.val());
		}
	});

	//other input fields
	jQuery('body').undelegate('#propertyList input:not(.viewWithUnit):not(.unit):not(.withUnit):not(.input_range):not(.slider_value)','change').delegate('#propertyList input:not(.viewWithUnit):not(.unit):not(.withUnit):not(.input_range):not(.slider_value)','change',function(){
		var dataField = jQuery(this);
		changeValue(dataField, dataField.val(), dataField.val());
	});
	jQuery('body').undelegate('#propertyList .closeButton','click').delegate('#propertyList .closeButton','click',function(){
		var inputs = $('#propertyList').find('.param:visible .dataField');
		var okCount = 0;
		var firstErrorField = -1;
		inputs.each(function(i){
			var elem = $(this);
			if (elem.val() !== '' && elem.val() !== elem.attr('data-defaultValue')){
				okCount++;
				elem.closest('.param').removeClass('error');
			} else {
				if(firstErrorField == -1){
					firstErrorField = i;
				}
				elem.closest('.param').addClass('error');
			}
		});
		if(okCount == inputs.length){
			jQuery.fancybox.close();
		} else {
			$(inputs.get(firstErrorField)).select(); 
		}
	});

	//##################################### history change logic #####################################

	var updateRowTimeouts = new Array();
	var updateTimeout = undefined;
	var forceUpdateTimeout = undefined;
	var changeList = new Array();
	var undoList = new Array();
	glob.recipeCreator.SendDataToBackendRowTimeout = 5000;
	glob.recipeCreator.SendDataToBackendForceTimeout = 3000;
	
	
	function checkForceUpdate(){
		if (typeof(forceUpdateTimeout) !== 'undefined'){
			window.clearTimeout(forceUpdateTimeout['timeoutId']);
			forceUpdateTimeout = undefined;
			SendDataToBackendForceCallback();
		}
	}
	
	function checkUpdateDelayedRows(){
		//run delayed updates:
		for(var stepIndex in updateRowTimeouts){
			var timeoutInfo = updateRowTimeouts[stepIndex];
			if (typeof(timeoutInfo) !== 'undefined'){
				window.clearTimeout(timeoutInfo['timeoutId']);
				updateRowTimeouts[stepIndex] = undefined;
				SendDataToBackendRow(timeoutInfo['jsonString'], stepIndex, timeoutInfo['action'], timeoutInfo['prefIndex'], false);
			}
		}
	}
	
	function checkUpdateRecipe(){
		if (typeof(updateTimeout) !== 'undefined'){
			var additional = updateTimeout['additional'];
			if (typeof(updateTimeout['fields']) !== 'undefined'){
				additional += '&fields='+updateTimeout['fields'];
			}
			updateTimeout = undefined;
			SendDataToBackend(additional, false);
		}
	}
	
	//######################## recipe / detail infos ########################
	function SendDataToBackend(additional, async){
		if (typeof(forceUpdateTimeout) !== 'undefined'){
			window.clearTimeout(forceUpdateTimeout['timeoutId']);
			forceUpdateTimeout = undefined;
		}
		if (typeof(async) === 'undefined'){
			async = true;
		}
		
		var url = jQuery('#updateSessionValuesLink').val();
		var form = $('#recipes-form');
		
		var propertyFields = jQuery('[name^="Steps[]["]');
		propertyFields.prop('disabled',true);
		var data = form.serialize() + additional;
		propertyFields.prop('disabled',false);
		
		if (async){
			glob.ShowActivity = false;
		}
		jQuery.ajax({'type':'post', 'url':url, 'data': data,'async':async,'cache':false,/*'success':function(data){
				//alert('success');
			},
			'error':function(xhr){
				//alert('error');
			},*/
		});
		glob.ShowActivity = true;
	}

	function SendDataToBackendCallback(){
		checkForceUpdate();
		if (typeof(updateTimeout) !== 'undefined'){
			var additional = updateTimeout['additional'];
			if (typeof(updateTimeout['fields']) !== 'undefined'){
				additional += '&fields='+updateTimeout['fields'];
			}
			checkUpdateDelayedRows();
			
			SendDataToBackend(additional);
			updateTimeout = undefined;
		}
	}
	glob.recipeCreator.SendDataToBackendCallback = SendDataToBackendCallback;
	
	function SendDataToBackendSetTimeout(additional, field){
		var fields = ',';
		if (typeof(updateTimeout) !== 'undefined'){
			window.clearTimeout(updateTimeout['timeoutId']);
			fields=timeoutInfo['fields'];
			updateTimeout = undefined;
		}
		timeoutInfo = new Array();
		timeoutInfo['timeoutId'] = window.setTimeout('glob.recipeCreator.SendDataToBackendCallback();', glob.recipeCreator.SendDataToBackendRowTimeout);
		timeoutInfo['additional'] = additional;
		if (fields.indexOf(','+field+',') === -1){
			fields += field+',';
		}
		timeoutInfo['fields'] = fields;
		
		updateTimeout = timeoutInfo;
	}
	
	jQuery('body').undelegate('#recipes-form [name^="Recipes"], #recipes-form [name^="COI_ID"]','change.recipeCreatorFields').delegate('#recipes-form [name^="Recipes"], #recipes-form [name^="COI_ID"]','change.recipeCreatorFields',function(){
		var dataField = jQuery(this);
		
		SendDataToBackendSetTimeout('', dataField.attr('name'));
	});
	
	//######################## row / step ########################
	function SendDataToBackendRow(jsonString, stepIndex, action, prefIndex, async){
		if (typeof(async) === 'undefined'){
			async = true;
		}
		updateRowTimeouts[stepIndex] = undefined;
		var url = jQuery('#updateSessionValueLink').val();
		url = glob.urlAddParamStart(url) + 'StepNr=' + (stepIndex+1);
		url +='&action='+action+'&prefIndex='+(prefIndex+1);
		
		//var jsonValues = $.parseJSON(row.find('.json').val());
		//var data = 'json=' + jsonString;
		var data = 'Steps['+(stepIndex+1)+'][json]=' + jsonString;
		
		
		
		if (async){
			glob.ShowActivity = false;
		}
		jQuery.ajax({'type':'post', 'url':url, 'data': data,'async':async,'cache':false,/*'success':function(data){
				//alert('success');
			},
			'error':function(xhr){
				//alert('error');
			},*/
		});
		glob.ShowActivity = true;
	}
	
	function SendDataToBackendRowCallback(stepIndex){
		var timeoutInfo = updateRowTimeouts[stepIndex];
		if (typeof(timeoutInfo) !== 'undefined'){
			updateRowTimeouts[stepIndex] = undefined;
			SendDataToBackendRow(timeoutInfo['jsonString'], stepIndex, timeoutInfo['action'], timeoutInfo['prefIndex']);
		}
	}
	glob.recipeCreator.SendDataToBackendRowCallback = SendDataToBackendRowCallback;
	
	function SendDataToBackendRowSetTimeout(jsonString, stepIndex, action, prefIndex){
		var timeoutInfo = updateRowTimeouts[stepIndex];
		if (typeof(timeoutInfo) !== 'undefined'){
			window.clearTimeout(timeoutInfo['timeoutId']);
			if (action !== "CHANGE"){
				SendDataToBackendRow(timeoutInfo['jsonString'], stepIndex, timeoutInfo['action'], timeoutInfo['prefIndex'], false);
			}
		}
		if (action !== "CHANGE" || glob.recipeCreator.SendDataToBackendRowTimeout === 0){
			updateRowTimeouts[stepIndex] = undefined;
			SendDataToBackendRow(jsonString, stepIndex, action, prefIndex);
		} else {
			timeoutInfo = new Array();
			timeoutInfo['timeoutId'] = window.setTimeout('glob.recipeCreator.SendDataToBackendRowCallback('+stepIndex+');', glob.recipeCreator.SendDataToBackendRowTimeout);
			timeoutInfo['jsonString'] = jsonString;
			timeoutInfo['action'] = action;
			timeoutInfo['prefIndex'] = prefIndex;
			
			updateRowTimeouts[stepIndex] = timeoutInfo;
		}
	}
	
	function SendDataToBackendForceCallback(){
		forceUpdateTimeout = undefined;
		var focusedElement = $(document.activeElement);
		if (focusedElement.is(":input")){
			focusedElement.blur();
			focusedElement.focus();
		}
	}
	glob.recipeCreator.SendDataToBackendForceCallback = SendDataToBackendForceCallback;
	
	jQuery('body').undelegate('#propertyList input, #propertyList select, #recipes-form [name^="Recipes"], #recipes-form [name^="COI_ID"]','keydown').delegate('#propertyList input, #propertyList select, #recipes-form [name^="Recipes"], #recipes-form [name^="COI_ID"]','keydown',function(){
		if (typeof(forceUpdateTimeout) !== 'undefined'){
			window.clearTimeout(forceUpdateTimeout['timeoutId']);
			forceUpdateTimeout = undefined;
		}
		forceUpdateTimeout = new Array();
		forceUpdateTimeout['timeoutId'] = window.setTimeout('glob.recipeCreator.SendDataToBackendForceCallback();', glob.recipeCreator.SendDataToBackendForceTimeout);
	});

	
	function historyUpdateChange(fieldId, oldValue, newValue){
		if (typeof(forceUpdateTimeout) !== 'undefined'){
			window.clearTimeout(forceUpdateTimeout['timeoutId']);
			forceUpdateTimeout = undefined;
		}
		var stepIndex = parseInt(currentStepIndexField.val());
		
		SendDataToBackendRowSetTimeout(currentStepJsonField.val(), stepIndex, 'CHANGE', stepIndex);
	}
	

	//######################## add/remove/move ########################
	//see stepRemoved, stepAdded, stepMoved functions
	
	
	//##################################### initialisation #####################################

	jQuery('body').undelegate('#recipes-form input[type="submit"]','click').delegate('#recipes-form input[type="submit"]','click', function(){
		//jQuery('[name^="Steps[]["]').attr('disabled','disabled');
		jQuery('[name^="Steps[]["]').prop('disabled',true);
		return true;
	});
	
	$('#page').bind('newContent.recipeCreator', function(e, type, contentParent) {
		if(type == 'initial' || type == 'hash' || type == 'form'){
			if (contentParent.find('#recipeCreator:not(.initialized)')){
				glob.recipeCreator.init();
			}
		}
	});
	$('#recipeCreator').not('.initialized').each(glob.recipeCreator.init);
});