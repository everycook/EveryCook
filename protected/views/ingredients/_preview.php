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
if (!isset($linkClass)){
	$linkClass = '';
}
if (!isset($linkTarget)){
	$linkTarget = '';
}

echo '<div class="item">';
	echo CHtml::link($ingredient['ING_NAME_' . Yii::app()->session['lang']], array('ingredients/view', 'id'=>$ingredient['ING_ID']), array('class'=>'title ' . $linkClass, 'title'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']], 'target'=>$linkTarget));
	echo '<div class="small_img">';
		echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')), $ingredient['ING_NAME_' . Yii::app()->session['lang']], array('class'=>'ingredient', 'title'=>$ingredient['ING_NAME_' . Yii::app()->session['lang']])), array('ingredients/view', 'id'=>$ingredient['ING_ID']), array('class'=>$linkClass, 'target'=>$linkTarget));
		echo '<div class="img_auth">';
		if ($ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $ingredient['ING_IMG_AUTH']; }
		echo '</div>';
	echo '</div>';
echo '</div>';
?>