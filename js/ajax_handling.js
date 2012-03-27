var glob = glob || {};

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
	glob.initAjaxUpload = initAjaxUpload;
	
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
		var form = jQuery(this);
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
	
	
	
	jQuery('body').undelegate('#ingredients_form #groupNames input','click').delegate('#ingredients_form #groupNames input','click',function(){
		jQuery.ajax({'type':'post', 'url':jQuery('#SubGroupSearchLink').attr('value'),'data':jQuery('#ingredients_form').serialize(),'cache':false,'success':function(html){
			jQuery('#subgroupNames').replaceWith(html);
			jQuery.fancybox.close();
		}});
	});
	
	jQuery('body').undelegate('#ingredients_form #groupNames select','change').delegate('#ingredients_form #groupNames select','change',function(){
		jQuery.ajax({'type':'get','url':jQuery('#SubGroupFormLink').attr('value') + '/?id=' + jQuery('#ingredients_form #groupNames select').attr('value'),'success':function(html){
			jQuery('#ingredients_form #subgroupNames select').replaceWith(html);
		}});
	});
	
	//Inredient Fancy Choose functions
	jQuery('body').undelegate('#ingredients_form.fancyForm','submit').delegate('#ingredients_form.fancyForm','submit', function(){
		jQuery.ajax({'type':'post', 'url':jQuery('#advanceChooseIngredientLink').attr('href'),'data':jQuery('#ingredients_form.fancyForm').serialize(),'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	
	jQuery('body').undelegate('.fancyForm .button.IngredientSelect','click').delegate('.fancyForm .button.IngredientSelect','click', function(){
		elem = jQuery('.activeFancyField');
		if (elem.length == 0){
			elem = jQuery('.fancyChoose').siblings('input');
		}
		elem.attr('value', jQuery(this).attr('href'));
		elem.siblings('.fancyChoose.IngredientSelect').html(jQuery(this).parent().find('.name:first a').html());
		jQuery.fancybox.close();
		return false;
	});
	
	jQuery('body').undelegate('.fancyForm #advanceSearch.button','click').delegate('.fancyForm #advanceSearch.button','click', function(){
		var url = jQuery(this).attr('href');
		if (url.indexOf('#')===0){
			url = glob.hashToUrl(url.substr(1));
		}
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
});

