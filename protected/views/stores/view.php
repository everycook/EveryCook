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

<h1><?php printf($this->trans->TITLE_STORES_VIEW, $model->STO_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STO_ID',
		'STO_NAME',
		'STO_STREET',
		'STO_HOUSE_NO',
		'STO_ZIP',
		'STO_CITY',
		'STO_COUNTRY',
		'STO_STATE',
		'STY_ID',
		'STO_GPS_LAT',
		'STO_GPS_LNG',
		'STO_GPS_POINT',
		'STO_PHONE',
		//'STO_IMG_FILENAME',
		'STO_IMG_AUTH',
		'STO_IMG_ETAG',
		'SUP_ID',
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',
	),
)); ?>
