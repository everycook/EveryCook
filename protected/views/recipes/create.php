<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);
?>

<h1>Create Recipes</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'recipeTypes'=>$recipeTypes,
	'stepTypes'=>$stepTypes,
	'actions'=>$actions,
	'ingredients'=>$ingredients,
	'stepTypeConfig'=>$stepTypeConfig,
	)); ?>