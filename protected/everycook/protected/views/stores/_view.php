<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STO_ID), array('view', 'id'=>$data->STO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_LOC_GPS')); ?>:</b>
	<?php echo CHtml::encode($data->STO_LOC_GPS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_LOC_ADDR')); ?>:</b>
	<?php echo CHtml::encode($data->STO_LOC_ADDR); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUP_ID')); ?>:</b>
	<!-- STL link to Suppliers View -->
	<?php echo CHtml::link($data->SUP_ID,CController::createUrl('suppliers/view&id='.$data->SUP_ID)); ?>
	<!--<?php echo CHtml::encode($data->SUP_ID); ?>-->
	<br />


</div>