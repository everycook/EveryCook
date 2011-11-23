<?php
$this->breadcrumbs=array(
	'Ingredient States'=>array('index'),
	$model->STATE_ID,
);

$this->menu=array(
	array('label'=>'List IngredientStates', 'url'=>array('index')),
	array('label'=>'Create IngredientStates', 'url'=>array('create')),
	array('label'=>'Update IngredientStates', 'url'=>array('update', 'id'=>$model->STATE_ID)),
	array('label'=>'Delete IngredientStates', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STATE_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IngredientStates', 'url'=>array('admin')),
);
?>

<h1>View IngredientStates #<?php echo $model->STATE_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STATE_ID',
		'STATE_DESC_EN',
		'STATE_DESC_DE',
	),
)); ?>
