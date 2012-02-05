<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->SHO_ID), array('view', 'id'=>$data->SHO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_DATE')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_DATE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_PRODUCTS')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_PRODUCTS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_QUANTITIES')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_QUANTITIES); ?>
	<br />


</div>