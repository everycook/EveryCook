<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'STT_ID'); ?>
		<?php echo $form->textField($model,'STT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_DESC_EN'); ?>
		<?php echo $form->textArea($model,'STT_DESC_EN',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_DESC_DE'); ?>
		<?php echo $form->textArea($model,'STT_DESC_DE',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->