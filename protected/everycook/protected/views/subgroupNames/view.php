<?php
$this->breadcrumbs=array(
	'Subgroup Names'=>array('index'),
	$model->SUBGRP_ID,
);

$this->menu=array(
	array('label'=>'List SubgroupNames', 'url'=>array('index')),
	array('label'=>'Create SubgroupNames', 'url'=>array('create')),
	array('label'=>'Update SubgroupNames', 'url'=>array('update', 'id'=>$model->SUBGRP_ID)),
	array('label'=>'Delete SubgroupNames', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SUBGRP_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1>View SubgroupNames #<?php echo $model->SUBGRP_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'SUBGRP_ID',
		'SUBGRP_OF',
		'SUBGRP_DESC_EN',
		'SUBGRP_DESC_DE',
	),
)); ?>
