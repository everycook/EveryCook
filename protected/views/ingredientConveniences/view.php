<?php
$this->breadcrumbs=array(
	'Ingredient Conveniences'=>array('index'),
	$model->ICO_ID,
);

$this->menu=array(
	array('label'=>'List IngredientConveniences', 'url'=>array('index')),
	array('label'=>'Create IngredientConveniences', 'url'=>array('create')),
	array('label'=>'Update IngredientConveniences', 'url'=>array('update', 'id'=>$model->ICO_ID)),
	array('label'=>'Delete IngredientConveniences', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ICO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IngredientConveniences', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_INGREDIENTCONVENIENCES_VIEW, $model->ICO_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ICO_ID',
		'ICO_DESC_EN',
		'ICO_DESC_DE',
	),
)); ?>
