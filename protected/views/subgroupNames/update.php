<?php
$this->breadcrumbs=array(
	'Subgroup Names'=>array('index'),
	$model->SGR_ID=>array('view','id'=>$model->SGR_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List SubgroupNames', 'url'=>array('index')),
	array('label'=>'Create SubgroupNames', 'url'=>array('create')),
	array('label'=>'View SubgroupNames', 'url'=>array('view', 'id'=>$model->SGR_ID)),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_SUBGROUPNAMES_UPDATE, $model->SGR_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>