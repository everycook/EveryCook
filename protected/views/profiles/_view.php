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
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PRF_UID), array('view', 'id'=>$data->PRF_UID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_FIRSTNAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_FIRSTNAME); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LASTNAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LASTNAME); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NICK')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NICK); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_GENDER')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_GENDER); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_BIRTHDAY')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_BIRTHDAY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_EMAIL')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_EMAIL); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LANG')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LANG); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_IMG_FILENAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_IMG); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_PW')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_PW); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LOC_GPS_LAT')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LOC_GPS_LAT); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LOC_GPS_LNG')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LOC_GPS_LNG); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LOC_GPS_POINT')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LOC_GPS_POINT); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_I')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_I); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_R')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_R); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_P')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_P); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_S')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_S); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NOTLIKES_I')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NOTLIKES_I); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NOTLIKES_R')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NOTLIKES_R); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NOTLIKES_P')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NOTLIKES_P); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_SHOPLISTS')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_SHOPLISTS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_ACTIVE')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_ACTIVE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_RND')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_RND); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_BY')); ?>:</b>
	<?php echo CHtml::encode($data->CREATED_BY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_ON')); ?>:</b>
	<?php echo CHtml::encode($data->CREATED_ON); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_BY')); ?>:</b>
	<?php echo CHtml::encode($data->CHANGED_BY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_ON')); ?>:</b>
	<?php echo CHtml::encode($data->CHANGED_ON); ?>
	<br />

	*/ ?>

</div>