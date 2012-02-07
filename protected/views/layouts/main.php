<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />

		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css"/>
		
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/lib/jquery/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/langpick.js"></script>
		
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body>
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/bg.png" alt="Background" id="index_pic_bg">
		<div id="index_div_content">
			<a href="#">
				<div>
					<div class="index_text_middle">
						<div>
							<a href="<?php echo Yii::app()->createUrl('site/index',array()); ?>"><img id="index_div_logo" src="<?php echo Yii::app()->request->baseUrl; ?>/pics/logo.png" alt="EveryCook Logo"></a>
						</div>
					</div>
				</div>
			</a>
			<?php /*
			<div id="mainmenu">
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Admin', 'url'=>array('/site/admin'), 'visible'=>!Yii::app()->user->isGuest),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); ?>
			</div><!-- mainmenu -->
			
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
				)); ?><!-- breadcrumbs -->
			<?php endif?>
			*/ ?>
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
							<span id="loginRegister"></span>
						</div>
					</a>
					<label for="user"><span id="loginUser"></span></label>
					<input type="text" id="user" name="user"><br>
					<label for="pass"><span id="loginPass"></span></label>
					<input type="password" id="pass" name="pass"><br><br>
					<a href="#" id="loginButton" OnClick="Login()">
						<div>
							<span id="loginSent"></span>
						</div>
					</a>
				</form>
			</div>
			<div class="index_div_register">
				<form name="form_register">
				<label for="firstname"><span id="registerFirstname"></span></label>
				<input onkeyup="sregister()" type="text" id="firstname" name="fname" value="Ihr Vorname"><br>
				<span class="error" id="errorRegisterFirstname"></span><br>
				<label for="lastname"><span id="registerLastname"></span></label>
				<input onkeyup="sregister()" type="text" id="lastname" name="lname" value="Ihr Nachname"><br>
				<span class="error" id="errorRegisterLastname"></span><br>
				<label for="username"><span id="registerUsername"></span></label>
				<input onkeyup="sregister()" type="text" id="username" name="uname" value="Gewünschter Benutzername"><br>
				<span class="error" id="errorRegisterUsername"></span><br>
				<label for="e-mail"><span id="registerEmail"></span></label>
				<input onkeyup="sregister()" type="text" id="e-mail" name="email" value="Ihre E-Mail Adresse"><br>
				<span class="error" id="errorRegisterEmail"></span><br>
				<label for="password1"><span id="registerPass"></span></label>
				<input onkeyup="sregister()" type="password" id="password1" name="pass1"><br>
				<span class="error" id="errorRegisterPass"></span><br>
				<label for="password2"><span id="registerPassT"></span></label>
				<input onkeyup="sregister()" type="password" id="password2" name="pass2"><br>
				<span class="error" id="errorRegisterPassT"></span><br>
				</form>
				<a href="#" id="sBRegister" OnClick="">
					<span class="error" id="errorRegisterfields"></span><br>
					<div>
						<span id="registerSent"></span>
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
			
			<?php echo $content; ?>
			
			<?php $this->widget('ext.widgets.MenuWidget',array(
					'test'=>'das ist ein test',
					'items'=>array(
						array('label'=>'Rezept Suchen', 'link_id'=>'left', 'url'=>array('recipes/index',array())),
						array('label'=>'Essen Suchen', 'link_id'=>'middle', 'url'=>array('ingredients/index',array())),
						array('label'=>'Die Kochende Maschiene', 'link_id'=>'right', 'url'=>array('site/page', array('view'=>'about'))),
					),
				)); ?>
			<div id="index_div_lang">
				<div class="index_text_middle">
					<div>
						<span id="lang"></span>
					</div>
				</div>
				<div class="index_div_lang">
					<?php
						//langlist();
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
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
			All Rights Reserved.<br/>
			<?php echo Yii::powered(); ?>
		</div><!-- footer -->
	</body>
</html>