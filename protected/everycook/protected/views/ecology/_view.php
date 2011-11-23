<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ECO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ECO_ID), array('view', 'id'=>$data->ECO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ECO_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->ECO_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ECO_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->ECO_DESC_DE); ?>
	<br />


</div>