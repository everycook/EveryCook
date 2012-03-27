<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'PRO_ID'); ?>
		<?php echo $form->textField($model,'PRO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_BARCODE'); ?>
		<?php echo $form->textField($model,'PRO_BARCODE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_PACKAGE_GRAMMS'); ?>
		<?php echo $form->textField($model,'PRO_PACKAGE_GRAMMS'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_ID'); ?>
		<?php echo $form->textField($model,'ING_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_ECO'); ?>
		<?php echo $form->textField($model,'PRO_ECO'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_ETHIC'); ?>
		<?php echo $form->textField($model,'PRO_ETHIC'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_PICTURE'); ?>
		<?php echo $form->textField($model,'PRO_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_PICTURE_COPYR'); ?>
		<?php echo $form->textField($model,'PRO_PICTURE_COPYR',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->