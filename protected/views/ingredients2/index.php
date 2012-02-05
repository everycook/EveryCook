<?php
$this->breadcrumbs=array(
	'Ingredients2s',
);

$this->menu=array(
	array('label'=>'Create Ingredients2', 'url'=>array('create')),
	array('label'=>'Manage Ingredients2', 'url'=>array('admin')),
);
?>

<h1>Ingredients2s</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
