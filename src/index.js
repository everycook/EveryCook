var view = "0";
function getlang(language) {
	if (language != localStorage.getItem('lang')){
		localStorage.setItem('lang', language);
	}
	if (view != localStorage.getItem('view')){
		localStorage.setItem('view',view);
	}
	$.getJSON("profile/langmenu.php?language="+language+"&view="+view,function(data) {
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
	if ($("div.index_div_register").css('visibility')=="visible") {
		$("div.index_div_register").css('visibility','hidden');
	}
	else {
		if ($("div.index_div_login").css('visibility')=="visible"){
			$("div.index_div_login").css('visibility','hidden');
		}
		else {
			$("div.index_div_login").css('visibility','visible');
		}
	}
}


function Login(){
	var user = document.form_login.user.value;
	var pass = document.form_login.pass.value;
	
	$.getJSON("profile/login.php?user="+user+"&pass="+pass, function(data) {
		if (data==1) {
			alert("anmeldung erfolgreich");
			document.form_login.reset();
			$("div.index_div_login").css('visibility','hidden');
		}
		else if (data>=2) {
			alert("Mehrere einträge vorhanden");
			document.form_login.reset();
		}
		else {
			alert("Anmeldedaten Falsch");
			document.form_login.reset();
		}
	});
}

function register() {
	$("div.index_div_login").css('visibility','hidden');
	$("div.index_div_register").css('visibility','visible');
}

function sregister() {
	var fname = document.form_register.fname.value;
	var lname = document.form_register.lname.value;
	var uname = document.form_register.uname.value;
	var email = document.form_register.email.value;
	var pass = document.form_register.pass1.value;
	var passt = document.form_register.pass2.value;
	
	if (fname!=""&&lname!=""&&uname!=""&&email!=""){
		if(pass == passt){
			if(pass.length >= 6){
					
					$.getJSON("profile/register.php?fname="+fname+"&lname="+lname+"&uname="+uname+"&email="+email+"&pass="+pass, function(data){
					if (data) {
						alert("User erfolgreich angelegt");
					}
					else {
						alert("Fehler bei der Registrierung, bitte melden sie sich beim administrator");
					}
					$("div.index_div_register").css('visibility','hidden');
				});
				
		}
		else alert("passwort muss mindestens 6 zeichen lang sein");
		}
		else {
			alert("Passwörter sind nicht identisch");
			document.form_register.pass1.value = "";
			document.form_register.pass2.value = "";
		}
	}
	else {
		alert("bitte alle felder ausfüllen");
	}
}
function ingredient() {
	if ($("div.index_div_ingredient").css('visibility')=="visible") {
		$("div.index_div_ingredient").css('visibility','hidden');
	}
	else {
		$("div.index_div_ingredient").css('visibility','visible');
	}
}
function singredent() {
	var nut_id = document.form_ingredent.nut_id.value;
	var ing_pic = document.form_ingredent.ing_pic.value;
	var pic_auth = document.form_ingredent.pic_auth.value;
	var ing_tit_EN = document.form_ingredent.ing_tit_EN.value;
	var ing_tit_DE = document.form_ingredent.ing_tit_DE.value;
	alert(ing_pic+pic_auth);
	document.form_ingredent.reset();
}
