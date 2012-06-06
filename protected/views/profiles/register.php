<?php if(Yii::app()->user->hasFlash('register')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('register');?>
<?php echo '<br />'.CHtml::link($this->trans->LOGIN,array('site/login'), array('class' => 'actionlink')); ?>
</div>

<?php else: ?>
<input type="hidden" id="LanguageChangeLink" value="<?php echo CController::createUrl('Profiles/LanguageChanged'); ?>"/>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-register-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'noAjax'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="mapDetails">
		<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LANG'); ?>
			<?php /*echo $form->textField($model,'PRF_LANG'); */?>
			<?php /*echo $form->dropDownList($model,'PRF_LANG', CHtml::listData(InterfaceMenu::model()->findAll(),'IME_LANG', 'IME_LANGNAME'), array('empty'=>'Choose...',*/
			
			 echo $form->dropDownList($model,'PRF_LANG', array('EN_GB'=>'English','DE_CH'=>'Deutsch','FR_FR'=>'Francais'), array('empty'=>$this->trans->GENERAL_CHOOSE,
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
			<?php echo $form->textField($model,'PRF_EMAIL',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_EMAIL'); ?>
		</div>
	<!--
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_GENDER'); ?>
			<?php echo $form->textField($model,'PRF_GENDER'); ?>
			<?php echo $form->error($model,'PRF_GENDER'); ?>
		</div>
	-->
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
	<!--
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_BIRTHDAY'); ?>
			<?php echo $form->textField($model,'PRF_BIRTHDAY'); ?>
			<?php echo $form->error($model,'PRF_BIRTHDAY'); ?>
		</div>
	-->
		<?php
			if (isset(Yii::app()->session['Profiles_Backup']) && isset(Yii::app()->session['Profiles_Backup']->PRF_IMG_ETAG)){
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'png')), '', array('class'=>'profiles cropable'));
			} else if ($model->PRF_UID) {
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$model->PRF_UID, 'ext'=>'png')), '', array('class'=>'profiles cropable'));
			}
		?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'filename'); ?>
			<?php echo $form->FileField($model,'filename'); ?>
			<?php echo $form->error($model,'filename'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_PW'); ?>
			<?php echo $form->passwordField($model,'PRF_PW',array('size'=>20,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'PRF_PW'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'pw_repeat'); ?>
			<?php echo $form->passwordField($model,'pw_repeat',array('size'=>20,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'pw_repeat'); ?>
		</div>


		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LAT'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LAT', array('class'=>'cord_lat')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LAT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LNG'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LNG', array('class'=>'cord_lng')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LNG'); ?>
		</div>
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
			<?php $this->widget('CCaptcha'); ?><br/>
			</div>
			<div class="hint">Please write the result from the given calulation in the corresponding field.</div>
			<?php echo $form->error($model,'verifyCaptcha'); ?>
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
			<?php echo CHtml::submitButton($this->trans->GENERAL_CREATE); ?>
		</div>
	</div>
	<strong>Select your home Place:</strong>
	<div id="map_canvas" style="height:300px; width:300px;"></div>
	<div class="clearfix"></div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	loadScript(false, "CH", false, false, true);
</script>

</div><!-- form -->

<?php endif; ?>
