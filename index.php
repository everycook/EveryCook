<!DOCTYPE html>
<html>
	<head>
		<title>EveryCook "The worlds best recipe database"</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="js/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
		<script type="text/javascript" src="js/langpick.js"></script>
		<?php
		include 'includes/db.inc.php';
		?>
	</head>
	<body>
		<img src="pics/bg.png" alt="Background" id="index_pic_bg">
		<div id="index_div_content">
			<a href="#" OnClick="ingredient()">
				<div id="index_div_logo">
					<div class="index_text_middle">
						<div>
							<img src="pics/logo.png" alt="EveryCook Logo">
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="ShowLogin()">
				<div id="index_div_login">
					<div class="index_text_middle">
						<div>
							<span id="login"></span>
						</div>
					</div>					
				</div>
			</a>
			<div class="index_div_login">
				<form name="form_login">
					<a href="#" OnClick="register()">
						<div>
							Registrieren
						</div>
					</a>
					<label for="user">Username:</label>
					<input type="text" id="user" name="user"><br>
					<label for="pass">Password:</label>
					<input type="password" id="pass" name="pass"><br>
					<a href="#" OnClick="Login()">
						<div>
							Anmelden
						</div>
					</a>
				</form>
			</div>
			<div class="index_div_register">
				<form name="form_register">
				<label for="firstname">Firstname:</label>
				<input type="text" id="firstname" name="fname" value="Ihr Vorname"><br>
				<label for="lastname">Lastname:</label>
				<input type="text" id="lastname" name="lname" value="Ihr Nachname"><br>
				<label for="username">Username:</label>
				<input type="text" id="username" name="uname" value="Gewünschter Benutzername"><br>
				<label for="e-mail">E-Mail:</label>
				<input type="text" id="e-mail" name="email" value="Ihre E-Mail Adresse"><br>
				<label for="password1">Password:</label>
				<input type="password" id="password1" name="pass1"><br>
				<label for="password2">Again:</label>
				<input type="password" id="password2" name="pass2"><br>
				</form>
				<a href="#" OnClick="sregister()">
					<div>
						Sent
					</div>
				</a>
				
			</div>
			<a href="#" OnClick="ShowSettings()">
				<div id="index_div_settings">
					<div class="index_text_middle">
						<div>
							<span id="settings"></span>
						</div>
					</div>
				</div>
			</a>
			<div id="index_div_mf">
				<span id="mf"></span>
			</div>
			<div id="index_div_mf_t">
				<span id="mf_t"></span>
			</div>
			<a href="#" OnClick="#" id="sr_onclick">
				<div id="index_div_sr">
					<div class="index_text_middle">
						<div>
							<span id="sr_content"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="#" id="sf_onclick">
				<div id="index_div_sf">
					<div class="index_text_middle">
						<div>
							<span id="sf_content"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="#" OnClick="#" id="tcm_onclick">
				<div id="index_div_tcm">
					<div class="index_text_middle">
						<div>
							<span id="tcm_content"></span>
						</div>
					</div>
				</div>
			</a>
			<div id="index_div_lang">
				<div class="index_text_middle">
					<div>
						<span id="lang"></span>
					</div>
				</div>
				<div class="index_div_lang">
					<?php
						langlist();
						//git change
					?>
				</div>
			</div>
			<div class="index_div_ingredient">
				<form name="form_ingredent">
					<label for="nut_id">Nut_ID:</label>
					<input type="text" id="nut_id" name="nut_id"><br>
					<label for="ing_pic">Bild auswählen:</label>
					<input type="file" id="ing_pic" name="ing_pic" size="20"><br>
					<label for="pic_auth">Bild-Author:</label>
					<input type="text" id="pic_auth" name="pic_auth"><br>
					<label for="ing_tit_EN">Inggredient Title EN:</label>
					<input type="text" id="ing_tit_EN" name="ing_tit_EN"><br>
					<label for="ing_tit_DE">Inggredient Title DE:</label>
					<input type="text" id="ing_tit_DE" name="ing_tit_DE"><br>
				</form>
				<a href="#" OnClick="singredent()">
					<div>
						Sent
					</div>
				</a>
				
			</div>
		</div>
	</body>
</html>  
