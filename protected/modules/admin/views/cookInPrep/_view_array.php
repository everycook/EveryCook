
<div class="resultArea adminArea">
<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['COI_PREP'], array('class'=>'f-right button CookInPrepSelect'));
	} else {
		/*
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['COI_PREP']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', 'COI_PREP'=>$data['COI_PREP']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['COI_PREP']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
		*/
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['COI_PREP_DESC']);
			} else {
				echo CHtml::link(CHtml::encode($data['COI_PREP_DESC']), array('view', 'id'=>$data['COI_PREP']));
			}
			?>
		</div>
		
		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_PREP')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->COI_PREP), array('view', 'id'=>$data->COI_PREP)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('COI_PREP_DESC')); ?>:</b>
		<?php echo CHtml::encode($data->COI_PREP_DESC); ?>
		<br />

		*/ ?>
	</div>
	<div class="clearfix"></div>
</div>