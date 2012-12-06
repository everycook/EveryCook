
<div class="resultArea adminArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['TOO_ID'], array('class'=>'f-right button ToolsSelect'));
	} else {
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['TOO_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'TOO_ID'=>$data['TOO_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['TOO_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
		*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['TOO_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['TOO_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['TOO_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('TOO_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->TOO_ID), array('view', 'id'=>$data->TOO_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TOO_DESC_DE_CH')); ?>:</b>
		<?php echo CHtml::encode($data->TOO_DESC_DE_CH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('TOO_DESC_EN_GB')); ?>:</b>
		<?php echo CHtml::encode($data->TOO_DESC_EN_GB); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>