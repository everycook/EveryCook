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
			<?php echo CHtml::link(CHtml::encode($data->__get('REC_TITLE_' . Yii::app()->session['lang'])), array('view', 'id'=>$data->REC_ID)); ?>
		</div>
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CREATED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CREATED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_CHANGED')); ?>:</b>
		<?php echo CHtml::encode($data->REC_CHANGED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_PICTURE')); ?>:</b>
		<?php echo CHtml::encode($data->REC_PICTURE); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_PICTURE_AUTH')); ?>:</b>
		<?php echo CHtml::encode($data->REC_PICTURE_AUTH); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TYPE')); ?>:</b>
		<?php echo CHtml::encode($data->REC_TYPE); ?>
		<br />
	</div>

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TITLE_EN')); ?>:</b>
	<?php echo CHtml::encode($data->REC_TITLE_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('REC_TITLE_DE')); ?>:</b>
	<?php echo CHtml::encode($data->REC_TITLE_DE); ?>
	<br />

	*/ ?>

</div>