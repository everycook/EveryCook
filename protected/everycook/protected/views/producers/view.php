<?php
$this->breadcrumbs=array(
	'Producers'=>array('index'),
	$model->PRD_ID,
);

$this->menu=array(
	array('label'=>'List Producers', 'url'=>array('index')),
	array('label'=>'Create Producers', 'url'=>array('create')),
	array('label'=>'Update Producers', 'url'=>array('update', 'id'=>$model->PRD_ID)),
	array('label'=>'Delete Producers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PRD_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Producers', 'url'=>array('admin')),
);
?>

<h1>View Producers #<?php echo $model->PRD_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'PRD_ID',
		'PRD_NAME',
	),
)); ?>
