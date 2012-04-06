<div class="resultArea">
	<!-- STL show image -->
	<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')), array('view', 'id'=>$data->ING_ID)); ?>
	
	<div class="options">
		<?php echo CHtml::link(CHtml::encode('Cook with'), array('recipes/search', 'ing_id'=>$data->ING_ID), array('class'=>'button')); ?>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data->ING_ID), array('class'=>'delicious', 'title'=>'delicious')); ?>
		<?php echo CHtml::link('&nbsp;', array('disquesting', 'id'=>$data->ING_ID), array('class'=>'disquesting','title'=>'disquesting')); ?>
		<?php echo CHtml::link(CHtml::encode('view food'), array('view', 'id'=>$data->ING_ID), array('class'=>'button')); ?>
	</div>
	
	<div class="data">
		<div class="name">
		<?php echo CHtml::link(CHtml::encode($data->__get('ING_NAME_' . Yii::app()->session['lang'])), array('view', 'id'=>$data->ING_ID)); ?>
		</div>
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('NUT_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->nutrientData->NUT_DESC), array('nutrientData/view', 'id'=>$data->nutrientData->NUT_ID)); ?>
		<br />
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('GRP_ID')); ?>:</b>
		<?php echo CHtml::encode($data->groupNames->__get('GRP_DESC_' . Yii::app()->session['lang'])); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('SGR_ID')); ?>:</b>
		<?php echo CHtml::encode($data->subgroupNames->__get('SGR_DESC_' . Yii::app()->session['lang'])); ?>
		<br />
		
		<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_ID')); ?>:</b>
		<?php echo CHtml::encode($data->ingredientConveniences->__get('ICO_DESC_' . Yii::app()->session['lang'])); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('STB_ID')); ?>:</b>
		<?php echo CHtml::encode($data->storability->__get('STB_DESC_' . Yii::app()->session['lang'])); ?>
		<br />
	</div>
	
	<?php /*
	
		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->ING_ID), array('view', 'id'=>$data->ING_ID)); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</b>
		<?php echo CHtml::encode($data->PRF_UID); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_CREATED')); ?>:</b>
		<?php echo CHtml::encode($data->ING_CREATED); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('ING_CHANGED')); ?>:</b>
		<?php echo CHtml::encode($data->ING_CHANGED); ?>
		<br />
		
	<b><?php echo CHtml::encode($data->getAttributeLabel('IST_ID')); ?>:</b>
	<?php echo CHtml::encode($data->IST_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ICO_ID')); ?>:</b>
	<?php echo CHtml::encode($data->ICO_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STB_ID')); ?>:</b>
	<?php echo CHtml::encode($data->STB_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_DENSITY')); ?>:</b>
	<?php echo CHtml::encode($data->ING_DENSITY); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG')); ?>:</b>
	<?php echo CHtml::encode($data->ING_IMG); ?><br />
	
	
	<!-- STL show image -->
	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')); ?><br />
	
	
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_IMG_AUTH')); ?>:</b>
	<?php echo CHtml::encode($data->ING_IMG_AUTH); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_EN')); ?>:</b>
	<?php echo CHtml::encode($data->ING_NAME_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_NAME_DE')); ?>:</b>
	<?php echo CHtml::encode($data->ING_NAME_DE); ?>
	<br />

	*/ ?>

</div>