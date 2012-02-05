<?php
$this->breadcrumbs=array(
	'Step Types'=>array('index'),
	$model->STT_ID,
);

$this->menu=array(
	array('label'=>'List StepTypes', 'url'=>array('index')),
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'Update StepTypes', 'url'=>array('update', 'id'=>$model->STT_ID)),
	array('label'=>'Delete StepTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STT_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);
?>

<h1>View StepTypes #<?php echo $model->STT_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STT_ID',
		'STT_DESC_EN',
		'STT_DESC_DE',
	),
)); ?>
