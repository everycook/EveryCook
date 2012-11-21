<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actions-out-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>
	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	
	foreach($this->allLanguages as $lang=>$name){
	echo '<div class="row">'."\r\n";
		echo $form->labelEx($model,'AOU_DESC_'.$lang) ."\r\n";
		echo $form->textField($model,'AOU_DESC_'.$lang,array('size'=>60,'maxlength'=>100)) ."\r\n";
		echo $form->error($model,'AOU_DESC_'.$lang) ."\r\n";
	echo '</div>'."\r\n";
	}
	
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('stepTypes/create',array('newModel'=>time())), array('class'=>'button f-right'));
	echo Functions::createInput(null, $model, 'STT_ID', $stepTypes, Functions::DROP_DOWN_LIST, 'stepTypes', $htmlOptions_type0, $form);
	echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('tools/create',array('newModel'=>time())), array('class'=>'button f-right'));
	echo Functions::createInput(null, $model, 'TOO_ID', $tools, Functions::DROP_DOWN_LIST, 'tools', $htmlOptions_type0, $form);
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'AOU_PREP'); ?>
		<?php
			echo $this->trans->GENERAL_NO . ' ' . $form->radioButton($model,'AOU_PREP',array('uncheckValue'=>null,'value'=>'N'));
			echo $this->trans->GENERAL_YES . ' ' . $form->radioButton($model,'AOU_PREP',array('uncheckValue'=>null,'value'=>'Y')); ?>
		<?php echo $form->error($model,'AOU_PREP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'AOU_DURATION'); ?>
		<?php echo $form->textField($model,'AOU_DURATION'); ?>
		<?php echo $form->error($model,'AOU_DURATION'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'AOU_DUR_PRO'); ?>
		<?php echo $form->textField($model,'AOU_DUR_PRO'); ?>
		<?php echo $form->error($model,'AOU_DUR_PRO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'AOU_CIS_CHANGE'); ?>
		<?php echo $form->textField($model,'AOU_CIS_CHANGE'); ?>
		<?php echo $form->error($model,'AOU_CIS_CHANGE'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->