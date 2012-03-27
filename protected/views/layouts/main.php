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
		
		<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />-->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/designs/color1.css" media="screen, projection" id="design"/>
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.Jcrop.css"/>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body class="backpic">
		<?php /*<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/bg.png" alt="Background" id="index_pic_bg">*/ ?>
		<div id="page">
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
			<div id="metaNav">
				<a href="<?php echo Yii::app()->createUrl('site/index',array()); ?>"><div id="logo" class="backpic" alt="EveryCook Logo"></div></a>
				<div id="metaNavButtons">
					<a href="#" OnClick="ShowLogin()">
						<div class="nav_button">
							<span id="login"><?php echo $this->trans->LOGIN; ?></span>                  
						</div>
					</a>
					<div class="index_div_login">
						<form name="form_login">
							<label for="user"><span id="loginUser"><?php echo $this->trans->LOGIN_USER; ?></span></label>
							<input type="text" id="user" name="user"><br>
							<label for="pass"><span id="loginPass"><?php echo $this->trans->LOGIN_PASS; ?></span></label>
							<input type="password" id="pass" name="pass"><br><br>
							<a href="#" id="loginButton" OnClick="Login()">
								<div>
									<span id="loginSent"></span>
								</div>
							</a>
						</form>
					</div>
					<a href="#" OnClick="ShowSettings()">
						<div class="nav_button">
							<span id="settings"><?php echo $this->trans->SETTINGS; ?></span>
						</div>
					</a>
					<div class="nav_button">
						<span id="lang"><?php echo $this->trans->LANGSEL; ?></span>
					</div>
					<div class="index_div_lang">
						<?php
							//langlist();
						?>
					</div>
				</div>
				<div id="designs">
					<?php echo CHtml::link('Color1', Yii::app()->request->baseUrl . '/css/designs/color1.css'); ?><br>
					<?php echo CHtml::link('Color2', Yii::app()->request->baseUrl . '/css/designs/color2.css'); ?><br>
				</div>
			</div>
			<div id="changable_content">
				<?php echo $content; ?>
			</div>
		</div>
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by EveryCook.<br/>
			All Rights Reserved.<br/>
			<?php echo Yii::powered(); ?>
		</div><!-- footer -->
	</body>
</html>