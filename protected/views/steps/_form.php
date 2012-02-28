<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'steps-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_ID'); ?>
		<?php echo $form->textField($model,'REC_ID'); ?>
		<?php echo $form->error($model,'REC_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ACT_ID'); ?>
		<?php echo $form->textField($model,'ACT_ID'); ?>
		<?php echo $form->error($model,'ACT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_ID'); ?>
		<?php echo $form->textField($model,'ING_ID'); ?>
		<?php echo $form->error($model,'ING_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STEP_NO'); ?>
		<?php echo $form->textField($model,'STE_STEP_NO'); ?>
		<?php echo $form->error($model,'STE_STEP_NO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_GRAMS'); ?>
		<?php echo $form->textField($model,'STE_GRAMS'); ?>
		<?php echo $form->error($model,'STE_GRAMS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_CELSIUS'); ?>
		<?php echo $form->textField($model,'STE_CELSIUS'); ?>
		<?php echo $form->error($model,'STE_CELSIUS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_KPA'); ?>
		<?php echo $form->textField($model,'STE_KPA'); ?>
		<?php echo $form->error($model,'STE_KPA'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_RPM'); ?>
		<?php echo $form->textField($model,'STE_RPM'); ?>
		<?php echo $form->error($model,'STE_RPM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_CLOCKWISE'); ?>
		<?php echo $form->textField($model,'STE_CLOCKWISE'); ?>
		<?php echo $form->error($model,'STE_CLOCKWISE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STIR_RUN'); ?>
		<?php echo $form->textField($model,'STE_STIR_RUN'); ?>
		<?php echo $form->error($model,'STE_STIR_RUN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STIR_PAUSE'); ?>
		<?php echo $form->textField($model,'STE_STIR_PAUSE'); ?>
		<?php echo $form->error($model,'STE_STIR_PAUSE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STEP_DURATION'); ?>
		<?php echo $form->textField($model,'STE_STEP_DURATION'); ?>
		<?php echo $form->error($model,'STE_STEP_DURATION'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STT_ID'); ?>
		<?php echo $form->textField($model,'STT_ID'); ?>
		<?php echo $form->error($model,'STT_ID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->