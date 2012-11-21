
<div class="resultArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['COI_ID'], array('class'=>'f-right button CookInSelect'));
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['COI_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'COI_ID'=>$data['COI_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['COI_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['COI_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['COI_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['COI_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->COI_ID), array('view', 'id'=>$data->COI_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TOO_ID')); ?>:</b>
		<?php echo CHtml::encode($data->TOO_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->COI_DESC_DE_CH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->COI_DESC_EN_GB); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>