var glob = glob || {};

var cpage=0;
function generateFlickrImg(reqtype){
//reqtype
//0:initial request
//-1:left flip
//1:right flip

rab=glob.prefix + "pics/rablack.png";
lab=glob.prefix + "pics/lablack.png";
rag=glob.prefix + "pics/ragray.png";
lag=glob.prefix + "pics/lagray.png";


if(!(cpage==0&&reqtype!=0)&&!(cpage==1&&reqtype==-1)){
	if(reqtype==0) cpage=1;
	cpage=cpage+reqtype;
	if(cpage==1){
		cpage=1;
		$("#leftarrow").attr("src",lag);
		$("#rightarrow").attr("src",rab);
	}else{
		$("#leftarrow").attr("src",lab);
		$("#rightarrow").attr("src",rab);
}

	$.ajax({
			url: glob.prefix + 'ingredients/generateFlickrImage',
			type: 'get', 
			data: { 
				keyword: jQuery("#Ingredients_ING_NAME_EN_GB").val(),
				page: cpage
			},
			success: function(msg){
				jQuery('#flickrimgboxIng').html(msg);
			}
			});
		}
}

function flickrupload(linkname,author,photoid){
		var elem = jQuery('#Ingredients_filename');
		jQuery('#flickr_link').val(linkname);
		jQuery('#flickrauthor').val(author);
		jQuery('#photoid').val(photoid);

		var form = elem.parents('form:first');
		var oldAction = form.attr('action');
		var oldEnctype = form.attr('enctype');
		form.attr('action', jQuery('#uploadImageLink').attr('value'));
		
		form.attr('enctype', 'multipart/form-data');
		form.unbind('submit');

		
		form.iframePostForm({
			'json' : false, /*JSON.parse sems do not work correct...*/
			'iframeID' : 'imageUploadFrame',
			'post' : function (){

			},
			complete : function (data) {
				glob.showImageOrError(elem, data);
				jQuery('#Ingredients_ING_IMG_AUTH').val(author);

			}
		});
		form.submit();

		form.attr('action', oldAction);
		if (typeof(oldEnctype) === 'undefined'){
			form.removeAttr('enctype');
		} else {
			form.attr('enctype', oldEnctype);
		}
		glob.initAjaxUpload('form', form.parent());
}

$(function(){
jQuery("#Ingredients_ING_NAME_EN_GB").bind('blur',translateMStext);
});

function translateMStext(eventob){
	keyword=jQuery("#Ingredients_ING_NAME_EN_GB").val();
	if(keyword!=''){
	$.ajax({
			url: glob.prefix + 'ingredients/translateTextMicrosoft',
			type: 'get', 
			data: { 
				text: keyword,
			},
			success: function(msg){
				jQuery('#Ingredients_ING_NAME_DE_CH').val(msg);
			}
			});
		}
}
