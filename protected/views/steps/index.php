<?php
$this->breadcrumbs=array(
	'Steps2s',
);

$this->menu=array(
	array('label'=>'Create Steps2', 'url'=>array('create')),
	array('label'=>'Manage Steps2', 'url'=>array('admin')),
);
?>

<h1>Steps2s</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
