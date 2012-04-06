<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('IST_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->IST_ID), array('view', 'id'=>$data->IST_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IST_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->IST_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IST_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->IST_DESC_DE); ?>
	<br />


</div>