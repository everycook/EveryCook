if (localStorage.getItem('view')){
    var view = localStorage.getItem('view');
}
else {
    var view = "0";
}

function getlang(language) {
    if (language != localStorage.getItem('lang')){
        localStorage.setItem('lang', language);
    }
    if (view != localStorage.getItem('view')){
        localStorage.setItem('view',view);
    }
    $.getJSON("protected/profile/langmenu.php?language="+language+"&view="+view,function(data) {
        $('#lang').html(data[2]);
        $('#settings').html(data[3]);
        $('#login').html(data[4]);
        $('#loginUser').html(data[5]);
        $('#loginPass').html(data[6]);
        $('#loginRegister').html(data[7]);
        $('#loginSent').html(data[8]);
        $('#registerFirstname').html(data[9]);
        $('#registerLastname').html(data[10]);
        $('#registerUsername').html(data[11]);
        $('#registerEmail').html(data[12]);
        $('#registerPass').html(data[13]);
        $('#registerPassT').html(data[14]);
        $('#registerSent').html(data[7]);
        $('#errorRegisterFirstname').html(data[15]);
        $('#errorRegisterLastname').html(data[16]);
        $('#errorRegisterUsername').html(data[17]);
        $('#errorRegisterEmail').html(data[18]);
        $('#errorRegisterPass').html(data[19]);
        $('#errorRegisterPassT').html(data[20]);
        $('#errorRegisterfields').html(data[21]);
        $('#sr_content').html(data[24]);
        $('#sr_onclick').attr('onclick', data[25]);
        $('#sf_content').html(data[26]);
        $('#sf_onclick').attr('onclick', data[27]);
        $('#tcm_content').html(data[28]);
        $('#tcm_onclick').attr('onclick', data[29]);
        $('#mf_t').html(data[30]);
        $('#mf').html(data[31]);
    });
}

function ShowLogin(){
    if ($("div.index_div_register").css('visibility')=="visible") {
        $("div.index_div_register").css('visibility','hidden');
        $("#errorRegisterFirstname").css('visibility','hidden');
        $("#errorRegisterLastname").css('visibility','hidden');
        $("#errorRegisterUsername").css('visibility','hidden');
        $("#errorRegisterEmail").css('visibility','hidden');
        $("#errorRegisterPass").css('visibility','hidden');
        $("#errorRegisterPassT").css('visibility','hidden');
        $("#errorRegisterfields").css('visibility','hidden');
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

function register() {
    $("div.index_div_login").css('visibility','hidden');
    $("div.index_div_register").css('visibility','visible');
}

function sregister() {
    var fname = document.form_register.fname.value;
    var lname = document.form_register.lname.value;
    var uname = document.form_register.uname.value.toLowerCase();
    var email = document.form_register.email.value.toLowerCase();
    var pass = document.form_register.pass1.value;
    var passt = document.form_register.pass2.value;
    var test = 1;
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
