<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->PRO_ID,
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->PRO_ID)),
	array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PRO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
?>

<h1>View Products #<?php echo $model->PRO_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'PRO_ID',
		'PRO_BARCODE',
		'PRO_PACKAGE_GRAMMS',
		'ING_ID',
		'ECO_ID',
		'ETH_ID',
		//'PRO_IMG',
		'PRO_IMG_CR',
	),
)); ?>
