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
	'Recipes'=>array('index'),
	$model->REC_ID,
);

?>

<div class="historyCompareView">
	<?php
		echo CHtml::link($this->trans->RECIPE_BACK_TO_HISTORY, array('history', 'id'=>$model->REC_ID), array('class'=>'button backbutton'));
	?>
	<div class="details">
		<?php
			/*
			echo '<pre>';
			print_r($changes);
			echo '</pre>';
			*/
			$dateFormat = $this->trans->HISTORY_DATE_FORMAT;
			echo '<div class="header clearfix">';
			echo '<div class="col0">';
			echo '<br />';
			echo 'Date: <br />';
			echo 'userId: <br />';
			echo '</div>';
			echo '<div class="col1">';
			echo CHtml::link(CHtml::encode($leftModel->__get('REC_NAME_' . Yii::app()->session['lang'])), array('viewHistory', 'id'=>$leftModel->REC_ID, 'CHANGED_ON'=>$leftModel->CHANGED_ON), array('class'=>'actionlink')) . '<br />';
			echo date($dateFormat, $leftModel["CHANGED_ON"]) . '<br />';
			echo $leftModel["CHANGED_BY"] . '<br />';
			echo '</div>';
			echo '<div class="col2">';
			echo CHtml::link(CHtml::encode($rightModel->__get('REC_NAME_' . Yii::app()->session['lang'])), array('viewHistory', 'id'=>$rightModel->REC_ID, 'CHANGED_ON'=>$rightModel->CHANGED_ON), array('class'=>'actionlink')) . '<br />';
			echo date($dateFormat, $rightModel["CHANGED_ON"]) . '<br />';
			echo $rightModel["CHANGED_BY"] . '<br />';
			echo '</div>';
			echo '</div>';
			$i = 0;
			foreach($changes as $change){
				if ($i < $stepStartIndex){
					echo '<div class="recipeChange clearfix">';
					echo '<div class="col0">' . $change[1] . '&nbsp;</div>';
					echo '<div class="col1' . $change[0]. '">' . $change[2] . '&nbsp;</div>';
					echo '<div class="col2">' . $change[3] . '&nbsp;</div>';
					echo '</div>';
				} else {
					echo '<div class="stepChange clearfix">';
					echo '<div class="col0">' . $change[1] . '&nbsp;</div>';
					//complex logic
					//echo '<div class="col1' . $change[0] . '">' . $change[2] . $change[6] . '&nbsp;</div>';
					//echo '<div class="col2">' . $change[3] . '&nbsp;</div>';
					//simple logic
					echo '<div class="col1' . (($change[0]!=' change_remove')?$change[0]:'') . '">' . $change[2] . (($this->debug)?$change[6]:'') . '&nbsp;</div>';
					echo '<div class="col2' . (($change[0]==' change_remove')?$change[0]:'') . '">' . $change[3] . '&nbsp;</div>';
					echo '</div>';
				}
				$i++;
			}
		?>
	</div>
	<div class="clearfix"></div>
</div>