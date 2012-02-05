<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_FIRSTNAME'); ?>
		<?php echo $form->textField($model,'PRF_FIRSTNAME',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PRF_FIRSTNAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LASTNAME'); ?>
		<?php echo $form->textField($model,'PRF_LASTNAME',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PRF_LASTNAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_NICK'); ?>
		<?php echo $form->textField($model,'PRF_NICK',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PRF_NICK'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_EMAIL'); ?>
		<?php echo $form->textField($model,'PRF_EMAIL',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PRF_EMAIL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_PW'); ?>
		<?php echo $form->textField($model,'PRF_PW',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'PRF_PW'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LOC_GPS'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PRF_LOC_GPS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LIKES_I'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_I',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'PRF_LIKES_I'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_LIKES_R'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_R',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'PRF_LIKES_R'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_NOTLIKES_I'); ?>
		<?php echo $form->textArea($model,'PRF_NOTLIKES_I',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'PRF_NOTLIKES_I'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_NOTLIKES_R'); ?>
		<?php echo $form->textArea($model,'PRF_NOTLIKES_R',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'PRF_NOTLIKES_R'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_SHOPLISTS'); ?>
		<?php echo $form->textArea($model,'PRF_SHOPLISTS',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'PRF_SHOPLISTS'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->