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
	<div class="shoppingList_left">
		<div class="list_img">
			<?php
			$imgContent = CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), $data['ING_NAME'], array('class'=>'ingredient', 'title'=>$data['ING_NAME']));
			echo CHtml::link($imgContent, array('ingredients/view', 'id'=>$data['ING_ID']), array('class'=>'fancyLink','title'=>$data['ING_NAME']));
			?>
			<div class="img_auth"><?php if ($data['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['ING_IMG_AUTH']; } ?></div>
		</div>
		<div class="data">
			<div class="name">
			<?php echo CHtml::link($data['ING_NAME'], array('ingredients/view', 'id'=>$data['ING_ID']), array('class'=>'fancyLink', 'title'=>$data['ING_NAME'])); ?>
			</div>
			<span><?php printf($this->trans->SHOPPINGLISTS_YOU_NEED, $data['ing_weight']); ?></span>
		</div>
	</div>
	<div class="shoppingList_right">
		<input type="hidden" class="setHaveItLink" value="<?php echo $this->createUrl('setHaveIt', array('id'=>$data['SHO_ID'], 'ing_id'=>$data['ING_ID'])); ?>"/>
		<?php echo CHtml::checkBox('haveit['.$index.']', $data['haveIt'] == 1, array('class'=>'haveIt', 'title'=>$this->trans->SHOPPINGLISTS_HAVE_IT)); ?>
	</div>
	<div class="clearfix"></div>
	<?php
	echo CHtml::link('X', array('removeFromList', 'id'=>$data['SHO_ID'], 'ing_id'=>$data['ING_ID']), array('class'=>'shoppingList_remove noAjax removeFromList','title'=>$this->trans->GENERAL_REMOVE));
	?>
</div>