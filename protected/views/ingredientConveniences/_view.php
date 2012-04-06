<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ICO_ID), array('view', 'id'=>$data->ICO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->ICO_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->ICO_DESC_DE); ?>
	<br />


</div>