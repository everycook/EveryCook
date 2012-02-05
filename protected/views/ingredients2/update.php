<?php
$this->breadcrumbs=array(
	'Ingredients2s'=>array('index'),
	$model->ING_ID=>array('view','id'=>$model->ING_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ingredients2', 'url'=>array('index')),
	array('label'=>'Create Ingredients2', 'url'=>array('create')),
	array('label'=>'View Ingredients2', 'url'=>array('view', 'id'=>$model->ING_ID)),
	array('label'=>'Manage Ingredients2', 'url'=>array('admin')),
);
?>

<h1>Update Ingredients2 <?php echo $model->ING_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>