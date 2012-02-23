<?php
$this->breadcrumbs=array(
	'Steps2s'=>array('index'),
	$model->STE_ID,
);

$this->menu=array(
	array('label'=>'List Steps2', 'url'=>array('index')),
	array('label'=>'Create Steps2', 'url'=>array('create')),
	array('label'=>'Update Steps2', 'url'=>array('update', 'id'=>$model->STE_ID)),
	array('label'=>'Delete Steps2', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STE_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Steps2', 'url'=>array('admin')),
);
?>

<h1>View Steps2 #<?php echo $model->STE_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STE_ID',
		'REC_ID',
		'ACT_ID',
		'ING_ID',
		'STE_STEP_NO',
		'STE_GRAMS',
		'STE_T_BOTTOM',
		'STE_T_LID',
		'STE_T_STEAM',
		'STE_BAR',
		'STE_RPM',
		'STE_CLOCKWISE',
		'STE_STIR_RUN',
		'STE_STIR_PAUSE',
		'STE_STEP_DURATION',
		'STT_ID',
	),
)); ?>
