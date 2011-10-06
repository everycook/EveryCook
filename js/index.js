var view = "0";
var fields = new Array();
function getlang(language) {
	$.getScript('langmenu.php?language='+language, function() {
		$('#login').html(fields['IME_LOGIN']);
		$('#settings').html(fields['IME_SETTINGS']);
		$('#lang').html(fields['IME_LANGSEL']);	
		$('#sr_content').html(fields['ITE_BOTL_CONTENT']);
		$('#sf_content').html(fields['ITE_BOTM_CONTENT']);
		$('#tcm_content').html(fields['ITE_BOTR_CONTENT']);
		$('#mf_t').html(fields['ITE_MIDL']);
		$('#mf').html(fields['ITE_MIDR']);
	});
}
getlang('EN');
$(document).ready(function() {
	$("#index_div_lang").hover(function() {
		$("#index_div_lang_p").addClass("index_div_lang_v");
	},function(){
		$("#index_div_lang_p").removeClass("index_div_lang_v");
	});
});