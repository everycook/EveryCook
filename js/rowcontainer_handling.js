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
		
		var container = jQuery('.addRowContainer');
		var emptyLineContainer = container.find('#newLine');
		var data = jQuery('#rowsJSON').attr('value');
		if (emptyLineContainer.length !== 0 && data.length !== 0){
			var lastIndexElem = emptyLineContainer.find('input[name=lastIndex]')
			lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
			
			newLineContent = emptyLineContainer.find('input[name=addContent]').attr('value');
			var newLine = jQuery(newLineContent);
			var ingredientSelectContentElem = newLine.find('[id$=ING_ID]');
			ingredientSelectContent = ingredientSelectContentElem.parents(':first').html();
			//ingredientSelectContentElem.remove();
			ingredientSelectContentElem.parents(':first').html('');
			var weightContentElem = newLine.find('[id$=STE_GRAMS]');
			weightContent = weightContentElem.parents(':first').html();
			weightContentElem.remove();
			newLineContent = '<tr class="%class%">' + newLine.html() + '</tr>';
			
			ingredients = jQuery('#ingredientsJSON').attr('value');
			ingredients = JSON.parse(ingredients);
			
			rows = JSON.parse(data);
			for (var i = 0; i<rows.length; i++){
				var data_row = rows[i];
				newLine = addEmptyRow(emptyLineContainer);
				var stepType = newLine.find('[id$=STT_ID]');
				stepType.attr('value', data_row.STT_ID);
				updateFields(stepType);
				
				var actionType = newLine.find('[id$=ACT_ID]');
				actionType.attr('value', data_row.ACT_ID);
				updateIngredientVisible(actionType);
				
				var next = newLine.next();
				var fieldParents;
				if (next.attr('class') == 'addFields'){
					fieldParents = newLine.add(next);
				} else {
					fieldParents = newLine;
				}
				
				var fields = fieldParents.find('[name][type!=hidden]:not([id$=STT_ID]):not([id$=ACT_ID])'); //All non hidden input fields
				for(var j=0; j<fields.length; j++){
					var field = jQuery(fields[j]);
					
					var name = field.attr('name');
					var pos = name.lastIndexOf('[');
					name = name.substr(pos+1,name.length-pos-2);
					var value = data_row[name];
					field.attr('value',value);
				}
				
				var ingredientField = fieldParents.find('[id$=ING_ID]');
				if (ingredientField.length > 0){
					var value = data_row['ING_ID'];
					ingredientField.attr('value',value);
					jQuery('#' + ingredientField.attr('id') + '_DESC').html(ingredients[value]);
				}
			}
			
			var errors = jQuery('#errorJSON').attr('value');
			var fields = JSON.parse(errors);
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
		
		var prev = row.prev();
		if (prev.attr('class') !== 'addFields'){
			prev = null;
		}
		
		var nextEntry = row.next();
		if (nextEntry.attr('class') === 'addFields'){
			nextEntry = nextEntry.next();
		}
		
		if (nextEntry.not(row.nextAll().last()).length){
			changeInputTableIndex(row, 1);
			changeInputTableIndex(nextEntry, -1);
			row.insertAfter(nextEntry);			
			if (prev != null){
				prev.insertBefore(row);
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
	
	function updateFields(elem){
		var row = elem.parents('tr:first');
		var next = row.next();
		var value = elem.attr('value');
		
		var defaults = stepConfig[value].STT_DEFAULT.split(';');
		var required = stepConfig[value].STT_REQUIRED.split(';');
		
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
			var insertElem = next.find('td');
			var fieldParents = row.add(next);
			var oldFields = next.find('[name]'); //All input fields
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
			for(var i=0; i<required.length; i++){
				var field = fieldParents.find('[id$='+required[i]+']');
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
				var fieldOpt=defaults[i].split('=');
				var field = fieldParents.find('[id$='+fieldOpt[0]+']');
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
				var field = jQuery(oldFields[i]);
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
		var value = elem.attr('value');
		var text = elem.find(':selected').get(0).text;
		var row = elem.parents('tr:first');
		var ingredientElem = row.find('[id$=ING_ID]');
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