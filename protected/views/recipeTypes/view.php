<?php
$this->breadcrumbs=array(
	'Recipe Types'=>array('index'),
	$model->RET_ID,
);

$this->menu=array(
	array('label'=>'List RecipeTypes', 'url'=>array('index')),
	array('label'=>'Create RecipeTypes', 'url'=>array('create')),
	array('label'=>'Update RecipeTypes', 'url'=>array('update', 'id'=>$model->RET_ID)),
	array('label'=>'Delete RecipeTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->RET_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RecipeTypes', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_RECIPETYPES_VIEW, $model->RET_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'RET_ID',
		'RET_DESC_EN',
		'RET_DESC_DE',
	),
)); ?>
