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
                        <?/*Yii::app()->user->isGuest */?>
			<div id="metaNav">
				<a href="<?php echo Yii::app()->createUrl('site/index',array()); ?>"><div id="logo" class="backpic" alt="EveryCook Logo"></div></a>
				<div style="float: left;">
					<?php 
					if(Yii::app()->user->isGuest) {
						echo 'session: ' . Yii::app()->session['lang'];
					} else {
						echo 'user: ' . Yii::app()->user->lang;
						echo "<br>Welcome User: ".Yii::app()->user->nick;
					}
					?>
				</div>

				<div id="metaNavButtons">
					<!-- <a href="#site/login" OnClick="ShowLogin()"> -->
					<?php
					if(Yii::app()->user->isGuest) {
						echo '<a href="'. Yii::app()->createUrl('site/login',array()) . '">';
					} else {
						echo '<a class="noAjax" href="' . Yii::app()->createUrl('site/logout',array()) . '">';
					}
					?>
						<div class="nav_button">
							<span><?php if(Yii::app()->user->isGuest) echo $this->trans->GENERAL_LOGIN; else echo $this->trans->GENERAL_LOGOUT; ?></span>                  
						</div>
					</a>
					<?php
					if(!Yii::app()->user->isGuest) {
						echo '<a href="' . Yii::app()->createUrl('profiles/update',array('id'=>Yii::app()->user->id)) . '">';
					?>
						<div class="nav_button">
							<span><?php echo $this->trans->GENERAL_SETTINGS; ?></span>
						</div>
					</a>
					<?php } ?>
					<div class="nav_button" id="JumpTo">
						<span><?php echo $this->trans->GENERAL_JUMPTO; ?></span>
					</div>
					<div id="JumpTos" style="display: none;">
						<?php
						$first = true;
						foreach($this->getJumpTos() as $title=>$link){
							if ($first){
								echo '<a class="button first" href="' . $link . '">' . $title . '</a><br>'."\n";
								$first = false;
							} else {
								echo '<a class="button" href="' . $link . '">' . $title . '</a><br>'."\n";
							}
						}
						?>
					</div>
				</div>
				<div id="designs">
					<?php echo CHtml::link('Color1', Yii::app()->request->baseUrl . '/css/designs/color1.css', array('class'=>'noAjax')); ?><br>
					<?php echo CHtml::link('Color2', Yii::app()->request->baseUrl . '/css/designs/color2.css', array('class'=>'noAjax')); ?><br>
				</div>
			</div>
			<?php 
			if (isset(Yii::app()->session['current_gps_time']) && Yii::app()->session['current_gps_time']<time()) {
				unset(Yii::app()->session['current_gps']);
				unset(Yii::app()->session['current_gps_time']);
			}
			?>
			<input type="hidden" id="current_gps_lat" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][0];} ?>" />
			<input type="hidden" id="current_gps_lng" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][1];} ?>" />
			<input type="hidden" id="current_gps_time" value="<?php if (isset(Yii::app()->session['current_gps_time'])) {echo Yii::app()->session['current_gps_time'];} ?>" />
			<input type="hidden" id="markCurrentGPS" value="<?php echo Yii::app()->createUrl('stores/currentGPSForStores'); ?>" />
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
