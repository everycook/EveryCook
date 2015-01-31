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

<h1><?php printf($this->trans->TITLE_SHOPPINGLISTS_VIEW, $SHO_ID); ?></h1>
<style>
<!--
.resultAreaPrint .data {
	width: 20em;
	display: inline-block;
}
#shoppinglistPrint {
	margin-bottom: 1em;
}
-->
</style>
<?php /*$this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item_print',
	'id'=>'shoppinglistPrint',
	'template'=>"{items}",
));*/ ?>
<?php
//not adding any scripts, do simplyfied widget logic
$index=0;
echo '<div id="shoppinglistPrint">';
foreach ($dataProvider->rawData as $data){
	$data['index'] = $index;
	include '_item_print.php';
	++$index;
}
echo '</div>';
?>

<input type="button" onclick="window.print();" value="<?php echo $this->trans->SHOPPINGLISTS_PRINT; ?>">
