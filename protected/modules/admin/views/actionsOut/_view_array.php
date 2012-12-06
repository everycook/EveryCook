
<div class="resultArea adminArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['AOU_ID'], array('class'=>'f-right button ActionsOutSelect'));
	} else {
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['AOU_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'AOU_ID'=>$data['AOU_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['AOU_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
		*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['AOU_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['AOU_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['AOU_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->AOU_ID), array('view', 'id'=>$data->AOU_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STT_ID')); ?>:</b>
		<?php echo CHtml::encode($data->STT_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TOO_ID')); ?>:</b>
		<?php echo CHtml::encode($data->TOO_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_PREP')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_PREP); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_DURATION')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_DURATION); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_DUR_PRO')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_DUR_PRO); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_CIS_CHANGE')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_CIS_CHANGE); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_DESC_DE_CH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('AOU_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->AOU_DESC_EN_GB); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>