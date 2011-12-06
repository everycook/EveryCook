<?php
$this->breadcrumbs=array(
	'Profiles'=>array('index'),
	$model->PRF_UID=>array('view','id'=>$model->PRF_UID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Profiles', 'url'=>array('index')),
	array('label'=>'Create Profiles', 'url'=>array('create')),
	array('label'=>'View Profiles', 'url'=>array('view', 'id'=>$model->PRF_UID)),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>

<h1>Update Profiles <?php echo $model->PRF_UID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>