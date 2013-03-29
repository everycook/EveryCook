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

if ($this->route != 'site/index'){
	Functions::browserCheck();
}
?>
<input type="hidden" id="route" name="route" value="<?php echo $this->route; ?>"/>
<div id="container">
	<div id="content_left">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div id="content_right">
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
	<div class="clearfix"></div>
</div>
<div id="mainButtons"<?php if (!isset($this->mainButtons) || count($this->mainButtons) == 0){ echo ' style="display:none;"';} ?>>
<?php $this->widget('ext.widgets.MenuWidget',array(
		'items'=>$this->mainButtons,
	));
?>
	<div class="clearfix"></div>
</div>