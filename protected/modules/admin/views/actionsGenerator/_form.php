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

$this->breadcrumbs=array(
	'Actions Generator',
);?>
<h1><?php echo $this->trans->ADMIN_ACTIONS_GENERATOR_TITLE; ?></h1>

<div class="hidden" id="stepConfig">
<?php
	echo CHtml::hiddenField('rowsJSON', $stepsJSON);
	echo CHtml::hiddenField('errorJSON', CJSON::encode($this->errorFields));
	
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
?>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actionsGenerator_form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl('/'.$this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
	'htmlOptions'=>array('class'=>''),
)); ?>
	
	<?php
		echo $form->errorSummary($model);
		if ($this->errorText != ''){
			if (strpos($this->errorText, '<li>')){
				echo '<div class="errorSummary"><p>'.$this->trans->ACTIONSGENERATOR_FIX_STEPS.'</p><ul>';
				echo $this->errorText;
				echo '</ul></div>';
			} else {
				echo '<div class="errorSummary">';
				echo $this->errorText;
				echo '</div>';
			}
		}
	?>
	
	<?php
	if ($view == 'change'){
		echo '<div>'. $actionsIn['AIN_DESC_'.Yii::app()->session['lang']] .'</div>';
		echo '<div>'. $cookIn['COI_DESC_'.Yii::app()->session['lang']] .'</div>';
		echo $form->hiddenField($ainToCoi, 'AIN_ID', null);
		echo $form->hiddenField($ainToCoi, 'COI_ID', null);
	} else if ($view == 'copy'){
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('actionsIn/create',array('newModel'=>time())), array('class'=>'button f-right'));
		echo Functions::createInput(null, $ainToCoi, 'AIN_ID', $actionsIns, Functions::DROP_DOWN_LIST, 'actionsIns', $htmlOptions_type0, $form);
		echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('cookIn/create',array('newModel'=>time())), array('class'=>'button f-right'));
		echo Functions::createInput(null, $ainToCoi, 'COI_ID', $cookIns, Functions::DROP_DOWN_LIST, 'cookIns', $htmlOptions_type0, $form);
	}
	?>
	<div id="actionsOutDetails" style="display:none;">
		<span></span>
		<?php foreach ($actionsOuts as $actionsOut){
//					$desc .= $toolDescPart . $this->trans->FIELD_AOU_DURATION.': ' . $actionsOut['AOU_DURATION'] . ', '.$this->trans->FIELD_AOU_DUR_PRO.': ' . $actionsOut['AOU_DUR_PRO'] . ', '.$this->trans->FIELD_AOU_PREP.': ' . $actionsOut['AOU_PREP'] . ', '.$this->trans->FIELD_ATA_COI_PREP.': ' . $actionsOut['ATA_COI_PREP'] . ', '.$this->trans->FIELD_AOU_CIS_CHANGE.': ' . $actionsOut['AOU_CIS_CHANGE'] . ')</span></span>'."\r\n";
			echo '<div>'.$this->trans->FIELD_STT_ID.': ' . ((isset($stepTypes[$actionsOut['STT_ID']]))?$stepTypes[$actionsOut['STT_ID']]:$actionsOut['STT_ID']) . ', '.$this->trans->FIELD_TOO_ID.': ' . ((isset($tools[$actionsOut['TOO_ID']] ))?$tools[$actionsOut['TOO_ID']]:$actionsOut['TOO_ID'])  . ', '.$this->trans->FIELD_AOU_PREP.': ' . $actionsOut['AOU_PREP'] . ', '.$this->trans->FIELD_AOU_DURATION.': ' . $actionsOut['AOU_DURATION'] . ', '.$this->trans->FIELD_AOU_DUR_PRO.': ' . $actionsOut['AOU_DUR_PRO'] . ', '.$this->trans->FIELD_AOU_CIS_CHANGE.': ' . $actionsOut['AOU_CIS_CHANGE']. ', '.$this->trans->FIELD_ATY_ID.': ' . ((isset($actionTypes[$actionsOut['ATY_ID']]))?$actionTypes[$actionsOut['ATY_ID']]:$actionsOut['ATY_ID']) . '</div>'."\r\n";
		}?>
	</div>
	<div class="actions">
	<?php
		$fieldOptions = array(
			array('AIN_ID', null, null, array('hidden'=>true)),
			array('COI_ID', null, null, array('hidden'=>true)),
			array('ATA_NO', null, null, array('hidden'=>true)),
			
			//array('COI_ID', $this->trans->ACTIONSGENERATOR_COOK_IN, $cookIns, null),
			array('AOU_ID', $this->trans->ACTIONSGENERATOR_ACTIONS_OUT, $actionsOutsList, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			array('ATA_COI_PREP', $this->trans->ACTIONSGENERATOR_COI_PREP, $cookInPreps, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('empty'=>$this->trans->GENERAL_CHOOSE)),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>'#'.$this->createUrlHash('ingredients/chooseIngredient',array()), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect'))),
			//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>array('ingredients/chooseIngredient'), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect buttonSmall'))),
			//array('STE_GRAMS', $this->trans->RECIPES_INGREDIENT_AMOUNT, null, array('type_weight'=>'g')),
		);
		$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'move up'=>'-up-', 'move down'=>'-down-', 'options'=>'Options');
		
		$newEmpty = new AinToAou;
		$newEmpty->ATA_COI_PREP = 0;
		$options = array('newNotClean'=>$newEmpty);
		//echo Functions::createInputTable($model->steps, $fieldOptions, $options, $form, $text);
		echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
	?>
	</div>
	
	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('index', 'ain_id'=>$model->AIN_ID), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->