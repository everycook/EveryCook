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
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'meals-form',
	'enableAjaxValidation'=>false,
)); ?>

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
		<?php echo $form->labelEx($model,'MEA_DATE'); ?>
		<?php echo $form->textField($model,'MEA_DATE'); ?>
		<?php echo $form->error($model,'MEA_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MTY_ID'); ?>
		<?php echo $form->textField($model,'MTY_ID',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'MTY_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->