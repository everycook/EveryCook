<div class="resultArea">
	<?php echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'png')), '', array('class'=>'recipe', 'alt'=>$data['REC_IMG_AUTH'], 'title'=>$data['REC_IMG_AUTH'])), array('view', 'id'=>$data['REC_ID'])); ?>
	
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$data['REC_ID']), array('class'=>'button backpic addRecipe', 'title'=>$this->trans->RECIPES_ADD)); ?><br>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['REC_ID']), array('class'=>'delicious backpic', 'title'=>$this->trans->GENERAL_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['REC_ID']), array('class'=>'disgusting backpic','title'=>$this->trans->GENERAL_DISGUSTING)); ?><br>
		<?php echo CHtml::link(CHtml::encode($this->trans->RECIPES_VIEW_RECIPE), array('view', 'id'=>$data['REC_ID']), array('class'=>'button last')); ?>
	</div>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['REC_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['REC_ID'])); ?>
		</div>

<?php /*
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CREATED']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_CHANGED']); ?>
		<br />
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_IMG']); ?>
		<br />

		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($data['REC_IMG_AUTH']); ?>
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