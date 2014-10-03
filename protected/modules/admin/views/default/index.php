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
	$this->module->id,
);
?>
<h1>Admin</h1>
<div class="adminOverview">
	<div class="buttons">
	<?php
	if (isset(Yii::app()->params['isDevice']) && Yii::app()->params['isDevice']){
		echo CHtml::link('manualmode', '/manualmode', array('class'=>'button', 'target'=>'_blank'));
		echo "<br /><br />";
	}
	?>
	<?php echo CHtml::link('ActionsGenerator', array('actionsGenerator/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions In', array('actionsIn/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions Out', array('actionsOut/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Action Types', array('actionTypes/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Tools', array('tools/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('StepTypes', array('stepTypes/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In', array('cookIn/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In Prep', array('cookInPrep/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Recipe Voting Reasons', array('recipeVotingReasons/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('cusine Types', array('cusineTypes/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('cusine Sub Types', array('cusineSubTypes/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('relation Typs', array('relationTyps/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('tags', array('tags/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('food Allergy', array('foodAllergy/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('diet', array('diet/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('cooking Stove', array('cookingStove/search'), array('class'=>'button')); ?><br />
	</div>
</div>