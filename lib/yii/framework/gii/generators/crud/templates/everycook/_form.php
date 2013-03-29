<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$transKey = strtoupper($this->modelClass);
$tablePrefix = $this->tableSchema->primaryKey;
$tablePrefix = substr($tablePrefix, 0, strpos($tablePrefix,'_'));
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
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."_form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<?php echo '<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>'; ?>

	<?php echo '<?php'."\r\n"; ?>
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	
	echo '<div class="row">'."\r\n";
		echo $form->labelEx($model,'<?php echo $tablePrefix; ?>_DESC') ."\r\n";
		echo $form->textField($model,'<?php echo $tablePrefix; ?>_DESC',array('size'=>60,'maxlength'=>100)) ."\r\n";
		echo $form->error($model,'<?php echo $tablePrefix; ?>_DESC') ."\r\n";
	echo '</div>'."\r\n";
	
	/*
	//Example for select / checkboxlist
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::createInput(null, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	*/
	?>
	
<?php
//echo '<?php'."\r\n";
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
	/*
	echo 'echo \'<div class="row">\';'."\r\n";
		echo 'echo $this->generateActiveLabel('.$this->modelClass.',$column);'."\r\n";
		echo 'echo $this->generateActiveField('.$this->modelClass.',$column);'."\r\n";
		echo 'echo $form->error($model,\'{$column->name}\');'."\r\n";
	echo 'echo \'</div>\'."\\r\\n\\r\\n";'."\r\n\r\n";
	*/

?>
	<div class="row">
		<?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
	</div>

<?php
}
?>
	<div class="buttons">
		<?php echo '<?php'; ?> echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo '<?php'; ?> echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->