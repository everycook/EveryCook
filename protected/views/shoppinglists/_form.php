<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shoppinglists-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
		echo '<div class="errorSummary">';
		echo $this->errorText;
		echo '</div>';
	}
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_DATE'); ?>
		<?php echo $form->textField($model,'SHO_DATE'); ?>
		<?php echo $form->error($model,'SHO_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_INGREDIENTS'); ?>
		<?php echo $form->textArea($model,'SHO_INGREDIENTS',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'SHO_INGREDIENTS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SHO_WEIGHTS'); ?>
		<?php echo $form->textArea($model,'SHO_WEIGHTS',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'SHO_WEIGHTS'); ?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'CREATED_BY'); ?>
		<?php echo $form->textField($model,'CREATED_BY'); ?>
		<?php echo $form->error($model,'CREATED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CREATED_ON'); ?>
		<?php echo $form->textField($model,'CREATED_ON'); ?>
		<?php echo $form->error($model,'CREATED_ON'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CHANGED_BY'); ?>
		<?php echo $form->textField($model,'CHANGED_BY'); ?>
		<?php echo $form->error($model,'CHANGED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CHANGED_ON'); ?>
		<?php echo $form->textField($model,'CHANGED_ON'); ?>
		<?php echo $form->error($model,'CHANGED_ON'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->