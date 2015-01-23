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

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);



if(Yii::app()->user->hasFlash('forgottenPassword')){ ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('forgottenPassword');?>
	<?php echo '<br />'.CHtml::link($this->trans->LOGIN,array('site/login'), array('class' => 'actionlink')); ?>
</div>
<?php
} else {
?>

<h1><?php echo $this->trans->TITLE_SITE_LOGIN; ?></h1>

<p><?php echo $this->trans->LOGIN_FILL_OUT; ?></p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	//'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'noAjax'),
	/*
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),*/
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'LIF_NICKNAME'); ?>
		<?php echo $form->textField($model,'LIF_NICKNAME', array('autofocus'=>'autofocus')); ?>
		<?php echo $form->error($model,'LIF_NICKNAME'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LIF_PASSWORD'); ?>
		<?php echo $form->passwordField($model,'LIF_PASSWORD'); ?>
		<?php echo $form->error($model,'LIF_PASSWORD'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'LIF_REMEMBER'); ?>
		<?php echo $form->label($model,'LIF_REMEMBER'); ?>
		<?php echo $form->error($model,'LIF_REMEMBER'); ?>
	</div>

	<p>
	<?php echo $this->trans->LOGIN_DEMO; ?>
	</p>
	<p>
	<?php echo $this->trans->REGISTER_NOT_REGISTERED; ?>
	<?php echo CHtml::link($this->trans->REGISTER_REGISTER_NOW,array('profiles/register'), array('class'=>'actionlink')); ?>
	</p>

	<div class="buttons">
		<?php echo CHtml::submitButton('Login', array('id'=>'loginButton')); ?>
	</div>
	<p>
	<?php echo CHtml::link($this->trans->LOGIN_FORGOTTEN_PASSWORD,array('site/forgottenPassword'), array('class'=>'actionlink')); ?>
	</p>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php } ?>
