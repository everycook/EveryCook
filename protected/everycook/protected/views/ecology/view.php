<?php
$this->breadcrumbs=array(
	'Ecologys'=>array('index'),
	$model->ECO_ID,
);

$this->menu=array(
	array('label'=>'List Ecology', 'url'=>array('index')),
	array('label'=>'Create Ecology', 'url'=>array('create')),
	array('label'=>'Update Ecology', 'url'=>array('update', 'id'=>$model->ECO_ID)),
	array('label'=>'Delete Ecology', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ECO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ecology', 'url'=>array('admin')),
);
?>

<h1>View Ecology #<?php echo $model->ECO_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ECO_ID',
		'ECO_DESC_EN',
		'ECO_DESC_DE',
	),
)); ?>
