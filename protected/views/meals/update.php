<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	$model->MEA_ID=>array('view','id'=>$model->MEA_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Meals', 'url'=>array('index')),
	array('label'=>'Create Meals', 'url'=>array('create')),
	array('label'=>'View Meals', 'url'=>array('view', 'id'=>$model->MEA_ID)),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_MEALS_CREATE, $model->MEA_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>