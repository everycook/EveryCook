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
		if (url.substr(url.length-1,1) != '&'){
			url = url + '&';
		}
	} else {
		url = url + '?';
	}
	return url;
};

glob.changeLinkUrlParam = function(elem, param, newValue){
	var url = elem.attr('href');
	url = glob.removeUrlParam(url,param);
	url = glob.urlAddParamStart(url);
	url = url + param + '=' + encodeURI(newValue);
	elem.attr('href', url);
}

function ajaxResponceHandler(data, type, asFancy){
	if (data.indexOf('{')===0){
		eval('var data = ' + data + ';');
	}
	if (data.hash){
		if (asFancy){
			jQuery.fancybox({
				'href':glob.hashToUrl(data.hash),
				'onComplete': function(){
					jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
				}
			});
		} else {
			if (glob.prefix && window.location.pathname != glob.prefix){
				window.location.pathname = glob.prefix + '#' + data.hash;
			} else {
				window.location.hash = data.hash;
			}
		}
	} else if (data.fancy){
		jQuery.fancybox({
			'href':data.fancy,
			'onComplete': function(){
				jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
			}
		});
	}else {
		var JSONBegin = data.indexOf('{');
		if (JSONBegin<5){
			var JSONLength = parseInt(data.substr(0,JSONBegin));
			if (!isNaN(JSONLength) && JSONLength>0){
				var JSONText = data.substr(JSONBegin,JSONLength);
				data = data.substr(JSONBegin+JSONLength);
				if (type != 'fancy'){
					eval('var json = ' + JSONText + ';');
					if (json.title){
						jQuery('title').text(json.title);
					}
				}
			}
		}
		if (asFancy){
			jQuery.fancybox({
				'content':data,
				'onComplete': function(){
					jQuery.event.trigger( "newContent", [type, jQuery('#fancybox-content')] );
				}
			});
		} else {
			jQuery('#changable_content').html(data);
			jQuery.event.trigger( "newContent", [type, jQuery('#changable_content')] );
		}
	}
}

jQuery(function($){
	var navMenuTiemout = new Array();
	
	//AjaxPaging
	var ajaxpaging = {};
	ajaxpaging.doScrollTop = true;
	ajaxpaging.next = [];
	ajaxpaging.prev = [];
	
	
	
	function initAjaxUpload(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		contentParent.find('form.ajaxupload:not([target='+$.fn.iframePostForm.defaults.iframeID+'])').iframePostForm({
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
                    //jQuery.event.trigger( "ajaxComplete", [null, null] );
					jQuery.event.trigger( "newContent", ['form', jQuery('#changable_content')] );
				}
			}
		});
	}
	glob.initAjaxUpload = initAjaxUpload;
	
	function initFancyCoose(type, contentParent){
		contentParent.find('a.fancyChoose').bind('click.multiFancyCoose', function(){
			jQuery('.activeFancyField').removeClass('activeFancyField');
			jQuery(this).siblings('input.fancyValue:first').addClass('activeFancyField');
		});
		contentParent.find('a.fancyChoose').fancybox({
			'autoScale':true,
			'autoDimensions':true,
			'centerOnScroll':true,
			'onComplete': function(){
				jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
			}
		});
	}
	
	function initDatePicker(type, contentParent){
		var dateInput = contentParent.find('.input_date');
		if (dateInput.length > 0){
			if (dateInput.get(0).type == 'text'){
				//browser don't know 'date' input type, do jQuery-UI fallback.
				dateInput.datepicker({
				   dateFormat: 'yy-mm-dd'
				});
			}
		}
	}
	function initAutoFocus(type, contentParent){
		var focusElement = contentParent.find('input[autofocus]');
		if (focusElement.length > 0) {
			focusElement.get(0).focus();
			focusElement.removeAttr('autofocus');
		}
	}
	
	function updateHomeGPS(type, contentParent){
		var homePos = contentParent.find('#centerGPSHome');
		if (homePos.length>0){
			if (homePos.val() != ''){
				var cords = homePos.val().split(',');
				jQuery('#home_gps_lat').val(cords[0]);
				jQuery('#home_gps_lng').val(cords[1]);
			}
		}
	}
	
	//All currently used types are: hash, form, ajax, fancy, html, paging
	function newContentFunction(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		initAjaxUpload(type, contentParent);
		initFancyCoose(type, contentParent);
		initDatePicker(type, contentParent);
		if (type != 'initial'){
			initAutoFocus(type, contentParent);
		}
		initAjaxPaging(type, contentParent);
		updateHomeGPS(type, contentParent);
	}
	
	$('#page').bind('newContent.ajax_handling', function(e, type, contentParent) {
		newContentFunction(type, contentParent);
	});
	newContentFunction('initial', jQuery('#page'));
	
	
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
				successFunc(data, 'form');
			},
			'error':function(xhr){
				ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
			},
			});
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
				successFunc(data, 'form');
			},
			'error':function(xhr){
				ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
			},
			});
		}	
		return false;
	}
	
	
	//Ingredient functions
	jQuery('body').undelegate('#ingredients_form #groupNames input','click').delegate('#ingredients_form #groupNames input','click',function(){
		jQuery.ajax({'type':'post', 'url':jQuery('#SubGroupSearchLink').attr('value'),'data':jQuery('#ingredients_form').serialize(),'cache':false,'success':function(html){
			var newData = jQuery(html);
			jQuery('#subgroupNames').replaceWith(newData);
			jQuery.fancybox.close();
			jQuery.event.trigger( "newContent", ['ajax', newData] );
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
	});
	
	jQuery('body').undelegate('#ingredients_form #groupNames select','change').delegate('#ingredients_form #groupNames select','change',function(){
		jQuery.ajax({'type':'get','url':jQuery('#SubGroupFormLink').attr('value') + '/?id=' + jQuery('#ingredients_form #groupNames select').attr('value'),'success':function(html){
			var newData = jQuery(html);
			jQuery('#ingredients_form #subgroupNames select').replaceWith(newData);
			jQuery.event.trigger( "newContent", ['ajax', newData] );
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
	});
	
	jQuery('body').undelegate('.fancyForm #advanceSearch.button','click').delegate('.fancyForm #advanceSearch.button','click', function(){
		return openInFancy(jQuery(this));
	});
	
	
	jQuery('body').undelegate('.fancybutton.button','click').delegate('.fancybutton.button','click', function(){
		return openInFancy(jQuery(this));
	});
	
	function openInFancy(elem){
		var url = elem.attr('href');
		if (url.indexOf('#')===0){
			url = glob.hashToUrl(url.substr(1));
		}
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(html){
			jQuery.fancybox({
				'content':html,
				'onComplete': function(){
					jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
				}
			});
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		return false;
	}
	
	
	
	
	jQuery('body').undelegate('#Ingredients_ING_NAME_EN_GB','blur').delegate('#Ingredients_ING_NAME_EN_GB','blur', function(){
		var value = jQuery(this).val();
		var link = jQuery('.NutrientDataSelect');
		glob.changeLinkUrlParam(link, 'query', value);
		
		link = jQuery('#lookOnFlickr');
		glob.changeLinkUrlParam(link, 'q', value);
	});
	
	
	//Product functions
	jQuery('body').undelegate('#producer .add','click').delegate('#producer .add','click', function(){
		var list = jQuery('#producerList');
		var rows = list.find('li').length + 1;
		var newelem = jQuery('<li><input type="hidden" id="PRD_ID'+rows+'" value="0" class="fancyValue" name="PRD_ID['+rows+']"><a class="fancyChoose ProducerSelect" href="' + glob.prefix + 'producers/chooseProducer">' + jQuery('#newProducerText').attr('value') + '</a><span class="buttonSmall remove">' + jQuery('#removeText').attr('value') + '<span></li>');
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
			var isCurrent = true;
		} else {
			cord = jQuery('#centerGPSHome').val().split(',');
			var isCurrent = false;
		}
		var distInput = elem.prev('input.viewDistance');
		if (distInput.length == 0){
			distInput = jQuery('#viewDistance');
		}
		reinitialize(cord[0], cord[1], undefined, distInput.val(), loadDataProduct, isCurrent);
	});
	
	/*
	jQuery('body').undelegate('#Products_PRO_NAME_EN_GB','blur').delegate('#Products_PRO_NAME_EN_GB','blur', function(){
		var value = jQuery(this).val();
		var link = jQuery('#lookOnFlickr');
		glob.changeLinkUrlParam(link, 'q', value);
	});
	*/
	
	
	//Recipe functions
	jQuery('body').undelegate('#Recipes_REC_NAME_EN_GB','blur').delegate('#Recipes_REC_NAME_EN_GB','blur', function(){
		var value = jQuery(this).val();
		var link = jQuery('#lookOnFlickr');
		glob.changeLinkUrlParam(link, 'q', value);
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
		//TODO: this (hidden field) is not trigered...
		var input = jQuery(this);
		var value = input.attr('value');
		var values = value.split('_');
		if (values[1]>0){
			jQuery('#ProToSto_SUP_ID').attr('value',values[1]);
		}
		//if (values[2]>0){
			jQuery('#ProToSto_STY_ID').attr('value',values[2]);
		//}
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
		//jQuery.ajax({'type':'post', 'url':jQuery('#FancyChooseSubmitLink').attr('value'),'data':form.serialize() + submitValue,'cache':false,'success':function(html){
		jQuery.ajax({'type':'post', 'url':form.attr('action'),'data':form.serialize() + submitValue,'cache':false,'success':function(html){
			ajaxResponceHandler(html, 'fancy', true);
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'fancy', true); //xhr.status //xhr.statusText
		},
		});
		return false;
	});
	
	jQuery('body').undelegate('.fancyForm .button.IngredientSelect','click').delegate('.fancyForm .button.IngredientSelect','click', function(){
		return fancyChooseSelect('IngredientSelect', this);
	});
	
	jQuery('body').undelegate('.fancyForm .button.ProducerSelect','click').delegate('.fancyForm .button.ProducerSelect','click', function(){
		return fancyChooseSelect('ProducerSelect', this);
	});
	jQuery('body').undelegate('.fancyForm .button.StoresSelect','click').delegate('.fancyForm .button.StoresSelect','click', function(){
		return fancyChooseSelect('StoresSelect', this);
	});
	
	function fancyChooseSelect(fieldIdentifier, caller){
		var elem = jQuery('.activeFancyField');
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
		submitForm(form, url, '', function(data){
			jQuery.fancybox({
				'content':data,
				'onComplete': function(){
					jQuery.event.trigger( "newContent", ['fancy', jQuery('#fancybox-content')] );
				}
			})
		});
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
	
	jQuery('body').undelegate('.cord_lat','change').delegate('.cord_lat','change',function(){
		var elem = jQuery(this);
		if (elem.attr('type') == 'hidden'){
			elem.siblings('.value').text(elem.val());
		}
	});
	
	jQuery('body').undelegate('.cord_lng','change').delegate('.cord_lng','change',function(){
		var elem = jQuery(this);
		if (elem.attr('type') == 'hidden'){
			elem.siblings('.value').text(elem.val());
		}
	});
	
	
	//NavMenu L1
	jQuery('body').undelegate('.navMenu','mouseover').delegate('.navMenu','mouseover',function(){
		var listId = jQuery(this).attr('id') + '_List';
		window.clearTimeout(navMenuTiemout[listId]);
		jQuery(".navMenuList").hide();
		jQuery('#'+listId).show();
	});
	
	jQuery('body').undelegate('.navMenu','mouseout').delegate('.navMenu','mouseout',function(){
		var listId = jQuery(this).attr('id') + '_List';
		window.clearTimeout(navMenuTiemout[listId]);
		navMenuTiemout[listId] = window.setTimeout('jQuery("#' + listId + '").hide();', 1000);
	});
	
	jQuery('body').undelegate('.navMenuList','mouseover').delegate('.navMenuList','mouseover',function(){
		var listId = jQuery(this).attr('id');
		window.clearTimeout(navMenuTiemout[listId]);
	});
	
	jQuery('body').undelegate('.navMenuList','mouseout').delegate('.navMenuList','mouseout',function(){
		var listId = jQuery(this).attr('id');
		window.clearTimeout(navMenuTiemout[listId]);
		navMenuTiemout[listId] = window.setTimeout('jQuery("#' + listId + '").hide();', 1000);
	});
	
	//NavMenu L2
	jQuery('body').undelegate('.navMenuL2','mouseover').delegate('.navMenuL2','mouseover',function(){
		var listId = jQuery(this).attr('id') + '_List';
		window.clearTimeout(navMenuTiemout[listId]);
		jQuery(".navMenuListL2").hide();
		jQuery('#'+listId).show();
		
		var parent = jQuery(this).parent();
		if (parent.hasClass('navMenuList')){
			var parentListId = parent.attr('id');
			window.clearTimeout(navMenuTiemout[parentListId]);
		}
	});
	
	jQuery('body').undelegate('.navMenuL2','mouseout').delegate('.navMenuL2','mouseout',function(){
		var listId = jQuery(this).attr('id') + '_List';
		window.clearTimeout(navMenuTiemout[listId]);
		navMenuTiemout[listId] = window.setTimeout('jQuery("#' + listId + '").hide();', 1000);
	});
	
	jQuery('body').undelegate('.navMenuListL2','mouseover').delegate('.navMenuListL2','mouseover',function(){
		var listId = jQuery(this).attr('id');
		window.clearTimeout(navMenuTiemout[listId]);
		
		var parent = jQuery(this).parent();
		if (parent.hasClass('navMenuList')){
			var parentListId = parent.attr('id');
			window.clearTimeout(navMenuTiemout[parentListId]);
		}
	});
	
	jQuery('body').undelegate('.navMenuListL2','mouseout').delegate('.navMenuListL2','mouseout',function(){
		var listId = jQuery(this).attr('id');
		window.clearTimeout(navMenuTiemout[listId]);
		navMenuTiemout[listId] = window.setTimeout('jQuery("#' + listId + '").hide();', 1000);
		
		var parent = jQuery(this).parent();
		if (parent.hasClass('navMenuList')){
			var parentListId = parent.attr('id');
			window.clearTimeout(navMenuTiemout[parentListId]);
			navMenuTiemout[parentListId] = window.setTimeout('jQuery("#' + parentListId + '").hide();', 1000);
		}
	});
	
	//NavMenu Entry
	jQuery('body').undelegate('.navMenuListEntry','mouseover').delegate('.navMenuListEntry','mouseover',function(){
		var parent = jQuery(this).parent();
		if (parent.hasClass('navMenuList') || parent.hasClass('navMenuListL2')){
			var parentListId = parent.attr('id');
			window.clearTimeout(navMenuTiemout[parentListId]);
		}
		parent = parent.parent();
		if (parent.hasClass('navMenuList') || parent.hasClass('navMenuListL2')){
			var parentListId = parent.attr('id');
			window.clearTimeout(navMenuTiemout[parentListId]);
		}
	});
	
	
	//delicious / disgusting
	jQuery('body').undelegate('.delicious','click').delegate('.delicious','click',function(){
		var url = jQuery(this).attr('href');
		if (url.substr(0,1) == '#'){
			url = glob.hashToUrl(url.substr(1));
		}
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			ajaxResponceHandler(data, 'ajax');
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		
		return false;
	});

	jQuery('body').undelegate('.disgusting','click').delegate('.disgusting','click',function(){
		var url = jQuery(this).attr('href');
		if (url.substr(0,1) == '#'){
			url = glob.hashToUrl(url.substr(1));
		}
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			ajaxResponceHandler(data, 'ajax');
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		
		return false;
	});
	
	
	//AjaxPaging
	function initAjaxPaging(type, contentParent){
		var pagingParent = null;
		var usePaging = false;
		
		if (type == 'hash'){
			//full page changed, clear paging infos
			ajaxpaging.next = [];
			ajaxpaging.prev = [];
		}
		if (type == 'paging'){
			itemsParent = contentParent.parent();
		} else {
			itemsParent = contentParent;
		}
		
		var newNext = {};
		newNext.dom = {};
		newNext.top = 0;
		newNext.parent = {};
	
		newNext.dom = itemsParent.find('#ajaxPaging');
		if (typeof(newNext.dom)==='object' && newNext.dom != null && newNext.dom.length > 0) {
			newNext.dom.click(doAjaxPaging);
			if (newNext.dom.is('.ajaxPagingAutoLoad')){
				usePaging = true;
				pagingParent = newNext.dom.parents('.scrollArea:first');
				if (pagingParent.length == 0){
					pagingParent = jQuery(window);
					newNext.top = newNext.dom.offset().top;
				} else {
					newNext.top = newNext.dom.position().top + pagingParent.scrollTop() - pagingParent.position().top;
				}
				newNext.parent = pagingParent;
				ajaxpaging.next.push(newNext);
			}
		} else {
			newNext.dom = undefined;
		}
		
		var newPrev = {};
		newPrev.dom = {};
		newPrev.bottom = 0;
		newPrev.parent = {};
		
		newPrev.dom = itemsParent.find('#ajaxPagingPrev');
		if (typeof(newPrev.dom)==='object' && newPrev.dom != null && newPrev.dom.length > 0) {
			newPrev.dom.click(doAjaxPaging);
			if (newPrev.dom.is('.ajaxPagingAutoLoad')){
				usePaging = true;
				pagingParent = newPrev.dom.parents('.scrollArea:first');
				if (pagingParent.length == 0){
					pagingParent = jQuery(window);
					newPrev.bottom = newPrev.dom.offset().top + newPrev.dom.height();
				} else {
					newPrev.bottom = newPrev.dom.position().top + newPrev.dom.height() + pagingParent.scrollTop();
				}
				newPrev.parent = pagingParent;
				ajaxpaging.next.push(newPrev);
			}
			if (ajaxpaging.doScrollTop){
				var next = newPrev.dom.next();
				if (typeof(next) !== 'undefined'){
					window.scrollTo(0, next.offset().top);
				}
			}
			ajaxpaging.doScrollTop = true;
		} else {
			newPrev.dom = undefined;
		}
	
		if (pagingParent != null && usePaging) {
			pagingParent.bind('scroll.ajaxpaging resize.ajaxpaging', checkStartAjaxPaging);
			//check if it should already load
			checkStartAjaxPaging();
		}
	}
	
	function doAjaxPaging(){
		var elem = jQuery(this);
		elem.unbind('click');
		elem.css('cursor','auto');
		
		elem.addClass('loading');
		
		var url = elem.find('.pagingUrl').val();
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			if(data.indexOf('<html>') !== -1 ) {
				elem.replaceWith('');
			} else {
				var newElements = jQuery(data);
				if (elem.is('#ajaxPagingPrev') && newElements.length>3){
					ajaxpaging.doScrollTop = false;
					var next = elem.next();
					//var oldPos = elem.offset().top + elem.height() + 10;
					elem.replaceWith(newElements);
					//window.scrollTo(0, oldPos);
					if (typeof(next) !== 'undefined'){
						window.scrollTo(0, next.offset().top);
					}
					//window.scrollTo(0, jQuery(newElements.get(3)).offset().top);
				} else {
					elem.replaceWith(newElements);
				}
				jQuery.event.trigger( "newContent", ['paging', newElements] );
			}
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
	}
	
	function checkStartAjaxPaging(){
		if (ajaxpaging.next.length>0){
			for(var i=ajaxpaging.next.length-1; i >= 0; --i){
				var nextPager = ajaxpaging.next[i];
				if (typeof(nextPager.dom)==='object' && nextPager.dom.is('.ajaxPagingAutoLoad')) {
					if (!nextPager.dom.is(':visible')) {
						ajaxpaging.prev.splice(i, 1);
					} else if (nextPager.parent.scrollTop() + nextPager.parent.height() > nextPager.top ) {
						nextPager.parent.unbind('.ajaxpaging');
						nextPager.activ = false;
						ajaxpaging.next.splice(i, 1);
						nextPager.dom.click();
						return;
					}
				}
			}
		}
		
		if (ajaxpaging.prev.length>0){
			for(var i=ajaxpaging.prev.length-1; i >= 0; --i){
				var prevPager = ajaxpaging.prev[i];
				if (typeof(prevPager.dom)==='object' && prevPager.dom.is('.ajaxPagingAutoLoad:visible')) {
					if (!prevPager.dom.is(':visible')) {
						ajaxpaging.prev.splice(i, 1);
					} else if (prevPager.parent.scrollTop() < prevPager.bottom ) {
						prevPager.parent.unbind('.ajaxpaging');
						prevPager.activ = false;
						ajaxpaging.prev.splice(i, 1);
						prevPager.dom.click();
						return;
					}
				}
			}
		}
	}
	
	
	//Shoppinglist
	jQuery('body').undelegate('.shoppingList_right .removeFromList','click').delegate('.shoppingList_right .removeFromList','click',function(){
		var elem = jQuery(this);
		var url = elem.attr('href');
		if (url.substr(0,1) == '#'){
			url = glob.hashToUrl(url.substr(1));
		}
		
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			elem.parents('.resultArea:first').remove();
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		
		return false;
	});
	
	jQuery('body').undelegate('.fancyForm .button.ProductSelect','click').delegate('.fancyForm .button.ProductSelect','click', function(){
		/*
		if (jQuery('#shoppinglistView').length == 0){
			return fancyChooseSelect('ProductSelect', this);
		}
		*/
		var elem = jQuery(this);
		var elemParent = elem.parent();
		
		var destElem = jQuery('.activeFancyField');
		if (destElem.length == 0){
			destElem = jQuery('.fancyChoose.ProductSelect').siblings('input.fancyValue');
		}
		var destParent = destElem.parent();
		
		var gramms = elemParent.find('.PRO_PACKAGE_GRAMMS').val();
		var url = destParent.parent().find('.setProductLink').val();
		url = glob.urlAddParamStart(url) + 'pro_id=' + elem.attr('href');
		url = glob.urlAddParamStart(url) + 'gramms=' + gramms;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			//Change Name
			destElem.siblings('div.name').html(elemParent.find('.name').text());
			
			//Change Image
			var proImg = destParent.parent().find('img');
			var newProImg = elemParent.find('img');
			proImg.attr('src', newProImg.attr('src'));
			proImg.attr('title', newProImg.attr('title'));
			proImg.attr('alt', newProImg.attr('alt'));
			
			//"add" change button
			var productSelects = destParent.find('.ProductSelect');
			if (productSelects.length == 2){
				var secondButton = jQuery(productSelects.get(1));
				secondButton.prev().remove(); //<br>
				secondButton.text('change'); //TODO: $this->trans->SHOPPINGLIST_CHANGE_PRODUCT
				jQuery(productSelects.get(0)).remove();
			}
			
			//Change store infos
			destParent.find('.ProductsStoreInfo').remove();
			
			var storeInfos = elemParent.find('.ProductsStoreInfo');
			storeInfos = storeInfos.not(storeInfos.has('span a'));
			destParent.append(storeInfos);
			
			jQuery.fancybox.close();
		},
		'error':function(xhr){
			ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		
		return false;
	});
	
	//divers functions
	jQuery('body').undelegate('.closeFancy','click').delegate('.closeFancy','click', function(){
		jQuery.fancybox.close();
		return false;
	});
	
	jQuery('body').undelegate('.newModelTime','click').delegate('.newModelTime','click', function(){
		var link = jQuery(this);
		var value = Math.round((new Date().getTime())/1000);
		glob.changeLinkUrlParam(link, 'newModel', value);
	});
	
});

