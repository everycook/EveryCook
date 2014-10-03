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
<div class="resultArea">
	<?php 
	if ($this->isFancyAjaxRequest){
		echo '<div class="list_img">';
			echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$data['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$data['REC_NAME_' . Yii::app()->session['lang']]));
			echo '<div class="img_auth">';
			if ($data['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['REC_IMG_AUTH']; } 
			echo '</div>';
		echo '</div>';
		
		if ($this->isTemplateChoose){
			$class = ' RecipeTemplateSelect';
		} else {
			$class = ' RecipeSelect';
		}
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['REC_ID'], array('class'=>'f-right button'.$class));
	} else {
		echo '<div class="list_img">';
			echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$data['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$data['REC_NAME_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['REC_ID'])); 
			echo '<div class="img_auth">';
			if ($data['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['REC_IMG_AUTH']; } 
			echo '</div>';
		echo '</div>';
		echo '<div class="options">';
			//echo CHtml::link('+', array('user/addrecipes', 'id'=>$data['REC_ID']), array('class'=>'button backpic addRecipe', 'title'=>$this->trans->RECIPES_ADD));
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['REC_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			echo CHtml::link('&nbsp;', array('meals/mealPlanner', 'rec_id'=>$data['REC_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->RECIPES_MEALPLANNER));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['REC_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING)) . '<br>';
			echo CHtml::link(CHtml::encode($this->trans->RECIPES_VIEW_RECIPE), array('view', 'id'=>$data['REC_ID']), array('class'=>'button last'));
		echo '</div>';
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

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_SUMMERY')); ?>:</b>
		<?php echo CHtml::encode($data->REC_SUMMERY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_APPROVED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_APPROVED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_SERVING_COUNT')); ?>:</b>
		<?php echo CHtml::encode($data->REC_SERVING_COUNT); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_WIKI_LINK')); ?>:</b>
		<?php echo CHtml::encode($data->REC_WIKI_LINK); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_IS_PRIVATE')); ?>:</b>
		<?php echo CHtml::encode($data->REC_IS_PRIVATE); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_COMPLEXITY')); ?>:</b>
		<?php echo CHtml::encode($data->REC_COMPLEXITY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_ID')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CST_ID')); ?>:</b>
		<?php echo CHtml::encode($data->CST_ID); ?>
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
	</div>
	<div class="clearfix"></div>
</div>