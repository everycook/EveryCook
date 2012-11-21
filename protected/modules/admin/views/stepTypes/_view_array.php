
<div class="resultArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['STT_ID'], array('class'=>'f-right button StepTypesSelect'));
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['STT_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'STT_ID'=>$data['STT_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['STT_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['STT_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['STT_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['STT_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->STT_ID), array('view', 'id'=>$data->STT_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_DEFAULT')); ?>:</b>
		<?php echo CHtml::encode($data->STT_DEFAULT); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_REQUIRED')); ?>:</b>
		<?php echo CHtml::encode($data->STT_REQUIRED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->STT_DESC_EN_GB); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->STT_DESC_DE_CH); ?>
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
	<div class="clearfix"></div>
</div>