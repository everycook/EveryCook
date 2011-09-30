<!DOCTYPE html>
<html>
	<head>
		<title>EveryCook "The worlds best recipe database"</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="js/index.js"></script>
		<script type="text/javascript">
		var view = "0";
		var fields = new Array();
		function getlang(language) {
			$.getScript('langmenu.php?language='+language, function() {
				document.getElementById("login").innerHTML=fields['IME_LOGIN'];
				document.getElementById("settings").innerHTML=fields['IME_SETTINGS'];
				document.getElementById("lang").innerHTML=fields['IME_LANGSEL'];	
			});
			$.getScript('langview.php?language='+language+'&view='+view, function() {
				document.getElementById("sr").innerHTML=fields['ITE_BOTL'];
				document.getElementById("sf").innerHTML=fields['ITE_BOTM'];
				document.getElementById("tcm").innerHTML=fields['ITE_BOTR'];
				document.getElementById("text_mf").innerHTML=fields['ITE_MIDL'];
				document.getElementById("mf").innerHTML=fields['ITE_MIDR'];
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
		</script>
	</head>
	<body>
		<img src="pics/bg.png" alt="Background" id="index_bg"/>
		<div id="index_content">
			<span id="login"></span>
			<span id="settings"></span>
			<span id="mf"></span>
			<span id="text_mf"></span>
			<span id="sr"></span>
			<span id="sf"></span>
			<span id="tcm"></span>
			<div id="index_lang">
				<div class="index_text_middle">
					<div>
						<span id="lang"></span>
					</div>
				</div>
				<div id="index_lang_pick" class="index_lang_h">
					<?php
						$lang_array;			
						/* Datenbankserver - In der Regel die IP */
						$db_server = 'localhost';
						/* Datenbankname */
						$db_name = '30608_everycook';
						/* Datenbankuser */
						$db_user = 'root';
						/* Datenbankpasswort */
						$db_passwort = 'test';
							 
						/* Erstellt Connect zu Datenbank */
						$db = @ mysql_connect ( $db_server, $db_user, $db_passwort );
						
						$db_select = @ mysql_select_db( $db_name );
						mysql_query("SET NAMES 'utf8'");
						$sql = 'SELECT * FROM interface_menu ';
						
						$ergebnis = mysql_query( $sql );
						while ($row=mysql_fetch_assoc($ergebnis)){
							echo '<a href="#" onClick="getlang(\''.$row['IME_LANGNAME'].'\');">';
							echo $row['IME_LANGNAME'];
							echo '</a>';
							echo "<br>";
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>  
