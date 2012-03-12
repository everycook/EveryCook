<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	$model->REC_ID,
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'Update Recipes', 'url'=>array('update', 'id'=>$model->REC_ID)),
	array('label'=>'Delete Recipes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->REC_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->RECIPES_EDIT, 'link_id'=>'middle_single', 'url'=>array('recipes/update',$this->getActionParams())),
);
?>

<div class="detailView">
	<?php
	if (isset(Yii::app()->session['Recipe'])){
		echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/search'), array('class'=>'button'));
	}
	?><br>
	<br>
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$model->REC_ID), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD)); ?><br>
		<div class="ingredients">
			<?php echo CHtml::encode($this->trans->RECIPES_INGREDIENTS_NEEDED); ?>
			<ul>
			<?php foreach($model->steps as $step){
				if ($step->ingredient != null){
					echo '<li>' . CHtml::link($step->ingredient->__get('ING_TITLE_' . Yii::app()->session['lang']), array('ingredients/view', 'id'=>$step->ingredient->ING_ID), array('title'=>$this->trans->RECIPES_TOOLTIP_OPEN_INGREDIENT)) . '</li>';
				}
			}
			?>
			</ul>
		</div>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model->REC_ID), array('class'=>'delicious_big last', 'title'=>$this->trans->RECIPES_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model->REC_ID), array('class'=>'disgusting_big last','title'=>$this->trans->RECIPES_DISGUSTING)); ?>
	</div>
	
	<div class="details">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($model->__get('REC_TITLE_' . Yii::app()->session['lang'])), array('view', 'id'=>$model->REC_ID)); ?>
		</div>
		<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'png')), '', array('class'=>'recipe', 'alt'=>$model->REC_PICTURE_AUTH, 'title'=>$model->REC_PICTURE_AUTH)); ?><br />
		
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($model->recipeTypes->__get('RET_DESC_' . Yii::app()->session['lang'])); ?>
		<br /><br />
		
		<?php 
			$i = 1;
			foreach($model->steps as $step){
				echo '<div class="step">';
				echo '<span class="stepNo">' . $i . '.</span> ';
				if ($step->stepType){
					echo '<span class="stepType">' . $step->stepType->__get('STT_DESC_' . Yii::app()->session['lang']) . ':</span> ';
				}
				$text = $step->action->__get('ACT_DESC_' . Yii::app()->session['lang']);
				if ($step->ingredient != null){
					$replText = '<span class="igredient">' . $step->ingredient->__get('ING_TITLE_' . Yii::app()->session['lang']) . '</span> ';
					if ($step->STE_GRAMS){
						$replText .= '<span class="amount">' . $step->STE_GRAMS . 'g' . '</span> ';
					}
					$text = str_replace('#objectofaction#', $replText, $text);
				}
				echo '<span class="action">' . $text . '</span>';
				echo '</div>';
				$i++;
			}
		?>
		<br />
		<br />
	</div>
	<div class="clearfix"></div>
</div>

<?php print_r($data); /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'REC_CREATED',
		'REC_CHANGED',
		'REC_PICTURE',
		'REC_PICTURE_AUTH',
		'REC_TYPE',
		'REC_TITLE_EN',
		'REC_TITLE_DE',
	),
)); */ ?>
