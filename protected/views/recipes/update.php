<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	$model->REC_ID=>array('view','id'=>$model->REC_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'View Recipes', 'url'=>array('view', 'id'=>$model->REC_ID)),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);
?>

<h1>Update Recipes <?php echo $model->REC_ID; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'recipeTypes'=>$recipeTypes,
	'stepTypes'=>$stepTypes,
	'actions'=>$actions,
	'ingredients'=>$ingredients,
	'stepTypeConfig'=>$stepTypeConfig,
	)); ?>