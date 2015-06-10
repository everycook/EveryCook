<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="<?php echo strtolower(substr(Yii::app()->session['lang'],0,2)) . substr(Yii::app()->session['lang'],2); ?>" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css"/>
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/designs/<?php echo Yii::app()->user->design; ?>.css" id="design"/>
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.Jcrop.css"/>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body class="backpic">

		<?php /*<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/bg.png" alt="Background" id="index_pic_bg">*/ ?>
		<div id="page">
			<div id="metaNav">
				<a href="<?php echo Yii::app()->createUrl('site/index',array()); ?>"><div id="logo" class="backpic" alt="EveryCook Logo"></div></a>
				<?php $form=$this->beginWidget('CActiveForm', array(
					'action'=>Yii::app()->createUrl('recipes/search', array('newSearch'=>time())),
					'id'=>'search_form',
					'method'=>'post',
					'htmlOptions'=>array('class'=>'submitToUrl', 'style'=>'display:none;'),
				)); ?>
				<div class="search">
					<?php
					//echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>$this->trans->RECIPES_TYPE_A_DISH));
					echo CHtml::hiddenField('query', '', array('id'=>'siteSearchRecipe', 'data-placeholder'=>$this->trans->RECIPES_TYPE_A_DISH));
					
					//TODO: fix align by selecting element with jQuery('.select2-choices').scrollLeft(5000)
					$this->widget('ext.select2.ESelect2', array(
						'target' => '#siteSearchRecipe',
						'config' => array (
							'width' => 'resolve',
							'multiple' => true,
							'minimumInputLength' => 1,
							'formatInputTooShort' => null,
							'openOnEnter' => false,
							'placeholder'=>$this->trans->RECIPES_TYPE_A_DISH,
							'ajax' => 'js:glob.select2.searchRecipeAjax',
							'formatResult' => 'js:glob.select2.searchRecipeFormatResult', // omitted for brevity, see the source of this page
							'formatSelection' => 'js:glob.select2.searchRecipeFormatSelection', // omitted for brevity, see the source of this page
							//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
							'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
							'escapeMarkup' => 'js:function (m) { return m; }', // we do not want to escape markup since we are displaying html in results
							'createSearchChoice' => 'js:glob.select2.createSearchChoice',
							'initSelection' => 'js:function(element, callback) {}',
						)
					));
					echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH));
					?>
				</div>
				<?php $this->endWidget(); ?>
				<div id="metaNavButtons">
					<?php
					$info = Functions::getFromCache('cookingInfo');
					if (isset($info) && $info != null && !$info->allFinished){
					?>
					<a class="nav_entry" id="cookassistant" href="<?php echo Yii::app()->createUrl('cookAssistant',array()); ?>">
						<div class="nav_button">
							<span><?php echo $this->trans->GENERAL_COOKASSISTANT; ?></span>
						</div>
					</a>
					<?php
					}
					if (Yii::app()->user->checkAccess('admin')){
					?>
					<a class="nav_entry" href="<?php echo Yii::app()->createUrl('admin/index',array()); ?>">
						<div class="nav_button" id="admin">
							<span><?php echo $this->trans->GENERAL_ADMIN; ?></span>
						</div>
					</a>
					<?php
					}
					if(Yii::app()->user->isGuest) {
						echo '<a class="noAjax nav_entry" href="'. Yii::app()->createUrl('site/login',array()) . '">';
					} else {
						echo '<a class="noAjax nav_entry" href="' . Yii::app()->createUrl('site/logout',array()) . '">';
					}
					?>
						<div class="nav_button">
							<span><?php if(Yii::app()->user->isGuest) {echo $this->trans->GENERAL_LOGIN;} else {printf($this->trans->GENERAL_LOGOUT, Yii::app()->user->nick);} ?></span>
						</div>
					</a>
					<?php
					if(!Yii::app()->user->isGuest) {
					?>
					<a href="#" class="nav_button nav_entry navMenu" id="settings">
						<span><?php echo $this->trans->GENERAL_SETTINGS; ?></span>
					</a>
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
					<a href="#" class="nav_button nav_entry navMenu" id="JumpTo">
						<span><?php echo $this->trans->GENERAL_JUMPTO; ?></span>
					</a>
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
						if(!Yii::app()->user->isGuest) {
							$profile = Profiles::model()->findByPk(Yii::app()->user->id);
							if (isset($profile) && $profile != null){
								if (isset($profile->PRF_EVERYCOOP_IP) && $profile->PRF_EVERYCOOP_IP != ''){
									if (!isset(Yii::app()->params['isDevice']) || !Yii::app()->params['isDevice']){
										echo '<a target="_blank" class="button navMenuListEntry noAjax" href="http://' . $profile->PRF_EVERYCOOP_IP . '/db/">' . $this->trans->GENERAL_MY_MACHINE . '</a><br>'."\n";
									}
								}
							}
						} 
						if (isset(Yii::app()->params['isDevice']) && Yii::app()->params['isDevice']){
							$additionalClass = '';
							if (isset(Yii::app()->session['syncDone'])){
								if (Yii::app()->session['syncDone']){
									$additionalClass = ' syncDone';
								} else {
									$additionalClass = ' syncRunning';
								}
							}
							$lastUpdateDate = 'unknown';
							try {
								$data = array();
								$returncode = -1;
								if (file_exists(Yii::app()->params['lastSyncDateFile'])){
									exec('cat '. Yii::app()->params['lastSyncDateFile'], $data, $returncode);
									if ($returncode == 0 && isset($data) && count($data)>0){
										$lastUpdateDate = $data[0];
									}
								}
							} catch(Exception $e){}
							echo CHtml::link(sprintf($this->trans->GENERAL_SYNC_FROM_PLATFORM, $lastUpdateDate), array('site/syncFromPlatform'), array('class'=>'button navMenuListEntry syncButton' . $additionalClass)) . '<br>'."\n";
						}
						?>
					</div>
					<?php /*<a class="nav_entry noAjax" href="<?php echo '/cms/' . strtolower(substr(Yii::app()->session['lang'],0,2)) . '/'; ?>"> */ ?>
					<a class="nav_entry noAjax" href="/">
						<div class="nav_button" id="about">
							<span><?php echo $this->trans->GENERAL_ABOUT; ?></span>
						</div>
					</a>
					<div class="clearfix"></div>
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
			<input type="hidden" id="home_gps_lat" value="<?php if (isset(Yii::app()->user->home_gps)) {echo Yii::app()->user->home_gps[0];} ?>" />
			<input type="hidden" id="home_gps_lng" value="<?php if (isset(Yii::app()->user->home_gps)) {echo Yii::app()->user->home_gps[1];} ?>" />
			<input type="hidden" id="markCurrentGPS" value="<?php echo Yii::app()->createUrl('stores/currentGPSForStores'); ?>" />
			<input type="hidden" id="addressFormLink" value="<?php echo Yii::app()->createUrl('stores/addressInput'); ?>" />
			<div id="changable_content">
				<?php echo $content; ?>
			</div>
		</div>
		<div id="footer">
				Copyright &copy; <?php echo date('Y'); ?> by EveryCook. <a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank"><img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/by-sa.png" width="57" height="20"></a> <?php echo Yii::powered(); ?>
		</div><!-- footer -->
		<!--
		<a href="https://github.com/everycook/EveryCook" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub"></a>
		-->
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
