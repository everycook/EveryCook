<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('STATE_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STATE_ID), array('view', 'id'=>$data->STATE_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STATE_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->STATE_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STATE_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->STATE_DESC_DE); ?>
	<br />


</div>