<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PRF_UID), array('view', 'id'=>$data->PRF_UID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_FIRSTNAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_FIRSTNAME); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LASTNAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LASTNAME); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NICK')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NICK); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_EMAIL')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_EMAIL); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_PW')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_PW); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LOC_GPS')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LOC_GPS); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_I')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_I); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_LIKES_R')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_LIKES_R); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NOTLIKES_I')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NOTLIKES_I); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_NOTLIKES_R')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_NOTLIKES_R); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_SHOPLISTS')); ?>:</b>
	<?php echo CHtml::encode($data->PRF_SHOPLISTS); ?>
	<br />

	*/ ?>

</div>