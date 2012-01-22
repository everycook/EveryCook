<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingredients-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_CREATED'); ?>
		<?php echo $form->textField($model,'ING_CREATED'); ?>
		<?php echo $form->error($model,'ING_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_CHANGED'); ?>
		<?php echo $form->textField($model,'ING_CHANGED'); ?>
		<?php echo $form->error($model,'ING_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ID'); ?>
		<?php echo $form->textField($model,'NUT_ID'); ?>
		<?php echo $form->error($model,'NUT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_GROUP'); ?>
		<?php echo $form->textField($model,'ING_GROUP'); ?>
		<?php echo $form->error($model,'ING_GROUP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_SUBGROUP'); ?>
		<?php echo $form->textField($model,'ING_SUBGROUP'); ?>
		<?php echo $form->error($model,'ING_SUBGROUP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_STATE'); ?>
		<?php echo $form->textField($model,'ING_STATE'); ?>
		<?php echo $form->error($model,'ING_STATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_CONVENIENCE'); ?>
		<?php echo $form->textField($model,'ING_CONVENIENCE'); ?>
		<?php echo $form->error($model,'ING_CONVENIENCE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_STORABILITY'); ?>
		<?php echo $form->textField($model,'ING_STORABILITY'); ?>
		<?php echo $form->error($model,'ING_STORABILITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
		<?php echo $form->error($model,'ING_DENSITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_PICTURE'); ?>
		<?php echo $form->textField($model,'ING_PICTURE'); ?>
		<?php echo $form->error($model,'ING_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'ING_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'ING_PICTURE_AUTH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_TITLE_EN'); ?>
		<?php echo $form->textField($model,'ING_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_TITLE_EN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_TITLE_DE'); ?>
		<?php echo $form->textField($model,'ING_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_TITLE_DE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->