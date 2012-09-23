<input type="hidden" id="SubGroupFormLink" value="<?php echo $this->createUrl('ingredients/getSubGroupForm'); ?>"/>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('ingredients/uploadImage',array('id'=>$model->ING_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingredients_form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); 

	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
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
	
	<?php foreach($this->allLanguages as $lang=>$name){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_NAME_'.$lang); ?>
		<?php echo $form->textField($model,'ING_NAME_'.$lang,array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_NAME_'.$lang); ?>
	</div>
	<?php } ?>
	
	<?php
	echo Functions::createInput(null, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	if ($model->GRP_ID){
		echo Functions::createInput(null, $model, 'SGR_ID', $subgroupNames, Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_type0, $form);
	} else {
		$htmlOptions_subGroup = array('empty'=>$this->trans->INGREDIENTS_CHOOSE_GROUP_FIRST);
		echo Functions::createInput(null, $model, 'SGR_ID', array(), Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_subGroup, $form);
	}
	echo Functions::createInput(null, $model, 'STB_ID', $storability, Functions::DROP_DOWN_LIST, 'storability', $htmlOptions_type0, $form);
	echo Functions::createInput(null, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0, $form);
	echo Functions::createInput(null, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0, $form);
	
	if ($model->nutrientData && $model->nutrientData->NUT_DESC){
		$NutrientDescription = $model->nutrientData->NUT_DESC;
	} else {
		$NutrientDescription = $this->trans->GENERAL_CHOOSE;
	}
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->labelEx($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT/*, 'style'=>'vertical-align: middle;'*/)); ?>
		<div class="imageTip">
		<?php
		echo $this->trans->TIP_NUT_ID . '<br>';
		echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue'));
		echo CHtml::link($NutrientDescription, array('nutrientData/chooseNutrientData', 'query'=>$model->ING_NAME_EN_GB), array('class'=>'fancyChoose NutrientDataSelect buttonSmall'));
		?>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
		<?php echo $form->error($model,'ING_DENSITY'); ?>
	</div>
	
	<?php
		if (isset(Yii::app()->session['Ingredients_Backup']) && isset(Yii::app()->session['Ingredients_Backup']->ING_IMG_ETAG)){
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())) , '', array('class'=>'ingredient' .(($model->imagechanged)?' cropable':''), 'alt'=>$model->__get('ING_NAME_' . Yii::app()->session['lang']) 'title'=>$model->__get('ING_NAME_' . Yii::app()->session['lang'])));
		} else if ($model->ING_ID && isset($model->ING_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ING_ID, 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$model->__get('ING_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('ING_NAME_' . Yii::app()->session['lang'])));
		}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<div class="imageTip">
		<?php
		echo $this->trans->TIP_OWN_IMAGE . '<br>';
		echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
		printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('ING_NAME_EN_GB')); //.Yii::app()->session['lang']
		echo '<br>';
		echo $form->FileField($model,'filename');
		?>
		</div>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'ING_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'ING_IMG_AUTH'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->