<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('SGR_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->SGR_ID), array('view', 'id'=>$data->SGR_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('GRP_ID')); ?>:</b>
	<?php echo CHtml::encode($data->GRP_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SGR_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->SGR_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SGR_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->SGR_DESC_DE); ?>
	<br />


</div>