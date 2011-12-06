<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'PRD_ID'); ?>
		<?php echo $form->textField($model,'PRD_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRD_NAME'); ?>
		<?php echo $form->textField($model,'PRD_NAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->