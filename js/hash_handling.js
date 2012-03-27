var glob = glob || {};

glob.prefix = window.location.pathname.substr(0,window.location.pathname.indexOf('/',1)+1);
	
glob.hashToUrl = function(url){
	return glob.prefix + 'index.php/' + url;
};

jQuery(function($){
	//Initialize Links
	function initLinks(){
		$('a[href*="index.php/"]:not(.fancyChoose)').each(function(){
			$(this).attr('href', "#" + $(this).attr('href').substr(glob.prefix.length+10));
		});
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initLinks();
	});
	initLinks();
	
	
	$(window).bind('hashchange', function(e) {
		// Get the hash (fragment) as a string, with any leading # removed. Note that
		// in jQuery 1.4, you should use e.fragment instead of $.param.fragment().
		var url = $.param.fragment();
		
		// Remove .bbq-current class from any previously "current" link(s).
		$( 'a.bbq-current' ).removeClass( 'bbq-current' );
		
		// Hide any visible ajax content.
		//$( '.bbq-content' ).children( ':visible' ).hide();
		
		if (url !== ''){
			// Add .bbq-current class to "current" nav link(s), only if url isn't empty.
			url && $( 'a[href="#' + url + '"]' ).addClass( 'bbq-current' );
			
			 // Show "loading" content while AJAX content loads.
			$( '.bbq-loading' ).show();
			
			url = glob.hashToUrl(url);
			
			$('#changable_content').load( url, function(){
				// Content loaded, hide "loading" content.
				$( '.bbq-loading' ).hide();
			});
		}
	});
	
	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	$(window).trigger( 'hashchange' );
});