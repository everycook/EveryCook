<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	$model->MEA_ID,
);

$this->menu=array(
	array('label'=>'List Meals', 'url'=>array('index')),
	array('label'=>'Create Meals', 'url'=>array('create')),
	array('label'=>'Update Meals', 'url'=>array('update', 'id'=>$model->MEA_ID)),
	array('label'=>'Delete Meals', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->MEA_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1>View Meals #<?php echo $model->MEA_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'MEA_ID',
		'MEA_DATE',
		'MEA_TYPE',
		'PRF_UID',
		'CREATED_ON',
		'CREATED_BY',
		'CHANGED_ON',
		'CHANGED_BY',
	),
)); ?>
