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
?>
<input type="hidden" id="getNextLink" value="<?php echo $this->createUrl('site/getNext'); ?>"/>

<h1><?php printf($this->trans->TITLE_SITE_INDEX, CHtml::encode(Yii::app()->name)); ?></h1>

<div class="startpage">
	<div class="leftTeaser">
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
		<br />
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
	<div class="centerTeaser">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl('recipes/search'),
			'id'=>'recipes_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); ?>
		<div class="search">
			<?php
			echo '<span>' . $this->trans->HOME_WHAT_WANT_TO_COOK . '</span><br />';
			//echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>$this->trans->RECIPES_TYPE_A_DISH));
			echo CHtml::hiddenField('query', '', array('id'=>'searchRecipe', 'data-placeholder'=>$this->trans->RECIPES_TYPE_A_DISH));
			
			$this->widget('ext.select2.ESelect2', array(
				'target' => '#searchRecipe',
				'config' => array (
					'multiple' => true,
					'minimumInputLength' => 1,
					'placeholder'=>$this->trans->RECIPES_TYPE_A_DISH,
					'ajax' => 'js:glob.select2.searchRecipeAjax',
					'formatResult' => 'js:glob.select2.searchRecipeFormatResult', // omitted for brevity, see the source of this page
					'formatSelection' => 'js:glob.select2.searchRecipeFormatSelection', // omitted for brevity, see the source of this page
					//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
					'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
					'escapeMarkup' => 'js:function (m) { return m; }', // we do not want to escape markup since we are displaying html in results
					'createSearchChoice' => 'js:glob.select2.createSearchChoice',
				)
			));
			
			echo '<br />';
			//echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH));
			echo CHtml::submitButton($this->trans->HOME_FIND_RECIPE, array('class'=>'button'));
			?>
		</div>
		<?php $this->endWidget(); ?>
		<br /><br />
		<?php $form=$this->beginWidget('CActiveForm', array(
			//'action'=>Yii::app()->createUrl('recipes/searchFridge'),
			'action'=>Yii::app()->createUrl('recipes/search'),
			'id'=>'fridge_form',
			'method'=>'post',
			'htmlOptions'=>array('class'=>'submitToUrl'),
		)); 
		//recipes/search?ing_id=10
		?>
		<div class="search">
			<?php
			echo '<span>' . $this->trans->HOME_WHAT_IN_FRIDGE . '</span><br />';
			//echo Functions::specialField('query', '', 'search', array('class'=>'search_query', 'placeholder'=>'Type an ingredient'));
			echo CHtml::hiddenField('ing_id', '', array('id'=>'searchFridge', 'data-placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT));
			
			$this->widget('ext.select2.ESelect2', array(
				'target' => '#searchFridge',
				'config' => array (
					'multiple' => true,
					'minimumInputLength' => 1,
					'placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT,
					'ajax' => 'js:glob.select2.searchIngredientAjax',
					'formatResult' => 'js:glob.select2.searchIngredientFormatResult', // omitted for brevity, see the source of this page
					'formatSelection' => 'js:glob.select2.searchIngredientFormatSelection', // omitted for brevity, see the source of this page
					//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
					'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
					'escapeMarkup' => 'js:function (m) { return m; }', // we do not want to escape markup since we are displaying html in results
					//'createSearchChoice' => 'js:glob.select2.createSearchChoice',
				)
			));
			
			echo '<br />';
			//echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH));
			echo CHtml::submitButton($this->trans->HOME_SHOW_WHAT_CAN_COOK, array('class'=>'button'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="rightTeaser">
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
		<br />
		<div class="teaser">
			<div class="title">
				<?php echo $this->trans->HOME_YOUR_RECIPE; ?>
			</div>
			<div class="recipePic">
			<?php echo CHtml::link($this->trans->HOME_CREATE_RECIPE, array('recipes/create')); ?>
			</div>
		</div>
	</div>
</div>