var view = "0";
function getlang(language) {
	$.getJSON("langmenu.php?language="+language+"&view="+view,function(data) {
		$('#lang').html(data[2]);
		$('#settings').html(data[3]);
		$('#login').html(data[4]);
		$('#sr_content').html(data[7]);
		$('#sr_onclick').attr('onclick', data[8]);
		$('#sf_content').html(data[9]);
		$('#sf_onclick').attr('onclick', data[10]);
		$('#tcm_content').html(data[11]);
		$('#tcm_onclick').attr('onclick', data[12]);
		$('#mf_t').html(data[13]);
		$('#mf').html(data[15]);
		
	});
}
