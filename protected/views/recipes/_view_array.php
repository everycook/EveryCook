<div class="resultArea">
	<?php 
	if ($this->isFancyAjaxRequest){
		echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$data['REC_IMG_AUTH'], 'title'=>$data['REC_IMG_AUTH']));
	
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['REC_ID'], array('class'=>'f-right button RecipeSelect'));
	} else {
		echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$data['REC_ID'], 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$data['REC_IMG_AUTH'], 'title'=>$data['REC_IMG_AUTH'])), array('view', 'id'=>$data['REC_ID'])); 
		echo '<div class="options">';
			//echo CHtml::link('+', array('user/addrecipes', 'id'=>$data['REC_ID']), array('class'=>'button backpic addRecipe', 'title'=>$this->trans->RECIPES_ADD));
			echo CHtml::link('&nbsp;', array('meals/mealPlanner', 'rec_id'=>$data['REC_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->RECIPES_MEALPLANNER));
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['REC_ID']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['REC_ID']), array('class'=>'disgusting noAjax backpic','title'=>$this->trans->GENERAL_DISGUSTING)) . '<br>';
			echo CHtml::link(CHtml::encode($this->trans->RECIPES_VIEW_RECIPE), array('view', 'id'=>$data['REC_ID']), array('class'=>'button last'));
		echo '</div>';
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['REC_NAME_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['REC_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['REC_ID']));
			}
			?>
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
</div>