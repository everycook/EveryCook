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

class MenuWidget extends CWidget {
	public $items=array();
	public $test="";
	
	public function run() {
		$pos = 0;
		$handlers = '';
		foreach($this->items as $item) {
			$pos++;
			if (is_array($item['url'])){
				$link = $this->getController()->createUrl($item['url'][0],$item['url'][1]);
			} else {
				$link = $item['url'];
			}
			echo CHtml::openTag('a',array('href'=>$link, 'id'=>$item['link_id']))."\n";
				echo CHtml::openTag('div',array('id'=>'item_' . $pos, 'class'=>'index_button'))."\n";
					echo CHtml::openTag('span',array('id'=>'text_' . $pos))."\n";
						echo $item['label'];
					echo CHtml::closeTag('span');
				echo CHtml::closeTag('div');
			echo CHtml::closeTag('a');
			//$handlers .= "jQuery('body').undelegate('#" . $item['link_id'] . "','click').delegate('#" . $item['link_id'] . "','click',function(){\n" . CHtml::ajax(array('url' => $link, 'update' => '#changable_content')) . ";\n return false;\n});\n";
		}
		if ($handlers !== ''){
			?>
			<script type="text/javascript">
			/*<![CDATA[*/
			jQuery(function($) {
				<?php echo $handlers ?>
			});
			/*]]>*/
			</script>
			<?php
		}
	}
}