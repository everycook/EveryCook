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

<h1>Create SubgroupNames</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>