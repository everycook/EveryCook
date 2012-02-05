<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('STT_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STT_ID), array('view', 'id'=>$data->STT_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STT_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->STT_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STT_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->STT_DESC_DE); ?>
	<br />


</div>