<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_CREATED'); ?>
		<?php echo $form->textField($model,'REC_CREATED'); ?>
		<?php echo $form->error($model,'REC_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_CHANGED'); ?>
		<?php echo $form->textField($model,'REC_CHANGED'); ?>
		<?php echo $form->error($model,'REC_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_PICTURE'); ?>
		<?php echo $form->textField($model,'REC_PICTURE'); ?>
		<?php echo $form->error($model,'REC_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'REC_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'REC_PICTURE_AUTH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_TYPE'); ?>
		<?php echo $form->textField($model,'REC_TYPE'); ?>
		<?php echo $form->error($model,'REC_TYPE'); ?>
	</div>

	<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID)), '', array('class'=>'recipe')); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<?php echo $form->FileField($model,'filename'); ?>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_TITLE_EN'); ?>
		<?php echo $form->textField($model,'REC_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'REC_TITLE_EN'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'REC_TITLE_DE'); ?>
		<?php echo $form->textField($model,'REC_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'REC_TITLE_DE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->