<?php
$this->breadcrumbs=array(
	'Ingredient Conveniences'=>array('index'),
	$model->CONV_ID=>array('view','id'=>$model->CONV_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List IngredientConveniences', 'url'=>array('index')),
	array('label'=>'Create IngredientConveniences', 'url'=>array('create')),
	array('label'=>'View IngredientConveniences', 'url'=>array('view', 'id'=>$model->CONV_ID)),
	array('label'=>'Manage IngredientConveniences', 'url'=>array('admin')),
);
?>

<h1>Update IngredientConveniences <?php echo $model->CONV_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>