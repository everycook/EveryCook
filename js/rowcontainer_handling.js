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
			prev = elem.prev();
			if (prev.is('label')){
				prev.attr('for', prev.attr('for').replace(currentIndexStr2,newIndexIntStr2));	
			}
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
	
	/*
	jQuery('body').undelegate('.addRowContainer .add','click').delegate('.addRowContainer .add','click',function(){
		addEmptyRow(jQuery(this).parents('tr:first'));
	});
	*/
	
	jQuery('body').undelegate('.addRowContainer .remove','click').delegate('.addRowContainer .remove','click',function(){
		var row = jQuery(this).parents('tr:first');
		var followedRows = row.nextAll().not('.addFields');
		
		lastIndex = lastIndex-1;
		
		var next = row.next();
		if (next.attr('class') === 'addFields'){
			next.remove();
		}
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
		var inputField = jQuery(this)
		var unitField = inputField.next();
		var multiplier = unitField.val();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
		}
	});
	
	jQuery('body').undelegate('.addRowContainer .unit','change').delegate('.addRowContainer .unit','change',function(){
		var unitField = jQuery(this)
		var multiplier = unitField.val();
		var inputField = unitField.prev();
		var dataField = unitField.next();
		var value = inputField.val();
		if (value !== ''){
			dataField.val(value * multiplier);
		}
	});
	
	jQuery('body').undelegate('.addRowContainer .withUnit','change').delegate('.addRowContainer .withUnit','change',function(){
		var dataField = jQuery(this)
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
			newInput = jQuery(newFieldText);
		}
		insertElem.append(newInput);
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
	
	
	function initRowContainer(container, data, errors, excludingFieldQuery, bevoreRowCallback, eachRowBeginCallback, eachRowEndCallback){
		var emptyLineContainer = container.find('#newLine');
		if (emptyLineContainer.length !== 0 && data.length !== 0){
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
				fields = fields.filter('[type!=hidden]');
				fields = fields.add(withUnit);
				for(var fieldId=0; fieldId<fields.length; fieldId++){
					var field = jQuery(fields[fieldId]);
					
					var name = field.attr('name');
					var pos = name.lastIndexOf('[');
					name = name.substr(pos+1,name.length-pos-2);
					var value = data_row[name];
					if (typeof(value) !== 'undefined'){
						setFieldValue(field,value);
					}
				}
				
				if (typeof(eachRowEndCallback) !== 'undefined'){
					eachRowEndCallback(fieldParents,data_row);
				}
			}
			
			var fields = JSON.parse(errors);
			for (var i = 0; i<fields.length; i++){
				field = fields[i];
				jQuery('#'+field).addClass('error');
				jQuery('[for='+field+']').addClass('error');
			}
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
	function initRecipeStepsRowContainer(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		if (contentParent.find('.steps .addRowContainer').length == 0){
			return;
		}
		if (contentParent.find('.steps .addRowContainer .rowContainerInitialized').length > 0){
			return;
		}
		
		stepConfig = contentParent.find('#stepConfigValues').attr('value');
		if (stepConfig && stepConfig.length > 0){
			stepConfig = JSON.parse(stepConfig);
		} else {
			stepConfig = [];
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
		
		initRowContainer(container, data, errors, ':not([id$=STT_ID]):not([id*=ACT_ID])', function(newLine){
			var ingredientSelectContentElem = newLine.find('[id$=ING_ID]');
			ingredientSelectContent = ingredientSelectContentElem.parents(':first').html();
			//ingredientSelectContentElem.remove();
			ingredientSelectContentElem.parents(':first').html('');
			var weightContentElem = newLine.find('[id*=STE_GRAMS]');
			weightContent = weightContentElem.parents(':first').html();
			weightContentElem.remove();
			newLineContent = '<tr class="%class%">' + newLine.html() + '</tr>';
		}, function(newLine, data_row){
			var stepType = newLine.find('[id$=STT_ID]');
			setFieldValue(stepType,data_row.STT_ID);
			updateFields(stepType);
			
			var actionType = newLine.find('[id*=ACT_ID]');
			setFieldValue(actionType,data_row.ACT_ID);
			updateIngredientVisible(actionType);
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
	
	
	jQuery('body').undelegate('.steps .addRowContainer .add','click').delegate('.steps .addRowContainer .add','click',function(){
		var insertBefore = jQuery(this).parents('tr:first');
		var newRow = addEmptyRow(insertBefore);
		updateFields(newRow.find('[id$=STT_ID]'));
		updateIngredientVisible(newRow.find('[id*=ACT_ID]'));
		if(jQuery('#CookVariant').val() == 0){
			newRow.find('[id$=ACT_ID_0]').css('display','block'); //.removeAttr('disabled')
			newRow.find('[id$=ACT_ID_1]').css('display','none'); //.attr('disabled','disabled')
		} else {
			newRow.find('[id$=ACT_ID_0]').css('display','none');
			newRow.find('[id$=ACT_ID_1]').css('display','block');
		}
		newRow.find('[id*=ACT_ID]')
		initMultiFancyCoose();
	});
	
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
		
		row.find('[id*=ACT_ID]').removeAttr('disabled');
		next.find('[id$=ACT_ID_backup]').remove();
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
						if (field.attr('type') == 'hidden'){
							field.remove();
							addInputTableField(required[requiredIndex], '', elem, insertElem, 'number');
						}
					}
				} else {
					addInputTableField(required[requiredIndex], '', elem, insertElem, 'number');
				}
			}
			for(var defaultIndex=0; defaultIndex<defaults.length; defaultIndex++){
				if (defaults[defaultIndex] == ''){
					continue;
				}
				var fieldOpt=defaults[defaultIndex].split('=');
				var field = fieldParents.find('[id*='+fieldOpt[0]+']');
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
							var newInput = addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, 'hidden');
							newInput.attr('id',newInput.attr('id') + '_backup');
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
			for(var oldFieldIndex=0; oldFieldIndex<oldFields.length; oldFieldIndex++){
				var field = jQuery(oldFields[oldFieldIndex]);
				next.find('[for='+field.attr('id')+']').remove();
				field.remove();
			}
		}
	}
	
	jQuery('body').undelegate('.steps .addRowContainer [id$=STT_ID]','change').delegate('.steps .addRowContainer [id$=STT_ID]','change',function(){
		updateFields(jQuery(this));
		updateIngredientVisible(jQuery(this).parents('tr:first').find('[id*=ACT_ID]'));
		initMultiFancyCoose();
	});
	
	function updateIngredientVisible(elem){
		var value = elem.attr('value');
		var text = elem.find(':selected').get(0).text;
		if (text != ''){ //do not change, because thre is no value for this display varaint (auto/man)
			var row = elem.parents('tr:first');
			var ingredientElem = row.find('[id*=ING_ID]');
			var weightElem = row.find('[id*=STE_GRAMS]');
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
	}
	
	jQuery('body').undelegate('.steps .addRowContainer [id*=ACT_ID]','change').delegate('.steps .addRowContainer [id*=ACT_ID]','change',function(){
		var elem = jQuery(this);
		setFieldValue(elem.parent().find('[id*=ACT_ID]').not(elem),elem.val());
		updateIngredientVisible(elem);
		initMultiFancyCoose();
	});
	
	
	
	jQuery('body').undelegate('#recipes-form .button#RecipeAuto','click').delegate('#recipes-form .button#RecipeAuto','click', function(){
		var elem = jQuery(this);
		elem.parents('form:first').find('[id$=ACT_ID_0]').css('display','block'); //.removeAttr('disabled')
		elem.parents('form:first').find('[id$=ACT_ID_1]').css('display','none'); //.attr('disabled','disabled')
		jQuery('#CookVariant').val(0);
		elem.siblings().removeClass('selected');
		elem.addClass('selected');
	});
	
	jQuery('body').undelegate('#recipes-form .button#RecipeMan','click').delegate('#recipes-form .button#RecipeMan','click', function(){
		var elem = jQuery(this);
		elem.parents('form:first').find('[id$=ACT_ID_0]').css('display','none');
		elem.parents('form:first').find('[id$=ACT_ID_1]').css('display','block');
		jQuery('#CookVariant').val(1);
		elem.siblings().removeClass('selected');
		elem.addClass('selected');
	});
	
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
					if (data.model.REC_IMG === 'backup'){
						glob.showImageOrError(jQuery('input[id$=filename][type=file]'), '{imageId:\'' + data.model.REC_IMG + '\'}');
					}
				}
				if (data.steps){
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
	
	//----------------------------------------------


	$('#page').bind('newContent.rowcontainer_handling', function(e, type, contentParent) {
		initRecipeStepsRowContainer(type, contentParent);
		initMealplannerPeopleRowContainer(type, contentParent);
	});
	initRecipeStepsRowContainer('initial', jQuery('#page'));
	initMealplannerPeopleRowContainer('initial', jQuery('#page'));
});