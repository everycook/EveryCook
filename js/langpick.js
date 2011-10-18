if (localStorage.getItem('lang')){
	getlang(localStorage.getItem('lang'));
}
else {
	getlang('EN');
}
$(document).ready(function() {
	$("#index_div_lang").hover(function() {
		$("#index_div_lang_p").addClass("index_div_lang_v");
	},function() {
		$("#index_div_lang_p").removeClass("index_div_lang_v");
	});
});