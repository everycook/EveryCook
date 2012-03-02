<div class="form">

<div class="hidden" id="stepConfig">
<?php
	foreach ($stepTypeConfig as $config){
		echo CHtml::hiddenField('stepConfig['.$config['STT_ID'].'][STT_DEFAULT]', $config['STT_DEFAULT']);
		echo CHtml::hiddenField('stepConfig['.$config['STT_ID'].'][STT_REQUIRED]', $config['STT_REQUIRED']);
	}
?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_TITLE_EN'); ?>
		<?php echo $form->textField($model,'REC_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'REC_TITLE_EN'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'REC_TITLE_DE'); ?>
		<?php echo $form->textField($model,'REC_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'REC_TITLE_DE'); ?>
	</div>
	
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->RECIPES_SEARCH_CHOOSE);
	echo Functions::createInput($this->trans->RECIPES_TYPE, $model, 'REC_TYPE', $recipeTypes, Functions::DROP_DOWN_LIST, 'recipeTypes', $htmlOptions_type0, $form);
	?>
	
	<?php if ($model->REC_ID) {echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'png')), '', array('class'=>'recipe', 'alt'=>$model->REC_PICTURE_AUTH, 'title'=>$model->REC_PICTURE_AUTH));} ?><br />
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<?php echo $form->FileField($model,'filename'); ?>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'REC_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'REC_PICTURE_AUTH'); ?>
	</div>
	
	<div class="steps">
	<?php
		$fieldOptions = array(
			array('REC_ID', null, null, null),
			array('STE_STEP_NO', null, null, null),
			array('STT_ID', $this->trans->RECIPES_STEP_TYPE, $stepTypes, null),
			array('ACT_ID', $this->trans->RECIPES_ACTION, $actions, array('empty'=>$this->trans->RECIPES_SEARCH_CHOOSE)),
			array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('empty'=>$this->trans->RECIPES_SEARCH_CHOOSE)),
			array('STE_GRAMS', $this->trans->RECIPES_INGREDIENT_AMOUNT, null, null),
		);
		$text = array('add'=>$this->trans->RECIPES_ADD_STEP, 'remove'=>$this->trans->RECIPES_REMOVE_STEP, 'move up'=>'-up-', 'move down'=>'-down-', 'options'=>'Options');
		
		$options = array('new'=>new Steps);
		echo Functions::createInputTable($model->steps, $fieldOptions, $options, $form, $text);
		$newStep = new Steps;
	?>
	
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->