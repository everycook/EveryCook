var glob = glob || {};

glob.prefix = window.location.pathname.substr(0,window.location.pathname.indexOf('/',1)+1);
glob.hashToUrl = function(url){
	return glob.prefix + url;
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

jQuery(function($){
	//Initialize Links
	function initLinks(){
		if (glob.prefix && window.location.pathname != glob.prefix){
			$('a[href*="' + glob.prefix + '"]:not(a[href*="#"]):not(.fancyChoose):not(.noAjax)').each(function(){
				$(this).attr('href', glob.prefix + "#" + $(this).attr('href').substr(glob.prefix.length));
			});
		} else {
			$('a[href*="' + glob.prefix + '"]:not(.fancyChoose):not(.noAjax)').each(function(){
				$(this).attr('href', "#" + $(this).attr('href').substr(glob.prefix.length));
			});
		}
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initLinks();
	});
	initLinks();
	
	
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
			
			$('#changable_content').load( url, function(){
				// Content loaded, hide "loading" content.
				$( '.bbq-loading' ).hide();
				glob.lastHash = hash;
			});
		}
	});
	
	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	$(window).trigger( 'hashchange' );
});