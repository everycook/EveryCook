<?php
$this->breadcrumbs=array(
	'Storabilities',
);

$this->menu=array(
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1>Storabilities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
