<?php
$this->pageTitle=Yii::app()->name;
$preloadedInfoResetScript = "\r\n".'var glob = glob || {};'."\r\n".'glob.preloadedInfo = {};';
?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('site/getNext'); ?>"/>

<h1><?php printf($this->trans->TITLE_SITE_INDEX, CHtml::encode(Yii::app()->name)); ?></h1>

<div class="startpage">
	<div class="slot f-left">
		<input name="recipe" class="imgIndex" type="hidden" value="0" />
		<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
		<?php		
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe = {};';
			$index = 0;
			foreach($recipes as $recipe){
				if ($index == 0){?>
					<div class="item">
						<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID'], 'nosearch'=>'true')); ?>
						<div class="img_auth"><?php if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $recipe['REC_IMG_AUTH']; } ?></div>
					</div>
					<?php
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.idx' . $index . ' = {img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$recipe['REC_ID'])).'", auth:"'.$recipe['REC_IMG_AUTH'].'", name:"'.$recipe['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				} else {
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.idx' . $index . ' = {img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$recipe['REC_ID'])).'", auth:"'.$recipe['REC_IMG_AUTH'].'", name:"'.$recipe['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				}
				++$index;
			}
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.nextPreloadIndex = '.$index.';';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.prevPreloadIndex = -1;';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.prevPreloadCheck = -1;';
		?>
		<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('recipes/search'),
			'id'=>'recipes_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); ?>
		<div class="search">
			<?php echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>$this->trans->RECIPES_SEARCH_RECIPE)); ?>
			<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="slot f-right">
		<input name="product" class="imgIndex" type="hidden" value="0" />
		<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
		<?php
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product = {};';
			$index = 0;
			foreach($products as $product){
				if ($index == 0){?>
					<div class="item">
						<?php echo CHtml::link(CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$product['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$product['PRO_NAME_' . Yii::app()->session['lang']], 'title'=>$product['PRO_NAME_' . Yii::app()->session['lang']])), array('products/view', 'id'=>$product['PRO_ID'], 'nosearch'=>'true')); ?>
						<div class="img_auth"><?php if ($product['PRO_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $product['PRO_IMG_AUTH']; } ?></div>
					</div>
					<?php
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product.idx' . $index . ' = {img:"'.$this->createUrl('products/displaySavedImage', array('id'=>$product['PRO_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('products/view', array('id'=>$product['PRO_ID'])).'", auth:"'.$product['PRO_IMG_AUTH'].'", name:"'.$product['PRO_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				} else {
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product.idx' . $index . ' = {img:"'.$this->createUrl('products/displaySavedImage', array('id'=>$product['PRO_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('products/view', array('id'=>$product['PRO_ID'])).'", auth:"'.$product['PRO_IMG_AUTH'].'", name:"'.$product['PRO_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				}
				++$index;
			}
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product.nextPreloadIndex = '.$index.';';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product.prevPreloadIndex = -1;';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.product.prevPreloadCheck = -1;';
		?>
		<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('products/search'),
			'id'=>'products_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); ?>
		<div class="search">
			<?php echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>$this->trans->PRODUCTS_SEARCH_PRODUCT)); ?>
			<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="slot f-center">
		<input name="ingredient" class="imgIndex" type="hidden" value="0" />
		<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
		<?php		
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient = {};';
			$index = 0;
			foreach($ingredients as $ingredient){
				if ($index == 0){?>
					<div class="item">
						<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$ingredient['ING_ID'], 'nosearch'=>'true')); ?>
						<div class="img_auth"><?php if ($ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $ingredient['ING_IMG_AUTH']; } ?></div>
					</div>
					<?php
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient.idx' . $index . ' = {img:"'.$this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('ingredients/view', array('id'=>$ingredient['ING_ID'])).'", auth:"'.$ingredient['ING_IMG_AUTH'].'", name:"'.$ingredient['ING_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				} else {
					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient.idx' . $index . ' = {img:"'.$this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('ingredients/view', array('id'=>$ingredient['ING_ID'])).'", auth:"'.$ingredient['ING_IMG_AUTH'].'", name:"'.$ingredient['ING_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
				}
				++$index;
			}
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient.nextPreloadIndex = '.$index.';';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient.prevPreloadIndex = -1;';
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.ingredient.prevPreloadCheck = -1;';
		?>
		<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('ingredients/search'),
			'id'=>'ingredients_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); ?>
		<div class="search">
			<?php echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>$this->trans->INGREDIENTS_SEARCH_INGREDIENT)); ?>
			<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="clearfix"></div>
</div>
<?php echo '<script>' . $preloadedInfoResetScript . "\r\n".'</script>'; ?>