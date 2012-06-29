<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'SHO_ID'); ?>
		<?php echo $form->textField($model,'SHO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SHO_DATE'); ?>
		<?php echo $form->textField($model,'SHO_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SHO_INGREDIENTS'); ?>
		<?php echo $form->textArea($model,'SHO_INGREDIENTS',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SHO_WEIGHTS'); ?>
		<?php echo $form->textArea($model,'SHO_WEIGHTS',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SHO_PRODUCTS'); ?>
		<?php echo $form->textArea($model,'SHO_PRODUCTS',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SHO_QUANTITIES'); ?>
		<?php echo $form->textArea($model,'SHO_QUANTITIES',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CREATED_BY'); ?>
		<?php echo $form->textField($model,'CREATED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CREATED_ON'); ?>
		<?php echo $form->textField($model,'CREATED_ON'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CHANGED_BY'); ?>
		<?php echo $form->textField($model,'CHANGED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CHANGED_ON'); ?>
		<?php echo $form->textField($model,'CHANGED_ON'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->