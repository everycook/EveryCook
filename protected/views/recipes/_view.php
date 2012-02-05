<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->REC_ID), array('view', 'id'=>$data->REC_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CREATED')); ?>:</b>
	<?php echo CHtml::encode($data->REC_CREATED); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CHANGED')); ?>:</b>
	<?php echo CHtml::encode($data->REC_CHANGED); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_PICTURE')); ?>:</b>
	<?php echo CHtml::encode($data->REC_PICTURE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_PICTURE_AUTH')); ?>:</b>
	<?php echo CHtml::encode($data->REC_PICTURE_AUTH); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TYPE')); ?>:</b>
	<?php echo CHtml::encode($data->REC_TYPE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TITLE_EN')); ?>:</b>
	<?php echo CHtml::encode($data->REC_TITLE_EN); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TITLE_DE')); ?>:</b>
	<?php echo CHtml::encode($data->REC_TITLE_DE); ?>
	<br />

	*/ ?>

</div>