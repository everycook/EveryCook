<div class="resultArea">
	<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['STO_ID'] . '_' .$data['SUP_ID'] . '_' .$data['STY_ID'] . '_' .$data['STO_GPS_LAT'] . '_' .$data['STO_GPS_LNG'], array('class'=>'f-right button StoresSelect'));
	} else {
		?>
		<div class="options">
			<?php echo CHtml::link('&nbsp;', array('favorite', 'id'=>$data['STO_ID']), array('class'=>'favorite backpic', 'title'=>$this->trans->STORES_FAVORITE)); ?>
		</div>
		<?php
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			//TODO echo CHtml::link(CHtml::encode($data['SUP_NAME'] . ' ' . $data['STO_NAME']), array('view', 'id'=>$data['STO_ID']));
			echo CHtml::link($data['STO_NAME']), array('view', 'id'=>$data['STO_ID']));
			?>
		</div>
		
		<div class="adress">
		<?php
			if ($data['STO_STREET'] != null && strlen($data['STO_STREET'])>0){
				echo '<div class="street">' . CHtml::encode($data['STO_STREET']) . '&nbsp;';
				echo CHtml::encode($data['STO_HOUSE_NO']) . '</div>';
			}
			if ($data['STO_CITY'] != null && strlen($data['STO_CITY'])>0){
				echo '<div class="city">' . CHtml::encode($data['STO_ZIP']) . '&nbsp;';
				echo CHtml::encode($data['STO_CITY']) . '</div>';
			}
			if ($data['STO_COUNTRY'] != null && strlen($data['STO_COUNTRY'])>0 && $data['STO_COUNTRY'] != 0){
				echo '<div class="country">' . CHtml::encode($data['STO_COUNTRY']) . '</div>';
			}
			if ($data['STO_STATE'] != null && strlen($data['STO_STATE'])>0){
				echo '<div class="state">' . CHtml::encode($data['STO_STATE']) . '</div>';
			}
		?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<?php
	/*
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->STO_ID), array('view', 'id'=>$data->STO_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_NAME')); ?>:</b>
	<?php echo CHtml::encode($data->STO_NAME); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_STREET')); ?>:</b>
	<?php echo CHtml::encode($data->STO_STREET); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_HOUSE_NO')); ?>:</b>
	<?php echo CHtml::encode($data->STO_HOUSE_NO); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_ZIP')); ?>:</b>
	<?php echo CHtml::encode($data->STO_ZIP); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_CITY')); ?>:</b>
	<?php echo CHtml::encode($data->STO_CITY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_COUNTRY')); ?>:</b>
	<?php echo CHtml::encode($data->STO_COUNTRY); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_STATE')); ?>:</b>
	<?php echo CHtml::encode($data->STO_STATE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STY_ID')); ?>:</b>
	<?php echo CHtml::encode($data->STY_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_GPS')); ?>:</b>
	<?php echo CHtml::encode($data->STO_GPS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_PHONE')); ?>:</b>
	<?php echo CHtml::encode($data->STO_PHONE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STO_IMG')); ?>:</b>
	<?php echo CHtml::encode($data->STO_IMG); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SUP_ID')); ?>:</b>
	<?php echo CHtml::encode($data->SUP_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CREATED_BY')); ?>:</b>
	<?php echo CHtml::encode($data->CREATED_BY); ?>
	<br />

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