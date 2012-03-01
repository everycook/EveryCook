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
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initAjaxUpload();
	});
	initAjaxUpload();
	
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
	
	/*
	$this->widget('application.extensions.fancybox.EFancyBox', array(
		'target'=>'a.fancyChoose',
		'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
		)
	);
	*/
});