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

jQuery(function($){
	function backpicFix(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		if (!('backgroundSize' in document.documentElement.style)){
			contentParent.find('.backpic').each(function(){
				var elem = jQuery(this);
				var image = elem.css('background-image');
				elem.css('background','none');
				elem.removeClass('backpic');
				image = image.substr(5,image.length-7);//remove 'url("")' part
				
				var title = elem.attr('title');
				if (typeof(title) === 'undefined'){title = '';}
				var innerImage = jQuery('<img src="' + image + '" class="backpicfix" title="' + title + '" alt="' + title + '" style="width:100%; height:100%"/>');
				//elem.contents().remove();
				elem.children('.backpicfix').remove();
				elem.prepend(innerImage);
			});
		}
	}
	
	
	$('#page').bind('newContent.iefix_handling', function(e, type, contentParent) {
		backpicFix(type, contentParent);
	});
	backpicFix('initial', jQuery('html'));
});