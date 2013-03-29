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
	<div class="list_img">
	<?php 
	if (!$this->isFancyAjaxRequest){
		echo CHtml::link(CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$data['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$data['PRO_NAME_' . Yii::app()->session['lang']], 'title'=>$data['PRO_NAME_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['PRO_ID']));
	} else {
		echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$data['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$data['PRO_NAME_' . Yii::app()->session['lang']], 'title'=>$data['PRO_NAME_' . Yii::app()->session['lang']]));
	}
	?>
	<div class="img_auth"><?php if ($data['PRO_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $data['PRO_IMG_AUTH']; } ?></div>
	</div>
	
	<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['PRO_ID'], array('class'=>'f-right button ProductSelect'));
	} else { ?>
		<div class="options">
			<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['PRO_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS)); ?>
			<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['PRO_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING)); ?><br>
		</div>
	<?php } ?>
	
	<div class="data">
		<input type="hidden" class="productId" value="<?php echo $data['PRO_ID']; ?>"/>
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['PRO_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['PRO_ID'])); ?>
		</div>
		
		<?php
		//$noShopsAssigned = false;
		if ($data['distance_to_you'] == -2 || $data['distance_to_home'] == -2){
			echo '<div class="ProductsStoreInfo">';
			echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";
			echo '</div>';
			//$noShopsAssigned = true;
		} else {
			echo '<div class="ProductsStoreInfo">';
			if ($data['distance_to_you'] == -1){
				echo '<span>' . CHtml::link($this->trans->PRODUCTS_LOCATE_FOR_STORES, '#', array('class'=>'actionlink', 'id'=>'updateCurrentGPS')) . '</span>'."\n";
			} else {
				if (is_array($data['distance_to_you'])){
					if ($data['distance_to_you'][3] == 0){
						echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_YOU, $data['distance_to_you'][1], $data['distance_to_you'][0]); echo '</span> <input type="hidden" class="viewDistance" value="' . $data['distance_to_you'][0] . '" />';
						if (!$this->isFancyAjaxRequest){
							echo ' <div class="button showOnMap centerGPSYou">' . $this->trans->PRODUCTS_SHOW_ON_MAP . '</div>'."\n";
						}
					} else {
						echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_YOU, $data['distance_to_you'][3], Yii::app()->user->view_distance); echo '</span>';
						if (!$this->isFancyAjaxRequest){
							echo ' <div class="button showOnMap centerGPSYou">' . $this->trans->PRODUCTS_SHOW_ON_MAP . '</div>'."\n";
						}
					}
				/*} else {
					echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";
					$noShopsAssigned = true;*/
				}
			}
			echo '</div>';
			
			//if (!$noShopsAssigned) {
				echo '<div class="ProductsStoreInfo">';
				if ($data['distance_to_home'] == -1){
					if (Yii::app()->user->isGuest){
						echo '<span>' . CHtml::link($this->trans->PRODUCTS_LOGIN_FOR_STORES, array('site/login'), array('class'=>'actionlink')) . '</span>'."\n";
					} else {
						echo '<span>' . CHtml::link($this->trans->PRODUCTS_SET_HOME_FOR_STORES, array('profiles/update', 'id'=>Yii::app()->user->id, 'afterSave'=>urlencode($this->createUrl($this->route, $this->getActionParams()))), array('class'=>'actionlink')) . '</span>'."\n";
					}
				} else {
					if (is_array($data['distance_to_home'])){
						if ($data['distance_to_home'][3] == 0){
							echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_HOME, $data['distance_to_home'][1], $data['distance_to_home'][0]); echo '</span> <input type="hidden" class="viewDistance" value="' . $data['distance_to_home'][0] . '" />';
							if (!$this->isFancyAjaxRequest){
								echo ' <div class="button showOnMap centerGPSHome">' . $this->trans->PRODUCTS_SHOW_ON_MAP . '</div>'."\n";
							}
						} else {
							echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_HOME, $data['distance_to_home'][3], Yii::app()->user->view_distance); echo '</span>';
							if (!$this->isFancyAjaxRequest){
								echo ' <div class="button showOnMap centerGPSHome">' . $this->trans->PRODUCTS_SHOW_ON_MAP . '</div>'."\n";
							}
						}
					/*} else {
						echo '<span>'; echo $this->trans->PRODUCTS_NO_SHOPS_ASSIGNED; echo '</span>'."\n";*/
					}
				}
				echo '</div>';
			//}
		}
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_SUSTAINABILITY) .':</strong> ' . CHtml::encode($data['ECO_DESC_'.Yii::app()->session['lang']]) ."</span><br/>\n";
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_ETHICAL) .':</strong> ' . CHtml::encode($data['ETH_DESC_'.Yii::app()->session['lang']]) ."</span><br/>\n";
		if (!$this->isFancyAjaxRequest){
			echo '<div class="f-right">';
			echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_ASSIGN_SHOP), array('stores/assign', 'pro_id'=>$data['PRO_ID']), array('class'=>'button', 'style'=>'margin-right: 0.8em;'));
			echo CHtml::link(CHtml::encode($this->trans->GENERAL_EDIT), array('products/update', 'id'=>$data['PRO_ID']), array('class'=>'button'));
			echo '</div>';
		}
		echo '<span>'; printf($this->trans->PRODUCTS_PACKAGE_SIZE, $data['PRO_PACKAGE_GRAMMS']); echo '</span><br/>'."\n";
		echo '<input type="hidden" class="PRO_PACKAGE_GRAMMS" value="' . $data['PRO_PACKAGE_GRAMMS'] . '" />';
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_BARCODE) . ':</strong> ' . $data['PRO_BARCODE'] . '</span><br/>'."\n";
		?>
	</div>
</div>