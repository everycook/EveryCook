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
<?php 
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/feedback.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/example.js');
Yii::app()->clientScript->registerScript('dragging', '
	  $(function() {
    $( "#draggable" ).draggable();
    $( "#draggable" ).resizable();
    $( "#tester" ).draggable();
    $( "#tester" ).resizable();
  });
', CClientScript::POS_HEAD);
?>
<!--
<div id="tester" class="ui-widget-content">
    <h3 class="ui-widget-header">Tester</h3>
</div> -->
<div id="draggable" class="form ui-widget-content">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'feedbacks_form_simple',
	'enableAjaxValidation'=>false,
)); ?>

    <?php /*

    Draggable::begin([
    'clientOptions' => ['grid' => [50, 20]],
]);

echo 'Draggable contents here...';

Draggable::end();
    */?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>
	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'FEE_TEXT'); ?>
		<?php echo $form->textArea($model,'FEE_TEXT',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'FEE_TEXT'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->