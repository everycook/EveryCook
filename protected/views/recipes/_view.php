<div class="resultArea">
	<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data->REC_ID)), '', array('class'=>'recipe')), array('view', 'id'=>$data->REC_ID)); ?>
	
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$data->REC_ID), array('class'=>'button addRecipe', 'title'=>'add')); ?><br>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data->REC_ID), array('class'=>'delicious', 'title'=>'delicious')); ?>
		<?php echo CHtml::link('&nbsp;', array('disquesting', 'id'=>$data->REC_ID), array('class'=>'disquesting','title'=>'disquesting')); ?>
		<?php echo CHtml::link(CHtml::encode('view food'), array('view', 'id'=>$data->REC_ID), array('class'=>'button')); ?>
	</div>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data->__get('REC_NAME_' . Yii::app()->session['lang'])), array('view', 'id'=>$data->REC_ID)); ?>
		</div>
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CREATED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CREATED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CHANGED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CHANGED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_IMG')); ?>:</b>
		<?php echo CHtml::encode($data->REC_IMG); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_IMG_AUTH')); ?>:</b>
		<?php echo CHtml::encode($data->REC_IMG_AUTH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('RET_ID')); ?>:</b>
		<?php echo CHtml::encode($data->RET_ID); ?>
		<br />
	</div>

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_NAME_EN')); ?>:</b>
	<?php echo CHtml::encode($data->REC_NAME_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_NAME_DE')); ?>:</b>
	<?php echo CHtml::encode($data->REC_NAME_DE); ?>
	<br />

	*/ ?>

</div>