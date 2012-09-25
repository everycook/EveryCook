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

<h1><?php printf($this->trans->TITLE_INGREDIENTS_VIEW, $model->ING_ID); ?></h1>

<?php 
//TODO: show list result as detail
//$this->renderPartial('_view_array', array('data'=>$model));
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ING_ID',
		'PRF_UID',
		array(
            'label'=>'nut',
			'type'=>'raw',
            'value'=>CHtml::link(CHtml::encode($model->NUT_ID), array('nutrientData/view','id'=>$model->NUT_ID)),
		),
		'GRP_ID',
		'SGR_ID',
		'IST_ID',
		'ICO_ID',
		'STB_ID',
		'ING_DENSITY',
		//'ING_IMG',
/*        array(
           'label'=>'Image',
           'type'=>'image',
           'name'=>$model->ING_IMG,
           'value'=>$model->ING_IMG,
        'value'=>'Yii::app()->request->baseUrl."/upload/".$data->file_content'

        
        ),*/
		'ING_IMG_AUTH',
		'ING_NAME_EN_GB',
		'ING_NAME_DE_CH',
	),
)); ?>
