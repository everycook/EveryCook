<?php
$this->breadcrumbs=array(
	'Ecologys',
);

$this->menu=array(
	array('label'=>'Create Ecology', 'url'=>array('create')),
	array('label'=>'Manage Ecology', 'url'=>array('admin')),
);
?>

<h1>Ecologys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
