<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUP_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->SUP_ID), array('view', 'id'=>$data->SUP_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUP_NAME')); ?>:</b>
	<?php echo CHtml::encode($data->SUP_NAME); ?>
	<br />


</div>