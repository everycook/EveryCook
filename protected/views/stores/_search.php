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
		<?php echo $form->label($model,'STO_NAME'); ?>
		<?php echo $form->textField($model,'STO_NAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_STREET'); ?>
		<?php echo $form->textField($model,'STO_STREET',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_HOUSE_NO'); ?>
		<?php echo $form->textField($model,'STO_HOUSE_NO',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_ZIP'); ?>
		<?php echo $form->textField($model,'STO_ZIP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_CITY'); ?>
		<?php echo $form->textField($model,'STO_CITY',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_COUNTRY'); ?>
		<?php echo $form->textField($model,'STO_COUNTRY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_STATE'); ?>
		<?php echo $form->textField($model,'STO_STATE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STY_ID'); ?>
		<?php echo $form->textField($model,'STY_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_GPS'); ?>
		<?php echo $form->textField($model,'STO_GPS',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_PHONE'); ?>
		<?php echo $form->textField($model,'STO_PHONE',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_IMG'); ?>
		<?php echo $form->textField($model,'STO_IMG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SUP_ID'); ?>
		<?php echo $form->textField($model,'SUP_ID'); ?>
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