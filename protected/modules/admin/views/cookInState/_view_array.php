
<div class="resultArea adminArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['CIS_ID'], array('class'=>'f-right button CookInStateSelect'));
	} else {
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['CIS_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'CIS_ID'=>$data['CIS_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['CIS_ID']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
		*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['CIS_DESC']);
			} else {
				echo CHtml::link(CHtml::encode($data['CIS_DESC']), array('view', 'id'=>$data['CIS_ID']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('CIS_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->CIS_ID), array('view', 'id'=>$data->CIS_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_ID')); ?>:</b>
		<?php echo CHtml::encode($data->COI_ID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('CIS_DESC')); ?>:</b>
		<?php echo CHtml::encode($data->CIS_DESC); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>