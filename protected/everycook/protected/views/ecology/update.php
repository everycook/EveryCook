<?php
$this->breadcrumbs=array(
	'Ecologys'=>array('index'),
	$model->ECO_ID=>array('view','id'=>$model->ECO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ecology', 'url'=>array('index')),
	array('label'=>'Create Ecology', 'url'=>array('create')),
	array('label'=>'View Ecology', 'url'=>array('view', 'id'=>$model->ECO_ID)),
	array('label'=>'Manage Ecology', 'url'=>array('admin')),
);
?>

<h1>Update Ecology <?php echo $model->ECO_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>