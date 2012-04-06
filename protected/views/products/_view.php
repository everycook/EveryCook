<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PRO_ID), array('view', 'id'=>$data->PRO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_BARCODE')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_BARCODE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PACKAGE_GRAMMS')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_PACKAGE_GRAMMS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ING_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ECO_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ECO_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ETH_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ETH_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_IMG')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_IMG); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_IMG_CR')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_IMG_CR); ?>
	<br />

	*/ ?>

</div>