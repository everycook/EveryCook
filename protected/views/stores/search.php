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
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Create Stores', 'url'=>array('create')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('stores/create',array('newModel'=>time()))),
	);
//}

$ingSearch = array(($this->isFancyAjaxRequest)?'stores/advanceChooseStores':'stores/advanceSearch');
if (isset(Yii::app()->session['Stores']) && isset(Yii::app()->session['Stores']['time'])){
	$ingSearch=array_merge($ingSearch,array('newSearch'=>Yii::app()->session['Stores']['time']));
}

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('stores/chooseStores'); ?>"/>
	<?php
}
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'stores_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, $ingSearch, array('class'=>'button', 'id'=>'advanceSearch')); ?><br>
	</div>
	
	<div class="clearfix"></div>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'storesResult',
)); ?>

<?php $this->endWidget(); ?>
</div>