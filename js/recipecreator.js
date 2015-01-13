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

glob.rowContainer = {};

jQuery(function($){
	
	var fieldToCssClass = $([]);
	var currentStep = $([]);
	var currentStepIndexField = $([]);
	var currentStepJsonField = $([]);
	var currentStepJsonValues = {};
	var ingredientsList = {};
	var currentIngredientField = $([]);
	var currentIngredientCooseLink = $([]);
	var currentIngredientImg = $([]);
	var currentIngredientAmountField = $([]);
	var currentIngredientAmountParam = $([]);
	
	var selecting = false;
	var ingredientDragHover = $([]);

	function initIngredientDraggable(ingredients){
		ingredients.draggable({
			appendTo: '.recipeCreator',
			helper: 'clone',
			revert: 'invalid',
			scope: 'ingredient',
			cursor: 'crosshair',
			//opacity: 0.35,
			/*start: function( event, ui ) {
				ui.helper.addClass('dragging');
			},*/
		 });
	}
	
	function initDroppable(steps){
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
	
	glob.initRecipeCreator = function(){
		fieldToCssClass = $.parseJSON($('#fieldToCssJSON').val());
		currentStepIndexField = $('#Steps_STE_STEP_NO');
		ingredientsList = $.parseJSON($('#ingredientsJSON').val());
		currentIngredientField =  $('#Steps_ING_ID');
		currentIngredientCooseLink =  $('#Steps_ING_ID_DESC');
		currentIngredientImg =  $('#Steps_ING_ID_IMG');
		currentIngredientAmountField =  $('#Steps_STE_GRAMS');
		currentIngredientAmountParam = $('#param_STE_GRAMS');
		
		$( "#stepList" ).sortable({
			placeholder: "addstep",
			stop: function( event, ui ) {
				var sender = ui.sender;
				var item = $(ui.item);
				var newIndex = item.index();
				if (item.hasClass('action')){
					var id = item.data('id');
					var ain_desc = $('#ain_desc_'+id);
					var actionText = ain_desc.find('.actionHtml').val();
					var jsonValues = {};
					var defaults = [];
					try {
						defaults = $.parseJSON(ain_desc.find('.actionDefaults').val());
						jsonValues = $.parseJSON($('#emptyStepsJSON').val());
						//eval('defaults = '+ain_desc.find('.actionDefaults').val()+';');
						//eval('jsonValues = '+$('#emptyStepsJSON').val()+';');
					} catch(ex){}
					jsonValues['AIN_ID'] = id;
					for(var key in defaults){
						jsonValues[key] = defaults[key];
					}
					var newItem = $('<div class="step"><span class="stepNo">' + (newIndex+1) + '</span> <span class="actionText">' + actionText + '</span><input type="hidden" id="Steps_' + newIndex + '_json" class="json" name="Steps[' + newIndex + '][json]" value="' + JSON.stringify(jsonValues).replace(/"/g, '&quot;') + '"></div>');
					newItem.insertAfter(item);
					item.remove();
					initDroppable(newItem.has('.ingredient'));
					stepAdded(newItem, newIndex);
				} else {
					var stepNo = item.find('.stepNo');
					var oldIndex = stepNo.text();
					stepNo.text(newIndex+1);
					stepMoved(item, newIndex, oldIndex-1);
				}
			},
		 });
		 $( ".actionList > .action" ).draggable({
			 connectToSortable: "#stepList",
			 //appendTo: "#stepList",
			 //helper: "clone",
			 helper: function(event){
				 var id = $(event.target).data('id');
				 return $('<div class="step"><span class="stepNo">?</span> <span class="actionText">' + $(event.target).text() + '</span></div>'); 
			 },
			 revert: "invalid"
		 });
		 $('.step').disableSelection();
		 $('.step:first').click();
		 
		 initIngredientDraggable($('.ingredientList .ingredientEntry'));
		 initDroppable($('.step:has(.ingredient)'));
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
			currentStepJsonValues = jsonValues;
			var ain_desc = $('#ain_desc_'+jsonValues['AIN_ID']);
			var required = [];
			try {
				required = $.parseJSON(ain_desc.find('.actionRequireds').val());
				//eval('required = '+ain_desc.find('.actionRequireds').val()+';');
			} catch(ex){}
			$('.propertyList .param').hide();
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
					updateStepSuggestion(jsonValues['ING_ID']);
				}
			}
			$('#Steps_STE_STEP_NO').val(elem.index());
		} catch(ex){}
		selecting = false;
	});
	
	function updateStepSuggestion(ing_id){
		//TODO update step suggestion
	}
	
	jQuery('body').undelegate('#recipes-form .ingredientList .ingredientEntry','click').delegate('#recipes-form .ingredientList .ingredientEntry','click',function(){
		var elem = $(this);
		var ing_id = elem.find('[id$="_ING_ID"]').val()
		if (ing_id){
			$('.ingredientList .selected').removeClass('selected');
			elem.addClass('selected');
			updateStepSuggestion(ing_id);
		}
	});
	
	function stepAdded(item, index) {
		console.log('new:' + index);
		var steps = item.parent().children();
		steps.each(function(i){
			if (i>index){
				var step = $(this);
				var stepNo = step.find('.stepNo');
				stepNo.text(i+1);
			}
		});
		currentStepIndexField.val($('.step.selected').index());
	}
	
	function stepMoved(item, newIndex, oldIndex){
		console.log('moved:' + newIndex + ', ' + oldIndex);
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
			}
		});
		currentStepIndexField.val($('.step.selected').index());
	}
	
	function historyUpdateChange(fieldId, oldValue, newValue){
		//TODO send history event to server
	}
	
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
	
	jQuery('body').undelegate('.propertyList .viewWithUnit','change').delegate('.propertyList .viewWithUnit','change',function(){
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
	
	jQuery('body').undelegate('.propertyList .unit','change').delegate('.propertyList .unit','change',function(){
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

	jQuery('body').undelegate('.propertyList .withUnit','change').delegate('.propertyList .withUnit','change',function(){
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

	jQuery('body').undelegate('.propertyList .input_range','change').delegate('.propertyList .input_range','change',function(){
		var dataField = jQuery(this);
		var valueField = dataField.next();
		var rangeField = dataField;
		var value = dataField.val();
		if (value !== ''){
			valueField.val(value);
			changeValue(dataField, dataField.val(), dataField.val());
		}
	});

	jQuery('body').undelegate('.propertyList .slider_value','change').delegate('.propertyList .slider_value','change',function(){
		var dataField = jQuery(this);
		var valueField = dataField;
		var rangeField = dataField.prev();
		var value = dataField.val();
		if (value !== ''){
			rangeField.val(value);
			changeValue(dataField, dataField.val(), dataField.val());
		}
	});


	jQuery('body').undelegate('.propertyList input:not(.viewWithUnit):not(.unit):not(.withUnit):not(.input_range):not(.slider_value)','change').delegate('.propertyList input:not(.viewWithUnit):not(.unit):not(.withUnit):not(.input_range):not(.slider_value)','change',function(){
		var dataField = jQuery(this);
		changeValue(dataField, dataField.val(), dataField.val());
	});

	jQuery('body').undelegate('.recipeDetailsTitle','click').delegate('.recipeDetailsTitle','click', function(){
		jQuery(this).siblings('.recipeDetails').toggle();
		return true;
	});
	
	

	
	/*
	//Wait for reload after cookInUpdate so steps could add successfull...
	$('#page').bind('newContent.recipeCreator', function(e, type, contentParent) {
		if (data.steps){
			ingredients = data.ingredients;
			var container = jQuery('.steps .addRowContainer');
			//lastIndex = container.find('.odd').add(container.find('.even')).length;
			var emptyLineContainer = container.find('#newLine');
			var lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
			lastIndexElem.attr('value', lastIndex);
			initRecipeStepsRowContainerDoIt(container, data.steps, '[]', true);
		}
	});
	*/
});