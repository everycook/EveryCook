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
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/designs/<?php echo Yii::app()->user->design; ?>.css" media="screen, projection" id="design"/>
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.Jcrop.css"/>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body class="backpic">
		<?php /*<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/bg.png" alt="Background" id="index_pic_bg">*/ ?>
		<div id="page">
			<div id="metaNav">
				<a href="<?php echo Yii::app()->createUrl('site/index',array()); ?>"><div id="logo" class="backpic" alt="EveryCook Logo"></div></a>
				<div id="metaNavButtons">
					<?php
					if(Yii::app()->user->isGuest) {
						echo '<a href="'. Yii::app()->createUrl('site/login',array()) . '">';
					} else {
						echo '<a class="noAjax" href="' . Yii::app()->createUrl('site/logout',array()) . '">';
					}
					?>
						<div class="nav_button">
							<span><?php if(Yii::app()->user->isGuest) {echo $this->trans->GENERAL_LOGIN;} else {printf($this->trans->GENERAL_LOGOUT, Yii::app()->user->nick);} ?></span>
						</div>
					</a>
					<?php
					if(!Yii::app()->user->isGuest) {
					?>
					<div class="nav_button navMenu" id="settings">
						<span><?php echo $this->trans->GENERAL_SETTINGS; ?></span>
					</div>
					<div id="settings_List" class="navMenuList" style="right: 23em; display: none;">
						<?php echo '<a href="' . Yii::app()->createUrl('profiles/update',array('id'=>Yii::app()->user->id)) . '" class="button navMenuListEntry first">'; ?>
							<?php echo $this->trans->SETTINGS_PROFILE; ?>
						</a><br>
						<div class="button navMenuL2 navMenuListEntry" id="designs">
							<span><?php echo $this->trans->SETTINGS_DESIGNS; ?></span>
						</div><br>
						<div id="designs_List" class="navMenuListL2" style="display: none;">
							<input type="hidden" id="changeDesignLink" value="<?php echo $this->createUrl('profiles/changeDesignMenu'); ?>"/>
								<?php
								$designs = array();
								if ($handle = opendir('./css/designs')) {
									while (false !== ($file = readdir($handle))) {
										if (substr($file,strlen($file)-4) == '.css') {
											$designs[] = substr($file,0,strlen($file)-4);
										}
									}
									closedir($handle);
								}
								$first = true;
								foreach($designs as $name){
									$addClass = '';
									if ($name == Yii::app()->user->design){
										$addClass .= ' active';
									}
									if ($first){
										$addClass .= ' first';
										$first = false;
									}
									echo CHtml::link($name, Yii::app()->request->baseUrl . '/css/designs/' . $name . '.css', array('class'=>'button navMenuListEntry noAjax' . $addClass)) . '<br>';
								}
							?>
						</div>
						<div class="button navMenuL2 navMenuListEntry" id="languages">
							<span><?php echo $this->trans->SETTINGS_LANGUAGES; ?></span>
						</div><br>
						<div id="languages_List" class="navMenuListL2" style="display: none;">
							<?php
								$first = true;
								foreach($this->allLanguages as $id=>$name) {
									$addClass = '';
									if ($id == Yii::app()->session['lang']){
										$addClass .= ' active';
									}
									if ($first){
										$addClass .= ' first';
										$first = false;
									}
									echo CHtml::link($name, array('profiles/changeLanguageMenu','lang'=>$id), array('class'=>'button navMenuListEntry noAjax' . $addClass, 'id'=>'lang_'.$id)) . '<br>';
								}
							?>
						</div>
					</div>
					<?php } ?>
					<div class="nav_button navMenu" id="JumpTo">
						<span><?php echo $this->trans->GENERAL_JUMPTO; ?></span>
					</div>
					<div id="JumpTo_List" class="navMenuList" style="display: none;">
						<?php
						$first = true;
						foreach($this->getJumpTos() as $title=>$link){
							if (count($link)>1){
								$class = $link[1];
							} else {
								$class = '';
							}
							$link = $link[0];
							if ($first){
								echo '<a class="button navMenuListEntry'.$class.' first" href="' . $link . '">' . $title . '</a><br>'."\n";
								$first = false;
							} else {
								echo '<a class="button navMenuListEntry'.$class.'" href="' . $link . '">' . $title . '</a><br>'."\n";
							}
						}
						?>
					</div>
					<a class="noAjax" href="<?php echo '/cms/' . strtolower(substr(Yii::app()->session['lang'],0,2)) . '/'; ?>">
						<div class="nav_button" id="about">
							<span><?php echo $this->trans->GENERAL_ABOUT; ?></span>
						</div>
					</a>
				</div>
			</div>
			<?php Functions::browserCheck(); ?>
			<?php 
			if (isset(Yii::app()->session['current_gps_time']) && Yii::app()->session['current_gps_time']<time()) {
				unset(Yii::app()->session['current_gps']);
				unset(Yii::app()->session['current_gps_time']);
			}
			?>
			<input type="hidden" id="current_gps_lat" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][0];} ?>" />
			<input type="hidden" id="current_gps_lng" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][1];} ?>" />
			<input type="hidden" id="current_gps_time" value="<?php if (isset(Yii::app()->session['current_gps_time'])) {echo Yii::app()->session['current_gps_time'];} ?>" />
			<input type="hidden" id="home_gps_lat" value="<?php if (isset(Yii::app()->user->home_gps)) {echo Yii::app()->user->home_gps[0];} ?>" />
			<input type="hidden" id="home_gps_lng" value="<?php if (isset(Yii::app()->user->home_gps)) {echo Yii::app()->user->home_gps[1];} ?>" />
			<input type="hidden" id="markCurrentGPS" value="<?php echo Yii::app()->createUrl('stores/currentGPSForStores'); ?>" />
			<input type="hidden" id="addressFormLink" value="<?php echo Yii::app()->createUrl('stores/addressInput'); ?>" />
			<div id="changable_content">
				<?php echo $content; ?>
			</div>
		</div>
		<div id="footer">
				Copyright &copy; <?php echo date('Y'); ?> by EveryCook. <a href="http://creativecommons.org/licenses/by-sa/3.0/"><img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/by-sa.png" width="57" height="20"></a> <?php echo Yii::powered(); ?>
		</div><!-- footer -->
		
		<script>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-32739550-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type =
			'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
			'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
		</script>
	</body>
</html>
