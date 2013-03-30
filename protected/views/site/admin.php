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

$this->menu=array(
	array('label'=>'ecology', 'url'=>array('ecology/index')),
	array('label'=>'ethicalCriteria', 'url'=>array('ethicalCriteria/index')),
	array('label'=>'ingredientConveniences', 'url'=>array('ingredientConveniences/index')),
	array('label'=>'ingredientStates', 'url'=>array('ingredientStates/index')),
	array('label'=>'nutrientData', 'url'=>array('nutrientData/index')),
	array('label'=>'producers', 'url'=>array('producers/index')),
	array('label'=>'profiles', 'url'=>array('profiles/index')),
	array('label'=>'recipes', 'url'=>array('recipes/index')),
	array('label'=>'recipeTypes', 'url'=>array('recipeTypes/index')),
	array('label'=>'shoplists', 'url'=>array('shoplists/index')),
	array('label'=>'storability', 'url'=>array('storability/index')),
	array('label'=>'stores', 'url'=>array('stores/index')),
	array('label'=>'subgroupNames', 'url'=>array('subgroupNames/index')),
	array('label'=>'suppliers', 'url'=>array('suppliers/index'))
);

$this->pageTitle=Yii::app()->name; ?>



<div class="container">
	<div class="span-19">
		<div id="content">
			<h1>Welcome to admin page</h1>
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	</div>
</div>