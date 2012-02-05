<?php
$this->breadcrumbs=array(
	'Ingredient Conveniences'=>array('index'),
	$model->CONV_ID,
);

$this->menu=array(
	array('label'=>'List IngredientConveniences', 'url'=>array('index')),
	array('label'=>'Create IngredientConveniences', 'url'=>array('create')),
	array('label'=>'Update IngredientConveniences', 'url'=>array('update', 'id'=>$model->CONV_ID)),
	array('label'=>'Delete IngredientConveniences', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->CONV_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IngredientConveniences', 'url'=>array('admin')),
);
?>

<h1>View IngredientConveniences #<?php echo $model->CONV_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'CONV_ID',
		'CONV_DESC_EN',
		'CONV_DESC_DE',
	),
)); ?>
