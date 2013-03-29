<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/
?>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('recipes/uploadImage',array('id'=>$model->REC_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<input type="hidden" id="stepDetailsLink" value="<?php echo $this->createUrl('recipes/getRecipeInfos'); ?>"/>
<input type="hidden" id="updateSessionValuesLink" value="<?php echo $this->createUrl('recipes/updateSessionValues'); ?>"/>
<input type="hidden" id="updateSessionValueLink" value="<?php echo $this->createUrl('recipes/updateSessionValue'); ?>"/>
<input type="hidden" id="ingredientsChooseLink" value="<?php echo $this->createUrl('ingredients/chooseIngredient'); ?>"/>
<input type="hidden" id="preparedIngredientsChooseLink" value="<?php echo $this->createUrl('ingredients/chooseIngredientInRecipe'); ?>"/>
<input type="hidden" id="preparedAIN_ID" value="<?php echo Yii::app()->params['PrepareActionId']; ?>"/>
<div class="form">

<div class="hidden" id="stepConfig">
<?php
	echo CHtml::hiddenField('rowsJSON', $stepsJSON);
	echo CHtml::hiddenField('ingredientsJSON', CJSON::encode($ingredients));
	echo CHtml::hiddenField('errorJSON', CJSON::encode($this->errorFields));	
?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array(/*'enctype' => 'multipart/form-data', */'class'=>'ajaxupload'),
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php
		echo $form->errorSummary($model);
		if ($this->errorText != ''){
			if (strpos($this->errorText, '<li>')){
				echo '<div class="errorSummary"><p>'.$this->trans->RECIPES_FIX_STEPS.'</p><ul>';
				echo $this->errorText;
				echo '</ul></div>';
			} else {
				echo '<div class="errorSummary">';
				echo $this->errorText;
				echo '</div>';
			}
		}
	?>
	
	<div class="row">
		<label for="Recipes_Template"><?php echo $this->trans->RECIPE_SELECT_TEMPLATE; ?></label>
		<?php echo CHtml::link($this->trans->GENERAL_CHOOSE, array('recipes/chooseTemplateRecipe'), array('class'=>'fancyChoose RecipeTemplateSelect buttonSmall', 'id'=>'Recipes_Template')) ?>
	</div>
	
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
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), '', array('class'=>'recipe' .(($model->imagechanged)?' cropable':''), 'alt'=>$model->__get('REC_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang'])));
		} else if ($model->REC_ID && isset($model->REC_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$model->__get('REC_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang'])));
		}
	?>
	
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
	
	<div class="row" id="cookIns">
		<?php
		$coi_ids = array();
		foreach ($model->recToCois as $recToCoi){
			if (isset($recToCoi->COI_ID) && $recToCoi->COI_ID > 0){
				$coi_ids[] = $recToCoi->COI_ID;
			}
		}
		$htmlOptions_type2 = array('empty'=>$this->trans->GENERAL_CHOOSE, 'size'=>8, 'multiple'=>true);
		//echo Functions::createInput(null, $model->recToCois, 'COI_ID', $cookIns, Functions::MULTI_LIST, 'cookIns', $htmlOptions_type2, $form);
		
		echo CHtml::label($this->trans->RECIPE_COOKINS,'COI_ID') . "\r\n";
		//echo CHtml::listBox('COI_ID', $coi_ids, $cookIns, $htmlOptions_type2) . "\r\n";
		
		echo '<ul class="options_choose">';
		echo CHtml::checkBoxList('COI_ID', $coi_ids, $cookIns, $htmlOptions_type2); 
		echo '</ul>';
		echo '<div class="clearfix"></div>';
		//echo $form->error($model,'REC_IMG_AUTH') . "\r\n";
		?>
	</div>
	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->RECIPES_UPDATE, array('class'=>'button', 'name'=>'updateCookIn')); ?>
	</div>
	
	<?php
	if (count($coi_ids)>0){
		echo '<div id="actionsInDetails" style="display:none;">';
		foreach ($actionsInDetails as $details){
			echo $details['desc'];
		}
		echo '</div>';
	?>
	<div class="row">
	<?php
		echo CHtml::label($this->trans->RECIPE_COOKIN_DISPLAY,'COI_ID') . "\r\n";
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		$first = 0;
		foreach ($cookInsSelected as $key=>$val){
			if ($first == 0){
				$first = $key;
				break;
			}
		}
		
		echo CHtml::dropDownList('cookInDisplay', $first, $cookInsSelected, $htmlOptions_type0) . "\r\n";
	?>
	</div>
	<div class="steps updateBackend">
	<?php
		$fieldOptions = array(
			array('REC_ID', null, null, array('hidden'=>true)),
			array('STE_STEP_NO', null, null, array('hidden'=>true)),
			array('AIN_ID', $this->trans->RECIPES_ACTION, $actionsIn, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			array('TOO_ID', $this->trans->RECIPES_TOOL, $tools, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>'#'.$this->createUrlHash('ingredients/chooseIngredient',array()), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect'))),
			array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>array('ingredients/chooseIngredient'), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect buttonSmall'))),
			array('STE_GRAMS', $this->trans->RECIPES_INGREDIENT_AMOUNT, null, array('type_weight'=>'g')),
		);
		$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'move up'=>'-up-', 'move down'=>'-down-', 'add2'=>$this->trans->GENERAL_ADD, 'options'=>'Options');
		
		$options = array('new'=>new Steps);
		//echo Functions::createInputTable($model->steps, $fieldOptions, $options, $form, $text);
		echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
	?>
	</div>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('class'=>'button')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	<?php } ?>

<?php $this->endWidget(); ?>

</div><!-- form -->
