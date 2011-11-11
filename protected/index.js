if (localStorage.getItem('view')){
    var view = localStorage.getItem('view');
}
else {
    var view = "0";
}

$(document).ready(function() {
    
    if (localStorage.getItem('lang')){
        getlang(localStorage.getItem('lang'));
    }
    else {
        getlang('EN');
    }
    
    var screenH = screen.height/100*7;
    $("#index_div_logo").css('max-height',screenH+"px");
    var screenW = screen.width/100*25;
    $("label").css('width',screenW+"px");
});

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
        $('#loginButton').attr('value', data[4]);
        $('#registerFirstname').html(data[9]);
        $('#registerLastname').html(data[10]);
        $('#registerUsername').html(data[5]);
        $('#registerEmail').html(data[11]);
        $('#registerPass').html(data[6]);
        $('#registerPassT').html(data[12]);
        $('#sBRegister').attr('value', data[7]);
        $('#errorRegisterFirstname').html(data[13]);
        $('#errorRegisterLastname').html(data[14]);
        $('#errorRegisterUsername').html(data[15]);
        $('#errorRegisterUserExist').html(data[19]);
        $('#errorRegisterEmail').html(data[16]);
        $('#errorRegisterMailExist').html(data[20]);        
        $('#errorRegisterPass').html(data[17]);
        $('#errorRegisterPassT').html(data[18]);
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
    $("div.index_div_lang").css('visibility','hidden');
}





