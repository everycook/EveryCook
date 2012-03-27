<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->PRO_ID=>array('view','id'=>$model->PRO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->PRO_ID)),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
?>

<h1>Update Products <?php echo $model->PRO_ID; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'ecology'=>$ecology,
	'ethicalCriteria'=>$ethicalCriteria,
)); ?>