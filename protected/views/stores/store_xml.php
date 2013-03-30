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

header("Content-Type:text/xml"); ?>
<?php echo "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<data>
	<status>success</status>
	<storeCount><?php echo count($stores); ?></storeCount>
	<stores>
<?php foreach($stores as $store){
?>		<store>
			<lat><?php echo $store['STO_GPS_LAT']; ?></lat>
			<lng><?php echo $store['STO_GPS_LNG']; ?></lng>
			<storeId><?php echo $store['STO_ID']; ?></storeId>
			<supplierId><?php echo $store['SUP_ID']; ?></supplierId>
			<distance><?php echo $store['distance']; ?></distance>
<?php if ($zoom > 9){ ?>
			<name><?php echo $store['STO_NAME']; ?></name>
			<street><?php echo $store['STO_STREET']; ?></street>
			<housenumber><?php echo $store['STO_HOUSE_NO']; ?></housenumber>
			<zip><?php echo $store['STO_ZIP']; ?></zip>
			<city><?php echo $store['STO_CITY']; ?></city>
			<supplier><?php echo $store['SUP_NAME']; ?></supplier>
			<type><?php echo $store['STY_TYPE']; ?></type>
			<typeId><?php echo $store['STY_ID']; ?></typeId>
			<imageUrl><?php /*echo $this->createUrl('stores/displaySavedImage', array('id'=>$store['STO_ID'], 'ext'=>'.png'));*/ ?></imageUrl><?php } ?>			
		</store>
<?php } ?>	</stores>
</data>