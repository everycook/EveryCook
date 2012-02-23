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
?>

<div class="detailView">
	<?php echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/search', 'query'=>$model->REC_ID), array('class'=>'button')); ?><br>
	<br>
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$model->REC_ID), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD)); ?><br>
		<div class="ingredients">
			<?php echo CHtml::encode($this->trans->RECIPES_INGREDIENTS_NEEDED); ?>
			<ul>
			<?php foreach($model->steps as $step){
				if ($step->ingredient != null){
					echo '<li>' . CHtml::link($step->ingredient->ING_TITLE_EN, array('ingredients/view', 'id'=>$step->ingredient->ING_ID), array('title'=>$this->trans->RECIPES_TOOLTIP_OPEN_INGREDIENT)) . '</li>';
				}
			}
			?>
			</ul>
		</div>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model->REC_ID), array('class'=>'delicious_big', 'title'=>$this->trans->RECIPES_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model->REC_ID), array('class'=>'disgusting_big','title'=>$this->trans->RECIPES_DISGUSTING)); ?>
	</div>
	
	<div class="details">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($model->REC_TITLE_EN), array('view', 'id'=>$model->REC_ID)); ?>
		</div>
		<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID)), '', array('class'=>'recipe')); ?><br />
		
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($model->recipeTypes->RET_DESC_EN); ?>
		<br /><br />
		
		<?php 
			$i = 0;
			foreach($model->steps as $step){
				echo '<div class="step">';
				echo '<span class="stepNo">' . $i . '.</span> ';
				echo '<span class="igredient">' . $step->ingredient->ING_TITLE_EN . '</span> ';
				echo '<span class="amount">' . $step->STE_GRAMS . 'g' . '</span> ';
				echo '<span class="action">' . $step->stepType->STT_DESC_EN . '</span>';
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
