<?php
$this->breadcrumbs=array(
	'Subgroup Names',
);

$this->menu=array(
	array('label'=>'Create SubgroupNames', 'url'=>array('create')),
	array('label'=>'Manage SubgroupNames', 'url'=>array('admin')),
);
?>

<h1>Subgroup Names</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
