<?php
$this->breadcrumbs=array(
	'Storabilities'=>array('index'),
	$model->STORAB_ID=>array('view','id'=>$model->STORAB_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Storability', 'url'=>array('index')),
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'View Storability', 'url'=>array('view', 'id'=>$model->STORAB_ID)),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1>Update Storability <?php echo $model->STORAB_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>