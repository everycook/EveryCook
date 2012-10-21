<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->PRO_ID,
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->PRO_ID)),
	array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PRO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('products/update',$this->getActionParams())),
);
?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('products/getNext', array('ing_id'=>$model->ING_ID)); ?>"/>
<input type="hidden" id="ProductStoreLocationsLink" value="<?php echo $this->createUrl('stores/getStoresInRangeWithProduct'); ?>"/>

<input type="hidden" id="centerGPSYou" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][0] . ',' . Yii::app()->session['current_gps'][1];} ?>"/>
<input type="hidden" id="centerGPSHome" value="<?php if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[0])){echo Yii::app()->user->home_gps[0] . ',' . Yii::app()->user->home_gps[1];} ?>"/>
<input type="hidden" id="viewDistance" value="<?php echo Yii::app()->user->view_distance; ?>"/>
<input type="hidden" class="productId selectedProduct" value="<?php echo $model['PRO_ID']; ?>"/>

<div class="detailView" id="products">
	<?php
	if (isset(Yii::app()->session['Products']) || isset(Yii::app()->session['Ingredients'])){
		echo '<div class="f-center">';
		if (isset(Yii::app()->session['Products'])){
			if (isset(Yii::app()->session['Products']['model'])){
				$back_url = array('products/advanceSearch');
			} else {
				$back_url = array('products/search');
			}
			echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_PRODUCTS), $back_url, array('class'=>'button')); 
		}
		if (isset(Yii::app()->session['Ingredients'])){
			if (isset(Yii::app()->session['Ingredients']['model'])){
				$back_url = array('ingredients/advanceSearch');
			} else {
				$back_url = array('ingredients/search');
			}
			echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button')); 
		}
		echo '</div>';
	}
	$productName = $model->__get('PRO_NAME_'.Yii::app()->session['lang']);
	?>
	<div class="clearfix"></div>
	<div id="map_canvas" style="height:300px; width:300px; display:none;"></div>
	
	<div>
		<div class="options">
			<?php
				echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model['PRO_ID']), array('class'=>'delicious noAjax backpic f-left', 'title'=>$this->trans->GENERAL_DELICIOUS));
				echo CHtml::link('&nbsp;', array('recipes/search', 'ing_id'=>$model->ingredient['ING_ID']), array('class'=>'cookwith backpic f-right', 'title'=>$this->trans->INGREDIENTS_COOK_WITH));
				echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model['PRO_ID']), array('class'=>'disgusting noAjax backpic f-center','title'=>$this->trans->GENERAL_DISGUSTING));
			?>
			<div class="recipes otherItems">
				<?php
				echo '<div class="otherItemsTitle">'.sprintf($this->trans->INGREDIENTS_MATCHING_RECIPES, $productName).'</div>';
				if ($otherItemsAmount['recipes'] == 0){
					echo '<span class="noItems">'.$this->trans->INGREDIENTS_NO_MATCHING_RECIPES .'</span>';
				} else {
					if ($otherItemsAmount['recipes'] > ProductsController::RECIPES_AMOUNT){?>
						<input name="recipe" class="imgIndex" type="hidden" value="0" />
						<input name="amount" class="imgIndexAmount" type="hidden" value="<?php echo ProductsController::RECIPES_AMOUNT; ?>" />
						<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
					<?php
					}
					foreach($recipes as $recipe){
						echo '<div class="item">';
							echo CHtml::link($recipe['REC_NAME_' . Yii::app()->session['lang']], array('recipes/view', 'id'=>$recipe['REC_ID']), array('class'=>'title'));
							echo '<div class="small_img">';
								echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID']));
								echo '<div class="img_auth">';
								if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $recipe['REC_IMG_AUTH']; }
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					if ($otherItemsAmount['recipes'] > 2){
						echo '<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>';
					}
				}
				?>
			</div>
			<div class="ingredients otherItems">
				<?php
				echo '<div class="otherItemsTitle">'.sprintf($this->trans->PRODUCTS_MATCHING_INGREDIENT, $productName).'</div>';
				
				echo '<div class="item">';
					echo CHtml::link($model->ingredient['ING_NAME_' . Yii::app()->session['lang']], array('ingredients/view', 'id'=>$model->ingredient['ING_ID']), array('class'=>'title'));
					echo '<div class="small_img">';
						echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ingredient['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$model->ingredient['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$model->ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$model->ingredient['ING_ID']));
						echo '<div class="img_auth">';
						if ($model->ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $model->ingredient['ING_IMG_AUTH']; }
						echo '</div>';
					echo '</div>';
				echo '</div>';
				?>
			</div>
		</div>
		
		<h1><?php echo $productName; ?></h1>
		<div class="detail_img f-left">
			<?php echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$model->PRO_ID, 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$model->__get('PRO_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('PRO_NAME_' . Yii::app()->session['lang']))); ?>
			<div class="img_auth"><?php if ($model->PRO_IMG_ETAG == '') { echo '&nbsp;'; } else {echo '© by ' . $model->PRO_IMG_CR; } ?></div>
		</div>
		
		<div class="proInfo">
		<?
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
		?>
		</div>
		<div class="clearfix"></div>
	</div>
	<script type="text/javascript">
		loadScript(false, "CH", false, true, false, false);
	</script>
</div>