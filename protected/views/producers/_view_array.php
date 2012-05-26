<div class="resultArea noPic">
	<?php echo CHtml::link($this->trans->GENERAL_SELECT, $data['PRD_ID'], array('class'=>'f-right button ProducerSelect')); ?>

	<div class="data">
		<div class="name">
			<?php // echo CHtml::link(CHtml::encode($data['PRD_NAME']), array('view', 'id'=>$data['PRD_ID'])); ?>
			<?php echo CHtml::encode($data['PRD_NAME']); ?>
		</div>
	</div>
	
	<div class="clearfix"></div>
<?php /*	
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRD_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PRD_ID), array('view', 'id'=>$data->PRD_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRD_NAME')); ?>:</b>
	<?php echo CHtml::encode($data->PRD_NAME); ?>
	<br />

 */ ?>
</div>