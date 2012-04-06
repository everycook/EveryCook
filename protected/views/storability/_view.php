<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('STB_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STB_ID), array('view', 'id'=>$data->STB_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STB_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->STB_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STB_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->STB_DESC_DE); ?>
	<br />


</div>