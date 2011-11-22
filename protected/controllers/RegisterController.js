function existcheck(user, email) {
	$.getJSON("protected/profile/usercheck.php?user="+user, function(data) {
        if (data==1) {
        	$("#errorRegisterUserExist").css('visibility','visible');
            return 0;
        }
        else {
        	$("#errorRegisterUserExist").css('visibility','hidden');
        }
    });
	$.getJSON("protected/profile/emailcheck.php?email="+email, function(data) {
        if (data==1) {
        	$("#errorRegisterMailExist").css('visibility','visible');
            return 0;
        }
        else {
            $("#errorRegisterMailExist").css('visibility','hidden');
        }
    });
}

function sregister() {
    var fname = document.form_register.fname.value;
    var lname = document.form_register.lname.value;
    var uname = document.form_register.uname.value.toLowerCase();
    var email = document.form_register.email.value.toLowerCase();
    var pass = document.form_register.pass1.value;
    var passt = document.form_register.pass2.value;
    var test = 1;
    existcheck(uname, email);
    $("#errorRegisterFirstname").css('visibility','hidden');
    if(!(fname.match(/^[a-zA-Z]+$/))){
        test = 0;
        $("#errorRegisterFirstname").css('visibility','visible');
    }
    $("#errorRegisterLastname").css('visibility','hidden');
    if(!(lname.match(/^[a-zA-Z]+$/))){
        test = 0;
        $("#errorRegisterLastname").css('visibility','visible');
    }
    $("#errorRegisterUsername").css('visibility','hidden');
    if(!(uname.match(/^[a-z0-9]+$/))){
        test = 0;
        $("#errorRegisterUsername").css('visibility','visible');
    } 
    $("#errorRegisterEmail").css('visibility','hidden');
    if(!(email.match(/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/))){
        test = 0;
        $("#errorRegisterEmail").css('visibility','visible');
    }
    $("#errorRegisterPass").css('visibility','hidden');
    if (pass.length <= 5 ){
        test = 0;
        $("#errorRegisterPass").css('visibility','visible');
    }
    $("#errorRegisterPassT").css('visibility','hidden');
    if (pass != passt){
        test = 0;
        $("#errorRegisterPassT").css('visibility','visible');
    }
    $("#errorRegisterfields").css('visibility','hidden');
    if (fname == "" || uname == "" || email == "" || pass == "" || passt == ""){
        test = 0;
        $("#errorRegisterfields").css('visibility','visible');
    }
    if (test == 1){
        $('#sBRegister').attr('onclick', "sentregister()");
    }
}

function sentregister(){
    var fname = document.form_register.fname.value;
    var lname = document.form_register.lname.value;
    var uname = document.form_register.uname.value.toLowerCase();
    var email = document.form_register.email.value.toLowerCase();
    var pass = document.form_register.pass1.value;
    var passt = document.form_register.pass2.value;
    $.getJSON("protected/profile/register.php?fname="+fname+"&lname="+lname+"&uname="+uname+"&email="+email+"&pass="+pass, function(data){
            if (data) {
                alert("User erfolgreich angelegt");
            }
            else {
                alert("Fehler bei der Registrierung, bitte melden sie sich beim administrator");
            }
            $("div.index_div_register").css('visibility','hidden');
            document.form_register.pass1.value = "";
            document.form_register.pass2.value = "";
    });
}