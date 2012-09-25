<?php
$this->breadcrumbs=array(
	'Ingredient Conveniences'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IngredientConveniences', 'url'=>array('index')),
	array('label'=>'Manage IngredientConveniences', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_INGREDIENTCONVENIENCES_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>