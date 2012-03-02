jQuery(function($){
	function initAjaxUpload(){
		$('form.ajaxupload:not([target='+$.fn.iframePostForm.defaults.iframeID+'])').iframePostForm({
			'json' : false, /*JSON.parse sems do not work correct...*/
			'post' : function (){
				//Do check if form is OK
				//return false; // to abort send
			},
			complete : function (data) {
				if (data.indexOf('{')===0){
					eval('var data = ' + data + ';');
				}
				if (data.hash){
					window.location.hash = data.hash;
				} else {
					jQuery('#changable_content').html(data);
				}
			}
		});
	}
	function initFancyCoose(){
		jQuery('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initAjaxUpload();
		initFancyCoose();
	});
	initAjaxUpload();
	initFancyCoose();
	
	jQuery('body').undelegate('form:not(.ajaxupload)','submit').delegate('form:not(.ajaxupload)','submit',function(){
		form = jQuery(this);
		if (form.attr('method').toLowerCase() == 'get'){
			jQuery.ajax({'type':'get', 'url':form.attr('action') + '?' + form.serialize(),'success':function(data){
				jQuery('#changable_content').html(data);
			}});
		} else {
			jQuery.ajax({'type':'post', 'url':form.attr('action'), 'data': form.serialize(),  'success':function(data){
				jQuery('#changable_content').html(data);
			}});
		}	
		return false;
	});
	
	jQuery('body').undelegate('#search_form #groupNames input','click').delegate('#search_form #groupNames input','click',function(){
		jQuery.ajax({'type':'post', 'url':jQuery('#SubGroupSearchLink').attr('value'),'data':jQuery('#search_form').serialize(),'cache':false,'success':function(html){
			jQuery('#subgroupNames').replaceWith(html);
			jQuery.fancybox.close();
		}});
	});
	
	jQuery('body').undelegate('#ingredients-form #groupNames select','change').delegate('#ingredients-form #groupNames select','change',function(){
		jQuery.ajax({'type':'get','url':jQuery('#SubGroupFormLink').attr('value') + '/?id=' + jQuery('#ingredients-form #groupNames select').attr('value'),'success':function(html){
			jQuery('#ingredients-form #subgroupNames select').replaceWith(html);
		}});
	});
	
	jQuery('body').undelegate('.addRowContainer .add','click').delegate('.addRowContainer .add','click',function(){
		newLineContent = jQuery(this).siblings('input[name=addContent]').attr('value');
		lastIndexElem = jQuery(this).siblings('input[name=lastIndex]')
		lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
		newLineContent = newLineContent.replace('%class%',(lastIndex % 2 == 1)?'odd':'even');
		newLineContent = newLineContent.replace(/%index%/g,lastIndex);
		
		lastIndex = lastIndex+1;
		lastIndexElem.attr('value', lastIndex);
		addtr = jQuery(this).parents('tr:first');
		addtr.attr('class', (lastIndex % 2 == 1)?'odd':'even');
		jQuery(newLineContent).insertBefore(addtr);
	});
	
	function changeInputTableIndex(elem, changeamount){
		inputs = elem.find('[name*="["]');
		
		name = inputs.attr('name');
		currentIndex = name.match(/\[([^\]]+)\]/);
		if (!currentIndex){
			return false;
		}
		currentIndexInt = new Number(currentIndex[1]).valueOf();
		newIndexInt = currentIndexInt+changeamount;
		
		newIndexIntStr = '['+newIndexInt+']';
		newIndexIntStr2 = '_'+newIndexInt+'_';
		currentIndexStr = currentIndex[0];
		currentIndexStr2 = '_'+currentIndexInt+'_';
			
		inputs.each(function(){
			elem = jQuery(this);
			elem.attr('name', elem.attr('name').replace(currentIndexStr,newIndexIntStr));
			elem.attr('id', elem.attr('id').replace(currentIndexStr2,newIndexIntStr2));
			
		});
	}
	
	jQuery('body').undelegate('.addRowContainer .remove','click').delegate('.addRowContainer .remove','click',function(){
		row = jQuery(this).parents('tr:first');
		followedRows = row.nextAll();
		
		lastIndexElem = followedRows.last().find('input[name=lastIndex]');
		lastIndex = new Number(lastIndexElem.attr('value')).valueOf();
		lastIndexElem.attr('value', lastIndex-1);
		
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
	
	function addInputTableField(valueName, value, patternElem, insertElem, hidden){
		name = patternElem.attr('name');
		pos = name.lastIndexOf('[');
		patternField = name.substr(pos,name.length-pos);
		name = name.substr(0,pos+1) + valueName + ']';
		
		id = patternElem.attr('id');
		pos = id.lastIndexOf(patternField);
		id = id.substr(0,pos) + valueName;
		if (hidden){
			newInput = jQuery('<input type="hidden" name="'+name+'" id="'+id+'" value="'+value+'" />');
		} else {
			newInput = jQuery('<label for="'+id+'">'+valueName+'</label><input type="text" name="'+name+'" id="'+id+'" value="'+value+'" />');
		}
		insertElem.append(newInput);
	}
	
	jQuery('body').undelegate('.addRowContainer [id$=STT_ID]','change').delegate('.addRowContainer [id$=STT_ID]','change',function(){
		elem = jQuery(this);
		row = elem.parents('tr:first');
		next = row.next();
		value = elem.attr('value');
		defaults = jQuery('#stepConfig_' + value + '_STT_DEFAULT').attr('value').split(';');
		required = jQuery('#stepConfig_' + value + '_STT_REQUIRED').attr('value').split(';');
		
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
			oldFields = next.find('[name*="["]');
			for(var i=0; i<required.length; i++){
				field = fieldParents.find('[id$='+required[i]+']');
				if (field.length){
					oldFields = oldFields.not(field);
					if (field.attr('type') == 'hidden'){
						field.remove();
						addInputTableField(required[i], '', elem, insertElem, false);
					}
				} else {
					addInputTableField(required[i], '', elem, insertElem, false);
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
								addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, true);
							}
						}
					}
				} else {
					addInputTableField(fieldOpt[0], fieldOpt[1], elem, insertElem, true);
				}
			}
			for(var i=0; i<oldFields.length; i++){
				field = jQuery(oldFields[i]);
				next.find('[for='+field.attr('id')+']').remove();
				field.remove();
			}
		}
	});
	
	
	/*
	$this->widget('application.extensions.fancybox.EFancyBox', array(
		'target'=>'a.fancyChoose',
		'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
		)
	);
	*/
});