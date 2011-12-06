<?php
$this->breadcrumbs=array(
	'Suppliers'=>array('index'),
	$model->SUP_ID=>array('view','id'=>$model->SUP_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Suppliers', 'url'=>array('index')),
	array('label'=>'Create Suppliers', 'url'=>array('create')),
	array('label'=>'View Suppliers', 'url'=>array('view', 'id'=>$model->SUP_ID)),
	array('label'=>'Manage Suppliers', 'url'=>array('admin')),
);
?>

<h1>Update Suppliers <?php echo $model->SUP_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>