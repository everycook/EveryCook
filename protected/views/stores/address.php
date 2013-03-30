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
	'id'=>'address-form',
	'enableAjaxValidation'=>false,
	'action'=>'#',
)); ?>

	<div class="hint"><?php
		if (isset($_GET['errorCode'])){
			$status = $_GET['errorCode'];
			if ($status == -1) {
				echo $this->trans->GENERAL_GPS_FAILED_ENTER_MANUAL;
			} else if ($status == -2) {
				echo $this->trans->GENERAL_GPS_NOT_AVAILABLE;
			}
		}
	?></div>
	<div class="row">
		<?php echo $form->label($model,'STO_STREET'); ?>
		<?php echo $form->textField($model,'STO_STREET',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_STREET'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_HOUSE_NO'); ?>
		<?php echo $form->textField($model,'STO_HOUSE_NO',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'STO_HOUSE_NO'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_ZIP'); ?>
		<?php echo $form->textField($model,'STO_ZIP'); ?>
		<?php echo $form->error($model,'STO_ZIP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_CITY'); ?>
		<?php echo $form->textField($model,'STO_CITY',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_CITY'); ?>
	</div>

	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo Functions::createInput($this->trans->STORES_COUNTRY, $model, 'STO_COUNTRY', $countrys, Functions::DROP_DOWN_LIST, 'countrys', $htmlOptions_type0, $form);
	?>

	<div class="row">
		<?php echo $form->label($model,'STO_STATE'); ?>
		<?php echo $form->textField($model,'STO_STATE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_STATE'); ?>
	</div>
		
	<div class="row">
		<?php
		echo $form->labelEx($model,'STO_GPS_LAT', array('label'=>$this->trans->GENERAL_LAT));
		$value = null;
		if (isset($_GET['lat'])){
			$value = $_GET['lat'];
		}
		echo $form->textField($model,'STO_GPS_LAT', array('class'=>'cord_lat', 'value'=>$value));
		echo $form->error($model,'STO_GPS_LAT');
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model,'STO_GPS_LNG', array('label'=>$this->trans->GENERAL_LNG));
		$value = null;
		if (isset($_GET['lng'])){
			$value = $_GET['lng'];
		}
		echo $form->textField($model,'STO_GPS_LNG', array('class'=>'cord_lng', 'value'=>$value));
		echo $form->error($model,'STO_GPS_LNG');
		?>
	</div>

	<div class="buttons">
		<?php echo CHtml::button($this->trans->GENERAL_GPS_ADDRESS_TO_GPS, array('id'=>'Address_to_GPS', 'class'=>'button')); ?>
		<?php echo CHtml::button($this->trans->GENERAL_GPS_GPS_TO_ADDRESS, array('id'=>'GPS_to_Address', 'class'=>'button')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, '#', array('class'=>'button closeFancy')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_SAVE, '#', array('class'=>'button', 'id'=>'useLocation')); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->