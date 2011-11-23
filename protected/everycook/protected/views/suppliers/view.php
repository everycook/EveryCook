<?php
$this->breadcrumbs=array(
	'Suppliers'=>array('index'),
	$model->SUP_ID,
);

$this->menu=array(
	array('label'=>'List Suppliers', 'url'=>array('index')),
	array('label'=>'Create Suppliers', 'url'=>array('create')),
	array('label'=>'Update Suppliers', 'url'=>array('update', 'id'=>$model->SUP_ID)),
	array('label'=>'Delete Suppliers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SUP_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Suppliers', 'url'=>array('admin')),
);
?>

<h1>View Suppliers #<?php echo $model->SUP_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'SUP_ID',
		'SUP_NAME',
	),
)); ?>
