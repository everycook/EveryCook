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
	'Action Types',
);

$this->menu=array(
	array('label'=>'Create ActionTypes', 'url'=>array('create')),
	array('label'=>'Manage ActionTypes', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}

$advanceSearch = array(($this->isFancyAjaxRequest)?'advanceChooseIngredient':'advanceSearch');
if (isset(Yii::app()->session['ActionTypes']) && isset(Yii::app()->session['ActionTypes']['time'])){
	$advanceSearch=array_merge($advanceSearch,array('newSearch'=>Yii::app()->session['ActionTypes']['time']));
}

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('advanceChooseActionTypes'); ?>"/>
	<?php
}
?>

<div id="action-typesAdvanceSearch">
<?php  $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'post',
		'id'=>'action-types_form',
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
	<div class="f-right">
		<?php  echo CHtml::link($this->trans->GENERAL_SIMPLE_SEARCH, $simpleSearch, array('class'=>'button', 'id'=>'simpleSearch')); ?><br>
	</div>
	<div class="f-center">
		<?php  echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('create','newModel'=>time()), array('class'=>'button', 'id'=>'create')); ?><br>
	</div>
	
	<div class="clearfix"></div>
	
<?php
/*	
	
	<div class="row">
		<?php echo $form->label($model,'ATY_ID'); ?>
		<?php echo $form->textField($model,'ATY_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ATY_DESC_EN_GB'); ?>
		<?php echo $form->textField($model,'ATY_DESC_EN_GB',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ATY_DESC_DE_CH'); ?>
		<?php echo $form->textField($model,'ATY_DESC_DE_CH',array('size'=>60,'maxlength'=>100)); ?>
	</div>

*/
?>

<br />

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'action-typesResult',
)); ?>

<?php $this->endWidget(); ?>

</div>