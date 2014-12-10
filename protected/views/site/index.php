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

$this->pageTitle=Yii::app()->name;
$preloadedInfoResetScript = "\r\n".'var glob = glob || {};'."\r\n".'glob.preloadedInfo = {};';
?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('site/getNext'); ?>"/>

<h1><?php printf($this->trans->TITLE_SITE_INDEX, CHtml::encode(Yii::app()->name)); ?></h1>

<div class="startpage">
	<div class="f-left">
		<div class="teaser">
			<div class="title">
				<?php echo $suggestedRecipes["top_left"][0]; ?>
			</div>
			<div class="recipePic">
				<?php
					$recipe = $suggestedRecipes["top_left"][1];
					if ($recipe != null && count($recipe)>0){
				?>
				<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID'], 'nosearch'=>'true')); ?>
				<div class="img_auth"><?php if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $recipe['REC_IMG_AUTH']; } ?></div>
				<?php } ?>
			</div>
		</div>
		
		<div class="teaser">
			<div class="title">
				<?php echo $suggestedRecipes["bottom_left"][0]; ?>
			</div>
			<div class="recipePic">
				<?php
					$recipe = $suggestedRecipes["bottom_left"][1];
					if ($recipe != null && count($recipe)>0){
				?>
				<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID'], 'nosearch'=>'true')); ?>
				<div class="img_auth"><?php if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $recipe['REC_IMG_AUTH']; } ?></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="f-right">
		<div class="teaser">
			<div class="title">
				<?php echo $suggestedRecipes["top_right"][0]; ?>
			</div>
			<div class="recipePic">
				<?php
					$recipe = $suggestedRecipes["top_right"][1];
					if ($recipe != null && count($recipe)>0){
				?>
				<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$recipe['REC_NAME_' . Yii::app()->session['lang']], 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID'], 'nosearch'=>'true')); ?>
				<div class="img_auth"><?php if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo $this->trans->GENERAL_COPYRITGHT_BY . ' ' . $recipe['REC_IMG_AUTH']; } ?></div>
				<?php } ?>
			</div>
		</div>
		
		<div class="teaser">
			<div class="title">
				Your recipe!
			</div>
			<div class="recipePic">
			<?php echo CHtml::link('Click to create your recpie', array('recipes/create')); ?>
			</div>
		</div>
	</div>
	<div class="f-center">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('recipes/search'),
			'id'=>'recipes_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); ?>
		<div class="search">
			<?php echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>'Type a dish here')); ?>
			<?php //echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
			<?php echo CHtml::submitButton('Find me a recipe!', array('class'=>'button')); ?>
		</div>
		<?php $this->endWidget(); ?>
		
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('recipes/searchFridge'),
			'id'=>'fridge_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); 
		//recipes/search?ing_id=10
		?>
		<div class="search">
			<?php echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>'Type an ingredient')); ?>
			<?php //echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
			<?php echo CHtml::submitButton('Show me what I can cook!', array('class'=>'button')); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="clearfix"></div>
</div>
<?php echo '<script>' . $preloadedInfoResetScript . "\r\n".'</script>'; ?>