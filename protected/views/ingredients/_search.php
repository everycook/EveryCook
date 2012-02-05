<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ING_ID'); ?>
		<?php echo $form->textField($model,'ING_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_CREATED'); ?>
		<?php echo $form->textField($model,'ING_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_CHANGED'); ?>
		<?php echo $form->textField($model,'ING_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'NUT_ID'); ?>
		<?php echo $form->textField($model,'NUT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_GROUP'); ?>
		<?php echo $form->textField($model,'ING_GROUP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_SUBGROUP'); ?>
		<?php echo $form->textField($model,'ING_SUBGROUP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_STATE'); ?>
		<?php echo $form->textField($model,'ING_STATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_CONVENIENCE'); ?>
		<?php echo $form->textField($model,'ING_CONVENIENCE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_STORABILITY'); ?>
		<?php echo $form->textField($model,'ING_STORABILITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_PICTURE'); ?>
		<?php echo $form->textField($model,'ING_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'ING_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_TITLE_EN'); ?>
		<?php echo $form->textField($model,'ING_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_TITLE_DE'); ?>
		<?php echo $form->textField($model,'ING_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->