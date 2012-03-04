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
                    jQuery.event.trigger( "ajaxComplete", [null, null] );
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
			jQuery.ajax({'type':'get', 'url':form.attr('action') + '?' + form.serialize(),'cache':false,'success':function(data){
				jQuery('#changable_content').html(data);
			}});
		} else {
			jQuery.ajax({'type':'post', 'url':form.attr('action'), 'data': form.serialize(),'cache':false,'success':function(data){
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
});