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
	echo CHtml::link($recipe['REC_NAME_' . Yii::app()->session['lang']], array('recipes/view', 'id'=>$recipe['REC_ID']), array('class'=>'title '.$linkClass, 'target'=>$linkTarget, 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']]));
	echo '<div class="small_img">';
		echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), $recipe['REC_NAME_' . Yii::app()->session['lang']], array('class'=>'recipe', 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID']), array('class'=>$linkClass,'target'=>$linkTarget));
		echo '<div class="img_auth">';
			if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $recipe['REC_IMG_AUTH']; }
		echo '</div>';
	echo '</div>';
echo '</div>';
?>