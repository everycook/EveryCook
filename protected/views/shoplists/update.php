<?php
$this->breadcrumbs=array(
	'Shoplists'=>array('index'),
	$model->SHO_ID=>array('view','id'=>$model->SHO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Shoplists', 'url'=>array('index')),
	array('label'=>'Create Shoplists', 'url'=>array('create')),
	array('label'=>'View Shoplists', 'url'=>array('view', 'id'=>$model->SHO_ID)),
	array('label'=>'Manage Shoplists', 'url'=>array('admin')),
);
?>

<h1>Update Shoplists <?php echo $model->SHO_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>