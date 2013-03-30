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
	
	<div class="f-right">
		<?php
		foreach($data['ingredients'] as $ingredient){
			echo '<div>' . $ingredient . '</div>';
		}
		?>
	</div>
	
	<div class="f-left">
	<h2><?php echo $this->trans->SHOPPINGLISTS_LIST_SAVED_AT . ' ' . date('d.m.Y', $data['SHO_DATE']); ?></h2>
	<?php
	echo '<div>';
	printf($this->trans->SHOPPINGLISTS_TOTAL_INGREDIENTS,$data['total_products']);
	echo '</div><div>';
	printf($this->trans->SHOPPINGLISTS_NOT_ASSIGNED,$data['not_assigned']);
	echo '</div>';
	foreach($data['products_from'] as $from){
		echo '<div>';
		printf($this->trans->SHOPPINGLISTS_PRODUCTS_FROM,$from['amount'],$from['supplier']);
		echo '</div>';
	}
	?>
	</div>
	
	<div class="f-center">
		<?php echo CHtml::link(CHtml::encode($this->trans->GENERAL_EDIT), array('view', 'id'=>$data['SHO_ID']), array('class'=>'button')); ?>
	</div>
</div>