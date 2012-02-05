<?php
$this->breadcrumbs=array(
	'Shoplists',
);

$this->menu=array(
	array('label'=>'Create Shoplists', 'url'=>array('create')),
	array('label'=>'Manage Shoplists', 'url'=>array('admin')),
);
?>

<h1>Shoplists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
