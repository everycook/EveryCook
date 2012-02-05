<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'STO_ID'); ?>
		<?php echo $form->textField($model,'STO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_LOC_GPS'); ?>
		<?php echo $form->textField($model,'STO_LOC_GPS',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_LOC_ADDR'); ?>
		<?php echo $form->textField($model,'STO_LOC_ADDR',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SUP_ID'); ?>
		<?php echo $form->textField($model,'SUP_ID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->