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
		<?php echo $form->label($model,'PRF_GENDER'); ?>
		<?php echo $form->textField($model,'PRF_GENDER'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_BIRTHDAY'); ?>
		<?php echo $form->textField($model,'PRF_BIRTHDAY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_EMAIL'); ?>
		<?php echo $form->textField($model,'PRF_EMAIL',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LANG'); ?>
		<?php echo $form->textField($model,'PRF_LANG',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_IMG'); ?>
		<?php echo $form->textField($model,'PRF_IMG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LOC_GPS_LAT'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS_LAT'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LOC_GPS_LNG'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS_LNG'); ?>
	</div>
<!--
	<div class="row">
		<?php echo $form->label($model,'PRF_LOC_GPS_POINT'); ?>
		<?php echo $form->textField($model,'PRF_LOC_GPS_POINT'); ?>
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
		<?php echo $form->label($model,'PRF_LIKES_P'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_P',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_LIKES_S'); ?>
		<?php echo $form->textArea($model,'PRF_LIKES_S',array('rows'=>6, 'cols'=>50)); ?>
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
		<?php echo $form->label($model,'PRF_NOTLIKES_P'); ?>
		<?php echo $form->textArea($model,'PRF_NOTLIKES_P',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_SHOPLISTS'); ?>
		<?php echo $form->textArea($model,'PRF_SHOPLISTS',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_ACTIVE'); ?>
		<?php echo $form->textField($model,'PRF_ACTIVE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_RND'); ?>
		<?php echo $form->textField($model,'PRF_RND',array('size'=>60,'maxlength'=>100)); ?>
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
	-->
	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->