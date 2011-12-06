<?php
$this->breadcrumbs=array(
	'Profiles'=>array('index'),
	$model->PRF_UID,
);

$this->menu=array(
	array('label'=>'List Profiles', 'url'=>array('index')),
	array('label'=>'Create Profiles', 'url'=>array('create')),
	array('label'=>'Update Profiles', 'url'=>array('update', 'id'=>$model->PRF_UID)),
	array('label'=>'Delete Profiles', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PRF_UID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>

<h1>View Profiles #<?php echo $model->PRF_UID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'PRF_UID',
		'PRF_FIRSTNAME',
		'PRF_LASTNAME',
		'PRF_NICK',
		'PRF_EMAIL',
		'PRF_PW',
		'PRF_LOC_GPS',
		'PRF_LIKES_I',
		'PRF_LIKES_R',
		'PRF_NOTLIKES_I',
		'PRF_NOTLIKES_R',
		'PRF_SHOPLISTS',
	),
)); ?>
