<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'meals-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
		echo '<div class="errorSummary">';
		echo $this->errorText;
		echo '</div>';
	}
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'MEA_DATE'); ?>
		<?php echo $form->textField($model,'MEA_DATE'); ?>
		<?php echo $form->error($model,'MEA_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MTY_ID'); ?>
		<?php echo $form->textField($model,'MTY_ID',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'MTY_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->