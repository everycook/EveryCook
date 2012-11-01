<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
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

<?php $this->endWidget(); ?>
</div><!-- form -->
