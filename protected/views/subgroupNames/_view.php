<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUBGRP_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->SUBGRP_ID), array('view', 'id'=>$data->SUBGRP_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUBGRP_OF')); ?>:</b>
	<?php echo CHtml::encode($data->SUBGRP_OF); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUBGRP_DESC_EN')); ?>:</b>
	<?php echo CHtml::encode($data->SUBGRP_DESC_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUBGRP_DESC_DE')); ?>:</b>
	<?php echo CHtml::encode($data->SUBGRP_DESC_DE); ?>
	<br />


</div>