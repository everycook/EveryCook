/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

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