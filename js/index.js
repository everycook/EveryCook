var view = "0";
function getlang(language) {
	if (language != localStorage.getItem('lang')){
		localStorage.setItem('lang', language);
	}
	if (view != localStorage.getItem('view')){
		localStorage.setItem('view',view);
	}
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

function ShowLogin(){
	$("div.index_div_login").css('visibility','visible');
}

function Login(){
	var user = document.login.user.value;
	var pass = document.login.pass.value;
	
	$.getJSON("login.php?user="+user+"&pass="+pass, function(data) {
		if (data==1) {
			alert("anmeldung erfolgreich");
			document.login.reset();
		}
		else if (data>=2) {
			alert("Mehrere einträge vorhanden");
			document.login.reset();
		}
		else {
			alert("Anmeldedaten Falsch");
			document.login.reset();
		}
		$("div.index_div_login").css('visibility','hidden');
	
	});
}
