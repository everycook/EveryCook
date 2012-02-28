<?php
$this->breadcrumbs=array(
	'Steps'=>array('index'),
	$model->REC_ID,
);

$this->menu=array(
	array('label'=>'List Steps', 'url'=>array('index')),
	array('label'=>'Create Steps', 'url'=>array('create')),
	array('label'=>'Update Steps', 'url'=>array('update', 'id'=>$model->REC_ID)),
	array('label'=>'Delete Steps', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->REC_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Steps', 'url'=>array('admin')),
);
?>

<h1>View Steps #<?php echo $model->REC_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'ACT_ID',
		'ING_ID',
		'STE_STEP_NO',
		'STE_GRAMS',
		'STE_CELSIUS',
		'STE_KPA',
		'STE_RPM',
		'STE_CLOCKWISE',
		'STE_STIR_RUN',
		'STE_STIR_PAUSE',
		'STE_STEP_DURATION',
		'STT_ID',
	),
)); ?>
