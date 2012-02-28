<div class="resultArea">
	<!-- STL show image -->
	<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_PICTURE_AUTH'], 'title'=>$data['ING_PICTURE_AUTH'])), array('view', 'id'=>$data['ING_ID'])); ?>
	
	<div class="options">
		<?php echo CHtml::link(CHtml::encode($this->trans->INGREDIENTS_COOK_WITH), array('recipes/search', 'ing_id'=>$data['ING_ID']), array('class'=>'button')); ?>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['ING_ID']), array('class'=>'delicious', 'title'=>$this->trans->INGREDIENTS_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['ING_ID']), array('class'=>'disgusting','title'=>$this->trans->INGREDIENTS_DISGUSTING)); ?>
		<?php echo CHtml::link(CHtml::encode($this->trans->INGREDIENTS_VIEW_FOOD), array('view', 'id'=>$data['ING_ID']), array('class'=>'button last')); ?>
	</div>
	
	<div class="data">
		<div class="name">
		<?php echo CHtml::link(CHtml::encode($data['ING_TITLE_'.Yii::app()->session['lang']]), array('view', 'id'=>$data['ING_ID'])); ?>
		</div>
		
		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_NUTRIENT); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data['NUT_DESC']), array('nutrientData/view', 'id'=>$data['NUT_ID'])); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_GROUP); ?>:</b>
		<?php echo CHtml::encode($data['GRP_DESC_'.Yii::app()->session['lang']]); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_SUBGROUP); ?>:</b>
		<?php echo CHtml::encode($data['SUBGRP_DESC_'.Yii::app()->session['lang']]); ?>
		<br />
		
		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_CONVENIENCE); ?>:</b>
		<?php echo CHtml::encode($data['CONV_DESC_'.Yii::app()->session['lang']]); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_STORABILITY); ?>:</b>
		<?php echo CHtml::encode($data['STORAB_DESC_'.Yii::app()->session['lang']]); ?>
		<br />
		
		<b><?php echo CHtml::encode($this->trans->INGREDIENTS_STATE); ?>:</b>
		<?php echo CHtml::encode($data['STATE_DESC_'.Yii::app()->session['lang']]); ?>
		<br />
	</div>
	
	<div class="clearfix"></div>
	
	<?php
	/*
	
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
		
	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_STATE')); ?>:</b>
	<?php echo CHtml::encode($data->ING_STATE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_CONVENIENCE')); ?>:</b>
	<?php echo CHtml::encode($data->ING_CONVENIENCE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_STORABILITY')); ?>:</b>
	<?php echo CHtml::encode($data->ING_STORABILITY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_DENSITY')); ?>:</b>
	<?php echo CHtml::encode($data->ING_DENSITY); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_PICTURE')); ?>:</b>
	<?php echo CHtml::encode($data->ING_PICTURE); ?><br />
	
	
	<!-- STL show image -->
	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')); ?><br />
	
	
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_PICTURE_AUTH')); ?>:</b>
	<?php echo CHtml::encode($data->ING_PICTURE_AUTH); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_TITLE_EN')); ?>:</b>
	<?php echo CHtml::encode($data->ING_TITLE_EN); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_TITLE_DE')); ?>:</b>
	<?php echo CHtml::encode($data->ING_TITLE_DE); ?>
	<br />

	*/ ?>

</div>