<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'REC_ID'); ?>
		<?php echo $form->textField($model,'REC_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CREATED'); ?>
		<?php echo $form->textField($model,'REC_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CHANGED'); ?>
		<?php echo $form->textField($model,'REC_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_PICTURE'); ?>
		<?php echo $form->textField($model,'REC_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'REC_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TYPE'); ?>
		<?php echo $form->textField($model,'REC_TYPE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TITLE_EN'); ?>
		<?php echo $form->textField($model,'REC_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TITLE_DE'); ?>
		<?php echo $form->textField($model,'REC_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->