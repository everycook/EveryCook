<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'noAjax'),
	/*
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),*/
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username', array('autofocus'=>'autofocus')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<p>
	<?php echo $this->trans->REGISTER_NOT_REGISTERED; ?>
	<?php echo CHtml::link($this->trans->REGISTER_REGISTER_NOW,array('profiles/register'), array('class'=>'actionlink')); ?>
	</p>

	<div class="buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>   

<?php $this->endWidget(); ?>
</div><!-- form -->
