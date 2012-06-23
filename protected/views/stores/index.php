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

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
