<?php
$this->breadcrumbs=array(
	'Ingredients'=>array('index'),
	$model->ING_ID,
);

$this->menu=array(
	array('label'=>'List Ingredients', 'url'=>array('index')),
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'Update Ingredients', 'url'=>array('update', 'id'=>$model->ING_ID)),
	array('label'=>'Delete Ingredients', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ING_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('ingredients/update',$this->getActionParams())),
);
?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('ingredients/getNext', array('ing_id'=>$model->ING_ID)); ?>"/>

<div class="detailView" id="ingredients">
<?php
	if (isset(Yii::app()->session['Ingredients'])){
		if (isset(Yii::app()->session['Ingredients']['model'])){
			$back_url = array('ingredients/advanceSearch');
		} else {
			$back_url = array('ingredients/search');
		}
		echo CHtml::link(CHtml::encode($this->trans->INGREDIENTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); 
	}
	$ingredientName = $model->__get('ING_NAME_'.Yii::app()->session['lang']);
	?>
	<div class="clearfix"></div>
	
	<div class="f-left">
		<h1><?php echo $ingredientName; ?></h1>
		<div class="detail_img f-left">
			<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ING_ID, 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$model->__get('ING_NAME_' . Yii::app()->session['lang']), 'title'=>$model->__get('ING_NAME_' . Yii::app()->session['lang']))); ?>
			<div class="img_auth"><?php if ($model->ING_IMG_ETAG == '') { echo '&nbsp;'; } else {echo '© by ' . $model->ING_IMG_AUTH; } ?></div>
		</div>
		
		<div class="ingInfo f-left">
			<?php
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_GROUP) .':</span> <span class="value">' . CHtml::encode($model->groupNames['GRP_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
			if ($model->subgroupNames['SGR_DESC_'.Yii::app()->session['lang']] != ''){
				echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_SUBGROUP) .':</span> <span class="value">' . CHtml::encode($model->subgroupNames['SGR_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
			}
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STORABILITY) .':</span> <span class="value">' . CHtml::encode($model->storability['STB_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_CONVENIENCE) .':</span> <span class="value">' . CHtml::encode($model->ingredientConveniences['ICO_DESC_'.Yii::app()->session['lang']]) ."</span></span><br>\n";
			echo '<span><span class="title">' . CHtml::encode($this->trans->INGREDIENTS_STATE) .':</span> <span class="value">' . CHtml::encode($model->ingredientStates['IST_DESC_'.Yii::app()->session['lang']]) ."</span></span>\n";
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	
	<div class="options">
		<?php
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model['ING_ID']), array('class'=>'delicious noAjax backpic f-left', 'title'=>$this->trans->GENERAL_DELICIOUS));
			echo CHtml::link('&nbsp;', array('recipes/search', 'ing_id'=>$model['ING_ID']), array('class'=>'cookwith backpic f-right', 'title'=>$this->trans->INGREDIENTS_COOK_WITH));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model['ING_ID']), array('class'=>'disgusting noAjax backpic f-center','title'=>$this->trans->GENERAL_DISGUSTING));
		?>
		<div class="recipes otherItems">
			<?php
			echo '<div class="otherItemsTitle">'.sprintf($this->trans->INGREDIENTS_MATCHING_RECIPES, $ingredientName).'</div>';
			if ($otherItemsAmount['recipes'] == 0){
				echo '<span class="noItems">'.$this->trans->INGREDIENTS_NO_MATCHING_RECIPES .'</span>';
			} else {
				if ($otherItemsAmount['recipes'] > IngredientsController::RECIPES_AMOUNT){?>
					<input name="recipe" class="imgIndex" type="hidden" value="0" />
					<input name="amount" class="imgIndexAmount" type="hidden" value="<?php echo IngredientsController::RECIPES_AMOUNT; ?>" />
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
		<div class="products otherItems">
			<?php
			echo '<div class="otherItemsTitle">'.sprintf($this->trans->INGREDIENTS_MATCHING_PRODUCTS, $ingredientName).'</div>';
			if ($otherItemsAmount['products'] == 0){
				echo '<span class="noItems">'.$this->trans->INGREDIENTS_NO_MATCHING_PRODUCTS .'</span>';
			} else {
				if ($otherItemsAmount['products'] > IngredientsController::PRODUCTS_AMOUNT){ ?>
				<input name="product" class="imgIndex" type="hidden" value="0" />
				<input name="amount" class="imgIndexAmount" type="hidden" value="<?php echo IngredientsController::PRODUCTS_AMOUNT; ?>" />
				<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
				<?php
				}
				foreach($products as $product){
					echo '<div class="item">';
						echo CHtml::link($product['PRO_NAME_' . Yii::app()->session['lang']], array('products/view', 'id'=>$product['PRO_ID']), array('class'=>'title'));
						echo '<div class="small_img">';
							echo CHtml::link(CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$product['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$product['PRO_NAME_' . Yii::app()->session['lang']], 'title'=>$product['PRO_NAME_' . Yii::app()->session['lang']])), array('products/view', 'id'=>$product['PRO_ID']));
							echo '<div class="img_auth">';
							if ($product['PRO_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $product['PRO_IMG_CR']; }
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
				if ($otherItemsAmount['products'] > 2){
					echo '<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>';
				}
			}
			?>
		</div>
	</div>
	
	<?php

	if ($nutrientData != null){
		$fields = array();
		$fields[0] = array(
			'NUT_WATER',
			'NUT_ENERG',
			'NUT_PROT',
			'NUT_LIPID',
			'NUT_ASH',
			'NUT_CARB',
			'NUT_FIBER',
			'NUT_SUGAR',
		);
		$fields[1] = array(
			'NUT_FA_SAT',
			'NUT_FA_MONO',
			'NUT_FA_POLY',
			'NUT_CHOLEST',
			'NUT_REFUSE',
		);
		$fields[3] = array(
			'NUT_VIT_C',
			'NUT_THIAM',
			'NUT_RIBOF',
			'NUT_NIAC',
			'NUT_PANTO',
			'NUT_VIT_B6',
			'NUT_FOLAT_TOT',
			'NUT_FOLIC',
			'NUT_FOLATE_FD',
			'NUT_FOLATE_DFE',
			'NUT_CHOLINE',
			'NUT_VIT_B12',
			'NUT_VIT_A_IU',
			'NUT_VIT_A_RAE',
			'NUT_RETINOL',
			'NUT_ALPHA_CAROT',
			'NUT_BETA_CAROT',
			'NUT_BETA_CRYPT',
			'NUT_LYCOP',
			'NUT_LUT_ZEA',
			'NUT_VIT_E',
			'NUT_VIT_D',
			'NUT_VIT_D_IU',
			'NUT_VIT_K',
		);
		$fields[2] = array(
			'NUT_CALC',
			'NUT_IRON',
			'NUT_MAGN',
			'NUT_PHOS',
			'NUT_POTAS',
			'NUT_SODIUM',
			'NUT_ZINC',
			'NUT_COPP',
			'NUT_MANG',
			'NUT_SELEN',
		);
		
		
		$units = array();
		$units[0] = array(
			'%',
			'kcal/100 g',
			'%',
			'%',
			'%',
			'%',
			'%',
			'%',
		);
		$units[1] = array(
			'%',
			'%',
			'%',
			'mg/100 g',
			'%',
		);
		$units[3] = array(
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'μg/100 g',
			'μ/100 g',
			'μ/100 g',
			'μ dietary folate equivalents/100 g',
			'mg/100 g',
			'μ/100 g',
			'IU/100 g',
			'μ retinol activity equivalents/100g',
			'μ/100 g',
			'μ/100 g',
			'μ/100 g',
			'μ/100 g',
			'μ/100 g',
			'μ/100 g',
			'alpha-tocopherol',
			'μ/100 g',
			'IU/100 g',
			'phylloquinone',
		);
		$units[2] = array(
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'mg/100 g',
			'μg/100 g',
		);
		
		echo '<div class="nutrientTable">';
		echo '<div class="f-left">';
		for($group=0; $group<count($fields); $group++){
			if ($group == 3){
				echo '</div>';
				echo '<div class="f-left">';
			}
			echo '<div class="nutrientDataGroup">';
				for($field=0; $field<count($fields[$group]); $field++){
					$nut_field = $fields[$group][$field];
					echo '<div class="nutrient_row' . (($field == count($fields[$group])-1)?' last':'') . '">';
					echo '<span class="name">' . CHtml::encode($this->trans->__get('FIELD_'.$nut_field)) . '</span>';
					echo '<span class="value">'; printf('%1.2f',$nutrientData->$nut_field); echo '</span>';
					echo '<span class="unit">' . $units[$group][$field] . '</span>';
					echo '</div>';
				}
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
	}
?>
	
	<div class="clearfix"></div>
</div>