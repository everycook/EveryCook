<?php
$this->breadcrumbs=array(
	'Profiles',
);

$this->menu=array(
	array('label'=>'Create Profiles', 'url'=>array('create')),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>

<h1>Profiles</h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
