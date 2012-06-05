<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_FIRSTNAME'); ?>
		<?php echo $form->textField($model,'PRF_FIRSTNAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LASTNAME'); ?>
		<?php echo $form->textField($model,'PRF_LASTNAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_NICK'); ?>
		<?php echo $form->textField($model,'PRF_NICK',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_EMAIL'); ?>
		<?php echo $form->textField($model,'PRF_EMAIL',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_PW'); ?>
		<?php echo $form->textField($model,'PRF_PW',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LOC_GPS'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LIKES_I'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_I',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LIKES_R'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_R',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_NOTLIKES_I'); ?>
		<?php echo $form->textArea($model,'PRF_NOTLIKES_I',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_NOTLIKES_R'); ?>
		<?php echo $form->textArea($model,'PRF_NOTLIKES_R',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_SHOPLISTS'); ?>
		<?php echo $form->textArea($model,'PRF_SHOPLISTS',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->