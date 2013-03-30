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
	'Stores',
);

$this->menu=array(
	array('label'=>'Create stores', 'url'=>array('create')),
	array('label'=>'Manage stores', 'url'=>array('admin')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('stores/create',array('newModel'=>time()))),
	);
//}

if ($this->isFancyAjaxRequest){
	?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('stores/advanceChooseStores'); ?>"/>
	<?php
}
?>
<div id="storesAdvanceSearch">
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'post',
		'id'=>'stores_form',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="clearfix"></div>
	
<?php 
	/*
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->STORES_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::searchCriteriaInput($this->trans->stores_GROUP, $model, 'GRP_ID', $groupNames, Functions::CHECK_BOX_LIST, 'groupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->stores_SUBGROUP, $model, 'SGR_ID', $subgroupNames, Functions::CHECK_BOX_LIST, 'subgroupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->stores_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->stores_CONVENIENCE, $model, 'ICO_ID', $storesConveniences, Functions::DROP_DOWN_LIST, 'storesConveniences', $htmlOptions_type0);
	echo Functions::searchCriteriaInput($this->trans->stores_STATE, $model, 'IST_ID', $storestates, Functions::DROP_DOWN_LIST, 'storestates', $htmlOptions_type0);
	//echo searchCriteriaInput($this->trans->stores_NUTRIENT, $model, 'NUT_ID', $nutrientData, Functions::DROP_DOWN_LIST, 'nutrientData', $htmlOptions_type0);
	*/
	/*
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->label($model,'NUT_ID',array('label'=>$this->trans->stores_NUTRIENT)); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CHOOSE, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect')) ?>
	</div>
	
<?php */ /*
	
	<div class="row">
		<?php echo $form->label($model,'STO_ID'); ?>
		<?php echo $form->textField($model,'STO_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_NAME'); ?>
		<?php echo $form->textField($model,'STO_NAME',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_STREET'); ?>
		<?php echo $form->textField($model,'STO_STREET',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_HOUSE_NO'); ?>
		<?php echo $form->textField($model,'STO_HOUSE_NO',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_ZIP'); ?>
		<?php echo $form->textField($model,'STO_ZIP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_CITY'); ?>
		<?php echo $form->textField($model,'STO_CITY',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_COUNTRY'); ?>
		<?php echo $form->textField($model,'STO_COUNTRY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_STATE'); ?>
		<?php echo $form->textField($model,'STO_STATE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STY_ID'); ?>
		<?php echo $form->textField($model,'STY_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_GPS'); ?>
		<?php echo $form->textField($model,'STO_GPS',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_PHONE'); ?>
		<?php echo $form->textField($model,'STO_PHONE',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_IMG_FILENAME'); ?>
		<?php echo $form->textField($model,'STO_IMG_FILENAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SUP_ID'); ?>
		<?php echo $form->textField($model,'SUP_ID'); ?>
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
	
	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->GENERAL_SEARCH); ?>
	</div>
*/ ?>

<br />

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'storesResult',
	/*'ajaxUpdate'=>'storesAdvanceSearch',*/
)); ?>

<?php $this->endWidget(); ?>
</div>