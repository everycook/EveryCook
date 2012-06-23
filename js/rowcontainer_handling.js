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
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initRecipeStepsRowContainer();
		initMealplanerPeopleRowContainer();
	});
	initRecipeStepsRowContainer();
	initMealplanerPeopleRowContainer();
	
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
		
		var currentIndexInt = getIndexFromFieldName(inputs.attr('name'))
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
		});
	}
	
	function addEmptyRow(emptyLineContainer){
		var currentNewLineContent = newLineContent.replace('%class%',(lastIndex % 2 == 1)?'odd':'even');
		currentNewLineContent = currentNewLineContent.replace(/%index%/g,lastIndex);
		
		lastIndex = lastIndex+1;
		
		currentNewLineContent = jQuery(currentNewLineContent);
		currentNewLineContent.insertBefore(emptyLineContainer);
		return currentNewLineContent;
	}
	
	jQuery('body').undelegate('.addRowContainer .add','click').delegate('.addRowContainer .add','click',function(){
		addEmptyRow(jQuery(this).parents('tr:first'));
	});
	
	jQuery('body').undelegate('.addRowContainer .remove','click').delegate('.addRowContainer .remove','click',function(){
		var row = jQuery(this).parents('tr:first');
		var followedRows = row.nextAll();
		
		lastIndex = lastIndex-1;
		
		row.remove();
		
		followedRows = followedRows.not(followedRows.last());
		followedRows.each(function(){
			changeInputTableIndex(jQuery(this), -1);
		});
	});
	
	jQuery('body').undelegate('.addRowContainer .up','click').delegate('.addRowContainer .up','click',function(){
		var row = jQuery(this).parents('tr:first');
		
		var next = row.next();
		if (next.attr('class') !== 'addFields'){
			next = null;
		}
		
		var prevEntry = row.prev();
		if (prevEntry.attr('class') === 'addFields'){
			prevEntry = prevEntry.prev();
		}
		
		if (prevEntry.length){
			changeInputTableIndex(row, -1);
			changeInputTableIndex(prevEntry, 1);
			row.insertBefore(prevEntry);
			if (next != null){
				next.insertAfter(row);
			}
		}
	});

	jQuery('body').undelegate('.addRowContainer .down','click').delegate('.addRowContainer .down','click',function(){
		var row = jQuery(this).parents('tr:first');
		
		var nextEntry;
		var next = row.next();
		if (next.attr('class') !== 'addFields'){
			nextEntry = next;
			next = null;
		} else {
			nextEntry = next.next();
		}
		var insertPos = nextEntry.next();
		if (insertPos.attr('class') !== 'addFields'){
			insertPos = nextEntry;
		}
		
		if (nextEntry.not(row.nextAll().last()).length){
			changeInputTableIndex(row, 1);
			changeInputTableIndex(nextEntry, -1);
			row.insertAfter(insertPos);			
			if (next != null){
				next.insertAfter(row);
			}
		}
	});
	
	function addInputTableField(valueName, value, patternElem, insertElem, type){
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
			newInput = jQuery('<label for="'+id+'">'+valueName+'<span class="required">*</span></label><input type="'+type+'" name="'+name+'" id="'+id+'" value="'+value+'" />');
		}
		insertElem.append(newInput);
	}
	
	function setFieldValue(field, value){
		if (field.prop('tagName') == 'SELECT'){
			field.find('option:selected').removeAttr('selected');
			field.find('option[value=' + value + ']').attr('selected','selected');
		} else {
			field.attr('value',value);
		}
	}
	
	
	function initRowContainer(excludingFieldQuery, bevoreRowCallback, eachRowBeginCallback, eachRowEndCallback){
		var container = jQuery('.addRowContainer');
		var emptyLineContainer = container.find('#newLine');
		var data = jQuery('#rowsJSON').attr('value');
		if (emptyLineContainer.length !== 0 && data.length !== 0){
			var lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
			lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
			
			newLineContent = emptyLineContainer.find('input[name=addContent]').attr('value');
			var newLine = jQuery(newLineContent);
			
			if (typeof(bevoreRowCallback) !== 'undefined'){
				bevoreRowCallback(newLine);
			}
			rows = JSON.parse(data);
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
				
				var fields = fieldParents.find('[name][type!=hidden]'+excludingFieldQuery); //All non hidden input fields
				for(var fieldId=0; fieldId<fields.length; fieldId++){
					var field = jQuery(fields[fieldId]);
					
					var name = field.attr('name');
					var pos = name.lastIndexOf('[');
					name = name.substr(pos+1,name.length-pos-2);
					var value = data_row[name];
					setFieldValue(field,value);
				}
				
				if (typeof(eachRowEndCallback) !== 'undefined'){
					eachRowEndCallback(fieldParents,data_row);
				}
			}
			
			var errors = jQuery('#errorJSON').attr('value');
			var fields = JSON.parse(errors);
			for (var i = 0; i<fields.length; i++){
				field = fields[i];
				jQuery('#'+field).addClass('error');
				jQuery('[for='+field+']').addClass('error');
			}
		} else {
			rows = [];
		}
	}
	
	
	//##################################################################################
	//Mealplaner people functions
	function initMealplanerPeopleRowContainer(){
		if (jQuery('.people .addRowContainer').length == 0){
			return;
		}
		initRowContainer('',undefined,undefined,function(fieldParents,data_row){
			fieldParents.find('[id*=gda_id_kcal_GDA_]').hide();
			var gender = fieldParents.find('[id$=gender]').val();
			var field = fieldParents.find('[id$=gda_id_kcal_GDA_' + gender + ']');
			field.show();
			updatePeopleText(fieldParents);
		});
	}
	
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
		
		var field = row.find('[id$=gda_id_kcal_GDA_' + gender + ']');
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
	
	var stepConfig;
	var ingredientSelectContent;
	var weightContent;
	var ingredients;
	function initRecipeStepsRowContainer(){
		if (jQuery('.steps .addRowContainer').length == 0){
			return;
		}
		
		stepConfig = jQuery('#stepConfigValues').attr('value');
		if (stepConfig && stepConfig.length > 0){
			stepConfig = JSON.parse(stepConfig);
		} else {
			stepConfig = [];
			return;
		}
		
		ingredients = jQuery('#ingredientsJSON').attr('value');
		ingredients = JSON.parse(ingredients);
		
		initRowContainer(':not([id$=STT_ID]):not([id$=ACT_ID])', function(newLine){
			var ingredientSelectContentElem = newLine.find('[id$=ING_ID]');
			ingredientSelectContent = ingredientSelectContentElem.parents(':first').html();
			//ingredientSelectContentElem.remove();
			ingredientSelectContentElem.parents(':first').html('');
			var weightContentElem = newLine.find('[id$=STE_GRAMS]');
			weightContent = weightContentElem.parents(':first').html();
			weightContentElem.remove();
			newLineContent = '<tr class="%class%">' + newLine.html() + '</tr>';
		}, function(newLine, data_row){
			var stepType = newLine.find('[id$=STT_ID]');
			setFieldValue(stepType,data_row.STT_ID);
			updateFields(stepType);
			
			var actionType = newLine.find('[id$=ACT_ID]');
			setFieldValue(actionType,data_row.ACT_ID);
			updateIngredientVisible(actionType);
		}, function(fieldParents, data_row){
			var ingredientField = fieldParents.find('[id$=ING_ID]');
			if (ingredientField.length > 0){
				var value = data_row['ING_ID'];
				ingredientField.attr('value',value);
				jQuery('#' + ingredientField.attr('id') + '_DESC').html(ingredients[value]);
			}
		});
		
		initMultiFancyCoose();
	}
	
	function getStepConfig(id){
		for(var i=0; i<stepConfig.length; i++){
			if (stepConfig[i].STT_ID == id){
				return stepConfig[i];
			}
		}
		return null;
	}
	
	function updateFields(elem){
		var row = elem.parents('tr:first');
		var next = row.next();
		var value = elem.attr('value');
		
		var curStepConfig = getStepConfig(value);
		
		if (curStepConfig != null){
			var defaults = curStepConfig.STT_DEFAULT.split(';');
			var required = curStepConfig.STT_REQUIRED.split(';');
		} else {
			var defaults = [];
			var required = [];
		}
		
		if ((defaults.length === 0 || (defaults.length == 1 && defaults[0] == "")) && (required.length === 0 || (required.length == 1 && required[0] == ""))){
			if (next.attr('class') == 'addFields'){
				next.remove();
			}
			row.find('[id$=ACT_ID]').removeAttr('disabled');
		} else {
			if (next.attr('class') != 'addFields'){
				next = jQuery('<tr class="addFields"><td colspan="'+row.children('td').length+'"></td></tr>');
				next.insertAfter(row);
			}
			var insertElem = next.find('td');
			var fieldParents = row.add(next);
			var oldFields = next.find('[name]'); //All input fields
			/*
			//now always needed if Action includes #objectofaction#, see function updateIngredientVisible(elem);
			var weightElem = row.find('[id$=STE_GRAMS]');
			if (required.indexOf('STE_GRAMS') == -1){
				weightElem.remove();
			} else {
				if (weightElem.length == 0){
					currentIndexInt = getIndexFromFieldName(elem.attr('name'))
					newWeightContent = weightContent.replace(/%index%/g,currentIndexInt);
					newWeightContent = jQuery(newWeightContent);
					jQuery(row.find('td').get(3)).append(newWeightContent);
				}
			}
			*/
			
			for(var requiredId=0; requiredId<required.length; requiredId++){
				if (required[requiredId] == ''){
					continue;
				}
				var field = fieldParents.find('[id$='+required[requiredId]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					if (field.attr('type') == 'hidden'){
						field.remove();
						addInputTableField(required[requiredId], '', elem, insertElem, 'number');
					}
				} else {
					addInputTableField(required[requiredId], '', elem, insertElem, 'number');
				}
			}
			for(var defaultId=0; defaultId<defaults.length; defaultId++){
				if (defaults[defaultId] == ''){
					continue;
				}
				var fieldOpt=defaults[defaultId].split('=');
				var field = fieldParents.find('[id$='+fieldOpt[0]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					setFieldValue(field,fieldOpt[1]);
					if (required.indexOf(fieldOpt[0]) == -1){
						if (field.parents('tr:first').not(row).length == 0){
							field.attr('disabled','disabled');
						} else {
							if (field.attr('type') != 'hidden'){
								next.find('[for='+field.attr('id')+']').remove();
								field.remove();
								addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden');
							}
						}
					}
				} else {
					addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden');
				}
			}
			for(var oldFieldId=0; oldFieldId<oldFields.length; oldFieldId++){
				var field = jQuery(oldFields[oldFieldId]);
				next.find('[for='+field.attr('id')+']').remove();
				field.remove();
			}
		}
	}
	
	jQuery('body').undelegate('.steps .addRowContainer [id$=STT_ID]','change').delegate('.steps .addRowContainer [id$=STT_ID]','change',function(){
		updateFields(jQuery(this));
		updateIngredientVisible(jQuery(this).parents('tr:first').find('[id$=ACT_ID]'));
		initMultiFancyCoose();
	});
	
	function updateIngredientVisible(elem){
		var value = elem.attr('value');
		var text = elem.find(':selected').get(0).text;
		var row = elem.parents('tr:first');
		var ingredientElem = row.find('[id$=ING_ID]');
		var weightElem = row.find('[id$=STE_GRAMS]');
		if (text.indexOf('#objectofaction#') == -1){
			ingredientElem.remove();
			weightElem.remove();
		} else {
			currentIndexInt = getIndexFromFieldName(elem.attr('name'));
			if (ingredientElem.length == 0){
				newIngredientSelectContent = ingredientSelectContent.replace(/%index%/g,currentIndexInt);
				newIngredientSelectContent = jQuery(newIngredientSelectContent);
				jQuery(row.find('td').get(2)).append(newIngredientSelectContent);
			}
			if (weightElem.length == 0){
				newWeightContent = weightContent.replace(/%index%/g,currentIndexInt);
				newWeightContent = jQuery(newWeightContent);
				jQuery(row.find('td').get(3)).append(newWeightContent);
			}
		}
	}
	
	jQuery('body').undelegate('.steps .addRowContainer [id$=ACT_ID]','change').delegate('.steps .addRowContainer [id$=ACT_ID]','change',function(){
		updateIngredientVisible(jQuery(this));
		initMultiFancyCoose();
	});
});