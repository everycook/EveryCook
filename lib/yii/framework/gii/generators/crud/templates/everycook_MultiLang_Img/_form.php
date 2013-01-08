<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$transKey = strtoupper($this->modelClass);
$tablePrefix = $this->tableSchema->primaryKey;
$tablePrefix = substr($tablePrefix, 0, strpos($tablePrefix,'_'));
?>
<input type="hidden" id="imageLink" value="<?php echo '<?php'; ?> echo $this->createUrl('displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."_form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl(\$this->route, array_merge(\$this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>\n"; ?>

	<?php echo '<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>'; ?>

	<?php echo '<?php'."\r\n"; ?>
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	
	foreach($this->allLanguages as $lang=>$name){
	echo '<div class="row">'."\r\n";
		echo $form->labelEx($model,'<?php echo $tablePrefix; ?>_DESC_'.$lang) ."\r\n";
		echo $form->textField($model,'<?php echo $tablePrefix; ?>_DESC_'.$lang,array('size'=>60,'maxlength'=>100)) ."\r\n";
		echo $form->error($model,'<?php echo $tablePrefix; ?>_DESC_'.$lang) ."\r\n";
	echo '</div>'."\r\n";
	}
	
	/*
	//Example for select / checkboxlist
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::createInput(null, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	*/
	?>
	
	<div class="row">
		<?php echo "<?php echo \$form->labelEx(\$model,'filename') ?>\r\n"; ?>
		<div class="imageTip">
		<?php echo '<?php'."\r\n"; ?>
		echo $this->trans->TIP_OWN_IMAGE . '<br>';
		echo $form->FileField($model,'filename'). '<br>' . "\r\n";
		echo $form->error($model,'filename') . "\r\n";
		echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
		printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('<?php echo $tablePrefix; ?>_DESC_EN_GB'));//'<?php echo $tablePrefix; ?>_DESC_'.Yii::app()->session['lang']
		echo '<br>' . $this->trans->TIP_FLICKR_LINK . '<input type="text" name="flickr_link" class="flickr_link"/> <div class="buttonSmall loadFromFlickr">' . $this->trans->TIP_FLICKR_LINK_LOAD . '</div>'
		?>
		</div>
	</div>
	
	<div class="row">
		<?php echo "<?php echo \$form->labelEx(\$model,'" .$tablePrefix . "_IMG_AUTH'); ?>\n"; ?>
		<?php echo "<?php echo \$form->textField(\$model,'" .$tablePrefix . "_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'" .$tablePrefix . "_IMG_AUTH'); ?>\n"; ?>
	</div>
	
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