<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'step-types-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'STT_DESC_EN'); ?>
		<?php echo $form->textArea($model,'STT_DESC_EN',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'STT_DESC_EN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STT_DESC_DE'); ?>
		<?php echo $form->textArea($model,'STT_DESC_DE',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'STT_DESC_DE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->