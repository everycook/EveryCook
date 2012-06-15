var glob = glob || {};

glob.currentDesign = '';

jQuery(function($){
	function initDesigns(){
		glob.currentDesign = jQuery('#design').attr('href');
	}
	
	initDesigns();
	
	jQuery('body').undelegate('#designs_List a','click').delegate('#designs_List a','click',function(){
		var link = jQuery(this);
		var file = link.attr('href');
		glob.currentDesign = file;
		jQuery('#design').attr('href', file);
		
		var namePos = file.lastIndexOf('/');
		var design = file.substr(namePos+1,file.length-namePos-5);
		
		var url = jQuery('#changeDesignLink').val();
		url = glob.urlAddParamStart(url);
		url = url + 'design=' + design;
		jQuery.ajax({'type':'get', 'url':url,'cache':false});
		return false;
	});
	
	jQuery('body').undelegate('#designs_List a','mouseenter').delegate('#designs_List a','mouseenter',function(){
		var link = jQuery(this);
		var file = link.attr('href');
		jQuery('#design').attr('href', file);
		return false;
	});
	
	jQuery('body').undelegate('#designs_List a','mouseleave').delegate('#designs_List a','mouseleave',function(){
		jQuery('#design').attr('href', glob.currentDesign);
		return false;
	});
	
});