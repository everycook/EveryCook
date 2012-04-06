<input type="hidden" id="SubGroupFormLink" value="<?php echo $this->createUrl('ingredients/getSubGroupForm'); ?>"/>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('ingredients/uploadImage',array('id'=>$model->ING_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'png')); ?>"/>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingredients-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); 

	$htmlOptions_type0 = array('empty'=>$this->trans->INGREDIENTS_SEARCH_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
?>
	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	?>

	
<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ING_CREATED'); ?>
		<?php echo $form->textField($model,'ING_CREATED'); ?>
		<?php echo $form->error($model,'ING_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_CHANGED'); ?>
		<?php echo $form->textField($model,'ING_CHANGED'); ?>
		<?php echo $form->error($model,'ING_CHANGED'); ?>
	</div>
	*/ ?>
	
	<?php foreach($this->allLanguages as $lang){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_NAME_'.$lang); ?>
		<?php echo $form->textField($model,'ING_NAME_'.$lang,array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_NAME_'.$lang); ?>
	</div>
	<?php } ?>
	
	<?php
	echo Functions::createInput($this->trans->INGREDIENTS_GROUP, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	if ($model->GRP_ID){
		echo Functions::createInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_type0, $form);
	} else {
		$htmlOptions_subGroup = array('empty'=>$this->trans->INGREDIENTS_CHOOSE_GROUP_FIRST);
		echo Functions::createInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', array(), Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_subGroup, $form);
	}
	echo Functions::createInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::DROP_DOWN_LIST, 'storability', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->INGREDIENTS_STATE, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0, $form);
	
	if ($model->nutrientData && $model->nutrientData->NUT_DESC){
		$NutrientDescription = $model->nutrientData->NUT_DESC;
	} else {
		$NutrientDescription = $this->trans->INGREDIENTS_SEARCH_CHOOSE;
	}
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->labelEx($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT)); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID')); ?>
		<?php echo CHtml::link($NutrientDescription, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect')) ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
		<?php echo $form->error($model,'ING_DENSITY'); ?>
	</div>
	
	<?php
		if (Yii::app()->session['Ingredient_Backup'] && Yii::app()->session['Ingredient_Backup']->ING_IMG_ETAG){
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'png')), '', array('class'=>'ingredient cropable', 'alt'=>$model->ING_IMG_AUTH, 'title'=>$model->ING_IMG_AUTH));
		} else if ($model->ING_ID) {
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ING_ID, 'ext'=>'png')), '', array('class'=>'ingredient cropable', 'alt'=>$model->ING_IMG_AUTH, 'title'=>$model->ING_IMG_AUTH));
		}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<?php echo $form->FileField($model,'filename'); ?>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'ING_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'ING_IMG_AUTH'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->