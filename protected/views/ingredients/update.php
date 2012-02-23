<?php
$this->breadcrumbs=array(
	'Ingredients'=>array('index'),
	$model->ING_ID=>array('view','id'=>$model->ING_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ingredients', 'url'=>array('index')),
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'View Ingredients', 'url'=>array('view', 'id'=>$model->ING_ID)),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);
?>

<h1>Update Ingredients <?php echo $model->ING_ID; ?></h1>

<?php echo $this->renderPartial('_form',array(
			'model'=>$model,
			'nutrientData'=>$nutrientData,
			'groupNames'=>$groupNames,
			'subgroupNames'=>$subgroupNames,
			'ingredientConveniences'=>$ingredientConveniences,
			'storability'=>$storability,
			'ingredientStates'=>$ingredientStates,
			)); ?>