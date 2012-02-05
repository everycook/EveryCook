<?php
$this->breadcrumbs=array(
	'Subgroup Names'=>array('index'),
	$model->SUBGRP_ID=>array('view','id'=>$model->SUBGRP_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List SubgroupNames', 'url'=>array('index')),
	array('label'=>'Create SubgroupNames', 'url'=>array('create')),
	array('label'=>'View SubgroupNames', 'url'=>array('view', 'id'=>$model->SUBGRP_ID)),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1>Update SubgroupNames <?php echo $model->SUBGRP_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>