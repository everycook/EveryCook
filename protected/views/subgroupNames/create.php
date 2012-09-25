<?php
$this->breadcrumbs=array(
	'Subgroup Names'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SubgroupNames', 'url'=>array('index')),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_SUBGROUPNAMES_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>