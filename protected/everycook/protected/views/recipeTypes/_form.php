<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipe-types-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'RET_DESC_EN'); ?>
		<?php echo $form->textField($model,'RET_DESC_EN',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'RET_DESC_EN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'RET_DESC_DE'); ?>
		<?php echo $form->textField($model,'RET_DESC_DE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'RET_DESC_DE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->