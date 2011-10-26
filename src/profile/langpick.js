if (localStorage.getItem('lang')){
	getlang(localStorage.getItem('lang'));
}
else {
	getlang('EN');
}
$(document).ready(function() {
	$("#index_div_lang").hover(function() {
		$("div.index_div_lang").css('visibility','visible');
	},function() {
		$("div.index_div_lang").css('visibility','hidden');
	});
});
