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
$isPro = isset($data['PRF_UID']) && $data['PRF_UID'] > 0;
if ($isPro){
	echo '<div class="resultArea professional">';
	$proName = $data['PRF_FIRSTNAME'] . ' ' . $data['PRF_LASTNAME'];
} else {
	echo '<div class="resultArea">';	
}
	echo '<div class="list_img">';
		if ($this->isFancyAjaxRequest){
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), $data['REC_NAME_' . Yii::app()->session['lang']], array('class'=>'recipe', 'title'=>$data['REC_NAME_' . Yii::app()->session['lang']]));
		} else {
			echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), $data['REC_NAME_' . Yii::app()->session['lang']], array('class'=>'recipe', 'title'=>$data['REC_NAME_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['REC_ID']));
		}
		echo '<div class="img_auth">';
		if ($data['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['REC_IMG_AUTH']; }
		echo '</div>';
		if ($isPro){
			echo '<div class="pro_flag" title="' . $this->trans->RECIPES_FLAG_PRO_TITLE . '">' . $this->trans->RECIPES_FLAG_PRO . '</div>';
		}
	echo '</div>';
	if ($isPro){
		echo CHtml::link(CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$data['PRF_UID'], 'ext'=>'.png')), $proName, array('class'=>'profile', 'title'=>$proName)), array('profiles/view', 'id'=>$data['PRF_UID']), array('class'=>'fancyLink professionalPic'));
	}
	if ($this->isFancyAjaxRequest){
		if ($this->isTemplateChoose){
			$class = ' RecipeTemplateSelect';
		} else {
			$class = ' RecipeSelect';
		}
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['REC_ID'], array('class'=>'f-right button'.$class));
	} else {
		if(!Yii::app()->user->isGuest) {
			echo '<div class="options">';
				echo CHtml::link(CHtml::encode($this->trans->RECIPES_DONT_LIKE_IT /*GENERAL_DISGUSTING*/), array('disgusting', 'id'=>$data['REC_ID']), array('class'=>'noAjax button')) . '<br>';
				//echo CHtml::link(CHtml::encode($this->trans->RECIPES_NOT_TODAY), array('hide', 'id'=>$data['REC_ID']), array('class'=>'button')) . '<br>';
				echo CHtml::link(CHtml::encode($this->trans->RECIPES_SAVE_FOR_LATER /*GENERAL_DELICIOUS*/), array('delicious', 'id'=>$data['REC_ID']), array('class'=>'noAjax button'));
			echo '</div>';
		}
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['REC_NAME_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['REC_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['REC_ID']));
			}
			?>
		</div>

<?php /*
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CREATED']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CHANGED']); ?>
		<br />
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_IMG_FILENAME']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_IMG_AUTH']); ?>
		<br />
		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_HAS_ALLERGY_INFO')); ?>:</b>
		<?php echo CHtml::encode($data->REC_HAS_ALLERGY_INFO); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_SUMMARY')); ?>:</b>
		<?php echo CHtml::encode($data->REC_SUMMARY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_APPROVED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_APPROVED); ?>
		<br />


		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_WIKI_LINK')); ?>:</b>
		<?php echo CHtml::encode($data->REC_WIKI_LINK); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_IS_PRIVATE')); ?>:</b>
		<?php echo CHtml::encode($data->REC_IS_PRIVATE); ?>
		<br />


		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CUSINE_GPS_LAT')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CUSINE_GPS_LAT); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CUSINE_GPS_LNG')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CUSINE_GPS_LNG); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TOOLS')); ?>:</b>
		<?php echo CHtml::encode($data->REC_TOOLS); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_SYNONYM_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->REC_SYNONYM_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_SYNONYM_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->REC_SYNONYM_DE_CH); ?>
		<br />
*/ ?>

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['RET_DESC_' . Yii::app()->session['lang']]); ?>
		<br />
		
		<?php
		$showCusins = false;
		if(!empty($data['CUT_DESC_'. Yii::app()->session['lang']])){
			$data['CUT_DESC'] = $data['CUT_DESC_'. Yii::app()->session['lang']];
		}
		if(!empty($data['CST_DESC_'. Yii::app()->session['lang']])){
			$data['CST_DESC'] = $data['CST_DESC_'. Yii::app()->session['lang']];
		}
		if(!empty($data['CSS_DESC_'. Yii::app()->session['lang']])){
			$data['CSS_DESC'] = $data['CSS_DESC_'. Yii::app()->session['lang']];
		}
		if (!empty($data['CUT_DESC']) || !empty($data['CST_DESC']) || !empty($data['CSS_DESC'])) {
			echo '<b>' . CHtml::encode($this->trans->FIELD_CUT_ID) . '</b>';
			$showCusins = true;
		}
		if (!empty($data['CUT_DESC'])) {
			//if (!empty($data['CUT_DESC'])) {
				if (!empty($data['CUT_IMG_ETAG'])) {
					echo CHtml::image($this->createUrl('savedImage/cusineTypes', array('id'=>$data['CUT_ID'], 'ext'=>'.png')), $data['CUT_DESC'], array('class'=>'cusineImg'));
				}
				echo '<div class="cusine">' . CHtml::encode($data['CUT_DESC']) . '</div>';
			/*} else {
				echo $this->trans->GENERAL_UNDEFINED;
			}*/
// 			echo '<br />';
		}
		if (!empty($data['CST_DESC'])) {
			//echo '<b>' . CHtml::encode($this->trans->FIELD_CST_ID) . '</b>';
			//if (!empty($data['CST_DESC'])) {
				if (!empty($data['CST_IMG_ETAG'])) {
					echo CHtml::image($this->createUrl('savedImage/cusineSubTypes', array('id'=>$data['CST_ID'], 'ext'=>'.png')), $data['CST_DESC'], array('class'=>'cusineImg'));
				}
				echo '<div class="cusine">' . CHtml::encode($data['CST_DESC']) . '</div>';
			/*} else {
				echo $this->trans->GENERAL_UNDEFINED;
			}*/
// 			echo '<br />';
		}
		if (!empty($data['CSS_DESC'])) {
			//echo '<b>' . CHtml::encode($this->trans->FIELD_CSS_ID) . '</b>';
			//if (!empty($data['CSS_DESC'])) {
				if (!empty($data['CSS_IMG_ETAG'])) {
					echo CHtml::image($this->createUrl('savedImage/cusineSubSubTypes', array('id'=>$data['CSS_ID'], 'ext'=>'.png')), $data['CSS_DESC'], array('class'=>'cusineImg'));
				}
				echo '<div class="cusine">' . CHtml::encode($data['CSS_DESC']) . '</div>';
			/*} else {
				echo $this->trans->GENERAL_UNDEFINED;
			}*/
// 			echo '<br />';
		}
		if ($showCusins) {
			echo '<br />';
		}
		?>
		
		<?php
			if (!empty($data['REC_COMPLEXITY'])) {
			echo '<b>' . CHtml::encode($this->trans->FIELD_REC_COMPLEXITY) . '</b>';
			//if (!empty($data['REC_COMPLEXITY'])) {
				echo CHtml::encode($data['REC_COMPLEXITY']); 
			/*} else {
				echo $this->trans->GENERAL_UNDEFINED;
			}*/
			echo '<br />';
		} ?>
		
		<?php
		if (!empty($data['REC_SERVING_COUNT'])){
			echo '<b>' . CHtml::encode($this->trans->FIELD_REC_SERVING_COUNT) . '</b>';
			//if (!empty($data['REC_SERVING_COUNT'])){
				echo CHtml::encode($data['REC_SERVING_COUNT']);
			/*} else {
				echo $this->trans->GENERAL_UNDEFINED;
			}*/
			echo '<br />';
		} ?>
		
		<b><?php echo CHtml::encode($this->trans->FIELD_REC_KCAL); ?>:</b>
		<?php echo CHtml::encode($data['REC_KCAL']) /*. ' ' . $this->trans->RECIPES_KCAL_PER_SERVING*/ ?>
		<br />
		
		<?php /* //Preparation Time
		<b><?php echo CHtml::encode($this->trans->FIELD_REC_KCAL); ?>:</b>
		<?php echo CHtml::encode($data['REC_KCAL']) . ' ' . $this->trans->RECIPES_KCAL_PER_SERVING ?>
		<br />
		*/ ?>
		
		<?php if (!$this->isFancyAjaxRequest){ ?>
		<div class="buttons">
			<?php
			//echo CHtml::link('+', array('user/addrecipes', 'id'=>$data['REC_ID']), array('class'=>'button backpic addRecipe', 'title'=>$this->trans->RECIPES_ADD));
			echo CHtml::link(CHtml::encode($this->trans->RECIPES_COOK_IT), array('meals/mealPlanner', 'rec_id'=>$data['REC_ID']), array('class'=>'button'));
			//echo CHtml::link(CHtml::encode($this->trans->MEALPLANNER_SAVE_TO_SHOPPINGLIST), array('shoppinglists/view', 'rec_id'=>$data['REC_ID']), array('class'=>'button'));
			?>
		</div>
		<?php } ?>
			
	</div>
	<div class="clearfix"></div>
</div>