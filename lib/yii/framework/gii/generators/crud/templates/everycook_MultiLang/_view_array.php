<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
 
$primaryKey = $this->tableSchema->primaryKey;
$tablePrefix = substr($primaryKey, 0, strpos($primaryKey,'_'));
?>
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
?>
<div class="resultArea">
<?php echo "<?php\n"; ?>
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['<?php echo $primaryKey; ?>'], array('class'=>'f-right button <?php echo $this->model; ?>Select'));
	/*
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', '<?php echo $primaryKey; ?>'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php echo "<?php\n"; ?>
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['<?php echo $tablePrefix; ?>_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['<?php echo $tablePrefix; ?>_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['<?php echo $primaryKey; ?>']));
			}
			?>
		</div>
		
<?php
		//$count=0;
		echo "\t\t<?php /*\n";
		echo "\t\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
		echo "\t\t<?php echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}), array('view', 'id'=>\$data->{$this->tableSchema->primaryKey})); ?>\n\t\t<br />\n\n";
		
		foreach($this->tableSchema->columns as $column)
		{
			if($column->isPrimaryKey)
				continue;
		//	if(++$count==7)
		//		echo "\t<?php /*\n";
			echo "\t\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
			echo "\t\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t\t<br />\n\n";
		}
		//if($count>=7)
			echo "\t\t*/ ?>\n";
		?>
	</div>
	<div class="clearfix"></div>
</div>