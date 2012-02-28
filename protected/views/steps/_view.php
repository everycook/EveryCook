<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->REC_ID), array('view', 'id'=>$data->REC_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ACT_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ACT_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ING_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_STEP_NO')); ?>:</b>
	<?php echo CHtml::encode($data->STE_STEP_NO); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_GRAMS')); ?>:</b>
	<?php echo CHtml::encode($data->STE_GRAMS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_CELSIUS')); ?>:</b>
	<?php echo CHtml::encode($data->STE_CELSIUS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_KPA')); ?>:</b>
	<?php echo CHtml::encode($data->STE_KPA); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_RPM')); ?>:</b>
	<?php echo CHtml::encode($data->STE_RPM); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_CLOCKWISE')); ?>:</b>
	<?php echo CHtml::encode($data->STE_CLOCKWISE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_STIR_RUN')); ?>:</b>
	<?php echo CHtml::encode($data->STE_STIR_RUN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_STIR_PAUSE')); ?>:</b>
	<?php echo CHtml::encode($data->STE_STIR_PAUSE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STE_STEP_DURATION')); ?>:</b>
	<?php echo CHtml::encode($data->STE_STEP_DURATION); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STT_ID')); ?>:</b>
	<?php echo CHtml::encode($data->STT_ID); ?>
	<br />

	*/ ?>

</div>