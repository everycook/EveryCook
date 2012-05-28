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
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LANG'); ?>
		<?php /*echo $form->textField($model,'PRF_LANG'); */?>
		<?php /*echo $form->dropDownList($model,'PRF_LANG', CHtml::listData(InterfaceMenu::model()->findAll(),'IME_LANG', 'IME_LANGNAME'), array('empty'=>'Choose...',*/
		
		 echo $form->dropDownList($model,'PRF_LANG', array('EN_GB'=>'English','DE_CH'=>'Deutsch','FR_FR'=>'Francais'), array('empty'=>'Choose...',
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
		<?php echo $form->textField($model,'PRF_NICK'); ?>
		<?php echo $form->error($model,'PRF_NICK'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_EMAIL'); ?>
		<?php echo $form->textField($model,'PRF_EMAIL'); ?>
		<?php echo $form->error($model,'PRF_EMAIL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_FIRSTNAME'); ?>
		<?php echo $form->textField($model,'PRF_FIRSTNAME'); ?>
		<?php echo $form->error($model,'PRF_FIRSTNAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LASTNAME'); ?>
		<?php echo $form->textField($model,'PRF_LASTNAME'); ?>
		<?php echo $form->error($model,'PRF_LASTNAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_PW'); ?>
		<?php echo $form->passwordField($model,'PRF_PW',array('size'=>20,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'PRF_PW'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pw_repeat'); ?>
		<?php echo $form->passwordField($model,'pw_repeat',array('size'=>20,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'pw_repeat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LOC_GPS'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS'); ?>
		<?php echo $form->error($model,'PRF_LOC_GPS'); ?>
	</div>
<!--
	<div class="row">
		<?php echo var_dump(function_exists('openssl_random_pseudo_bytes')); ?>
	</div>
-->
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'PRF_IMG'); ?>
		<?php echo $form->textField($model,'PRF_IMG'); ?>
		<?php echo $form->error($model,'PRF_IMG'); ?>
	</div>
-->

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">		
		<div>
      <?php echo $form->labelEx($model,'verifyCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCaptcha'); ?>
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
	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
