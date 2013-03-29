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
<div class="resultArea adminArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['COI_PREP'], array('class'=>'f-right button CookInPrepSelect'));
	} else {
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['COI_PREP']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'COI_PREP'=>$data['COI_PREP']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['COI_PREP']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
		*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['COI_PREP_DESC']);
			} else {
				echo CHtml::link(CHtml::encode($data['COI_PREP_DESC']), array('view', 'id'=>$data['COI_PREP']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_PREP')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->COI_PREP), array('view', 'id'=>$data->COI_PREP)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_PREP_DESC')); ?>:</b>
		<?php echo CHtml::encode($data->COI_PREP_DESC); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>