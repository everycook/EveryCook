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

jQuery(function($){
	var IMG_HEIGHT = 400;
	var IMG_WIDTH = 400;
	
	function initCrop(type, contentParent){
		if (typeof(contentParent) === 'undefined') return;
		var cropable = contentParent.find('.cropable');
		if (contentParent.find('#imagecrop_x').length == 0){
			cropable.each(function(){
				var parent = jQuery(this).parent();
				parent.append(jQuery('<input type="hidden" id="imagecrop_x" name="imagecrop_x" value="0"/>'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_y" name="imagecrop_y" value="0"/>'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_w" name="imagecrop_w" value="' + IMG_WIDTH + '"/>'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_h" name="imagecrop_h" value="' + IMG_HEIGHT + '"/>'));
			});
		}
		
		var maxWidth = window.screen.width  * 0.8;
		if (maxWidth<IMG_WIDTH*1.5){
			maxWidth = IMG_WIDTH*1.5;
		}
		var maxHeight = window.screen.height * 0.8;
		if (maxHeight<IMG_HEIGHT*1.5){
			maxHeight = IMG_HEIGHT*1.5;
		}
		
		cropable.Jcrop({
			//aspectRatio: 1,
			bgOpacity: .6,
			minSize: [40, 40],
			onSelect: updateCoords,
			boxWidth: maxWidth,
			boxHeight: maxHeight,
			//onChange: checkSelectionValide,
			onRelease: releaseCheck,
			handleOffset: 10,
		},function(){
			jcrop_api = this;
			var imgWidth = jcrop_api.getBounds()[0];
			var imgHeight = jcrop_api.getBounds()[1];
			var currentAspectRatio = imgWidth/imgHeight;
			if ((imgWidth>=(IMG_WIDTH*1.2) && imgHeight>=(IMG_HEIGHT*1.2) && currentAspectRatio>0.5 && currentAspectRatio<2) || (imgWidth==IMG_WIDTH && imgHeight==IMG_HEIGHT)){
				jcrop_api.setOptions({aspectRatio: 1, minSize: [IMG_WIDTH, IMG_HEIGHT],});
			} else {
				if (imgWidth>imgHeight){
					jcrop_api.setOptions({minSize: [IMG_WIDTH, 40],});
				} else {
					jcrop_api.setOptions({minSize: [40, IMG_HEIGHT],});
				}
			}
			jcrop_api.animateTo([0,0,IMG_WIDTH,IMG_HEIGHT], function(test){
				//jcrop_api.setOptions({onChange: checkSelectionValide,});
				updateCoords(jcrop_api.tellSelect());
			});
		});
		cropable.removeClass('cropable');
	}
	
	$('#page').bind('newContent.imgcrop_handling', function(e, type, contentParent) {
		initCrop(type, contentParent);
	});
	initCrop('initial', jQuery('#page'));
	
	
// The variable jcrop_api will hold a reference to the
// Jcrop API once Jcrop is instantiated.
	var jcrop_api;
	
	/*
	var jcrop_fixingSize = false;
	
	function checkSelectionValide(c){
		if (c.w<400 && c.h<400){
			if (!jcrop_fixingSize){
				var imgWidth = jcrop_api.getBounds()[0];
				var imgHeight = jcrop_api.getBounds()[1];
				jcrop_api.setOptions({ allowResize: false });
				jcrop_fixingSize = true;
				if (imgWidth>imgHeight){
					jcrop_api.setSelect([c.x,c.y,400,c.h]);
				} else {
					jcrop_api.setSelect([c.x,c.y,c.w,400]);
				}
				jcrop_fixingSize = false;
				jcrop_api.setOptions({ allowResize: true });
			}
		}
	};
	*/
	
	function updateCoords(c){
		if (isNaN(c.x)){
			return;
		}
		jQuery('#imagecrop_x').val(c.x);
		jQuery('#imagecrop_y').val(c.y);
		jQuery('#imagecrop_w').val(c.w);
		jQuery('#imagecrop_h').val(c.h);
	};
	function releaseCheck(){
		updateCoords({});
	}
	
	
	glob.showImageOrError = function(filenameInput, data){
		var elem = filenameInput;
		if (data.indexOf('{')===0){
			eval('var data = ' + data + ';');
			var isJSON = true;
		} else {
			var isJSON = false;
		}
		var imageParent = elem.parent().parent();
		var imageBefore = elem.parent();
		if (imageBefore.is('.imageTip')){
			imageParent = imageParent.parent();
			imageBefore = imageBefore.parent();
		}
		if (data.imageId){
			if (typeof(jcrop_api) !== 'undefined' && jcrop_api != null){
				jcrop_api.destroy();
			}
			imageParent.find('img').remove();
			imageParent.find('#img_error').remove();
			var rand = Math.floor(Math.random()*1000000000);
			var image = jQuery('<img src="' + jQuery('#imageLink').attr('value') + '?rand=' + rand+ '" class="cropable"/>');
			image.insertBefore(imageBefore);
			initCrop('form', imageBefore.parent());
			elem.attr('value','');
		} else {
			//No image/unknown type uploaded...
			if (typeof(jcrop_api) !== 'undefined' && jcrop_api != null){
				jcrop_api.destroy();
			}
			imageParent.find('img').remove();
			imageParent.find('#img_error').remove();
			var error = jQuery('<span id="img_error" class="error">' + data.error + '</span>');
			error.insertBefore(imageBefore);
			elem.attr('value','');
			
			if (!isJSON){
				//Show error in fancy
				glob.setContentWithImageChangeToFancy(data, {});
			}
		}
	};
	
	
	jQuery('body').undelegate('[name*="[filename]"]','change').delegate('[name*="[filename]"]','change', function(){
		var elem = jQuery(this);
		var form = elem.parents('form:first');
		var oldAction = form.attr('action');
		var oldEnctype = form.attr('enctype');
		form.attr('action', jQuery('#uploadImageLink').attr('value'));
		form.attr('enctype', 'multipart/form-data');
		form.unbind('submit');
		//form.append('<input type="hidden" class="cropMaxInitSize" name="MaxHeight" value="' + window.screen.height + '"/>');
		//form.append('<input type="hidden" class="cropMaxInitSize" name="MaxWidth" value="' + window.screen.width + '"/>');
		form.iframePostForm({
			'json' : false, /*JSON.parse sems do not work correct...*/
			'iframeID' : 'imageUploadFrame',
			'post' : function (){
				//Do check if form is OK
				//return false; // to abort send
			},
			complete : function (data) {
				glob.showImageOrError(elem, data);
			}
		});
		form.submit();
		
		form.attr('action', oldAction);
		if (typeof(oldEnctype) === 'undefined'){
			form.removeAttr('enctype');
		} else {
			form.attr('enctype', oldEnctype);
		}
		//form.find('.cropMaxInitSize').remove();
		glob.initAjaxUpload('form', form.parent());
	});
});