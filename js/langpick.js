$(document).ready(function() {
    $("#index_div_lang").hover(function() {
        $("div.index_div_lang").css('visibility','visible');
    },function() {
        $("div.index_div_lang").css('visibility','hidden');
    });
    if (localStorage.getItem('lang')){
        getlang(localStorage.getItem('lang'));
    }
    else {
        getlang('EN');
    }
    var screenH = screen.height/100*7;
    $("#index_div_logo").css('max-height',screenH+"px");

});
