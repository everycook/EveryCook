<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRD_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PRD_ID), array('view', 'id'=>$data->PRD_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRD_NAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRD_NAME); ?>
	<br />


</div>