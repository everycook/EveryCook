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

$this->pageTitle=Yii::app()->name . ' - Forgotten Passwort';
$this->breadcrumbs=array(
	'Forgotten Passwort',
);
?>

<h1><?php echo $this->trans->TITLE_SITE_FORGOTTEN_PASSWORD; ?></h1>

<p><?php echo $this->trans->PASSWORD_HINT; ?></p>

<p><?php
if(isset($error)){
	echo $error; 
} else if(isset($success)){
	echo $success;
} 
?></p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'passwort-form',
	//'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'noAjax'),
	/*
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),*/
)); ?>

	<div class="row">
		<?php echo CHtml::label($this->trans->PASSWORD_MAIL, 'email'); ?>
		<?php echo CHtml::textField('email',''); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->PASSWORD_SEND_MAIL, array('id'=>'forgottenButton')); ?>
	</div>
	<p>
	<?php echo CHtml::link($this->trans->PASSWORD_BACK_TO_LOGIN,array('site/login'), array('class'=>'actionlink')); ?>
	</p>

<?php $this->endWidget(); ?>
</div><!-- form -->
