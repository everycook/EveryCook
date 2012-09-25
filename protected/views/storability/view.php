<?php
$this->breadcrumbs=array(
	'Storabilities'=>array('index'),
	$model->STB_ID,
);

$this->menu=array(
	array('label'=>'List Storability', 'url'=>array('index')),
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'Update Storability', 'url'=>array('update', 'id'=>$model->STB_ID)),
	array('label'=>'Delete Storability', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STB_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_STORABILITY_VIEW, $model->STB_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STB_ID',
		'STB_DESC_EN',
		'STB_DESC_DE',
	),
)); ?>
