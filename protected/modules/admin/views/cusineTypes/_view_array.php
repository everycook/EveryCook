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
			echo CHtml::image($this->createUrl('displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), '', array('class'=>'cusineTypes', 'alt'=>$data['REC_DESC_' . Yii::app()->session['lang']], 'title'=>$data['REC_DESC_' . Yii::app()->session['lang']]));
		echo '</div>';
		
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['CUT_ID'], array('class'=>'f-right button CusineTypesSelect'));
	} else {
		echo '<div class="list_img">';
			echo CHtml::link(CHtml::image($this->createUrl('displaySavedImage', array('id'=>$data['CUT_ID'], 'ext'=>'.png')), '', array('class'=>'cusineTypes', 'alt'=>$data['CUT_DESC_' . Yii::app()->session['lang']], 'title'=>$data['CUT_DESC_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['CUT_ID'])); 
		echo '</div>';
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['CUT_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'CUT_ID'=>$data['CUT_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['CUT_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['CUT_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['CUT_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['CUT_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->CUT_ID), array('view', 'id'=>$data->CUT_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_GPS_LAT')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_GPS_LAT); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_GPS_LNG')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_GPS_LNG); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_GOOGLE_REGION')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_GOOGLE_REGION); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_DESC_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CUT_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->CUT_DESC_DE_CH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_BY')); ?>:</b>
		<?php echo CHtml::encode($data->CREATED_BY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_ON')); ?>:</b>
		<?php echo CHtml::encode($data->CREATED_ON); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_BY')); ?>:</b>
		<?php echo CHtml::encode($data->CHANGED_BY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_ON')); ?>:</b>
		<?php echo CHtml::encode($data->CHANGED_ON); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>