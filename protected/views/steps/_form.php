<?php
$this->widget('application.extensions.fancybox.EFancyBox', array(
    'target'=>'a.fancyChoose',
    'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
    )
);
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'steps-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'REC_ID'); ?>
		<?php echo $form->hiddenField($model,'REC_ID'); ?>
		<?php //echo $form->error($model,'REC_ID'); ?>
	</div>
	
	<div class="row">
		<?php //echo $form->labelEx($model,'STE_STEP_NO'); ?>
		<?php echo $form->hiddenField($model,'STE_STEP_NO'); ?>
		<?php //echo $form->error($model,'STE_STEP_NO'); ?>
	</div>
	
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->RECIPES_SEARCH_CHOOSE);
	echo Functions::createInput($this->trans->RECIPES_STEP_TYPE, $model, 'STT_ID', $stepTypes, Functions::DROP_DOWN_LIST, 'stepTypes', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->RECIPES_ACTION, $model, 'ACT_ID', $actions, Functions::DROP_DOWN_LIST, 'actions', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->RECIPES_INGREDIENT, $model, 'ING_ID', $ingredients, Functions::DROP_DOWN_LIST, 'ingredients', $htmlOptions_type0, $form);
	
	/*
	if ($model->ingredient && $model->ingredient->ING_ID){
		$ingredientDescription = $model->ingredient->__get('ING_TITLE_' . Yii::app()->session['lang'])
	} else {
		$ingredientDescription = $this->trans->RECIPES_SEARCH_CHOOSE;
	}
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->labelEx($model,'ING_ID',array('label'=>$this->trans->RECIPES_INGREDIENT)); ?>
		<?php echo $form->hiddenField($model,'ING_ID', array('id'=>'ING_ID')); ?>
		<?php echo CHtml::link($ingredientDescription, array('ingredients/chooseIngredientData'), array('class'=>'fancyChoose IngredientDataSelect')) ?>
	</div>
	*/ ?>

	<div class="row">
		<?php echo $form->labelEx($model,,array('label'=>$this->trans->RECIPES_INGREDIENT_AMOUNT)); ?>
		<?php echo $form->textField($model,'STE_GRAMS'); ?>
		<?php echo $form->error($model,'STE_GRAMS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_CELSIUS'); ?>
		<?php echo $form->textField($model,'STE_CELSIUS'); ?>
		<?php echo $form->error($model,'STE_CELSIUS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_KPA'); ?>
		<?php echo $form->textField($model,'STE_KPA'); ?>
		<?php echo $form->error($model,'STE_KPA'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_RPM'); ?>
		<?php echo $form->textField($model,'STE_RPM'); ?>
		<?php echo $form->error($model,'STE_RPM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_CLOCKWISE'); ?>
		<?php echo $form->textField($model,'STE_CLOCKWISE'); ?>
		<?php echo $form->error($model,'STE_CLOCKWISE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STIR_RUN'); ?>
		<?php echo $form->textField($model,'STE_STIR_RUN'); ?>
		<?php echo $form->error($model,'STE_STIR_RUN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STIR_PAUSE'); ?>
		<?php echo $form->textField($model,'STE_STIR_PAUSE'); ?>
		<?php echo $form->error($model,'STE_STIR_PAUSE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STE_STEP_DURATION'); ?>
		<?php echo $form->textField($model,'STE_STEP_DURATION'); ?>
		<?php echo $form->error($model,'STE_STEP_DURATION'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->