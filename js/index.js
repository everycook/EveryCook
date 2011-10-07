var view = "0";
function getlang(language) {
	$.getJSON("langmenu.php?language="+language+"&view="+view, function(data) {
		$('#lang').html(data[2]);
		$('#settings').html(data[3]);
		$('#login').html(data[4]);
		$('#sr_content').html(data[7]);
		$('#sf_content').html(data[9]);
		$('#tcm_content').html(data[11]);
		$('#mf_t').html(data[13]);
		$('#mf').html(data[15]);
	});
}
