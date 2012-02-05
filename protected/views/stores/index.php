<?php
$this->breadcrumbs=array(
	'Stores',
);

$this->menu=array(
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1>Stores</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
