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
	'Step Types',
);

$this->menu=array(
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}

$advanceSearch = array(($this->isFancyAjaxRequest)?'advanceChooseIngredient':'advanceSearch');
if (isset(Yii::app()->session['StepTypes']) && isset(Yii::app()->session['StepTypes']['time'])){
	$advanceSearch=array_merge($advanceSearch,array('newSearch'=>Yii::app()->session['StepTypes']['time']));
}

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('advanceChooseStepTypes'); ?>"/>
	<?php
}
?>

<div id="step-typesAdvanceSearch">
<?php  $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'post',
		'id'=>'step-types_form',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php  if ($model2->query == ''){
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query', 'autofocus'=>'autofocus'));
		} else {
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query'));
		} ?>
		<?php  echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="clearfix"></div>
	
<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_GROUP, $model, 'GRP_ID', $groupNames, Functions::CHECK_BOX_LIST, 'groupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, Functions::CHECK_BOX_LIST, 'subgroupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STATE, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0);
	//echo searchCriteriaInput($this->trans->INGREDIENTS_NUTRIENT, $model, 'NUT_ID', $nutrientData, Functions::DROP_DOWN_LIST, 'nutrientData', $htmlOptions_type0);
	
	/* //example FancyCoose
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->label($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT, 'style'=>'vertical-align: middle;')); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CHOOSE, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect buttonSmall')) ?>
	</div>
	
<?php
*/

/*	
	
	<div class="row">
		<?php echo $form->label($model,'STT_ID'); ?>
		<?php echo $form->textField($model,'STT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_DEFAULT'); ?>
		<?php echo $form->textField($model,'STT_DEFAULT',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_REQUIRED'); ?>
		<?php echo $form->textField($model,'STT_REQUIRED',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_DESC_EN_GB'); ?>
		<?php echo $form->textArea($model,'STT_DESC_EN_GB',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STT_DESC_DE_CH'); ?>
		<?php echo $form->textArea($model,'STT_DESC_DE_CH',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CREATED_BY'); ?>
		<?php echo $form->textField($model,'CREATED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CREATED_ON'); ?>
		<?php echo $form->textField($model,'CREATED_ON'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CHANGED_BY'); ?>
		<?php echo $form->textField($model,'CHANGED_BY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CHANGED_ON'); ?>
		<?php echo $form->textField($model,'CHANGED_ON'); ?>
	</div>

*/
?>

<br />

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'step-typesResult',
)); ?>

<?php $this->endWidget(); ?>

</div>