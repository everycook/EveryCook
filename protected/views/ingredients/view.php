<?php
$this->breadcrumbs=array(
	'Ingredients'=>array('index'),
	$model->ING_ID,
);

$this->menu=array(
	array('label'=>'List Ingredients', 'url'=>array('index')),
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'Update Ingredients', 'url'=>array('update', 'id'=>$model->ING_ID)),
	array('label'=>'Delete Ingredients', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ING_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);
?>

<h1>View Ingredients #<?php echo $model->ING_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ING_ID',
		'PRF_UID',
		'ING_CREATED',
		'ING_CHANGED',
		array(
            'label'=>'nut',
			'type'=>'raw',
            'value'=>CHtml::link(CHtml::encode($model->NUT_ID), array('nutrientData/view','id'=>$model->NUT_ID)),
		),
		'ING_GROUP',
		'ING_SUBGROUP',
		'ING_STATE',
		'ING_CONVENIENCE',
		'ING_STORABILITY',
		'ING_DENSITY',
		'ING_PICTURE',
/*        array(
           'label'=>'Image',
           'type'=>'image',
           'name'=>$model->ING_PICTURE,
           'value'=>$model->ING_PICTURE,
        'value'=>'Yii::app()->request->baseUrl."/upload/".$data->file_content'

        
        ),*/
		'ING_PICTURE_AUTH',
		'ING_TITLE_EN',
		'ING_TITLE_DE',
	),
)); ?>
