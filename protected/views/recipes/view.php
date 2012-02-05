<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	$model->REC_ID,
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'Update Recipes', 'url'=>array('update', 'id'=>$model->REC_ID)),
	array('label'=>'Delete Recipes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->REC_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);
?>

<h1>View Recipes #<?php echo $model->REC_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'REC_CREATED',
		'REC_CHANGED',
		'REC_PICTURE',
		'REC_PICTURE_AUTH',
		'REC_TYPE',
		'REC_TITLE_EN',
		'REC_TITLE_DE',
	),
)); ?>
