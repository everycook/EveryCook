<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('CONV_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->CONV_ID), array('view', 'id'=>$data->CONV_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CONV_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->CONV_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CONV_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->CONV_DESC_DE); ?>
	<br />


</div>