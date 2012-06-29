<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('recipes/uploadImage',array('id'=>$model->REC_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<div class="form">

<div class="hidden" id="stepConfig">
<?php
	echo CHtml::hiddenField('stepConfigValues', $stepTypeConfig);
	echo CHtml::hiddenField('rowsJSON', $stepsJSON);
	echo CHtml::hiddenField('ingredientsJSON', CJSON::encode($ingredients));
	echo CHtml::hiddenField('errorJSON', CJSON::encode($this->errorFields));	
?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php
		echo $form->errorSummary($model);
		if ($this->errorText != ''){
			echo '<div class="errorSummary"><p>Please fix the following input errors on Steps:</p><ul>';
			echo $this->errorText;
			echo '</ul></div>';
		}
	?>
	
	<?php foreach($this->allLanguages as $lang=>$name){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'REC_NAME_'.strtoupper($lang)); ?>
		<?php echo $form->textField($model,'REC_NAME_'.strtoupper($lang),array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'REC_NAME_'.strtoupper($lang)); ?>
	</div>
	<?php } ?>
	
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo Functions::createInput(null, $model, 'RET_ID', $recipeTypes, Functions::DROP_DOWN_LIST, 'recipeTypes', $htmlOptions_type0, $form);
	?>
	
	<?php
		if (isset(Yii::app()->session['Recipes_Backup']) && isset(Yii::app()->session['Recipes_Backup']->REC_IMG_ETAG)){
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')), '', array('class'=>'recipe' .(($model->imagechanged)?' cropable':''), 'alt'=>$model->REC_IMG_AUTH, 'title'=>$model->REC_IMG_AUTH));
		} else if ($model->REC_ID && isset($model->REC_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$model->REC_IMG_AUTH, 'title'=>$model->REC_IMG_AUTH));
		}
	?><br />
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<div class="imageTip">
		<?php
		echo $this->trans->TIP_OWN_IMAGE . '<br>';
		echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
		printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('REC_NAME_EN_GB')); //.Yii::app()->session['lang']
		echo '<br>';
		echo $form->FileField($model,'filename');
		?>
		</div>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'REC_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'REC_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'REC_IMG_AUTH'); ?>
	</div>
	
	<div class="steps">
	<?php
		$fieldOptions = array(
			array('REC_ID', null, null, array('hidden'=>true)),
			array('STE_STEP_NO', null, null, array('hidden'=>true)),
			array('STT_ID', $this->trans->RECIPES_STEP_TYPE, $stepTypes, null),
			array('ACT_ID', $this->trans->RECIPES_ACTION, $actions, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>'#'.$this->createUrlHash('ingredients/chooseIngredient',array()), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect'))),
			array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>array('ingredients/chooseIngredient'), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect'))),
			array('STE_GRAMS', $this->trans->RECIPES_INGREDIENT_AMOUNT, null, array('type_weight'=>'g')),
		);
		$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'move up'=>'-up-', 'move down'=>'-down-', 'options'=>'Options');
		
		$options = array('new'=>new Steps);
		//echo Functions::createInputTable($model->steps, $fieldOptions, $options, $form, $text);
		echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
	?>
	</div>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('class'=>'button')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
