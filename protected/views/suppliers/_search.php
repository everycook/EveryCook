<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'SUP_ID'); ?>
		<?php echo $form->textField($model,'SUP_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SUP_NAME'); ?>
		<?php echo $form->textField($model,'SUP_NAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->