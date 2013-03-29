<?php
/**
 * This is the template for generating an action view file.
 * The following variables are available in this template:
 * - $this: the ControllerCode object
 * - $action: the action ID
 */
?>
<?php
echo "<?php\n"; ?>
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

<?php
$label=ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', basename($this->getControllerID()))))));
if($action==='index')
{
	echo "\$this->breadcrumbs=array(
	'$label',
);";
}
else
{
	$action=ucfirst($action);
	echo "\$this->breadcrumbs=array(
	'$label'=>array('/{$this->uniqueControllerID}'),
	'$action',
);";
}
?>
?>
<h1><?php echo '<?php'; ?> echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo '<?php'; ?> echo __FILE__; ?></tt>.
</p>
