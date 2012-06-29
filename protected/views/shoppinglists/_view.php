<div class="resultArea">
	<?php echo CHtml::link(CHtml::encode($this->trans->GENERAL_EDIT), array('view', 'id'=>$data->SHO_ID), array('class'=>'button f-right')); ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->SHO_ID), array('view', 'id'=>$data->SHO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_DATE')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_DATE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_INGREDIENTS')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_INGREDIENTS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_WEIGHTS')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_WEIGHTS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_PRODUCTS')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_PRODUCTS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHO_QUANTITIES')); ?>:</b>
	<?php echo CHtml::encode($data->SHO_QUANTITIES); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_BY')); ?>:</b>
	<?php echo CHtml::encode($data->CREATED_BY); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_ON')); ?>:</b>
	<?php echo CHtml::encode($data->CREATED_ON); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_BY')); ?>:</b>
	<?php echo CHtml::encode($data->CHANGED_BY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CHANGED_ON')); ?>:</b>
	<?php echo CHtml::encode($data->CHANGED_ON); ?>
	<br />

	*/ ?>

</div>