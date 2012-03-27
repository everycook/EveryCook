jQuery(function($){
	function backpicFix(){
		if (!('backgroundSize' in document.documentElement.style)){
			jQuery('.backpic').each(function(){
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
	
	$('#page').ajaxComplete(function(e, xhr, settings) {
		backpicFix();
	});
	backpicFix();
});