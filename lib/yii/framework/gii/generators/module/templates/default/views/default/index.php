<?php echo "<?php\n"; ?>
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
	$this->module->id,
);
?>
<h1><?php echo "<?php"; ?> echo $this->uniqueId . '/' . $this->action->id; ?></h1>

<p>
This is the view content for action "<?php echo "<?php"; ?> echo $this->action->id; ?>".
The action belongs to the controller "<?php echo "<?php"; ?> echo get_class($this); ?>"
in the "<?php echo "<?php"; ?> echo $this->module->id; ?>" module.
</p>
<p>
You may customize this page by editing <tt><?php echo "<?php"; ?> echo __FILE__; ?></tt>
</p>