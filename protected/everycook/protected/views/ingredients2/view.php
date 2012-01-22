<?php
$this->breadcrumbs=array(
	'Ingredients2s'=>array('index'),
	$model->ING_ID,
);

$this->menu=array(
	array('label'=>'List Ingredients2', 'url'=>array('index')),
	array('label'=>'Create Ingredients2', 'url'=>array('create')),
	array('label'=>'Update Ingredients2', 'url'=>array('update', 'id'=>$model->ING_ID)),
	array('label'=>'Delete Ingredients2', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ING_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ingredients2', 'url'=>array('admin')),
);
?>

<h1>View Ingredients2 #<?php echo $model->ING_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ING_ID',
		'PRF_UID',
		'ING_CREATED',
		'ING_CHANGED',
		'NUT_ID',
		'ING_GROUP',
		'ING_SUBGROUP',
		'ING_STATE',
		'ING_CONVENIENCE',
		'ING_STORABILITY',
		'ING_DENSITY',
		'ING_PICTURE',
		'ING_PICTURE_AUTH',
		'ING_TITLE_EN',
		'ING_TITLE_DE',
	),
)); ?>
