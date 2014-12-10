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
	'Recipes',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('recipes/create',array('newModel'=>time()))),
	);
//}
if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl($this->route); ?>"/>
	<?php
}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
	'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="clearfix"></div>

	<div class="row">
		<?php echo $form->label($model,'REC_ID'); ?>
		<?php echo $form->textField($model,'REC_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_IMG_FILENAME'); ?>
		<?php echo $form->textField($model,'REC_IMG_FILENAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'REC_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RET_ID'); ?>
		<?php echo $form->textField($model,'RET_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_HAS_ALLERGY_INFO'); ?>
		<?php echo $form->textField($model,'REC_HAS_ALLERGY_INFO',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_SUMMARY'); ?>
		<?php echo $form->textField($model,'REC_SUMMARY',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_APPROVED'); ?>
		<?php echo $form->textField($model,'REC_APPROVED',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_SERVING_COUNT'); ?>
		<?php echo $form->textField($model,'REC_SERVING_COUNT'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_WIKI_LINK'); ?>
		<?php echo $form->textField($model,'REC_WIKI_LINK',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_IS_PRIVATE'); ?>
		<?php echo $form->textField($model,'REC_IS_PRIVATE',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_COMPLEXITY'); ?>
		<?php echo $form->textField($model,'REC_COMPLEXITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CUT_ID'); ?>
		<?php echo $form->textField($model,'CUT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CST_ID'); ?>
		<?php echo $form->textField($model,'CST_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CUSINE_GPS_LAT'); ?>
		<?php echo $form->textField($model,'REC_CUSINE_GPS_LAT'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CUSINE_GPS_LNG'); ?>
		<?php echo $form->textField($model,'REC_CUSINE_GPS_LNG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TOOLS'); ?>
		<?php echo $form->textField($model,'REC_TOOLS',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_SYNONYM_EN_GB'); ?>
		<?php echo $form->textField($model,'REC_SYNONYM_EN_GB',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_SYNONYM_DE_CH'); ?>
		<?php echo $form->textField($model,'REC_SYNONYM_DE_CH',array('size'=>60,'maxlength'=>200)); ?>
	</div>
	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'id'=>'recipesResult',
)); ?>

<?php $this->endWidget(); ?>
</div>