<?php $this->pageTitle=Yii::app()->name; ?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('site/getNext'); ?>"/>

<h1><?php printf($this->trans->TITLE_SITE_INDEX, CHtml::encode(Yii::app()->name)); ?></h1>

<div class="startpage">
	<div class="slot f-left">
		<input name="recipe" class="imgIndex" type="hidden" value="0" />
		<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
		<div class="item">
			<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID'], 'nosearch'=>'true')); ?>
			<div class="img_auth"><?php if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $recipe['REC_IMG_AUTH']; } ?></div>
		</div>
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
		<div class="item">
			<?php echo CHtml::link(CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$product['PRO_ID'], 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$product['PRO_NAME_' . Yii::app()->session['lang']], 'title'=>$product['PRO_NAME_' . Yii::app()->session['lang']])), array('products/view', 'id'=>$product['PRO_ID'], 'nosearch'=>'true')); ?>
			<div class="img_auth"><?php if ($product['PRO_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $product['PRO_IMG_CR']; } ?></div>
		</div>
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
		<div class="item">
			<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']], 'title'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$ingredient['ING_ID'], 'nosearch'=>'true')); ?>
			<div class="img_auth"><?php if ($ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $ingredient['ING_IMG_AUTH']; } ?></div>
		</div>
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