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

glob.rowContainer = {};

jQuery(function($){
	/*TODO use the one in ajax_handling.js*/
	function initMultiFancyCoose(){
		jQuery('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input:first').addClass('activeFancyField');
		});
		jQuery('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
	}
	
	var rows;
	var lastIndex = 0;
	var newLineContent;
	var updateRowTimeouts = new Array();
	glob.rowContainer.SendDataToBackendRowTimeout = 5000;
	
	function getIndexFromFieldName(name){
		var currentIndex = name.match(/\[([^\]]+)\]/);
		if (!currentIndex){
			return false;
		}
		return new Number(currentIndex[1]).valueOf();
	}
	
	function changeInputTableIndex(elem, changeamount){
		var next = elem.next();
		var inputs;
		if (next.attr('class') === 'addFields'){
			inputs = elem.add(next).find('[name]');//All input fields
		} else {
			inputs = elem.find('[name]');//All input fields
		}
		
		var currentIndexInt = getIndexFromFieldName(inputs.attr('name'));
		if (currentIndexInt === false) {
			return false;
		}
		var newIndexInt = currentIndexInt+changeamount;
		
		var newIndexIntStr = '['+newIndexInt+']';
		var newIndexIntStr2 = '_'+newIndexInt+'_';
		var currentIndexStr = '['+currentIndexInt+']';
		var currentIndexStr2 = '_'+currentIndexInt+'_';
		
		if (elem.attr('class') != 'addFields'){
			elem.attr('class', (newIndexInt % 2 == 1)?'odd':'even');
		}
		
		inputs.each(function(){
			elem = jQuery(this);
			elem.attr('name', elem.attr('name').replace(currentIndexStr,newIndexIntStr));
			elem.attr('id', elem.attr('id').replace(currentIndexStr2,newIndexIntStr2));
			var prev = elem.prev();
			if (prev.is('label')){
				prev.attr('for', prev.attr('for').replace(currentIndexStr2,newIndexIntStr2));
			}
			var next = elem.next();
			if (next.is('a')){  // && elem.is('.fancyValue')
				next.attr('id', next.attr('id').replace(currentIndexStr2,newIndexIntStr2));
			}
		});
		return newIndexInt;
	}
	
	function addEmptyRow(insertBeforeLine){
		var index;
		if (insertBeforeLine.is('#newLine')){
			index = lastIndex;
		} else {
			var fields = insertBeforeLine.find('input[name]');
			if (fields.length == 0){
				return null;
			} else {
				index = getIndexFromFieldName(fields.attr('name'));
			}
		}
		
		var currentNewLineContent = newLineContent.replace('%class%',(index % 2 == 1)?'odd':'even');
		currentNewLineContent = currentNewLineContent.replace(/%index%/g,index);
		
		currentNewLineContent = jQuery(currentNewLineContent);
		currentNewLineContent.insertBefore(insertBeforeLine);
		
		if (index != lastIndex){
			try {
				changeInputTableIndex(insertBeforeLine, 1);
				var followedRows = insertBeforeLine.nextAll().not('.addFields').not('.actionsInInfo');
				followedRows = followedRows.not(followedRows.last());
				followedRows.each(function(){
					changeInputTableIndex(jQuery(this), 1);
				});
				/*
				for (var i=0; i<followedRows.length; ++i){
					changeInputTableIndex(followedRows[i], 1);
				}*/
			} catch(ex){
				console.log(ex);
			}
		}
		
		lastIndex = lastIndex+1;
		
		return currentNewLineContent;
	}
	
	/*
	jQuery('body').undelegate('.addRowContainer .add','click').delegate('.addRowContainer .add','click',function(){
		addEmptyRow(jQuery(this).parents('tr:first'));
	});
	*/
	
	jQuery('body').undelegate('.addRowContainer .remove','click').delegate('.addRowContainer .remove','click',function(){
		var row = jQuery(this).parents('tr:first');
		var followedRows = row.nextAll().not('.addFields').not('.actionsInInfo');
		
		var rowContainer = jQuery(this).parents('.addRowContainer:first');
		
		lastIndex = lastIndex-1;
		
		var next = row.next();
		var next2 = next.next();
		if (next.attr('class') === 'addFields'){
			next.remove();
			if (next2.attr('class') === 'actionsInInfo'){
				next2.remove();
			}
		} else if (next.attr('class') === 'actionsInInfo'){
			next.remove();
		}
		row.remove();
		
		followedRows = followedRows.not(followedRows.last());
		followedRows.each(function(){
			changeInputTableIndex(jQuery(this), -1);
		});
		
		SendDataToBackend(rowContainer);
	});
	
	jQuery('body').undelegate('.addRowContainer .up','click').delegate('.addRowContainer .up','click',function(){
		var row = jQuery(this).parents('tr:first');
		
		var next = row.next();
		var next2 = next.next();
		var next2 = next.next();
		if (next.attr('class') !== 'addFields'){
			if (next.attr('class') !== 'actionsInInfo'){
				next2 = null;
				next = null;
			} else {
				next2 = next;
				next = null;
			}
		} else {
			if (next2.attr('class') !== 'actionsInInfo'){
				next2 = null;
			}
		}
		
		var prevEntry = row.prev();
		if (prevEntry.attr('class') === 'actionsInInfo'){
			prevEntry = prevEntry.prev();
		}
		if (prevEntry.attr('class') === 'addFields'){
			prevEntry = prevEntry.prev();
		}
		
		if (prevEntry.length){
			var rowNr = changeInputTableIndex(row, -1);
			var prevEntryNr = changeInputTableIndex(prevEntry, 1);
			row.insertBefore(prevEntry);
			if (next2 != null){
				next2.insertAfter(row);
			}
			if (next != null){
				next.insertAfter(row);
			}
			
			SendDataToBackendRowSetTimeout(row, rowNr);
			SendDataToBackendRowSetTimeout(prevEntry, prevEntryNr);
		}
	});

	jQuery('body').undelegate('.addRowContainer .down','click').delegate('.addRowContainer .down','click',function(){
		var row = jQuery(this).parents('tr:first');
		
		var nextEntry;
		var next = row.next();
		var next2 = next.next();
		if (next.attr('class') !== 'addFields'){
			if (next.attr('class') !== 'actionsInInfo'){
				nextEntry = next;
				next2 = null;
				next = null;
			} else {
				nextEntry = next2;
				next2 = next;
				next = null;
			}
		} else {
			if (next2.attr('class') !== 'actionsInInfo'){
				nextEntry = next2;
				next2 = null;
			} else {
				nextEntry = next2.next();
			}
		}
		
		var insertPos = nextEntry.next();
		if (insertPos.attr('class') !== 'addFields'){
			if (insertPos.attr('class') !== 'actionsInInfo'){
				insertPos = nextEntry;
			}
		} else {
			var insertPos2 = insertPos.next();
			
			if (insertPos2.attr('class') === 'actionsInInfo'){
				insertPos = insertPos2;
			}
		}
		
		if (nextEntry.not(row.nextAll().last()).length){
			var rowNr = changeInputTableIndex(row, 1);
			var nextEntryNr = changeInputTableIndex(nextEntry, -1);
			row.insertAfter(insertPos);
			if (next2 != null){
				next2.insertAfter(row);
			}
			if (next != null){
				next.insertAfter(row);
			}
			
			SendDataToBackendRowSetTimeout(row, rowNr);
			SendDataToBackendRowSetTimeout(nextEntry, nextEntryNr);
		}
	});
	
	/*
	jQuery('body').undelegate('.addRowContainer .unit','change').delegate('.addRowContainer .unit','change',function(){
		var elem = jQuery(this)
		var multiplier = elem.val();
		var inputField = elem.prev();
		var multiField = elem.next();
		var value = inputField.val();
		value = value / multiplier;
		inputField.val(value);
		elem.find('option').each(function(){
			var option = jQuery(this);
			option.val(option.val() / multiplier);
		});
		multiField.val(multiField.val() * multiplier);
	});
	*/
	
	jQuery('body').undelegate('.addRowContainer .viewWithUnit','change').delegate('.addRowContainer .viewWithUnit','change',function(){
		var inputField = jQuery(this);
		var unitField = inputField.next();
		var multiplier = unitField.val();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
		}
	});
	
	jQuery('body').undelegate('.addRowContainer .unit','change').delegate('.addRowContainer .unit','change',function(){
		var unitField = jQuery(this);
		var multiplier = unitField.val();
		var inputField = unitField.prev();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
		}
	});
	
	jQuery('body').undelegate('.addRowContainer .withUnit','change').delegate('.addRowContainer .withUnit','change',function(){
		var dataField = jQuery(this);
		var unitField = dataField.prev();
		var multiplier = unitField.val();
		var inputField = unitField.prev();
		var value = dataField.val();
		if (value !== ''){
			inputField.val(value / multiplier);
		}
	});
	
	var fieldTypes = {'STE_CELSIUS':'number','STE_KPA':'number','STE_RPM':'number','STE_CLOCKWISE':'boolean','STE_STIR_RUN':'time','STE_STIR_PAUSE':'time','STE_STEP_DURATION':'time'};
	
	var weightSelectType = '<select id="%id%" name="%name%" class="unit">' + 
		'<option value="1">g</option>' + 
		'<option value="1000">kg</option>' + 
		'<option value="453.59237">lb</option>' + 
		'<option value="28.349523125">oz</option>' + 
		'</select>';
	/*
	var timeSelectType = '<select id="%id%" name="%name%" class="unit">' + 
		'<option value="1">m</option>' + 
		'<option value="60">h</option>' + 
		'<option value="0.016666666666666666">s</option>' + 
		'</select>';
	*/
	var timeSelectType = '<select id="%id%" name="%name%" class="unit">' + 
		'<option value="60">m</option>' + 
		'<option value="3600">h</option>' + 
		'<option value="1">s</option>' + 
		'</select>';
	
	
	var recipeFieldOrder = 	[/*['AIN_ID','TOO_ID','ING_ID','STE_GRAMS'],*/
							['STE_STEP_DURATION','STE_CELSIUS','STE_KPA'],
							['STE_RPM','STE_STIR_RUN','STE_STIR_PAUSE','STE_CLOCKWISE']];



	function addInputTableField(valueName, value, patternElem, insertElem, type, fieldOrder){
		var name = patternElem.attr('name');
		var pos = name.lastIndexOf('[');
		var patternField = name.substr(pos+1,name.length-pos-2);
		var name = name.substr(0,pos+1) + valueName + ']';
		
		var id = patternElem.attr('id');
		var pos = id.lastIndexOf(patternField);
		id = id.substr(0,pos) + valueName;
		var newInput;
		if (type == 'hidden'){
			newInput = jQuery('<input type="hidden" name="'+name+'" id="'+id+'" value="'+value+'" />');
		} else {
			var newFieldText = '<label for="'+id+'">'+valueName+'<span class="required">*</span></label>';
			var data_type ='';
			if (typeof(fieldTypes[valueName]) !== 'undefined'){
				data_type = fieldTypes[valueName];
			}
			if (data_type == 'number'){
				newFieldText += '<input type="number" name="'+name+'" id="'+id+'" value="'+value+'" />';
			} else if (data_type == 'boolean'){
				newFieldText += '<input type="number" pattern="[01]" name="'+name+'" id="'+id+'" value="'+value+'" />';
			} else if (data_type == 'time'){
				name = name.substr(0,name.length-1);
				newFieldText += '<input type="number" step="any" name="'+name+'_VIEW]" id="'+id+'_VIEW" value="'+value+'" class="viewWithUnit" />';
				newFieldText += timeSelectType.replace('%name%', name+'_UNIT]').replace('%id%',id+'_UNIT');
				newFieldText += '<input type="hidden" name="'+name+']" id="'+id+'" value="'+value+'" class="withUnit" />';
			} else if (data_type == 'weight'){
				name = name.substr(0,name.length-1);
				newFieldText += '<input type="number" name="'+name+'_VIEW]" id="'+id+'_VIEW" value="'+value+'" class="viewWithUnit" />';
				newFieldText += weightSelectType.replace('%name%', name+'_UNIT]').replace('%id%',id+'_UNIT');
				newFieldText += '<input type="hidden" name="'+name+']" id="'+id+'" value="'+value+'" class="withUnit" />';
			} else {
				newFieldText += '<input type="'+type+'" name="'+name+'" id="'+id+'" value="'+value+'" />';
			}
			newFieldText = '<span class="noWrap">' + newFieldText + '</span>';
			newInput = jQuery(newFieldText);
		}
		
		var inserted = false;
		if (typeof(fieldOrder) !== 'undefined'){
			//var lastField = [];
			for(var i=0; i<fieldOrder.length; ++i){
				var lastField = [];
				var insertElemLine = insertElem.find('.row'+i);
				if(insertElemLine.length == 0){
					insertElemLine = jQuery('<span class="row'+i+'"></span>');
					insertElem.append(insertElemLine);
				}
				var fieldList = fieldOrder[i];
				for(var j=0; j<fieldList.length; ++j){
					var field = fieldList[j]
					if (field == valueName){
						if (lastField.length>0){
							newInput.insertAfter(lastField);
							jQuery('<span> </span>').insertAfter(newInput);
						} else if (/*i==0 && */j==0){
							insertElemLine.prepend('<span> </span>');
							insertElemLine.prepend(newInput);
						} else {
							insertElemLine.append(newInput);
							insertElemLine.append('<span> </span>');
						}
						inserted = true;
						break;
					}
					var searchField = insertElemLine.find('[id$='+field+']');
					searchField = searchField.parents('.noWrap:first');
					if (searchField.length > 0){
						if (searchField.next().not('.noWrap').length > 0){
							lastField = searchField.next();
						} else {
							lastField = searchField
						}
					}
				}
				if (inserted){
					break;
				}
			}
		}
		if (!inserted){
			insertElem.append(newInput);
			insertElem.append('<span> </span>');
		}
		return newInput;
	}
	
	function setFieldValue(field, value){
		var oldVal = field.val();
		if (field.prop('tagName') == 'SELECT'){
			field.find('option:selected').removeAttr('selected');
			field.find('option[value=' + value + ']').attr('selected','selected');
		} else {
			field.attr('value',value);
		}
		if (oldVal != value){
			field.change();
		}
	}
	glob.setFieldValue = setFieldValue;
	
	function initRowContainer(container, data, errors, excludingFieldQuery, bevoreRowCallback, eachRowBeginCallback, eachRowEndCallback){
		var emptyLineContainer = container.find('#newLine');
		if (emptyLineContainer.length !== 0 && data.length !== 0){
			container.addClass('initializeRowContainer');
			var lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
			lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
			
			newLineContent = emptyLineContainer.find('input[name=addContent]').attr('value');
			var newLine = jQuery(newLineContent);
			
			if (typeof(bevoreRowCallback) !== 'undefined'){
				bevoreRowCallback(newLine);
			}
			if (typeof(data) === 'string'){
				rows = JSON.parse(data);
			} else if (typeof(data) === 'object'){
				rows = data;
			}
			for (var rowId = 0; rowId<rows.length; rowId++){
				var data_row = rows[rowId];
				newLine = addEmptyRow(emptyLineContainer);
				
				if (typeof(eachRowBeginCallback) !== 'undefined'){
					eachRowBeginCallback(newLine,data_row);
				}
				
				var next = newLine.next();
				var fieldParents;
				if (next.attr('class') == 'addFields'){
					fieldParents = newLine.add(next);
				} else {
					fieldParents = newLine;
				}
				
				var fields = fieldParents.find('[name]'+excludingFieldQuery); //All non hidden input fields
				var withUnit = fields.filter('.withUnit');
				//fields = fields.filter('[type!=hidden]');
				fields = fields.add(withUnit);
				for(var fieldId=0; fieldId<fields.length; fieldId++){
					var field = jQuery(fields[fieldId]);
					
					var name = field.attr('name');
					var pos = name.lastIndexOf('[');
					name = name.substr(pos+1,name.length-pos-2);
					var value = data_row[name];
					if (typeof(value) !== 'undefined' && value !== null){
						setFieldValue(field,value);
					}
				}
				
				if (typeof(eachRowEndCallback) !== 'undefined'){
					eachRowEndCallback(fieldParents,data_row);
				}
			}
			
			var fields = JSON.parse(errors);
			for (var i = 0; i<fields.length; i++){
				var field = fields[i];
				var inputField = jQuery('#'+field);
				if (inputField.hasClass('withUnit')){
					inputField = jQuery('#'+field+'_VIEW');
				}
				if (inputField.hasClass('fancyValue')){
					inputField = jQuery('#'+field+'_DESC');
				}
				inputField.addClass('error');
				jQuery('[for='+field+']').addClass('error');
			}
			container.removeClass('initializeRowContainer');
		} else {
			rows = [];
		}
		container.append(jQuery('<span class="rowContainerInitialized" style="display:none;"></span>'));
	}
	glob.rowContainer.init = initRowContainer;
	
	function clearRowContainer(container){
		var rows = container.find('tbody tr:not(#newLine)');
		rows.remove();
		container.find('.rowContainerInitialized').remove();
	}
	
	glob.rowContainer.clear = clearRowContainer;
	
	function SendDataToBackend(rowContainer){
		if (rowContainer.parent().is('.updateBackend')){
			var url = jQuery('#updateSessionValuesLink').val();
			var form = rowContainer.parents('form:first');
			glob.ShowActivity = false;
			jQuery.ajax({'type':'post', 'url':url, 'data': form.serialize(),'cache':false,/*'success':function(data){
					//alert('success');
				},
				'error':function(xhr){
					//alert('error');
				},*/
			});
			glob.ShowActivity = true;
		}
	}
	
	function SendDataToBackendRow(row, rowNr){
		updateRowTimeouts[rowNr] = undefined;
		if (row.parents('.addRowContainer:first').parent().is('.updateBackend')){
			var url = jQuery('#updateSessionValueLink').val();
			url = glob.urlAddParamStart(url) + 'StepNr=' + rowNr;
			
			if (row.is('.addFields')){
				var prev = row.prev();
				if (prev.is('.odd') || prev.is('.even')){
					row = row.add(prev);
				}
			} else {
				var next = row.next();
				if (next.is('.addFields')){
					row = row.add(next);
				}
			}
			var data = ''
			var inputs = row.find('input').add(row.find('select'));
			inputs.each(function(i){
				var elem = jQuery(this);
				if (i != 0){
					data += '&';
				}
				data += elem.attr('name') + '=' + elem.val();
			});
			glob.ShowActivity = false;
			jQuery.ajax({'type':'post', 'url':url, 'data': data,'cache':false,/*'success':function(data){
					//alert('success');
				},
				'error':function(xhr){
					//alert('error');
				},*/
			});
			glob.ShowActivity = true;
		}
	}
	
	function SendDataToBackendRowCallback(rowNr){
		var timeoutInfo = updateRowTimeouts[rowNr];
		if (typeof(timeoutInfo) !== 'undefined'){
			SendDataToBackendRow(timeoutInfo['row'], rowNr);
		}
	}
	glob.rowContainer.SendDataToBackendRowCallback = SendDataToBackendRowCallback;
	
	function SendDataToBackendRowSetTimeout(row, rowNr){
		var timeoutInfo = updateRowTimeouts[rowNr];
		if (typeof(timeoutInfo) !== 'undefined'){
			window.clearTimeout(timeoutInfo['timeoutId']);
		}
		
		timeoutInfo = new Array();
		timeoutInfo['timeoutId'] = window.setTimeout('glob.rowContainer.SendDataToBackendRowCallback('+rowNr+');', glob.rowContainer.SendDataToBackendRowTimeout);
		timeoutInfo['row'] = row;
		
		updateRowTimeouts[rowNr] = timeoutInfo;
	}
	
	jQuery('body').undelegate('.updateBackend .addRowContainer:not(.initializeRowContainer) input','change').delegate('.updateBackend .addRowContainer:not(.initializeRowContainer) input','change',function(){
		var dataField = jQuery(this);
		var rowNr = getIndexFromFieldName(dataField.attr('name'));
		
		SendDataToBackendRowSetTimeout(dataField.parents('tr:first'), rowNr);
	});
	
	
	//##################################################################################
	//Mealplanner people functions
	function initMealplannerPeopleRowContainer(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		var container = contentParent.find('.people .addRowContainer');
		var data = contentParent.find('#rowsJSON').attr('value');
		var errors = contentParent.find('#errorJSON').attr('value');
		initMealplannerPeopleRowContainerDoIt(container, data, errors);
	}
	
	function initMealplannerPeopleRowContainerDoIt(container, data, errors){
		if (!container.is('.people .addRowContainer')){
			return;
		}
		if (container.find('.rowContainerInitialized').length > 0){
			return;
		}
		initRowContainer(container, data, errors, '', undefined, undefined, function(fieldParents,data_row){
			fieldParents.find('[id*=gda_id_kcal_GDA_]').hide();
			var gender = fieldParents.find('[id$=gender]').val();
			var field = fieldParents.find('[id$=gda_id_kcal_GDA_' + gender + ']');
			field.show();
			updatePeopleText(fieldParents);
		});
	}
	glob.rowContainer.MealplannerPeopleInit = initMealplannerPeopleRowContainerDoIt;
	
	
	jQuery('body').undelegate('.people .addRowContainer .add','click').delegate('.people .addRowContainer .add','click',function(){
		addEmptyRow(jQuery(this).parents('tr:first'));
	});
	
	jQuery('body').undelegate('.people .addRowContainer [id$=gender]','change').delegate('.people .addRowContainer [id$=gender]','change',function(){
		updateFieldsPeople(jQuery(this));
	});
	
	jQuery('body').undelegate('.people .addRowContainer [id*=gda_id_kcal_GDA_]','change').delegate('.people .addRowContainer [id*=gda_id_kcal_GDA_]','change',function(){
		updatePeopleText(jQuery(this).parents('tr:first'));
	});
	
	jQuery('body').undelegate('.people .addRowContainer [id$=amount]','change').delegate('.people .addRowContainer [id$=amount]','change',function(){
		updatePeopleText(jQuery(this).parents('tr:first'));
	});
	
	function updateFieldsPeople(elem){
		var row = elem.parents('tr:first');
		var gender = elem.val();
		var oldField = row.find('[id*=gda_id_kcal_GDA_]:visible');
		oldField.hide();
		var oldIndex = oldField.find('option:selected').index();
		oldField.attr('disabled', 'disabled');
		var field = row.find('[id$=gda_id_kcal_GDA_' + gender + ']');
		field.removeAttr('disabled');
		field.find('option:selected').removeAttr('selected');
		field.find('option').slice(oldIndex,oldIndex+1).attr('selected','selected');
		field.show();
		updatePeopleText(row);
	}
	
	function updatePeopleText(row){
		var amount = row.find('[id$=amount]').val();
		var gender = row.find('[id$=gender]').val();
		var gda = row.find('[id$=gda_id_kcal_GDA_' + gender + '] option:selected').val();
		var pos = gda.indexOf('_');
		gda = gda.substr(pos+1);
		row.find('.value').text(amount*gda);
	}
	
	//##################################################################################
	//Recipe Steps functions

	var toolContent;
	var ingredientSelectContent;
	var weightContent;
	var ingredients;
	var specialFields = {};
	function initRecipeStepsRowContainer(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		if (contentParent.find('.steps .addRowContainer').length == 0){
			return;
		}
		if (contentParent.find('.steps .addRowContainer .rowContainerInitialized').length > 0){
			return;
		}
		
		ingredients = contentParent.find('#ingredientsJSON').attr('value');
		ingredients = JSON.parse(ingredients);
		
		var container = contentParent.find('.steps .addRowContainer');
		var data = contentParent.find('#rowsJSON').attr('value');
		var errors = contentParent.find('#errorJSON').attr('value');
		initRecipeStepsRowContainerDoIt(container, data, errors, false);
	}
	
	function initRecipeStepsRowContainerDoIt(container, data, errors, append){
		if (container.is('.steps .addRowContainer').length == 0){
			return;
		}
		if (container.find('.steps .addRowContainer .rowContainerInitialized').length > 0 && append !== true){
			return;
		}
		
		initRowContainer(container, data, errors, ':not([id$=STT_ID]):not([id$=AIN_ID])', function(newLine){
			var tds = newLine.find('td');
			var toolContentElem = newLine.find('[id*=TOO_ID]');
			var parent = toolContentElem.parents('td:first');
			var index = tds.index(parent);
			toolContent = parent.html();
			toolContentElem.remove();
			specialFields.TOO_ID = [toolContent, index];
			
			var ingredientSelectContentElem = newLine.find('[id$=ING_ID]');
			parent = ingredientSelectContentElem.parents('td:first');
			index = tds.index(parent);
			ingredientSelectContent = parent.html();
			//ingredientSelectContentElem.remove();
			ingredientSelectContentElem.parents('td:first').html('');
			specialFields.ING_ID = [ingredientSelectContent, index];
			
			var weightContentElem = newLine.find('[id*=STE_GRAMS]');
			parent = weightContentElem.parents('td:first');
			index = tds.index(parent);
			weightContent = parent.html();
			weightContentElem.remove();
			specialFields.STE_GRAMS = [weightContent, index];
			
			newLineContent = '<tr class="%class%">' + newLine.html() + '</tr>';
		}, function(newLine, data_row){
			var actionType = newLine.find('[id$=AIN_ID]');
			setFieldValue(actionType,data_row.AIN_ID);
			updateFields(actionType);
		}, function(fieldParents, data_row){
			var ingredientField = fieldParents.find('[id$=ING_ID]');
			if (ingredientField.length > 0){
				var value = data_row['ING_ID'];
				ingredientField.attr('value',value);
				container.find('#' + ingredientField.attr('id') + '_DESC').html(ingredients[value]);
			}
		});
		
		initMultiFancyCoose();
	}
	
	function showActionDetail(ain_id, coi_id, parent){
		var actionsInInfo = jQuery('#actionsInDetails #ain_desc_' + ain_id);
		var details = actionsInInfo.find('#ain_coi_desc_' + ain_id + '_' + coi_id);
		var next = parent.next();
		var destPos;
		
		if (next.length>0 && next.hasClass('addFields')){
			parent = next;
			next = next.next();
		}
		if (next.length>0 && next.hasClass('actionsInInfo')){
			destPos = next.find('td');
			destPos.children().remove();
			if (details.length>0){
				details.clone().appendTo(destPos);
			}
		} else if (details.length>0){
			next = jQuery('<tr class="actionsInInfo"><td colspan="5"></td></tr>')
			destPos = next.find('td');
			next.insertAfter(parent);
			details.clone().appendTo(destPos);
		}
	}
	
	jQuery('body').undelegate('.steps .addRowContainer [id$=AIN_ID]','change').delegate('.steps .addRowContainer [id$=AIN_ID]','change',function(){
		var elem = jQuery(this);
		var coi_id = jQuery('#cookInDisplay').val();
		var parent = elem.parents('tr:first');
		updateFields(elem);
		showActionDetail(elem.val(), coi_id, parent);
		initMultiFancyCoose();
	});
	
	jQuery('body').undelegate('.steps .addRowContainer .add','click').delegate('.steps .addRowContainer .add','click',function(){
		var insertBefore = jQuery(this).parents('tr:first');
		var newRow = addEmptyRow(insertBefore);
		updateFields(newRow.find('[id$=AIN_ID]'));
		initMultiFancyCoose();
		SendDataToBackend(jQuery(this).parents('.addRowContainer:first'));
	});
	
	jQuery('body').undelegate('#recipes-form #cookInDisplay','change').delegate('#recipes-form #cookInDisplay','change',function(){
		var container = jQuery(this).parents('#recipes-form').find('.addRowContainer:first');
		var rows = container.find('tbody tr');
		dataRows = rows.filter('.odd').add(rows.filter('.even'));
		var coi_id = jQuery('#cookInDisplay').val();
		dataRows.each(function(index, parent){
			parent = jQuery(parent);
			var elem = parent.find('[id$=AIN_ID]');
			showActionDetail(elem.val(), coi_id, parent);
		});
	});
	
	function updateFields(elem){
		var row = elem.parents('tr:first');
		var next = row.next();
		var value = elem.attr('value');
		var currentIndexInt = getIndexFromFieldName(elem.attr('name'));
		
		var defaults = [];
		var required = [];
		if (value != ''){
			var actionsInInfo = jQuery('#actionsInDetails #ain_desc_' + value);
			try {
				eval('defaults = '+actionsInInfo.find('.actionDefaults').val()+';');
				eval('required = '+actionsInInfo.find('.actionRequireds').val()+';');
			} catch(ex){}
		}
		
		if ((defaults.length === 0 || (defaults.length == 1 && defaults[0] == "")) && (required.length === 0 || (required.length == 1 && required[0] == ""))){
			if (next.attr('class') == 'addFields'){
				next.remove();
			}
		} else {
			if (next.attr('class') != 'addFields'){
				next = jQuery('<tr class="addFields"><td colspan="'+row.children('td').length+'"></td></tr>');
				next.insertAfter(row);
			}
			var insertElem = next.find('td');
			var fieldParents = row.add(next);
			var oldFields = fieldParents.find('[name]'); //All input fields
			oldFields = oldFields.not(row.find('[id$=AIN_ID]'));
			var hiddenKeyFields = row.children('[type=hidden]');
			oldFields = oldFields.not(hiddenKeyFields);
			
			for(var requiredIndex=0; requiredIndex<required.length; requiredIndex++){
				if (required[requiredIndex] == ''){
					continue;
				}
				var field = fieldParents.find('[id$='+required[requiredIndex]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					if (field.hasClass('withUnit')){
						oldFields = oldFields.not(field.prev());
						oldFields = oldFields.not(field.prev().prev());
					} else {
						if (field.attr('type') == 'hidden' && !field.hasClass('fancyValue')){
							field.remove();
							
							if (typeof(specialFields[required[requiredIndex]]) !== 'undefined'){
								var specialFieldOption = specialFields[required[requiredIndex]];
								newFieldContent = specialFieldOption[0].replace(/%index%/g,currentIndexInt);
								newFieldContent = jQuery(newFieldContent);
								jQuery(row.find('td').get(specialFieldOption[1])).append(newFieldContent);
							} else {
								addInputTableField(required[requiredIndex], '', elem, insertElem, 'number', recipeFieldOrder);
							}
						}
					}
				} else {
					if (typeof(specialFields[required[requiredIndex]]) !== 'undefined'){
						var specialFieldOption = specialFields[required[requiredIndex]];
						newFieldContent = specialFieldOption[0].replace(/%index%/g,currentIndexInt);
						newFieldContent = jQuery(newFieldContent);
						jQuery(row.find('td').get(specialFieldOption[1])).append(newFieldContent);
					} else {
						addInputTableField(required[requiredIndex], '', elem, insertElem, 'number', recipeFieldOrder);
					}
				}
			}
			for(var defaultIndex=0; defaultIndex<defaults.length; defaultIndex++){
				if (defaults[defaultIndex] == ''){
					continue;
				}
				var fieldOpt=defaults[defaultIndex].split('=');
				var field = fieldParents.find('[id$='+fieldOpt[0]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					if (field.hasClass('withUnit')){
						oldFields = oldFields.not(field.prev());
						oldFields = oldFields.not(field.prev().prev());
					}
					if (field.val().length == 0){
						setFieldValue(field,fieldOpt[1]);
					}
					if (required.indexOf(fieldOpt[0]) == -1){
						if (field.parents('tr:first').not(row).length == 0){
							field.attr('disabled','disabled');
							setFieldValue(field,fieldOpt[1]);
							var newInput = addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden', recipeFieldOrder);
							newInput.attr('id',newInput.attr('id') + '_backup');
						} else {
							if (field.attr('type') != 'hidden'){
								next.find('[for='+field.attr('id')+']').remove();
								field.remove();
								addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden', recipeFieldOrder);
							}
						}
					}
				} else {
					addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden', recipeFieldOrder);
				}
			}
			for(var oldFieldIndex=0; oldFieldIndex<oldFields.length; oldFieldIndex++){
				var field = jQuery(oldFields[oldFieldIndex]);
				if (field.parent().hasClass('noWrap')){
					field.parent().remove();
				} else {
					next.find('[for='+field.attr('id')+']').remove();
					if (field.hasClass('fancyValue')){
						fieldParents.find('#'+field.attr('id')+'_DESC').remove();
					}
					field.remove();
				}
			}
			
			//Check show prepared Ingredient link, or normale one
			if (fieldParents.find('[id*=STE_GRAMS]').length>0){
				//Normal link
				var field = fieldParents.find('[id$=ING_ID_DESC]');
				if (field.length>0){
					field.attr('href', jQuery('#ingredientsChooseLink').val());
				}
			} else {
				//prepared link
				var field = fieldParents.find('[id$=ING_ID_DESC]');
				if (field.length>0){
					field.attr('href', jQuery('#preparedIngredientsChooseLink').val());
				}
			}
		}
	}
		
	jQuery('body').undelegate('.fancyForm .button.RecipeTemplateSelect','click').delegate('.fancyForm .button.RecipeTemplateSelect','click', function(){
		var link = jQuery('#stepDetailsLink').val();
		
		var caller = jQuery(this);
		var recipeId = caller.attr('href');
		
		link = glob.urlAddParamStart(link);
		link = link + "id=" + recipeId;
		
		jQuery.ajax({'type':'get', 'url':link,'cache':false,'success':function(data){
				if (data.indexOf('{')===0){
					eval('var data = ' + data + ';');
				}
				if (data.model){
					var rows = jQuery('#recipes-form').children('.row');
					var inputs = rows.find('input').add(rows.find('select'));
					for (var value in data.model){
						var input = inputs.filter('[id$='+value+']');
						if (input.length > 0){
							setFieldValue(input, data.model[value]);
						}
					}
					if (data.model.REC_IMG_FILENAME != null && data.model.REC_IMG_FILENAME !== ''){ //'backup'){
						glob.showImageOrError(jQuery('input[id$=filename][type=file]'), '{imageId:\'backup\'}');
					}
				}
				if (data.recToCois){
					var cookIns = jQuery('#cookIns');
					cookIns.find(':checked').removeAttr('checked');
					for(var i=0; i < data.recToCois.length; ++i){
						var recToCoi = data.recToCois[i]
						cookIns.find('input[value=' + recToCoi.COI_ID+']').attr('checked','checked');
					}
					//Wait for reload after cookInUpdate so steps could add successfull...
					$('#page').bind('newContent.recipeTemplateSelect', function(e, type, contentParent) {
						$('#page').unbind('newContent.recipeTemplateSelect');
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
					jQuery('input[name=updateCookIn]').click();
					jQuery.fancybox.close();
				} else if (data.steps){
					ingredients = data.ingredients;
					var container = jQuery('.steps .addRowContainer');
					//lastIndex = container.find('.odd').add(container.find('.even')).length;
					var emptyLineContainer = container.find('#newLine');
					var lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
					lastIndexElem.attr('value', lastIndex);
					initRecipeStepsRowContainerDoIt(container, data.steps, '[]', true);
				}
				jQuery.fancybox.close();
			},
			'error':function(xhr){
				ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
				jQuery.fancybox.close();
			},
		});
		
		return false;
	});
	
	//##################################################################################
	//AinToAou functions
	function initAinToAouRowContainer(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		var container = contentParent.find('.actions .addRowContainer');
		var data = contentParent.find('#rowsJSON').attr('value');
		var errors = contentParent.find('#errorJSON').attr('value');
		initAinToAouRowContainerDoIt(container, data, errors);
	}
	
	function initAinToAouRowContainerDoIt(container, data, errors){
		if (!container.is('.actions .addRowContainer')){
			return;
		}
		if (container.find('.rowContainerInitialized').length > 0){
			return;
		}
		initRowContainer(container, data, errors, '', undefined, undefined, function(fieldParents,data_row){
		
		});
	}
	glob.rowContainer.AinToAouRowInit = initAinToAouRowContainerDoIt;
	
	
	jQuery('body').undelegate('.actions .addRowContainer .add','click').delegate('.actions .addRowContainer .add','click',function(){
		addEmptyRow(jQuery(this).parents('tr:first'));
	});
	
	jQuery('body').undelegate('.actions .addRowContainer [id*=AOU_ID]','change').delegate('.actions .addRowContainer [id*=AOU_ID]','change',function(){
		var elem = jQuery(this);
		var index = elem.find(':selected').index();
		var next = elem.next();
		if (next.length>0){
			next.remove();
		}
		var details = jQuery('#actionsOutDetails').children().get(index);
		//clone = details = jQuery('<div>'+jQuery(details).html() + '</div>');
		jQuery(details).clone().insertAfter(elem);
	});
	
	//----------------------------------------------


	$('#page').bind('newContent.rowcontainer_handling', function(e, type, contentParent) {
		initRecipeStepsRowContainer(type, contentParent);
		initMealplannerPeopleRowContainer(type, contentParent);
		initAinToAouRowContainer(type, contentParent);
	});
	initRecipeStepsRowContainer('initial', jQuery('#page'));
	initMealplannerPeopleRowContainer('initial', jQuery('#page'));
	initAinToAouRowContainer('initial', jQuery('#page'));
});