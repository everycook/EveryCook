var glob = glob || {};

glob.removeUrlParam = function(url, key){
	var paramStart = url.indexOf(key+'=');
	var paramEnd = url.indexOf('&', paramStart);
	if (paramStart != -1){
		if (paramEnd != -1){
			url = url.substr(0,paramStart) + url.substr(paramEnd);
		} else {
			url = url.substr(0,paramStart)
		}
	}
	return url;
}

glob.urlAddParamStart = function(url){
	if (url.indexOf('?')>0){
		url = url + '&';
	} else {
		url = url + '?';
	}
	return url;
};


function ajaxResponceHandler(data){
	if (data.indexOf('{')===0){
		eval('var data = ' + data + ';');
	}
	if (data.hash){
		if (glob.prefix && window.location.pathname != glob.prefix){
			window.location.pathname = glob.prefix + '#' + data.hash;
		} else {
			window.location.hash = data.hash;
		}
	} else {
		jQuery('#changable_content').html(data);
	}
}

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
					if (glob.prefix && window.location.pathname != glob.prefix){
						window.location.pathname = glob.prefix + '#' + data.hash;
					} else {
						window.location.hash = data.hash;
					}
				} else {
					jQuery('#changable_content').html(data);
                    jQuery.event.trigger( "ajaxComplete", [null, null] );
				}
			}
		});
	}
	glob.initAjaxUpload = initAjaxUpload;
	
	function initFancyCoose(){
		jQuery('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input:first').addClass('activeFancyField');
		});
		jQuery('a.fancyChoose').fancybox({'autoScale':true,'autoDimensions':true,'centerOnScroll':true});
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initAjaxUpload();
		initFancyCoose();
	});
	initAjaxUpload();
	initFancyCoose();
	
	
	jQuery('body').undelegate('form:not(.ajaxupload):not(.fancyForm):not(.noAjax)','submit').delegate('form:not(.ajaxupload):not(.fancyForm):not(.noAjax)','submit',function(){
		var form = jQuery(this);
		try {
			submitValue = "";
			pressedButton = arguments[0].originalEvent.explicitOriginalTarget;
			submitValue = "&" + encodeURI(pressedButton.name + "=" + pressedButton.value);
		} catch(ex){
		}
		return submitForm(form, form.attr('action'), submitValue, ajaxResponceHandler);
	});
	
	function submitForm(form, destUrl, submitValue, successFunc){
		if (form.attr('method').toLowerCase() == 'get'){
			destUrl = glob.urlAddParamStart(destUrl);
			jQuery.ajax({'type':'get', 'url':destUrl + form.serialize() + submitValue,'cache':false,'success':function(data){
				successFunc(data);
			}});
		} else {
			var queryInput = form.find('#SimpleSearchForm_query');
			if (queryInput.length && !queryInput.hasClass('notUrl')){
				queryValue = queryInput.attr('value').trim();
				if (queryValue.length > 0){
					destUrl = glob.urlAddParamStart(destUrl);
					destUrl = destUrl + 'query=' + queryValue;
					
					glob.changeHash('query', queryValue, true);
				} else {
					glob.changeHash('query', null, true);
				}
			}
			
			jQuery.ajax({'type':'post', 'url':destUrl, 'data': form.serialize() + submitValue,'cache':false,'success':function(data){
				successFunc(data);
			}});
		}	
		return false;
	}
	
	
	//Ingredient functions
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
	
	jQuery('body').undelegate('.fancyForm #advanceSearch.button','click').delegate('.fancyForm #advanceSearch.button','click', function(){
		var url = jQuery(this).attr('href');
		if (url.indexOf('#')===0){
			url = glob.hashToUrl(url.substr(1));
		}
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	
	jQuery('body').undelegate('#Ingredients_ING_NAME_EN_GB','blur').delegate('#Ingredients_ING_NAME_EN_GB','blur', function(){
		var value = jQuery('#Ingredients_ING_NAME_EN_GB').val();
		var link = jQuery('.NutrientDataSelect');
		var url = link.attr('href');
		url = glob.removeUrlParam(url,'query');
		url = glob.urlAddParamStart(url);
		url = url + 'query=' + encodeURI(value);
		link.attr('href', url);
	});
	
	


	//Product functions
	jQuery('body').undelegate('#producer .add','click').delegate('#producer .add','click', function(){
		var list = jQuery('#producerList');
		var rows = list.find('li').length + 1;
		var newelem = jQuery('<li><input type="hidden" id="PRD_ID'+rows+'" value="0" name="PRD_ID['+rows+']"><a class="fancyChoose ProducerSelect" href="/EveryCook/index.php/producers/chooseProducer">' + jQuery('#newProducerText').attr('value') + '</a><span class="buttonSmall remove">' + jQuery('#removeText').attr('value') + '<span></li>');
		list.append(newelem);
		initFancyCoose();
	});
	jQuery('body').undelegate('#producer .remove','click').delegate('#producer .remove','click', function(){
		var elem = jQuery(this);
		elem.parent().remove();
	});
	
	jQuery('body').undelegate('#productsResult .showOnMap','click').delegate('#productsResult .showOnMap','click', function(){
		jQuery('.selectedProduct').removeClass('selectedProduct');
		var elem = jQuery(this);
		elem.parents('.data:first').find('.productId').addClass('selectedProduct');
		if (elem.hasClass('centerGPSYou')){
			cord = jQuery('#centerGPSYou').val().split(',');
			title = 'your Position';
		} else {
			cord = jQuery('#centerGPSHome').val().split(',');
			title = 'your Home';
		}
		reinitialize(cord[0], cord[1], undefined, jQuery('#viewDistance').val(), loadDataProduct, title);
	});
	
	//Store assing functions
	jQuery('body').undelegate('#STO_MAP','change').delegate('#STO_MAP','change', function(){
		var input = jQuery(this);
		var value = input.attr('value');
		var values = value.split('_');
		jQuery('#ProToSto_SUP_ID').attr('value',values[1]);
		jQuery('#ProToSto_STY_ID').attr('value',values[2]);
		return false;
	});
	jQuery('body').undelegate('#STO_SEARCH','change').delegate('#STO_SEARCH','change', function(){
		//TODO: this (hidde field) is not trigered...
		var input = jQuery(this);
		var value = input.attr('value');
		var values = value.split('_');
		if (values[1]>0){
			jQuery('#ProToSto_SUP_ID').attr('value',values[1]);
		}
		if (values[2]>0){
			jQuery('#ProToSto_STY_ID').attr('value',values[2]);
		}
		return false;
	});
	
	//FancyForm functions
	jQuery('body').undelegate('.fancyForm','submit').delegate('.fancyForm','submit', function(){
		var form = jQuery(this);
		try {
			submitValue = "";
			pressedButton = arguments[0].originalEvent.explicitOriginalTarget;
			submitValue = "&" + encodeURI(pressedButton.name + "=" + pressedButton.value);
		} catch(ex){
		}
		jQuery.ajax({'type':'post', 'url':jQuery('#FancyChooseSubmitLink').attr('value'),'data':form.serialize() + submitValue,'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	
	jQuery('body').undelegate('.fancyForm .button.IngredientSelect','click').delegate('.fancyForm .button.IngredientSelect','click', function(){
		return fancyChooseSelect ('IngredientSelect', this);
	});
	
	jQuery('body').undelegate('.fancyForm .button.ProducerSelect','click').delegate('.fancyForm .button.ProducerSelect','click', function(){
		return fancyChooseSelect ('ProducerSelect', this);
	});
	jQuery('body').undelegate('.fancyForm .button.StoresSelect','click').delegate('.fancyForm .button.StoresSelect','click', function(){
		return fancyChooseSelect ('StoresSelect', this);
	});
	
	function fancyChooseSelect (fieldIdentifier, caller){
		elem = jQuery('.activeFancyField');
		if (elem.length == 0){
			elem = jQuery('.fancyChoose.'+fieldIdentifier).siblings('input.fancyValue');
		}
		elem.attr('value', jQuery(caller).attr('href'));
		elem.siblings('a.fancyChoose.' + fieldIdentifier).html(jQuery(caller).parent().find('.name').text());
		jQuery.fancybox.close();
		elem.change();
		return false;
	}
	
	jQuery('body').undelegate('.openFancyBySubmit','click').delegate('.openFancyBySubmit','click', function(){
		elem = jQuery(this);
		form = elem.parents('form:first');
		url = jQuery('#OpenFancyLink').attr('value');
		submitForm(form, url, '', function(data){jQuery.fancybox({'content':data})});
	});
	
	jQuery('body').undelegate('.emptyOnEnter','click').delegate('.emptyOnEnter','click', function(){
		var elem = jQuery(this);
		elem.attr('value','');
		elem.removeClass('emptyOnEnter');
	});
	
	
	//Profiles
	jQuery('body').undelegate('#Profiles_PRF_LANG','change').delegate('#Profiles_PRF_LANG','change',function(){
		var lang = jQuery('#Profiles_PRF_LANG').val();
		if (lang != ''){
			var destUrl = jQuery('#LanguageChangeLink').val();
			if (destUrl.indexOf('?')>0){
				destUrl = destUrl + '&';
			} else {
				destUrl = destUrl + '?';
			}
			destUrl = destUrl + 'lang=' + jQuery('#Profiles_PRF_LANG').val();

			window.location = destUrl;
		}
	});
	
	
	//JumpTo
	var JumpToOverlayTimeout;
	jQuery('body').undelegate('#JumpTo','mouseover').delegate('#JumpTo','mouseover',function(){
		window.clearTimeout(JumpToOverlayTimeout);
		jQuery('#JumpTos').show();
	});
	
	jQuery('body').undelegate('#JumpTo','mouseout').delegate('#JumpTo','mouseout',function(){
		JumpToOverlayTimeout = window.setTimeout('jQuery("#JumpTos").hide();', 1000);
	});
	
	jQuery('body').undelegate('#JumpTos','mouseover').delegate('#JumpTos','mouseover',function(){
		window.clearTimeout(JumpToOverlayTimeout);
	});
	
	jQuery('body').undelegate('#JumpTos','mouseout').delegate('#JumpTos','mouseout',function(){
		JumpToOverlayTimeout = window.setTimeout('jQuery("#JumpTos").hide();', 1000);
	});
	
	
	//delicious / disgusting
	jQuery('body').undelegate('.delicious','click').delegate('.delicious','click',function(){
		var url = jQuery(this).attr('href');
		if (url.substr(0,1) == '#'){
			url = glob.hashToUrl(url.substr(1));
		}
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			ajaxResponceHandler(data);
		}});
		
		return false;
	});

	jQuery('body').undelegate('.disgusting','click').delegate('.disgusting','click',function(){
		var url = jQuery(this).attr('href');
		if (url.substr(0,1) == '#'){
			url = glob.hashToUrl(url.substr(1));
		}
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			ajaxResponceHandler(data);
		}});
		
		return false;
	});

});

