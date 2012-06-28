jQuery(function($){
	function initCrop(){
		var cropable = $('.cropable');
		if (jQuery('#imagecrop_x').length == 0){
			cropable.each(function(){
				var parent = jQuery(this).parent();
				parent.append(jQuery('<input type="hidden" id="imagecrop_x" name="imagecrop_x" />'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_y" name="imagecrop_y" />'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_w" name="imagecrop_w" />'));
				parent.append(jQuery('<input type="hidden" id="imagecrop_h" name="imagecrop_h" />'));
			});
		}
		cropable.Jcrop({
			aspectRatio: 1,
			bgOpacity: .6,
			minSize: [400, 400],
			onSelect: updateCoords,
			onChange: updateCoords,
			onRelease: releaseCheck,
		},function(){
			jcrop_api = this;
			jcrop_api.animateTo([0,0,400,400]);
		});
		cropable.removeClass('cropable');
	}
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		initCrop();
	});
	initCrop();
	
// The variable jcrop_api will hold a reference to the
// Jcrop API once Jcrop is instantiated.
	var jcrop_api;
	
	function updateCoords(c){
		jQuery('#imagecrop_x').val(c.x);
		jQuery('#imagecrop_y').val(c.y);
		jQuery('#imagecrop_w').val(c.w);
		jQuery('#imagecrop_h').val(c.h);
	};
	function releaseCheck(){
		updateCoords({});
	}
	
	jQuery('body').undelegate('[name*="[filename]"]','change').delegate('[name*="[filename]"]','change', function(){
		var elem = jQuery(this);
		var form = elem.parents('form:first');
		var oldAction = form.attr('action');
		form.attr('action', jQuery('#uploadImageLink').attr('value'));
		form.unbind('submit');
		form.iframePostForm({
			'json' : false, /*JSON.parse sems do not work correct...*/
			'iframeID' : 'imageUploadFrame',
			'post' : function (){
				//Do check if form is OK
				//return false; // to abort send
			},
			complete : function (data) {
				if (data.indexOf('{')===0){
					eval('var data = ' + data + ';');
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
					initCrop();
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
				}
			}
		});
		form.submit();
		
		form.attr('action', oldAction);
		glob.initAjaxUpload();
	});
});