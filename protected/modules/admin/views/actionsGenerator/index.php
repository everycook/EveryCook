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


	
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actionsList_form',
	'enableAjaxValidation'=>false,
	'action'=>$this->createUrl('index', array('ajaxform'=>true)),
	'htmlOptions'=>array('class'=>''),
)); ?>

<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('actionsIn/create',array('newModel'=>time())), array('class'=>'button f-right'));
	echo Functions::createInput(null, $model, 'AIN_ID', $actionsIns, Functions::DROP_DOWN_LIST, 'actionsIns', $htmlOptions_type0, null);
	echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('cookIn/create',array('newModel'=>time())), array('class'=>'button f-right'));
	echo Functions::createInput(null, $model, 'COI_ID', $cookIns, Functions::DROP_DOWN_LIST, 'cookIns', $htmlOptions_type0, null);
?>

<?php $this->endWidget(); ?>

<?php
	$coi_id=-1;
	foreach($ainToAous as $ainToAou){
		if ($coi_id != $ainToAou['COI_ID']){
			if ($coi_id != -1){
				echo CHtml::link($this->trans->ACTIONSGENERATOR_ACTIONS_CHANGE, array('change', 'ain_id'=>$model->AIN_ID, 'coi_id'=>$coi_id), array('class'=>'button'));
				echo CHtml::link($this->trans->ACTIONSGENERATOR_ACTIONS_COPY, array('copy', 'ain_id'=>$model->AIN_ID, 'coi_id'=>$coi_id), array('class'=>'button'));
				echo '</div>'."\r\n";
			}
			echo '<div class="actionOutOverview">'."\r\n";
			echo $this->trans->ACTIONSGENERATOR_ACTIONS_COOK_WITH . "cook with:\r\n";
			echo '<span class="title">' . $cookIns[$ainToAou['COI_ID']] . '</span><br />'."\r\n";
			$coi_id = $ainToAou['COI_ID'];
		}
		echo '<span>' . $ainToAou['ATA_NO'] . '</span>'."\r\n";
		echo '<span>' . $actionsOuts[$ainToAou['AOU_ID']] . '</span>'."\r\n";
		if ($ainToAou['ATA_COI_PREP'] != 0){
			echo ' <span>(' . $cookInPreps[$ainToAou['ATA_COI_PREP']] . ')</span><br />'."\r\n";
		} else {
			echo '<br />'."\r\n";
		}
	}
	if ($coi_id != -1){
		echo CHtml::link($this->trans->ACTIONSGENERATOR_ACTIONS_CHANGE, array('change', 'ain_id'=>$model->AIN_ID, 'coi_id'=>$coi_id), array('class'=>'button'));
		echo CHtml::link($this->trans->ACTIONSGENERATOR_ACTIONS_COPY, array('copy', 'ain_id'=>$model->AIN_ID, 'coi_id'=>$coi_id), array('class'=>'button'));
		echo '</div>'."\r\n";
	} else if (isset($model->AIN_ID) && isset($model->COI_ID) && $model->COI_ID != null){
		echo '<div class="actionOutOverview">'."\r\n";
		echo CHtml::link($this->trans->ACTIONSGENERATOR_ACTIONS_CREATE, array('change', 'ain_id'=>$model->AIN_ID, 'coi_id'=>$model->COI_ID), array('class'=>'button'/*, 'id'=>'CreatNewAinToAou'*/));
		echo '</div>'."\r\n";
	}
?>

</div><!-- form -->