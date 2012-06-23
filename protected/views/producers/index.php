<?php
$this->breadcrumbs=array(
	'Producers',
);

$this->menu=array(
	array('label'=>'Create Producers', 'url'=>array('create')),
	array('label'=>'Manage Producers', 'url'=>array('admin')),
);
?>

<h1>Producers</h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
