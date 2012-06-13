<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shoplists-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_DATE'); ?>
		<?php echo $form->textField($model,'SHO_DATE'); ?>
		<?php echo $form->error($model,'SHO_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_PRODUCTS'); ?>
		<?php echo $form->textArea($model,'SHO_PRODUCTS',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'SHO_PRODUCTS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_QUANTITIES'); ?>
		<?php echo $form->textArea($model,'SHO_QUANTITIES',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'SHO_QUANTITIES'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->