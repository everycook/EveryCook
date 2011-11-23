<?php
$this->breadcrumbs=array(
	'Step Types',
);

$this->menu=array(
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);
?>

<h1>Step Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
