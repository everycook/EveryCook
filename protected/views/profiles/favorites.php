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
?>
<?php echo $this->renderPartial('buttons', array('model'=>$model, 'professional'=>$professional)); ?>
<div class="favorites">
<h1><?php echo $this->trans->PROFILES_FAVOURITES; ?></h1>
<h2><?php echo $this->trans->PROFILES_FAVOURITES_RECIPES; ?></h2>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_LIKE; ?></h3>
<?php Yii::app()->runController('recipes/showLike/layout/inline'); ?>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_NOT_LIKE; ?></h3>
<?php Yii::app()->runController('recipes/showNotLike/layout/inline'); ?>

<h2><?php echo $this->trans->PROFILES_FAVOURITES_INGREDIENTS; ?></h2>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_LIKE; ?></h3>
<?php Yii::app()->runController('ingredients/showLike/layout/inline'); ?>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_NOT_LIKE; ?></h3>
<?php Yii::app()->runController('ingredients/showNotLike/layout/inline'); ?>

<h2><?php echo $this->trans->PROFILES_FAVOURITES_PRODUCTS; ?></h2>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_LIKE; ?></h3>
<?php Yii::app()->runController('products/showLike/layout/inline'); ?>
<h3><?php echo $this->trans->PROFILES_FAVOURITES_NOT_LIKE; ?></h3>
<?php Yii::app()->runController('products/showNotLike/layout/inline'); ?>
</div>