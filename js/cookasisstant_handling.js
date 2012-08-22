var glob = glob || {};
jQuery(function($){
	function initTimer(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		/*
		finishTime
		finishTime
		nextTime*/
	}
	
	$('#page').bind('newContent.ajax_handling', function(e, type, contentParent) {
		initTimer(type, contentParent);
	});
	initTimer('initial', jQuery('#page'));
});