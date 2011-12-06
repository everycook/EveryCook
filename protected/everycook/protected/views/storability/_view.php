<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('STORAB_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STORAB_ID), array('view', 'id'=>$data->STORAB_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STORAB_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->STORAB_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STORAB_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->STORAB_DESC_DE); ?>
	<br />


</div>