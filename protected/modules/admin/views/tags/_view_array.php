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
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['TAG_ID'], array('class'=>'f-right button TagsSelect'));
	/*
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['TAG_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'TAG_ID'=>$data['TAG_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['TAG_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			foreach($this->allLanguages as $lang=>$name){
				$text = $data['TAG_DESC_' . $lang];
				if (!isset($text) || strlen($text) == 0){
					$text = 'NO TEXT DEFINED';
				}
				if ($this->isFancyAjaxRequest){
					echo '<div>' . $lang . ': '. CHtml::encode($text) . '</div>';
				} else {
					echo '<div>' . $lang . ': '. CHtml::link($text, array('update', 'id'=>$data['TAG_ID'])) . '</div>';
				}
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('TAG_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->TAG_ID), array('view', 'id'=>$data->TAG_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TAG_IGNORE')); ?>:</b>
		<?php echo CHtml::encode($data->TAG_IGNORE); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TAG_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->TAG_DESC_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TAG_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->TAG_DESC_DE_CH); ?>
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