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

if(Yii::app()->user->hasFlash('register')){ ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('register');?>
	<?php echo '<br />'.CHtml::link($this->trans->LOGIN,array('site/login'), array('class' => 'actionlink')); ?>
</div>

<?php } else { ?>
<input type="hidden" id="LanguageChangeLink" value="<?php echo CController::createUrl('Profiles/LanguageChanged', array('action'=>$this->route)); ?>"/>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('profiles/uploadImage',array('id'=>$model->PRF_UID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>

<div class="form">
<h1><?php echo $this->trans->TITLE_PROFILES_REGISTER; ?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-register-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'noAjax'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>

	<div class="mapDetails">
		<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LANG'); ?>
			<?php /*echo $form->textField($model,'PRF_LANG'); */?>
			<?php /*echo $form->dropDownList($model,'PRF_LANG', CHtml::listData(InterfaceMenu::model()->findAll(),'IME_LANG', 'IME_LANGNAME'), array('empty'=>'Choose...',*/
			
			 echo $form->dropDownList($model,'PRF_LANG', $this->allLanguages, array('empty'=>$this->trans->GENERAL_CHOOSE,
			//'submit'=>CController::createUrl('Profiles/LanguageChanged')
	//'ajax' => array(
	//'type'=>'POST', //request type
	//'url'=>CController::createUrl('Profiles/LanguageChanged', array('noajax'=>true)), //url to call.
	//Style: CController::createUrl('currentController/methodToCall')
	//'update'=>'#changable_content', //selector to update
	//'data'=>'js:javascript statement' 
	/*'success' => 'function (data){
		alert("test");
		jQuery("html").html(data);
	}',*/
	//leave out the data key to pass all form values through
	//)
	)); ?>

			<?php echo $form->error($model,'PRF_LANG'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_NICK'); ?>
			<?php echo $form->textField($model,'PRF_NICK',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_NICK'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_EMAIL'); ?>
			<?php echo Functions::activeSpecialField($model, 'PRF_EMAIL', 'email',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_EMAIL'); ?>
		</div>
		<?php /* ?>
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_GENDER'); ?>
			<?php echo $form->dropDownList($model,'PRF_GENDER', array('F'=>$this->trans->PROFILES_GENDER_F, 'M'=>$this->trans->PROFILES_GENDER_M), array('empty'=>$this->trans->GENERAL_CHOOSE,)); ?>
			<?php echo $form->error($model,'PRF_GENDER'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_FIRSTNAME'); ?>
			<?php echo $form->textField($model,'PRF_FIRSTNAME',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_FIRSTNAME'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LASTNAME'); ?>
			<?php echo $form->textField($model,'PRF_LASTNAME',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_LASTNAME'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_BIRTHDAY'); ?>
			<?php
				$years = array();
				for ($i=date('Y'); $i>=1900; $i--){
					$years[$i] = $i;
				}
				$months_temp = explode(',',$this->trans->GENERAL_MONTH_NAMES);
				$months = array();
				foreach($months_temp as $index=>$month){
					$months[substr('0'.($index+1),-2)] = $month;
				}
				$days = array();
				for ($i=1; $i<=31; $i++){
					$days[substr('0'.$i,-2)] = $i;
				}
				echo $form->dropDownList($model,'birthday_year', $years, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'year'));
				echo $form->dropDownList($model,'birthday_month', $months, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'month'));
				echo $form->dropDownList($model,'birthday_day', $days, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'day'));
			?>
			<?php echo $form->error($model,'PRF_BIRTHDAY'); ?>
		</div>
		
		<?php
			if (isset(Yii::app()->session['Profiles_Backup']) && isset(Yii::app()->session['Profiles_Backup']->PRF_IMG_ETAG)){
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), '', array('class'=>'profile cropable'));
			} else if ($model->PRF_UID) {
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$model->PRF_UID, 'ext'=>'.png')), '', array('class'=>'profile'));
			}
		?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'filename'); ?>
			<?php echo $form->FileField($model,'filename'); ?>
			<?php echo $form->error($model,'filename'); ?>
		</div>
		<?php */ ?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_PW'); ?>
			<?php echo $form->passwordField($model,'PRF_PW',array('size'=>20,'maxlength'=>256, 'autocomplete'=>'off')); ?>
			<?php echo $form->error($model,'PRF_PW'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'pw_repeat'); ?>
			<?php echo $form->passwordField($model,'pw_repeat',array('size'=>20,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'pw_repeat'); ?>
		</div>
		<?php /* ?>
		<div class="row">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/locate.png" width="24" height="24" id="setMarkerCurrentGPS"/>
			<span><?php echo $this->trans->PROFILES_SEARCH_CURRENT_POSITION; ?></span>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LAT'); ?>
			<?php echo $form->hiddenField($model,'PRF_LOC_GPS_LAT',array('pattern'=>'-?\d{1,3}\.\d+','class'=>'cord_lat')); ?>
			<?php echo '<span class="value">' . $model->PRF_LOC_GPS_LAT . '</span>'; ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LAT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LNG'); ?>
			<?php echo $form->hiddenField($model,'PRF_LOC_GPS_LNG',array('pattern'=>'-?\d{1,3}\.\d+','class'=>'cord_lng')); ?>
			<?php echo '<span class="value">' . $model->PRF_LOC_GPS_LNG . '</span>'; ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LNG'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_VIEW_DISTANCE'); ?>
			<?php echo Functions::activeSpecialField($model, 'PRF_VIEW_DISTANCE', 'number'); ?>
			<?php echo $form->error($model,'PRF_VIEW_DISTANCE'); ?>
		</div>
		<?php */ ?>
	<!--
		<div class="row">
			<?php echo var_dump(function_exists('openssl_random_pseudo_bytes')); ?>
		</div>
	-->

		<?php if(CCaptcha::checkRequirements()): ?>
		<div class="row">		
			<div>
		    <?php echo $form->labelEx($model,'verifyCaptcha'); ?>
			<?php echo $form->textField($model,'verifyCaptcha'); ?><br>
			<?php echo $form->error($model,'verifyCaptcha'); ?>
			<?php $this->widget('CCaptcha'); ?><br/>
			</div>
			<div class="hint"><?php echo $this->trans->PROFILES_CAPTCHA_HINT; ?></div>
		</div>
		<?php endif; ?>

	<!--
	<?php if(Yii::app()->session['lang'] === 'DE_CH'): ?>
	<div class="row">
	<div class="hint">German</div>
	</div>
	<?php endif; ?>
	<?php if(Yii::app()->session['lang'] === 'EN_GB'): ?>
	<div class="row">
	<div class="hint">English</div>
	</div>
	<?php endif; ?>
	<?php if(Yii::app()->session['lang'] === 'FR'): ?>
	<div class="row">
	<div class="hint">French</div>
	</div>
	<?php endif; ?>
	-->
		<div class="buttons">
			<?php echo CHtml::submitButton($this->trans->GENERAL_CREATE, array('name'=>'register')); ?>
		</div>
	</div>
	<?php /* ?>
	<strong><?php echo $this->trans->PROFILES_SELECT_HOME; ?></strong>
	<div id="map_canvas" style="height:300px; width:300px;"></div>
	<?php */ ?>
	<div class="clearfix"></div>
<?php $this->endWidget(); ?>
<?php /* ?>
<script type="text/javascript">
	loadScript(false, "CH", false, false, true, true);
</script>
<?php */ ?>
</div><!-- form -->

<?php } ?>
