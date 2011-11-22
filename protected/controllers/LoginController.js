function Login(){
    var user = document.form_login.user.value;
    var pass = document.form_login.pass.value;
    
    $.getJSON("protected/profile/login.php?user="+user+"&pass="+pass, function(data) {
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