var glob = glob || {};

glob.currentDesign = '';

jQuery(function($){
	function initDesigns(){
		glob.currentDesign = jQuery('#design').attr('href');
	}
	
	initDesigns();
	
	
	jQuery('body').undelegate('#designs a','click').delegate('#designs a','click',function(){
		var link = jQuery(this);
		var file = link.attr('href');
		glob.currentDesign = file;
		jQuery('#design').attr('href', file);
		return false;
	});
	
	jQuery('body').undelegate('#designs a','mouseenter').delegate('#designs a','mouseenter',function(){
		var link = jQuery(this);
		var file = link.attr('href');
		jQuery('#design').attr('href', file);
		return false;
	});
	
	jQuery('body').undelegate('#designs a','mouseleave').delegate('#designs a','mouseleave',function(){
		jQuery('#design').attr('href', glob.currentDesign);
		return false;
	});
	
});