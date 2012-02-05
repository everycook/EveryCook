<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('RET_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->RET_ID), array('view', 'id'=>$data->RET_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RET_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->RET_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RET_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->RET_DESC_DE); ?>
	<br />


</div>