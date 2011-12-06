<?php
$this->breadcrumbs=array(
	'Storabilities'=>array('index'),
	$model->STORAB_ID,
);

$this->menu=array(
	array('label'=>'List Storability', 'url'=>array('index')),
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'Update Storability', 'url'=>array('update', 'id'=>$model->STORAB_ID)),
	array('label'=>'Delete Storability', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STORAB_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1>View Storability #<?php echo $model->STORAB_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STORAB_ID',
		'STORAB_DESC_EN',
		'STORAB_DESC_DE',
	),
)); ?>
