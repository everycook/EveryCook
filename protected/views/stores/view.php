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
	'Stores'=>array('index'),
	$model->STO_ID,
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'Update Stores', 'url'=>array('update', 'id'=>$model->STO_ID)),
	array('label'=>'Delete Stores', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_STORES_VIEW, $model->STO_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STO_ID',
		'STO_NAME',
		'STO_STREET',
		'STO_HOUSE_NO',
		'STO_ZIP',
		'STO_CITY',
		'STO_COUNTRY',
		'STO_STATE',
		'STY_ID',
		'STO_GPS_LAT',
		'STO_GPS_LNG',
		'STO_GPS_POINT',
		'STO_PHONE',
		//'STO_IMG_FILENAME',
		'STO_IMG_AUTH',
		'STO_IMG_ETAG',
		'SUP_ID',
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',
	),
)); ?>
