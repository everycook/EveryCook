<!DOCTYPE html>
<html>
	<head>
		<title>EveryCook "The worlds best recipe database"</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="js/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
	</head>
	<body>
		<img src="pics/bg.png" alt="Background" id="index_bg"/>
		<div id="index_content">
			<a href="#" OnClick="ShowLogin()">
				<div id="index_log">
					<div class="index_text_middle">
						<div>
							<span id="login"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="ShowSettings()">
				<div id="index_set">
					<div class="index_text_middle">
						<div>
							<span id="settings"></span>
						</div>
					</div>
				</div>
			</a>
			<div id="index_mf">
				<span id="mf"></span>
			</div>
			<div id="index_text_mf">
				<span id="text_mf"></span>
			</div>
			<a href="#" OnClick="ShowView('1')">
				<div id="index_sr">
					<div class="index_text_middle">
						<div>
							<span id="sr"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="ShowView('2')">
				<div id="index_sf">
					<div class="index_text_middle">
					<img src="pics/Bananen.jpg" width="100%" height="100%">
						<div>
							<span id="sf"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="ShowView('3')">
				<div id="index_tcm">
					<div class="index_text_middle">
						<div>
							<span id="tcm"></span>
						</div>
					</div>
				</div>
			</a>
			<div id="index_lang">
				<div class="index_text_middle">
					<div>
						<span id="lang"></span>
					</div>
				</div>
				<div id="index_lang_pick" class="index_lang_h">
					<?php
						$lang_array;			
						$db_server = 'localhost';
						$db_name = '30608_everycook';
						$db_user = 'root';
						$db_passwort = 'test';
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
