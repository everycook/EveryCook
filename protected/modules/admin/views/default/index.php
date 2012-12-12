<?php	
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Admin</h1>
<div class="adminOverview">
	<div class="buttons">
	<?php echo CHtml::link('ActionsGenerator', array('actionsGenerator/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions In', array('actionsIn/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions Out', array('actionsOut/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Tools', array('tools/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('StepTypes', array('stepTypes/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In', array('cookIn/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In Prep', array('cookInPrep/search'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In State', array('cookInState/search'), array('class'=>'button')); ?><br />
	</div>
</div>