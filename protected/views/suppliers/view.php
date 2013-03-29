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
	'Suppliers'=>array('index'),
	$model->SUP_ID,
);

$this->menu=array(
	array('label'=>'List Suppliers', 'url'=>array('index')),
	array('label'=>'Create Suppliers', 'url'=>array('create')),
	array('label'=>'Update Suppliers', 'url'=>array('update', 'id'=>$model->SUP_ID)),
	array('label'=>'Delete Suppliers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SUP_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Suppliers', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_SUPPLIERS_VIEW, $model->SUP_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'SUP_ID',
		'SUP_NAME',
	),
)); ?>
