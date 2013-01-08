var glob = glob || {};
var _gaq = _gaq || [];

glob.prefix = window.location.pathname.substr(0,window.location.pathname.indexOf('/',1)+1);
glob.hashToUrl = function(url){
	return glob.prefix + url;
};

glob.urlToHash = function(url){
	return url.substr(glob.prefix.length);
};


glob.lastHash = '';

glob.changeHash = function(newParamName, newParamValue, noSubmit){
	var oldHash = location.hash;
	if (oldHash.substr(0,1) === '#'){
		oldHash = oldHash.substr(1);
	}
	var newHash;
	var matches=oldHash.match('[?&]'+newParamName+'=[^&]*');
	if (matches != null){
		if (newParamValue != null){
			newHash = oldHash.replace(matches[0], matches[0].substr(0,1) + newParamName + '=' + newParamValue);
		} else {
			newHash = oldHash.replace(matches[0], matches[0].substr(0,1));
		}
	} else {
		if (newParamValue != null){
			if (oldHash.indexOf('?')>0){
				newHash = oldHash + '&';
			} else {
				newHash = oldHash + '?';
			}
			newHash = newHash + newParamName + '=' + newParamValue;
		} else {
			newHash = oldHash;
		}
	}
	if (noSubmit){
		glob.lastHash = newHash;
	}
	location.hash = newHash;
};

glob.reloadPage = function(){
	glob.lastHash = '';
	jQuery(window).trigger( 'hashchange' );
}

glob.ShowActivity = true;

jQuery(function($){
	//Initialize Links
	function initLinks(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		if (glob.prefix && window.location.pathname != glob.prefix){
			contentParent.find('a[href*="' + glob.prefix + '"]:not(a[href*="#"]):not(.fancyChoose):not(.noAjax)').each(function(){
				$(this).attr('href', glob.prefix + "#" + $(this).attr('href').substr(glob.prefix.length));
			});
		} else {
			contentParent.find('a[href*="' + glob.prefix + '"]:not(.fancyChoose):not(.noAjax)').each(function(){
				$(this).attr('href', "#" + $(this).attr('href').substr(glob.prefix.length));
			});
		}
	}
	
	$('#page').ajaxStart(function(e) {
		if (glob.ShowActivity){
			jQuery.fancybox.showActivity();
		}
	});
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		jQuery.fancybox.hideActivity();
	});
	
	$('#page').bind('newContent.ajax_handling', function(e, type, contentParent) {
		initLinks(type, contentParent);
	});
	initLinks('initial', jQuery('#page'));
	
	
	$(window).bind('hashchange', function(e) {
		// Get the hash (fragment) as a string, with any leading # removed. Note that
		// in jQuery 1.4, you should use e.fragment instead of $.param.fragment().
		var hash = $.param.fragment();
		
		// Remove .bbq-current class from any previously "current" link(s).
		$( 'a.bbq-current' ).removeClass( 'bbq-current' );
		
		// Hide any visible ajax content.
		//$( '.bbq-content' ).children( ':visible' ).hide();
		
		if (hash === '' && jQuery('#changable_content').text().trim().length == 0){
			hash = 'site/index';
		}
		if (hash !== '' && hash != glob.lastHash){
			// Add .bbq-current class to "current" nav link(s), only if url isn't empty.
			hash && $( 'a[href="#' + hash + '"]' ).addClass( 'bbq-current' );
			
			 // Show "loading" content while AJAX content loads.
			$( '.bbq-loading' ).show();
			
			url = glob.hashToUrl(hash);
			
			jQuery.fancybox.close();
			
			jQuery.ajax({
				'type':'get',
				'url':url,
				'cache':false,
				'success':function(data){
					// Content loaded, hide "loading" content.
					$( '.bbq-loading' ).hide();
					glob.lastHash = hash;
					ajaxResponceHandler(data, 'hash');
					_gaq.push(['_trackPageview', hash]);
				},
				'error':function(xhr){
					// Content loaded, hide "loading" content.
					$( '.bbq-loading' ).hide();
					glob.lastHash = hash;
					//xhr.status
					//xhr.statusText
					ajaxResponceHandler(xhr.responseText, 'hash');
					_gaq.push(['_trackPageview', hash]);
				},
			});
			
			/*
			$('#changable_content').load( url, function(){
				// Content loaded, hide "loading" content.
				$( '.bbq-loading' ).hide();
				glob.lastHash = hash;
			});*/
		}
	});
	
	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	$(window).trigger( 'hashchange' );
});