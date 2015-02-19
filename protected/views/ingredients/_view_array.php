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
$fancyLinkClass = '';
if($this->isFancyAjaxRequest){
	$fancyLinkClass = ' fancyLink reopenCurrentFancyOnClose';
}
?>
<div class="resultArea">
	<div class="list_img">
	<?php
	if (!$this->isFancySelect){
		echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), $data['ING_NAME_' . Yii::app()->session['lang']], array('class'=>'ingredient', 'title'=>$data['ING_NAME_' . Yii::app()->session['lang']])), array('view', 'id'=>$data['ING_ID']), array('class'=>$fancyLinkClass));
	} else {
		echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), $data['ING_NAME_' . Yii::app()->session['lang']], array('class'=>'ingredient', 'title'=>$data['ING_NAME_' . Yii::app()->session['lang']]));
	}
	?>
	<div class="img_auth"><?php if ($data['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $data['ING_IMG_AUTH']; } ?></div>
	</div>
	
	<?php
	if ($this->isFancySelect){
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
		echo CHtml::link($this->trans->INGREDIENTS_MORE_DETAILS, array('ingredients/view', 'id'=>$data['ING_ID'], 'searchType'=>$this->route), array('class'=>'f-right button fancyButton moreInfo reopenCurrentFancyOnClose'));
	} else {
		?>
		<div class="options">
			<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['ING_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS)); ?>
			<?php //echo CHtml::link('&nbsp;', array('recipes/search', 'ing_id'=>$data['ING_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->INGREDIENTS_COOK_WITH)); ?>
			<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['ING_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING)); ?>
		</div>
		<?php
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancySelect){
				echo $data['ING_NAME_' . Yii::app()->session['lang']];
			} else {
				echo CHtml::link(CHtml::encode($data['ING_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['ING_ID']), array('class'=>$fancyLinkClass));
			}
			?>&nbsp;
		</div>
		<?php
		if (!$this->isFancySelect){
			echo '<a href="' . Yii::app()->createUrl('nutrientData/view',array('id'=>$data['NUT_ID'], 'ing_id'=>$data['ING_ID'])) . '" class="nutrientInfo'.$fancyLinkClass.'" title="' . $this->trans->INGREDIENTS_VIEW_FOOD . '">';
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
		<?php if (!$this->isFancySelect){
			echo '</a>';
			/*
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
			*/
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
		if (!$this->isFancySelect){
			echo '<a href="' . Yii::app()->createUrl('ingredients/update',array('id'=>$data['ING_ID'])) . '" class="button f-right">' . $this->trans->GENERAL_EDIT . '</a>';
		}
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_GROUP) .':</span> <span class="value">' . CHtml::encode($data['GRP_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		if ($data['SGR_DESC_'.Yii::app()->session['lang']] != ''){
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_SUBGROUP) .':</span> <span class="value">' . CHtml::encode($data['SGR_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		}
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STORABILITY) .':</span> <span class="value">' . CHtml::encode($data['STB_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
		if (isset($data['ORI_ID']) && $data['ORI_ID'] > 0 && $data['ORI_ID'] != IngredientsController::ORIGIN_IGNORE_ID){
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_ORIGINS) .':</span> <span class="value">' . CHtml::encode($data['ORI_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		}
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_CONVENIENCE) .':</span> <span class="value">' . CHtml::encode($data['ICO_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STATE) .':</span> <span class="value">' . CHtml::encode($data['IST_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		
		if (isset($data['CND_ID']) && $data['CND_ID'] > 0){
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_CONDITIONS) .':</span> <span class="value">' . CHtml::encode($data['CND_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
		}
		echo '<br>';
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STORAGE_TEMP) .':</span> <span class="value">';
		if (isset($data['ING_MIN_TEMP'])){
			if (isset($data['ING_MAX_TEMP'])){
				echo sprintf($this->trans->INGREDIENTS_TEMP_RANGE, $data['ING_MIN_TEMP'], $data['ING_MAX_TEMP']);
			} else {
				echo sprintf($this->trans->INGREDIENTS_TEMP_ABOVE, $data['ING_MIN_TEMP']);
			}
		} else if (isset($data['ING_MAX_TEMP'])){
			echo sprintf($this->trans->INGREDIENTS_TEMP_BELOW, $data['ING_MAX_TEMP']);
		} else {
			echo CHtml::encode($data['TGR_DESC_'.Yii::app()->session['lang']]);
		}
		echo "</span></span>\n";
		echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STORAGE_IN_FREEZER) .':</span> <span class="value">' . (($data['ING_FREEZER'] == 'Y')? $this->trans->GENERAL_YES: $this->trans->GENERAL_NO) ."</span></span>\n";
		
		?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<?php
	/*
	
		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->ING_ID), array('view', 'id'=>$data->ING_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</b>
		<?php echo CHtml::encode($data->PRF_UID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_ID')); ?>:</b>
		<?php echo CHtml::encode($data->NUT_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('GRP_ID')); ?>:</b>
		<?php echo CHtml::encode($data->GRP_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('SGR_ID')); ?>:</b>
		<?php echo CHtml::encode($data->SGR_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('IST_ID')); ?>:</b>
		<?php echo CHtml::encode($data->IST_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_ID')); ?>:</b>
		<?php echo CHtml::encode($data->ICO_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STB_ID')); ?>:</b>
		<?php echo CHtml::encode($data->STB_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_DENSITY')); ?>:</b>
		<?php echo CHtml::encode($data->ING_DENSITY); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_FILENAME')); ?>:</b>
		<?php echo CHtml::encode($data->ING_IMG_FILENAME); ?>
		<br />
	
	<!-- STL show image -->
	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')); ?><br />
	
	
	
		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_AUTH')); ?>:</b>
		<?php echo CHtml::encode($data->ING_IMG_AUTH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_ETAG')); ?>:</b>
		<?php echo CHtml::encode($data->ING_IMG_ETAG); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_HAS_ALLERGY_INFO')); ?>:</b>
		<?php echo CHtml::encode($data->ING_HAS_ALLERGY_INFO); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NEED_PEELING')); ?>:</b>
		<?php echo CHtml::encode($data->ING_NEED_PEELING); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NEED_WASH')); ?>:</b>
		<?php echo CHtml::encode($data->ING_NEED_WASH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_SCALE_PRECISION')); ?>:</b>
		<?php echo CHtml::encode($data->ING_SCALE_PRECISION); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_APPROVED')); ?>:</b>
		<?php echo CHtml::encode($data->ING_APPROVED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_WIKI_LINK')); ?>:</b>
		<?php echo CHtml::encode($data->ING_WIKI_LINK); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_WEIGHT_SMALL')); ?>:</b>
		<?php echo CHtml::encode($data->ING_WEIGHT_SMALL); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_WEIGHT_BIG')); ?>:</b>
		<?php echo CHtml::encode($data->ING_WEIGHT_BIG); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_SYNONYM_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->ING_SYNONYM_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_SYNONYM_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->ING_SYNONYM_DE_CH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->ING_NAME_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->ING_NAME_DE_CH); ?>
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