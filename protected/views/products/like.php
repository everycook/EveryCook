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
	'Products',
);

$this->menu=array(
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
?>

<div>
	<input type="hidden" id="ProductStoreLocationsLink" value="<?php echo $this->createUrl('stores/getStoresInRangeWithProduct'); ?>"/>
	
	<input type="hidden" id="centerGPSYou" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][0] . ',' . Yii::app()->session['current_gps'][1];} ?>"/>
	<input type="hidden" id="centerGPSHome" value="<?php if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[0])){echo Yii::app()->user->home_gps[0] . ',' . Yii::app()->user->home_gps[1];} ?>"/>
	<input type="hidden" id="viewDistance" value="<?php echo Yii::app()->user->view_distance; ?>"/>
	
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'products_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
<div id="map_canvas" style="height:300px; width:300px; display:none;"></div>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'productsResult',
)); ?>

<script type="text/javascript">
	loadScript(false, "CH", false, true, false, false);
</script>
<?php $this->endWidget(); ?>

</div>
