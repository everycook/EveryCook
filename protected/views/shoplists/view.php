<?php
$this->breadcrumbs=array(
	'Shoplists'=>array('index'),
	$model->SHO_ID,
);

$this->menu=array(
	array('label'=>'List Shoplists', 'url'=>array('index')),
	array('label'=>'Create Shoplists', 'url'=>array('create')),
	array('label'=>'Update Shoplists', 'url'=>array('update', 'id'=>$model->SHO_ID)),
	array('label'=>'Delete Shoplists', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SHO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Shoplists', 'url'=>array('admin')),
);
?>

<h1>View Shoplists #<?php echo $model->SHO_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'SHO_ID',
		'SHO_DATE',
		'SHO_PRODUCTS',
		'SHO_QUANTITIES',
	),
)); ?>
