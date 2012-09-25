<?php
$this->breadcrumbs=array(
	'Subgroup Names'=>array('index'),
	$model->SGR_ID,
);

$this->menu=array(
	array('label'=>'List SubgroupNames', 'url'=>array('index')),
	array('label'=>'Create SubgroupNames', 'url'=>array('create')),
	array('label'=>'Update SubgroupNames', 'url'=>array('update', 'id'=>$model->SGR_ID)),
	array('label'=>'Delete SubgroupNames', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SGR_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_SUBGROUPNAMES_VIEW, $model->SGR_ID); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'SGR_ID',
		'GRP_ID',
		'SGR_DESC_EN',
		'SGR_DESC_DE',
	),
)); ?>
