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

$this->breadcrumbs=array(
	'Ingredients',
);

$this->menu=array(
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('ingredients/create',array('newModel'=>time()))),
		);
	//}
}

$ingSearch = array(($this->isFancyAjaxRequest)?'ingredients/advanceChooseIngredient':'ingredients/advanceSearch');
if (isset(Yii::app()->session['Ingredients']) && isset(Yii::app()->session['Ingredients']['time'])){
	$ingSearch=array_merge($ingSearch,array('newSearch'=>Yii::app()->session['Ingredients']['time']));
}
$urlParams = array();
if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('ingredients/chooseIngredient'); ?>"/>
	<?php
	$urlParams['fancyAjax'] = '1';
}
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route, $urlParams),
		'id'=>'ingredients_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php //if ($model2->query == ''){
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query', 'autofocus'=>'autofocus'));
		/*} else {
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query'));
		}*/ ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<?php /* ?>
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, $ingSearch, array('class'=>'button', 'id'=>'advanceSearch')); ?><br>
	</div>
	<?php */ ?>
	
	<div class="clearfix"></div>

	<?php if($this->isRecipeIngredientSelect){
		echo '<div>' . $this->trans->INGREDIENTS_SELECT_FROM_RECIPE . '</div>';
	}
	if($isSuggestion){
		echo '<div class="suggestions">' . $this->trans->INGREDIENTS_SUGGESTIONS . '</div>';
	}
	?>

<?php
$params = array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'ingredientsResult',
);
if($isSuggestion){
	$params['template'] = "{items}";
}
$this->widget('AjaxPagingListView', $params); ?>
<?php /*if (!$this->isFancyAjaxRequest){ ?>
<script type="text/javascript">
	loadScript(false, "CH", false, true, false, false);
</script>
<?php }*/ ?>
<?php $this->endWidget(); ?>

</div>