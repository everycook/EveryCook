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

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'Update Recipes', 'url'=>array('update', 'id'=>$model->REC_ID)),
	array('label'=>'Delete Recipes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->REC_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('recipes/update',$this->getActionParams())),
);
?>

<div class="detailView">
	<?php
	if (isset(Yii::app()->session['Recipes'])){
		if (isset(Yii::app()->session['Recipes']['model'])){
			echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/advancesearch'), array('class'=>'button backbutton'));
		} else {
			echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/search'), array('class'=>'button backbutton'));
		}
	}
	?>
	
	<div class="options">
		<?php
		echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model->REC_ID), array('class'=>'delicious_big noAjax backpic f-left', 'title'=>$this->trans->GENERAL_DELICIOUS));
		//echo CHtml::link('+', array('user/addrecipes', 'id'=>$model->REC_ID), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD));
		echo CHtml::link('&nbsp;', array('meals/mealPlanner', 'rec_id'=>$model->REC_ID), array('class'=>'cookwith_big backpic f-right', 'title'=>$this->trans->RECIPES_MEALPLANNER));
		echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model->REC_ID), array('class'=>'disgusting_big noAjax backpic f-center','title'=>$this->trans->GENERAL_DISGUSTING));
		?>
		<div class="otherItems ingredients">
			<?php echo CHtml::encode($this->trans->RECIPES_INGREDIENTS_NEEDED); ?>
			<ul>
			<?php
			$ingredient_printed = array();
			foreach($model->steps as $step){
				if ($step->ingredient != null){
					if (!isset($ingredient_printed[$step->ingredient->ING_ID])){
						echo '<li>';
							echo CHtml::link($step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']), array('ingredients/view', 'id'=>$step->ingredient->ING_ID), array('title'=>$this->trans->RECIPES_TOOLTIP_OPEN_INGREDIENT));
							echo '<div class="small_img">';
								echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$step->ingredient['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$step->ingredient['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$step->ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$step->ingredient['ING_ID']));
								echo '<div class="img_auth">';
								if ($step->ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $step->ingredient['ING_IMG_AUTH']; }
								echo '</div>';
							echo '</div>';
						echo '</li>';
						$ingredient_printed[$step->ingredient->ING_ID] = true;
					}
				}
			}
			?>
			</ul>
		</div>
	</div>
	<div class="details">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])), array('view', 'id'=>$model->REC_ID)); ?>
		</div>
		<div class="detail_img">
			<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$model->__get('REC_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang']))); ?>
			<div class="img_auth"><?php if ($model->REC_IMG_ETAG == '') { echo '&nbsp;'; } else {echo '© by ' . $model->REC_IMG_AUTH; } ?></div>
		</div>
		
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($model->recipeTypes->__get('RET_DESC_' . Yii::app()->session['lang'])); ?>
		<?php 
		
			$i = 1;
			foreach($model->steps as $step){
				echo '<div class="step">';
				echo '<span class="stepNo">' . $i . '.</span> ';
				if (isset($step->actionIn) && $step->actionIn != null){
					$text = $step->actionIn->__get('AIN_DESC_' . Yii::app()->session['lang']);
					if (isset($step->ingredient) && $step->ingredient != null){
						$replText = '<span class="ingredient">' . $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']) . '</span> ';
						$text = str_replace('#ingredient', $replText, $text);
					}
					if ($step->STE_GRAMS){
						$replText = '<span class="weight">' . $step->STE_GRAMS . 'g</span> ';
						$text = str_replace('#weight', $replText, $text);
					}
					
					if (isset($step->tool) && $step->tool != null){
						$replText = '<span class="tool">' . $step->tool->__get('TOO_DESC_' . Yii::app()->session['lang']) . '</span> ';
						$text = str_replace('#tool', $replText, $text);
					}
					if ($step->STE_STEP_DURATION){
						$time = date('H:i:s', $step['STE_STEP_DURATION']-3600);
						$replText = '<span class="time">' . $time . 'h</span> ';
						$text = str_replace('#time', $replText, $text);
					}
					if ($step->STE_CELSIUS){
						$replText = '<span class="temp">' . $step->STE_CELSIUS . '°C</span> ';
						$text = str_replace('#temp', $replText, $text);
					}
					if ($step->STE_KPA){
						$replText = '<span class="pressure">' . $step->STE_KPA . 'kpa</span> ';
						$text = str_replace('#pressure', $replText, $text);
					}
					echo '<span class="action">' . $text . '</span>';
				}
				echo '</div>';
				$i++;
			}
		?>
		<br />
		<br />
	</div>
	<div class="clearfix"></div>
	
	<?php
	if ($nutrientData != null){
		/*
		'NUT_ID',
		'NUT_DESC',
		*/
		$fields = array();
		$fields[0] = array(
			'NUT_WATER',
			'NUT_ENERG',
			'NUT_PROT',
			'NUT_LIPID',
			'NUT_ASH',
			'NUT_CARB',
			'NUT_FIBER',
			'NUT_SUGAR',
		);
		$fields[1] = array(
			'NUT_FA_SAT',
			'NUT_FA_MONO',
			'NUT_FA_POLY',
			'NUT_CHOLEST',
			'NUT_REFUSE',
		);
		$fields[3] = array(
			'NUT_VIT_C',
			'NUT_THIAM',
			'NUT_RIBOF',
			'NUT_NIAC',
			'NUT_PANTO',
			'NUT_VIT_B6',
			'NUT_FOLAT_TOT',
			'NUT_FOLIC',
			'NUT_FOLATE_FD',
			'NUT_FOLATE_DFE',
			'NUT_CHOLINE',
			'NUT_VIT_B12',
			'NUT_VIT_A_IU',
			'NUT_VIT_A_RAE',
			'NUT_RETINOL',
			'NUT_ALPHA_CAROT',
			'NUT_BETA_CAROT',
			'NUT_BETA_CRYPT',
			'NUT_LYCOP',
			'NUT_LUT_ZEA',
			'NUT_VIT_E',
			'NUT_VIT_D',
			'NUT_VIT_D_IU',
			'NUT_VIT_K',
		);
		$fields[2] = array(
			'NUT_CALC',
			'NUT_IRON',
			'NUT_MAGN',
			'NUT_PHOS',
			'NUT_POTAS',
			'NUT_SODIUM',
			'NUT_ZINC',
			'NUT_COPP',
			'NUT_MANG',
			'NUT_SELEN',
		);
		
		
		$units = array();
		$units[0] = array(
			'g',
			'kcal',
			'g',
			'g',
			'g',
			'g',
			'g',
			'g',
		);
		$units[1] = array(
			'g',
			'g',
			'g',
			'mg',
			'g',
		);
		$units[3] = array(
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'µg',
			'µ',
			'µ',
			'µ dietary folate equivalents',
			'mg',
			'µ',
			'IU',
			'µ retinol activity equivalents',
			'µ',
			'µ',
			'µ',
			'µ',
			'µ',
			'µ',
			'alpha-tocopherol',
			'µ',
			'IU',
			'phylloquinone',
		);
		$units[2] = array(
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'µg',
		);
		
		echo '<div class="nutrientTable">';
		echo '<div class="f-left">';
		for($group=0; $group<count($fields); $group++){
			if ($group == 3){
				echo '</div>';
				echo '<div class="f-left">';
			}
			echo '<div class="nutrientDataGroup">';
				for($field=0; $field<count($fields[$group]); $field++){
					$nut_field = $fields[$group][$field];
					echo '<div class="nutrient_row' . (($field == count($fields[$group])-1)?' last':'') . '">';
					echo '<span class="name">' . CHtml::encode($this->trans->__get('FIELD_'.$nut_field)) . '</span>';
					echo '<span class="value">'; printf('%1.2f',$nutrientData->$nut_field); echo '</span>';
					echo '<span class="unit">' . $units[$group][$field] . '</span>';
					echo '</div>';
				}
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
	}
?>
<div class="clearfix"></div>

</div>

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'REC_CREATED',
		'REC_CHANGED',
		'REC_IMG_FILENAME',
		'REC_IMG_AUTH',
		'RET_ID',
		'REC_NAME_EN',
		'REC_NAME_DE',
	),
)); */ ?>
