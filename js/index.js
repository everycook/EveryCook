var view = "0";
			var fields = new Array();
			function getlang(language) {
				$.getScript('langmenu.php?language='+language, function() {
					$('#login').html(fields['IME_LOGIN']);
					$('#settings').html(fields['IME_SETTINGS']);
					$('#lang').html(fields['IME_LANGSEL']);	
				});
				$.getScript('langview.php?language='+language+'&view='+view, function() {
					$('#sr').html(fields['ITE_BOTL']);
					$('#sf').html(fields['ITE_BOTM']);
					$('#tcm').html(fields['ITE_BOTR']);
					$('#text_mf').html(fields['ITE_MIDL']);
					$('#mf').html(fields['ITE_MIDR']);
				});
			}
			getlang('English');
			$(document).ready(function() {
				$("#index_lang").hover(function() {
					$("#index_lang_pick").addClass("index_lang_v");
				},function(){
					$("#index_lang_pick").removeClass("index_lang_v");
				});
			});