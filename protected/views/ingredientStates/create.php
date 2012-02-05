<?php
$this->breadcrumbs=array(
	'Ingredient States'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IngredientStates', 'url'=>array('index')),
	array('label'=>'Manage IngredientStates', 'url'=>array('admin')),
);
?>

<h1>Create IngredientStates</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>