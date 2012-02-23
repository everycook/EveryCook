<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'STE_ID'); ?>
		<?php echo $form->textField($model,'STE_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_ID'); ?>
		<?php echo $form->textField($model,'REC_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ACT_ID'); ?>
		<?php echo $form->textField($model,'ACT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_ID'); ?>
		<?php echo $form->textField($model,'ING_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_STEP_NO'); ?>
		<?php echo $form->textField($model,'STE_STEP_NO'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_GRAMS'); ?>
		<?php echo $form->textField($model,'STE_GRAMS'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_T_BOTTOM'); ?>
		<?php echo $form->textField($model,'STE_T_BOTTOM'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_T_LID'); ?>
		<?php echo $form->textField($model,'STE_T_LID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_T_STEAM'); ?>
		<?php echo $form->textField($model,'STE_T_STEAM'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_BAR'); ?>
		<?php echo $form->textField($model,'STE_BAR'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_RPM'); ?>
		<?php echo $form->textField($model,'STE_RPM'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_CLOCKWISE'); ?>
		<?php echo $form->textField($model,'STE_CLOCKWISE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_STIR_RUN'); ?>
		<?php echo $form->textField($model,'STE_STIR_RUN'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_STIR_PAUSE'); ?>
		<?php echo $form->textField($model,'STE_STIR_PAUSE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STE_STEP_DURATION'); ?>
		<?php echo $form->textField($model,'STE_STEP_DURATION'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_ID'); ?>
		<?php echo $form->textField($model,'STT_ID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->