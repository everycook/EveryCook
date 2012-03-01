<div class="resultArea">
	<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'png')), '', array('class'=>'recipe', 'alt'=>$data['REC_PICTURE_AUTH'], 'title'=>$data['REC_PICTURE_AUTH'])), array('view', 'id'=>$data['REC_ID'])); ?>
	
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$data['REC_ID']), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD)); ?><br>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['REC_ID']), array('class'=>'delicious', 'title'=>$this->trans->RECIPES_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['REC_ID']), array('class'=>'disgusting','title'=>$this->trans->RECIPES_DISGUSTING)); ?>
		<?php echo CHtml::link(CHtml::encode($this->trans->RECIPES_VIEW_RECIPE), array('view', 'id'=>$data['REC_ID']), array('class'=>'button last')); ?>
	</div>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['REC_TITLE_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['REC_ID'])); ?>
		</div>

<?php /*
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CREATED']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CHANGED']); ?>
		<br />
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_PICTURE']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_PICTURE_AUTH']); ?>
		<br />
*/ ?>

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['RET_DESC_' . Yii::app()->session['lang']]); ?>
		<br />
	</div>
	<div class="clearfix"></div>
	
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