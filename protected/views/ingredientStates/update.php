<?php
$this->breadcrumbs=array(
	'Ingredient States'=>array('index'),
	$model->STATE_ID=>array('view','id'=>$model->STATE_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List IngredientStates', 'url'=>array('index')),
	array('label'=>'Create IngredientStates', 'url'=>array('create')),
	array('label'=>'View IngredientStates', 'url'=>array('view', 'id'=>$model->STATE_ID)),
	array('label'=>'Manage IngredientStates', 'url'=>array('admin')),
);
?>

<h1>Update IngredientStates <?php echo $model->STATE_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>