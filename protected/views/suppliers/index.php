<?php
$this->breadcrumbs=array(
	'Suppliers',
);

$this->menu=array(
	array('label'=>'Create Suppliers', 'url'=>array('create')),
	array('label'=>'Manage Suppliers', 'url'=>array('admin')),
);
?>

<h1>Suppliers</h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
