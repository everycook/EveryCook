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
			<a href="#" OnClick="ShowView('0')">
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
				<div id="index_div_lang_p" class="index_div_lang_h">
					<?php
						langlist();
					?>
				</div>
			</div>
		</div>
	</body>
</html>  
