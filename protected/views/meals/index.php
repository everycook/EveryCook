<?php
$this->breadcrumbs=array(
	'Meals',
);

$this->menu=array(
	array('label'=>'Create Meals', 'url'=>array('create')),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1>Meals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
