<?php
$this->breadcrumbs=array(
	'Stores'=>array('index'),
	$model->STO_ID,
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'Update Stores', 'url'=>array('update', 'id'=>$model->STO_ID)),
	array('label'=>'Delete Stores', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1>View Stores #<?php echo $model->STO_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STO_ID',
		'STO_LOC_GPS',
		'STO_LOC_ADDR',
		'SUP_ID',
	),
)); ?>
