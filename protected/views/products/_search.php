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
		<?php echo $form->label($model,'ECO_ID'); ?>
		<?php echo $form->textField($model,'ECO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ETH_ID'); ?>
		<?php echo $form->textField($model,'ETH_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_IMG'); ?>
		<?php echo $form->textField($model,'PRO_IMG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRO_IMG_CR'); ?>
		<?php echo $form->textField($model,'PRO_IMG_CR',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->