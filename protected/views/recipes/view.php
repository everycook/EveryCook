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

if (!$history){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('recipes/update',$this->getActionParams())),
	);
}
?>

<div id="recipes" class="detailView">
	<div class="shoppingList">
		&nbsp;
		<div class="detail_img">
			<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), $model->__get('REC_NAME_' . Yii::app()->session['lang']), array('class'=>'recipe', 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang']))); ?>
			<div class="img_auth"><?php if ($model->REC_IMG_ETAG == '') { echo '&nbsp;'; } else {echo '<C2><A9> by ' . $model->REC_IMG_AUTH; } ?></div>
		</div>
		<?php
		/*
		if (!$history){
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model->REC_ID), array('class'=>'delicious_big noAjax backpic f-left', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('+', array('user/addrecipes', 'id'=>$model->REC_ID), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD));
			echo CHtml::link('&nbsp;', array('meals/mealPlanner', 'rec_id'=>$model->REC_ID), array('class'=>'cookwith_big backpic f-right', 'title'=>$this->trans->RECIPES_MEALPLANNER));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model->REC_ID), array('class'=>'disgusting_big noAjax backpic f-center','title'=>$this->trans->GENERAL_DISGUSTING));
		}
		*/
		?>
		<?php //echo CHtml::link(CHtml::encode($this->trans->MEALPLANNER_SAVE_TO_SHOPPINGLIST), array('shoppinglists/view', 'rec_id'=>$model->REC_ID), array('class'=>'button')); ?>
		<?php echo CHtml::link($this->trans->RECIPES_VIEW_SHOPPINGLIST, array('recipes/viewShoppingList', 'ids'=>$model->REC_ID), array('class'=>'button', 'id'=>'viewShoppingList')); ?>
		<div class="otherItems ingredients">
			<?php echo CHtml::encode($this->trans->RECIPES_INGREDIENTS_NEEDED); ?>
			<ul>
			<?php
			$ingredient_printed = array();
			foreach($model->steps as $step){
				if ($step->ingredient != null){
					if (!isset($ingredient_printed[$step->ingredient->ING_ID])){
						echo '<li>';
							echo CHtml::link($step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']), array('ingredients/view', 'id'=>$step->ingredient->ING_ID), array('title'=>$this->trans->RECIPES_TOOLTIP_OPEN_INGREDIENT, 'class'=>'fancyLink'));
							echo '<div class="small_img">';
								echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$step->ingredient['ING_ID'], 'ext'=>'.png')), $step->ingredient['ING_NAME_' . Yii::app()->session['lang']], array('class'=>'ingredient', 'title'=>$step->ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$step->ingredient['ING_ID']), array('class'=>'fancyLink'));
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
	<div class="recipeDetail">
		<?php echo CHtml::link($this->trans->RECIPES_HISTORY, array('history', 'id'=>$model->REC_ID), array('class'=>'button history')); ?>
		<?php
		if (isset(Yii::app()->session['Recipes'])){
			if (isset(Yii::app()->session['Recipes']['model'])){
				echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/advancesearch'), array('class'=>'button backbutton'));
			} else {
				echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/search'), array('class'=>'button backbutton'));
			}
		}
		?>
		<?php if (!$history){ ?>
			<div class="buttons cookitbar">
			<?php echo CHtml::link(CHtml::encode($this->trans->RECIPES_COOK_IT), array('meals/mealPlanner', 'rec_id'=>$model->REC_ID), array('class'=>'button')); ?>
			</div>
		<?php } ?>
		<div class="details">
			<h1 class="name">
				<?php
					if ($history){
						$dateFormat = $this->trans->HISTORY_DATE_FORMAT;
						echo CHtml::link(CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])) . ' (' . date($dateFormat, $model->CHANGED_ON) . ')', array('viewHistory', 'id'=>$model->REC_ID, 'CHANGED_ON'=>$model->CHANGED_ON));
					} else {
						echo CHtml::link(CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])), array('view', 'id'=>$model->REC_ID));
					}
				?>
			</h1>
			<div class="otherNames">
				<?php
				echo '<span class="title">' . $this->trans->GENERAL_OTHER_NAMES . '</span>';
				foreach($this->allLanguages as $lang=>$name){
					echo '<div class="label">' . $model->getAttributeLabel('REC_NAME_'.$lang) . '</div><div class="otherName">' . $model->__get('REC_NAME_'.$lang) . '</div>';
					echo '<div class="clearfix"></div>';
				}
				foreach($this->allLanguages as $lang=>$name){
					echo '<div class="label">' . $model->getAttributeLabel('REC_SYNONYM_'.$lang) . '</div><div class="otherName">' . $model->__get('REC_SYNONYM_'.$lang) . '</div>';
					echo '<div class="clearfix"></div>';
				}
				?>
			</div>
			<div class="clearfix"></div>
			
			<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
			<?php echo CHtml::encode($model->recipeTypes->__get('RET_DESC_' . Yii::app()->session['lang'])); ?>
			<br />
			
			<?php
			if (!empty($model->cusineTypes)) {
				echo '<b>' . CHtml::encode($this->trans->FIELD_CUT_ID) . '</b>';
				//if (!empty($model->cusineTypes)) {
					echo CHtml::encode($model->cusineTypes->__get('CUT_DESC_' . Yii::app()->session['lang']));
				/*} else {
					echo $this->trans->GENERAL_UNDEFINED;
				}*/
				echo '<br />';
			} ?>
			
			<?php
			if (!empty($model->cusineSubTypes)) {
				echo '<b>' . CHtml::encode($this->trans->FIELD_CST_ID) . '</b>';
				//if (!empty($model->cusineSubTypes)) {
					echo CHtml::encode($model->cusineSubTypes->__get('CST_DESC_' . Yii::app()->session['lang']));
				/*} else {
					echo $this->trans->GENERAL_UNDEFINED;
				}*/
				echo '<br />';
			} ?>
			
			<?php
			if (!empty($model->REC_COMPLEXITY)) {
				echo '<b>' . CHtml::encode($this->trans->FIELD_REC_COMPLEXITY) . '</b>';
				//if (!empty($model->REC_COMPLEXITY)) {
					echo CHtml::encode($model->REC_COMPLEXITY); 
				/*} else {
					echo $this->trans->GENERAL_UNDEFINED;
				}*/
				echo '<br />';
			} ?>
			
			<?php
			if (!empty($model->REC_SERVING_COUNT)){
				echo '<b>' . CHtml::encode($this->trans->FIELD_REC_SERVING_COUNT) . '</b>';
				//if (!empty($model->REC_SERVING_COUNT)){
					echo CHtml::encode($model->REC_SERVING_COUNT);
				/*} else {
					echo $this->trans->GENERAL_UNDEFINED;
				}*/
				echo '<br />';
			} ?>
			
			<?php 
			echo '<b>' . CHtml::encode($this->trans->FIELD_REC_KCAL) . '</b>';
			echo CHtml::encode($model->REC_KCAL); /*. ' ' . $this->trans->RECIPES_KCAL_PER_SERVING*/
			echo '<br />';
			?>
		</div>
		
		
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
			echo '<span class="title">' . $this->trans->GENERAL_NUTRIENTS . '</span>';
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
			echo '<div class="clearfix"></div>';
			echo '</div>';
		}
		?>
		
		<div class="steps">
		<?php
			$i = 1;
			foreach($model->steps as $step){
				echo '<div class="step">';
				echo '<span class="stepNo">' . $i . '</span> ';
				echo '<span class="action">' . $step->getAsHTMLString($cookin) . '</span>';
				echo '</div>';
				$i++;
			}
		?>
		</div>
		<?php if (!$history){ ?>
			<div class="buttons cookitbar">
			<?php echo CHtml::link(CHtml::encode($this->trans->RECIPES_COOK_IT), array('meals/mealPlanner', 'rec_id'=>$model->REC_ID), array('class'=>'button')); ?>
			</div>
		<?php } ?>
	</div>
	

</div>

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
