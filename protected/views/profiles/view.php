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

<h1><?php echo $this->trans->TITLE_PROFILES_VIEW; ?></h1>

<input type="hidden" id="centerGPSHome" value="<?php if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[0])){echo Yii::app()->user->home_gps[0] . ',' . Yii::app()->user->home_gps[1];} ?>"/>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'PRF_UID',
		'PRF_FIRSTNAME',
		'PRF_LASTNAME',
		'PRF_NICK',
		'PRF_GENDER',
		'PRF_BIRTHDAY',
		'PRF_EMAIL',
		'PRF_LANG',
		//'PRF_IMG_FILENAME',
		//'PRF_PW',
		'PRF_LOC_GPS_LAT',
		'PRF_LOC_GPS_LNG',
/*		'PRF_LOC_GPS_POINT',
		'PRF_LIKES_I',
		'PRF_LIKES_R',
		'PRF_LIKES_P',
		'PRF_LIKES_S',
		'PRF_NOTLIKES_I',
		'PRF_NOTLIKES_R',
		'PRF_NOTLIKES_P',
		'PRF_SHOPLISTS',
		'PRF_ACTIVE',
		'PRF_RND',
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',*/
	),
)); ?>
