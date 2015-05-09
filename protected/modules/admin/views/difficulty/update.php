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
	'Difficulties'=>array('index'),
	$model->DIF_ID=>array('view','id'=>$model->DIF_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Difficulty', 'url'=>array('index')),
	array('label'=>'Create Difficulty', 'url'=>array('create')),
	array('label'=>'View Difficulty', 'url'=>array('view', 'id'=>$model->DIF_ID)),
	array('label'=>'Manage Difficulty', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_DIFFICULTY_UPDATE, $model->DIF_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>