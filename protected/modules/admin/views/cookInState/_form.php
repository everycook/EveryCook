<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cook-in-state_form',
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
	
	/*
	//Example for select / checkboxlist
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::createInput(null, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	*/
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'COI_ID'); ?>
		<?php echo $form->textField($model,'COI_ID'); ?>
		<?php echo $form->error($model,'COI_ID'); ?>
	</div>
	<?php echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('cookIn/create',array('newModel'=>time())), array('class'=>'button')); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'CIS_ID'); ?>
		<?php echo $form->textField($model,'CIS_ID'); ?>
		<?php echo $form->error($model,'CIS_ID'); ?>
	</div>
	
	<?php
	echo '<div class="row">'."\r\n";
		echo $form->labelEx($model,'CIS_DESC') ."\r\n";
		echo $form->textField($model,'CIS_DESC',array('size'=>60,'maxlength'=>100)) ."\r\n";
		echo $form->error($model,'CIS_DESC') ."\r\n";
	echo '</div>'."\r\n";
	?>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->