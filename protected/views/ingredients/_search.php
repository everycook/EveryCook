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
		<?php echo $form->label($model,'GRP_ID'); ?>
		<?php echo $form->textField($model,'GRP_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SGR_ID'); ?>
		<?php echo $form->textField($model,'SGR_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'IST_ID'); ?>
		<?php echo $form->textField($model,'IST_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ICO_ID'); ?>
		<?php echo $form->textField($model,'ICO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STB_ID'); ?>
		<?php echo $form->textField($model,'STB_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_IMG'); ?>
		<?php echo $form->textField($model,'ING_IMG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'ING_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_NAME_EN'); ?>
		<?php echo $form->textField($model,'ING_NAME_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_NAME_DE'); ?>
		<?php echo $form->textField($model,'ING_NAME_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->