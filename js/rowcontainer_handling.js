jQuery(function($){
	/*use the one in ajax_handling.js*/
	function initMultiFancyCoose(){
		jQuery('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input:first').addClass('activeFancyField');
		});
		jQuery('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
	}
	
	
	var rows;
	var stepConfig;
	var lastIndex = 0;
	var newLineContent;
	var ingredientSelectContent;
	var weightContent;
	var ingredients;
	function initRowContainer(){
		stepConfig = jQuery('#stepConfigValues').attr('value');
		if (stepConfig && stepConfig.length > 0){
			stepConfig = JSON.parse(stepConfig);
		} else {
			stepConfig = [];
		}
		
		container = jQuery('.addRowContainer');
		emptyLineContainer = container.find('#newLine');
		data = jQuery('#rowsJSON').attr('value');
		if (emptyLineContainer.length !== 0 && data.length !== 0){
			lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
			lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
			
			newLineContent = emptyLineContainer.find('input[name=addContent]').attr('value');
			newLine = jQuery(newLineContent);
			ingredientSelectContentElem = newLine.find('[id$=ING_ID]');
			ingredientSelectContent = ingredientSelectContentElem.parents(':first').html();
			//ingredientSelectContentElem.remove();
			ingredientSelectContentElem.parents(':first').html('');
			weightContentElem = newLine.find('[id$=STE_GRAMS]');
			weightContent = weightContentElem.parents(':first').html();
			weightContentElem.remove();
			newLineContent = '<tr class="%class%">' + newLine.html() + '</tr>';
			
			ingredients = jQuery('#ingredientsJSON').attr('value');
			ingredients = JSON.parse(ingredients);
			
			rows = JSON.parse(data);
			for (var i = 0; i<rows.length; i++){
				data_row = rows[i];
				newLine = addEmptyRow(emptyLineContainer);
				stepType = newLine.find('[id$=STT_ID]');
				stepType.attr('value', data_row.STT_ID);
				updateFields(stepType);
				
				actionType = newLine.find('[id$=ACT_ID]');
				actionType.attr('value', data_row.ACT_ID);
				updateIngredientVisible(actionType);
				
				next = newLine.next();
				if (next.attr('class') == 'addFields'){
					fieldParents = newLine.add(next);
				} else {
					fieldParents = newLine;
				}
				
				fields = fieldParents.find('[name][type!=hidden]:not([id$=STT_ID]):not([id$=ACT_ID])'); //All non hidden input fields
				
				for(var j=0; j<fields.length; j++){
					field = jQuery(fields[j]);
					
					name = field.attr('name');
					pos = name.lastIndexOf('[');
					name = name.substr(pos+1,name.length-pos-2);
				}
				
				ingredientField = fieldParents.find('[id$=ING_ID]');
				if (ingredientField.length > 0){
					value = data_row['ING_ID'];
					ingredientField.attr('value',value);
					jQuery('#' + ingredientField.attr('id') + '_DESC').html(ingredients[value]);
				}
			}
			
			errors = jQuery('#errorJSON').attr('value');
			fields = JSON.parse(errors);
			for (var i = 0; i<fields.length; i++){
				field = fields[i];
				jQuery('#'+field).addClass('error');
				jQuery('[for='+field+']').addClass('error');
			}
			initMultiFancyCoose();
		} else {
			rows = [];
		}
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initRowContainer();
	});
	initRowContainer();
	
	function getIndexFromFieldName(name){
		currentIndex = name.match(/\[([^\]]+)\]/);
		if (!currentIndex){
			return false;
		}
		return new Number(currentIndex[1]).valueOf();
	}
	
	function changeInputTableIndex(elem, changeamount){
		inputs = elem.find('[name]');//All input fields
		
		currentIndexInt = getIndexFromFieldName(inputs.attr('name'))
		if (currentIndexInt === false) {
			return false;
		}
		newIndexInt = currentIndexInt+changeamount;
		
		newIndexIntStr = '['+newIndexInt+']';
		newIndexIntStr2 = '_'+newIndexInt+'_';
		currentIndexStr = '['+currentIndexInt+']';
		currentIndexStr2 = '_'+currentIndexInt+'_';
		
		elem.attr('class', (newIndexInt % 2 == 1)?'odd':'even');
		
		inputs.each(function(){
			elem = jQuery(this);
			elem.attr('name', elem.attr('name').replace(currentIndexStr,newIndexIntStr));
			elem.attr('id', elem.attr('id').replace(currentIndexStr2,newIndexIntStr2));
		});
	}
	
	function addEmptyRow(emptyLineContainer){
		currentNewLineContent = newLineContent.replace('%class%',(lastIndex % 2 == 1)?'odd':'even');
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
		row = jQuery(this).parents('tr:first');
		followedRows = row.nextAll();
		
		lastIndex = lastIndex-1;
		
		row.remove();
		
		followedRows = followedRows.not(followedRows.last());
		followedRows.each(function(){
			changeInputTableIndex(jQuery(this), -1);
		});
	});
	
	jQuery('body').undelegate('.addRowContainer .up','click').delegate('.addRowContainer .up','click',function(){
		row = jQuery(this).parents('tr:first');
		if (row.prev().length){
			changeInputTableIndex(row, -1);
			changeInputTableIndex(row.prev(), 1);
			row.insertBefore(row.prev());
		}
	});

	jQuery('body').undelegate('.addRowContainer .down','click').delegate('.addRowContainer .down','click',function(){
		row = jQuery(this).parents('tr:first');
		followedRows = row.nextAll();
		if (row.next().not(row.nextAll().last()).length){
			changeInputTableIndex(row, 1);
			changeInputTableIndex(row.next(), -1);
			row.insertAfter(row.next());
		}
	});
	
	function addInputTableField(valueName, value, patternElem, insertElem, type){
		name = patternElem.attr('name');
		pos = name.lastIndexOf('[');
		patternField = name.substr(pos+1,name.length-pos-2);
		name = name.substr(0,pos+1) + valueName + ']';
		
		id = patternElem.attr('id');
		pos = id.lastIndexOf(patternField);
		id = id.substr(0,pos) + valueName;
		if (type == 'hidden'){
			newInput = jQuery('<input type="hidden" name="'+name+'" id="'+id+'" value="'+value+'" />');
		} else {
			newInput = jQuery('<label for="'+id+'">'+valueName+'<span class="required">*</span></label><input type="'+type+'" name="'+name+'" id="'+id+'" value="'+value+'" />');
		}
		insertElem.append(newInput);
	}
	
	function updateFields(elem){
		row = elem.parents('tr:first');
		next = row.next();
		value = elem.attr('value');
		
		defaults = stepConfig[value].STT_DEFAULT.split(';');
		required = stepConfig[value].STT_REQUIRED.split(';');
		
		if ((defaults.length === 0 || (defaults.length == 1 && defaults[0] == "")) && (required.length === 0 || (required.length == 1 && required[0] == ""))){
			if (next.attr('class') == 'addFields'){
				next.remove();
			}
			row.find('[id$=ACT_ID]').attr('disabled',false);
		} else {
			if (next.attr('class') != 'addFields'){
				next = jQuery('<tr class="addFields"><td colspan="'+row.children('td').length+'"></td></tr>');
				next.insertAfter(row);
			}
			insertElem = next.find('td');
			fieldParents = row.add(next);
			oldFields = next.find('[name]'); //All input fields
			weightElem = row.find('[id$=STE_GRAMS]');
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
			for(var i=0; i<required.length; i++){
				field = fieldParents.find('[id$='+required[i]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					if (field.attr('type') == 'hidden'){
						field.remove();
						addInputTableField(required[i], '', elem, insertElem, 'number');
					}
				} else {
					addInputTableField(required[i], '', elem, insertElem, 'number');
				}
			}
			for(var i=0; i<defaults.length; i++){
				fieldOpt=defaults[i].split('=');
				field = fieldParents.find('[id$='+fieldOpt[0]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					field.attr('value',fieldOpt[1]);
					if (required.indexOf(fieldOpt[0]) == -1){
						if (field.parents('tr:first').not(row).length == 0){
							field.attr('disabled',true);
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
			for(var i=0; i<oldFields.length; i++){
				field = jQuery(oldFields[i]);
				next.find('[for='+field.attr('id')+']').remove();
				field.remove();
			}
		}
	}
	
	jQuery('body').undelegate('.addRowContainer [id$=STT_ID]','change').delegate('.addRowContainer [id$=STT_ID]','change',function(){
		updateFields(jQuery(this));
		updateIngredientVisible(jQuery(this).parents('tr:first').find('[id$=ACT_ID]'));
		initMultiFancyCoose();
	});
	
	function updateIngredientVisible(elem){
		value = elem.attr('value');
		text = elem.find(':selected').get(0).text;
		row = elem.parents('tr:first');
		ingredientElem = row.find('[id$=ING_ID]');
		if (text.indexOf('#objectofaction#') == -1){
			ingredientElem.remove();
		} else {
			if (ingredientElem.length == 0){
				currentIndexInt = getIndexFromFieldName(elem.attr('name'))
				newIngredientSelectContent = ingredientSelectContent.replace(/%index%/g,currentIndexInt);
				newIngredientSelectContent = jQuery(newIngredientSelectContent);
				jQuery(row.find('td').get(2)).append(newIngredientSelectContent);
			}
		}
	}
	
	jQuery('body').undelegate('.addRowContainer [id$=ACT_ID]','change').delegate('.addRowContainer [id$=ACT_ID]','change',function(){
		updateIngredientVisible(jQuery(this));
		initMultiFancyCoose();
	});
});