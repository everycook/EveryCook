<?php	
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Admin</h1>
<div class="adminOverview">
	<div class="buttons">
	<?php echo CHtml::link('ActionsGenerator', array('actionsGenerator/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions In', array('actionsIn/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Actions Out', array('actionsOut/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Tools', array('tools/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('StepTypes', array('stepTypes/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In', array('cookIn/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In Prep', array('cookInPrep/index'), array('class'=>'button')); ?><br />
	<?php echo CHtml::link('Cook In State', array('cookInState/index'), array('class'=>'button')); ?><br />
	</div>
</div>