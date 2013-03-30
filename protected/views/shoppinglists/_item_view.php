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
			<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_NAME'], 'title'=>$data['ING_NAME'])); ?>
			<div class="img_auth"><?php if ($data['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['ING_IMG_AUTH']; } ?></div>
		</div>
		<div class="data">
			<div class="name">
				<?php echo CHtml::encode($data['ING_NAME']); ?>
			</div>
			<span><?php printf($this->trans->SHOPPINGLIST_YOU_NEED, $data['ing_weight']); ?></span>
		</div>
	</div>
	<div class="shoppingList_right">
		<input type="hidden" class="setProductLink" value="<?php echo $this->createUrl('setProduct', array('id'=>$data['SHO_ID'], 'ing_id'=>$data['ING_ID'])); ?>"/>
		<?php if (isset($data['PRO_ID']) && $data['PRO_ID'] != ''){ ?>
			<div class="shoppingList_image">
				<div class="list_img">
					<?php echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$data['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'shoppinglist_product', 'alt'=>$data['PRO_NAME'], 'title'=>$data['PRO_NAME'])); ?>
					<div class="img_auth"><?php if ($data['PRO_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $data['PRO_IMG_AUTH']; } ?></div>
				</div>
				<?php echo CHtml::link($this->trans->SHOPPINGLIST_HAVE_IT, array('removeFromList', 'id'=>$data['SHO_ID'], 'ing_id'=>$data['ING_ID']), array('class'=>'button noAjax removeFromList')); ?>
			</div>
			<div class="data_right">
				<div class="name">
					<?php echo CHtml::encode($data['PRO_NAME']); ?>
				</div>
				<?php echo CHtml::link($this->trans->SHOPPINGLIST_CHANGE_PRODUCT, array('products/chooseProduct', 'ing_id'=>$data['ING_ID'], 'order'=>'you'), array('class'=>'button fancyChoose ProductSelect')); ?>
				<input type="hidden" class="fancyValue" />
				<?php
				//$noShopsAssigned = false;
				if ($data['distance_to_you'] == -2 || $data['distance_to_home'] == -2){
					echo '<div class="ProductsStoreInfo">';
					echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";
					echo '</div>';
					//$noShopsAssigned = true;
				} else {
					if ($data['distance_to_you'] == -1){
						//echo '<span>' . CHtml::link($this->trans->PRODUCTS_LOCATE_FOR_STORES, '#', array('class'=>'actionlink', 'id'=>'updateCurrentGPS')) . '</span>'."\n";
					} else {
						if (is_array($data['distance_to_you'])){
							echo '<div class="ProductsStoreInfo">';
							if ($data['distance_to_you'][3] == 0){
								echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_YOU, $data['distance_to_you'][1], $data['distance_to_you'][0]); echo '</span> <input type="hidden" class="viewDistance" value="' . $data['distance_to_you'][0] . '" />'."\n";
							} else {
								echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_YOU, $data['distance_to_you'][3], Yii::app()->user->view_distance); echo '</span>'."\n";
							}
							echo '</div>';
						/*} else {
							echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";
							$noShopsAssigned = true;*/
						}
					}
					
					//if (!$noShopsAssigned) {
						if ($data['distance_to_home'] == -1){
							/*
							if (Yii::app()->user->isGuest){
								echo '<span>' . CHtml::link($this->trans->PRODUCTS_LOGIN_FOR_STORES, array('site/login'), array('class'=>'actionlink')) . '</span>'."\n";
							} else {
								echo '<span>' . CHtml::link($this->trans->PRODUCTS_SET_HOME_FOR_STORES, array('profiles/update', 'id'=>Yii::app()->user->id, 'afterSave'=>urlencode($this->createUrl($this->route, $this->getActionParams()))), array('class'=>'actionlink')) . '</span>'."\n";
							}
							*/
						} else {
							if (is_array($data['distance_to_home'])){
								echo '<div class="ProductsStoreInfo">';
								if ($data['distance_to_home'][3] == 0){
									echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_HOME, $data['distance_to_home'][1], $data['distance_to_home'][0]); echo '</span> <input type="hidden" class="viewDistance" value="' . $data['distance_to_home'][0] . '" />'."\n";
								} else {
									echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_HOME, $data['distance_to_home'][3], Yii::app()->user->view_distance); echo '</span>'."\n";
								}
								echo '</div>';
							/*} else {
								echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";*/
							}
						}
					//}
				}
				?>
			</div>
		<?php } else { ?>
			<div class="shoppingList_image">
				<div class="list_img">
					<?php echo CHtml::image(Yii::app()->request->baseUrl . '/pics/unknown.png', '', array('class'=>'shoppinglist_product', )); ?><br>
					<div class="img_auth">&nbsp;</div>
				</div>
				<?php echo CHtml::link($this->trans->SHOPPINGLIST_HAVE_IT, array('removeFromList', 'id'=>$data['SHO_ID'], 'ing_id'=>$data['ING_ID']), array('class'=>'button noAjax removeFromList')); ?>
			</div>
			<div class="data_right">
				<div class="name">&nbsp;</div>
				<?php echo CHtml::link($this->trans->SHOPPINGLIST_ADD_PRODUCT_NEAR_HOME, array('products/chooseProduct', 'ing_id'=>$data['ING_ID'], 'order'=>'home'), array('class'=>'button fancyChoose ProductSelect')); ?><br>
				<?php echo CHtml::link($this->trans->SHOPPINGLIST_ADD_PRODUCT_NEAR_YOU, array('products/chooseProduct', 'ing_id'=>$data['ING_ID'], 'order'=>'you'), array('class'=>'button fancyChoose ProductSelect')); ?>
				<input type="hidden" class="fancyValue" />
			</div>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
	
</div>