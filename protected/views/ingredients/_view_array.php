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
		echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$data['ING_NAME_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['ING_ID']));
	} else {
		echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$data['ING_NAME_' . Yii::app()->session['lang']]));
	}
	?>
	<div class="img_auth"><?php if ($data['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $data['ING_IMG_AUTH']; } ?></div>
	</div>
	
	<?php
	if ($this->isFancyAjaxRequest){
		if($this->isRecipeIngredientSelect){
			if ($this->recipeIngredientIds != null){
				$found = false;
				foreach($this->recipeIngredientIds as $ing_id){
					if ($data['ING_ID'] == $ing_id){
						echo CHtml::link($this->trans->GENERAL_SELECT, $data['ING_ID'], array('class'=>'f-right button IngredientSelect'));
						$found = true;
						break;
					}
				}
				if (!$found){
					echo CHtml::link($this->trans->GENERAL_SELECT, $data['ING_ID'], array('class'=>'f-right button IngredientSelect RecipeAddPrepare'));
				}
			} else {
				echo CHtml::link($this->trans->GENERAL_SELECT, $data['ING_ID'], array('class'=>'f-right button IngredientSelect RecipeAddPrepare'));
			}
		} else {
			echo CHtml::link($this->trans->GENERAL_SELECT, $data['ING_ID'], array('class'=>'f-right button IngredientSelect'));
		}
	} else {
		?>
		<div class="options">
			<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['ING_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS)); ?>
			<?php echo CHtml::link('&nbsp;', array('recipes/search', 'ing_id'=>$data['ING_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->INGREDIENTS_COOK_WITH)); ?>
			<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['ING_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING)); ?>
		</div>
		<?php
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['ING_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['ING_ID'])); ?>&nbsp;
		</div>
		<?php
		if (!$this->isFancyAjaxRequest){
			echo '<a href="' . Yii::app()->createUrl('nutrientData/view',array('id'=>$data['NUT_ID'], 'ing_id'=>$data['ING_ID'])) . '" class="nutrientInfo" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
		}
		?>
			<div class="nutrientInfo">
				<?php if ($data['NUT_ID']){ ?>
					<span><span class="title"><?php echo CHtml::encode($this->trans->FIELD_NUT_LIPID); ?>:</span>
					<span class="value"><?php echo CHtml::encode($data['NUT_LIPID']); ?> %</span></span>
					
					<span><span class="title"><?php echo CHtml::encode($this->trans->FIELD_NUT_CARB); ?>:</span>
					<span class="value"><?php echo CHtml::encode($data['NUT_CARB']); ?> %</span>
					
					<span><span class="title"><?php echo CHtml::encode($this->trans->FIELD_NUT_PROT); ?>:</span>
					<span class="value"><?php echo CHtml::encode($data['NUT_PROT']); ?> %</span></span>
				<?php } else {
					echo '&nbsp;';
				} ?>
			</div>
		<?php if (!$this->isFancyAjaxRequest){
			echo '</a>';
			
			if($data['pro_count'] != 0){ // || $data['sup_names'] != ''
				if ($data['distance_to_you_prod'] == -1){
						echo '<a href="#" class="shopInfo" id="updateCurrentGPS">';
						echo '<div class="shopInfo"><span>';
						echo $this->trans->INGREDIENTS_LOCATE_FOR_STORES;
				} else {
					if ($data['distance_to_you_prod'] > 0){
						echo '<a href="' . Yii::app()->createUrl('products/search',array('ing_id'=>$data['ING_ID'])) . '" class="shopInfo" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
						echo '<div class="shopInfo"><span>';
						printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_IN_SHOPS_FROM_YOU), $data['distance_to_you_prod'], $data['distance_to_you'], Yii::app()->user->view_distance);
					} else {
						echo '<a href="' . Yii::app()->createUrl('products/search',array('ing_id'=>$data['ING_ID'])) . '" class="shopInfo" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
						echo '<div class="shopInfo"><span>';
						printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_NO_SHOPS_IN_RANGE_YOU), Yii::app()->user->view_distance);
					}
				}
				echo '</span></div></a>';
				
				if ($data['distance_to_home_prod'] == -1){
					if (Yii::app()->user->isGuest){
						echo '<a href="' . Yii::app()->createUrl('site/login') . '" class="shopInfo">';
						echo '<div class="shopInfo"><span>';
						echo $this->trans->INGREDIENTS_LOGIN_FOR_STORES;
					} else {
						echo '<a href="' . Yii::app()->createUrl('profiles/update', array('id'=>Yii::app()->user->id, 'afterSave'=>urlencode($this->createUrl($this->route, $this->getActionParams())))) . '" class="shopInfo">';
						echo '<div class="shopInfo"><span>';
						echo $this->trans->INGREDIENTS_SET_HOME_FOR_STORES;
					}
				} else {
					if ($data['distance_to_home_prod'] > 0){				
						echo '<a href="' . Yii::app()->createUrl('products/search',array('ing_id'=>$data['ING_ID'])) . '" class="shopInfo" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
						echo '<div class="shopInfo"><span>';
						printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_IN_SHOPS_FROM_YOUR_HOME), $data['distance_to_home_prod'], $data['distance_to_home'], Yii::app()->user->view_distance);
					} else {					
						echo '<a href="' . Yii::app()->createUrl('products/search',array('ing_id'=>$data['ING_ID'])) . '" class="shopInfo" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
						echo '<div class="shopInfo"><span>';
						printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_NO_SHOPS_IN_RANGE_HOME), Yii::app()->user->view_distance);
					}
				}
				//printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_IN_SHOPS), $data['pro_count'], $data['sup_names']);
				
				echo '</span></div></a>';
			} else {
				echo '<a href="' . Yii::app()->createUrl('products/create',array('ing_id'=>$data['ING_ID'], 'newModel'=>time())) . '" class="shopInfo" title="' . $this->trans->INGREDIENTS_CREATE_PRODUCTS . '">';
				echo '<div class="shopInfo">' . $this->trans->INGREDIENTS_CREATE_PRODUCTS . '</div>';
				echo '</a>';
			}
		/*
		} else {
			if($data['pro_count'] != 0 || $data['sup_names'] != ''){
				echo '<div class="shopInfo"><span>';
				printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_IN_SHOPS), $data['pro_count'], $data['sup_names']);
				echo '</span></div></a>';
			} else {
				echo '<div class="shopInfo">' . $this->trans->INGREDIENTS_CREATE_PRODUCTS . '</div>';
			}
		*/
		}
		?>
		<div class="ingInfo">
		<?php
		if (!$this->isFancyAjaxRequest){
			echo '<a href="' . Yii::app()->createUrl('ingredients/update',array('id'=>$data['ING_ID'])) . '" class="button f-right">' . $this->trans->GENERAL_EDIT . '</a>';
		}
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_GROUP) .':</span> <span class="value">' . CHtml::encode($data['GRP_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		if ($data['SGR_DESC_'.Yii::app()->session['lang']] != ''){
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_SUBGROUP) .':</span> <span class="value">' . CHtml::encode($data['SGR_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		}
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STORABILITY) .':</span> <span class="value">' . CHtml::encode($data['STB_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_CONVENIENCE) .':</span> <span class="value">' . CHtml::encode($data['ICO_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STATE) .':</span> <span class="value">' . CHtml::encode($data['IST_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<?php
	/*
	
		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</strong>
		<?php echo CHtml::link(CHtml::encode($data->ING_ID), array('view', 'id'=>$data->ING_ID)); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</strong>
		<?php echo CHtml::encode($data->PRF_UID); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_CREATED')); ?>:</strong>
		<?php echo CHtml::encode($data->ING_CREATED); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_CHANGED')); ?>:</strong>
		<?php echo CHtml::encode($data->ING_CHANGED); ?>
		<br />
		
	<strong><?php echo CHtml::encode($data->getAttributeLabel('IST_ID')); ?>:</strong>
	<?php echo CHtml::encode($data->IST_ID); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ICO_ID')); ?>:</strong>
	<?php echo CHtml::encode($data->ICO_ID); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('STB_ID')); ?>:</strong>
	<?php echo CHtml::encode($data->STB_ID); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_DENSITY')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_DENSITY); ?>
	<br />
	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_FILENAME')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_IMG); ?><br />
	
	
	<!-- STL show image -->
	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')); ?><br />
	
	
	
	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_AUTH')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_IMG_AUTH); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_EN')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_NAME_EN); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_DE')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_NAME_DE); ?>
	<br />

	*/ ?>

</div>