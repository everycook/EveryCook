<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ETH_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ETH_ID), array('view', 'id'=>$data->ETH_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ETH_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->ETH_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ETH_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->ETH_DESC_DE); ?>
	<br />


</div>