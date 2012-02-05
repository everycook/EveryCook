<?php
$this->breadcrumbs=array(
	'Recipe Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RecipeTypes', 'url'=>array('index')),
	array('label'=>'Manage RecipeTypes', 'url'=>array('admin')),
);
?>

<h1>Create RecipeTypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>