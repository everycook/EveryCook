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

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ECO')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_ECO); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ETHIC')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_ETHIC); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PICTURE')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_PICTURE); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PICTURE_COPYR')); ?>:</b>
	<?php echo CHtml::encode($data->PRO_PICTURE_COPYR); ?>
	<br />

	*/ ?>

</div>