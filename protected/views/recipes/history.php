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
	$model->REC_ID=>array('view','id'=>$model->REC_ID),
	'History',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-history-form',
	'enableAjaxValidation'=>false,
	'method'=>'get',
	'action'=>Yii::app()->createUrl('recipes/historyCompare', array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array(/*'enctype' => 'multipart/form-data', */'class'=>'submitToUrl'),
)); ?>

<div class="historyOverview">
	<?php
		echo CHtml::link($this->trans->RECIPE_BACK_TO_RECIPE, array('view', 'id'=>$model->REC_ID), array('class'=>'button backbutton'));
	?>
	
	<div class="details">
		<h1 class="name">
			<?php echo CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])) . " - " . $this->trans->TITLE_RECIPES_HISTORY; ?>
		</h1>
		
		<?php
			$dateFormat = $this->trans->HISTORY_DATE_FORMAT;
			$last = count($history);
			for($i=0; $i<$last; $i++){
				$historyEntry = $history[$i];
				echo '<div class="historyEntry">';
				//compare history quick links
				echo '<div class="f-left">(';
					if ($i == 0){
						echo $this->trans->HISTORY_CURRENT;
					} else {
						echo CHtml::link($this->trans->HISTORY_CURRENT, array('historyCompare', 'id'=>$model->REC_ID, 'leftVersion'=>$model->CHANGED_ON, 'rightVersion'=>$historyEntry["CHANGED_ON"]), array('class'=>'actionlink'));
					}
					echo ' | ';
					if ($i >= $last-1){
						echo $this->trans->HISTORY_PREV;
					} else {
						echo CHtml::link($this->trans->HISTORY_PREV, array('historyCompare', 'id'=>$model->REC_ID, 'leftVersion'=>$historyEntry["CHANGED_ON"], 'rightVersion'=>$history[$i+1]["CHANGED_ON"]), array('class'=>'actionlink'));
					}
				echo ')</div>';
				//compare version selection
				echo '<div class="f-left">';
					if ($i == $last-1){
						echo '<input type="radio" name="leftVersion" value="' . $historyEntry["CHANGED_ON"] . '" style="visibility:hidden"> ';
					} else {
						echo '<input type="radio" name="leftVersion" value="' . $historyEntry["CHANGED_ON"] . '"' . (($i == 0)?' checked="checked"':'') . '> ';
					}
					if ($i == 0){
						echo '<input type="radio" name="rightVersion" value="' . $historyEntry["CHANGED_ON"] . '" style="visibility:hidden"> ';
					} else {
						echo '<input type="radio" name="rightVersion" value="' . $historyEntry["CHANGED_ON"] . '"' . (($i == 1)?' checked="checked"':'') . '> ';
					}
				echo '</div>';
				
				echo '<div class="f-left' . (($historyEntry["CHANGED_ON"] == $model->CHANGED_ON)?'current':'') . '">';
				//, array('class'=>'actionlink')
				echo CHtml::link('Change Date: ' . date($dateFormat, $historyEntry["CHANGED_ON"]), array('viewHistory', 'id'=>$historyEntry["REC_ID"], 'CHANGED_ON'=>$historyEntry["CHANGED_ON"]), array('class'=>'actionlink')) . ', ';
				echo CHtml::link('userId: ' . $historyEntry["CHANGED_BY"], array('profile/view', 'id'=>$historyEntry["CHANGED_BY"]), array('class'=>'actionlink')) . ', ';
				echo 'REC_SUMMARY: ' . $historyEntry["REC_SUMMARY"] . '</div> ';
				echo '<div class="clearfix"></div>';
				echo '</div>';
			}
		?>
	</div>
	<div class="clearfix"></div>

	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->GENERAL_COMPARE, array('class'=>'button')); ?>
		<?php //echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
</div>


<?php $this->endWidget(); ?>


<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'PRF_UID',
		'REC_IMG_FILENAME',
		'REC_IMG_AUTH',
		'REC_IMG_ETAG',
		'RET_ID',
		'REC_KCAL',
		'REC_HAS_ALLERGY_INFO',
		'REC_SUMMARY',
		'REC_APPROVED',
		'REC_SERVING_COUNT',
		'REC_WIKI_LINK',
		'REC_IS_PRIVATE',
		'REC_COMPLEXITY',
		'CUT_ID',
		'CST_ID',
		'REC_CUSINE_GPS_LAT',
		'REC_CUSINE_GPS_LNG',
		'REC_TOOLS',
		'REC_SYNONYM_EN_GB',
		'REC_SYNONYM_DE_CH',
		'REC_NAME_EN_GB',
		'REC_NAME_DE_CH',
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',
	),
)); */ ?>
