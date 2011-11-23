<?php
$this->breadcrumbs=array(
	'Shoplists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Shoplists', 'url'=>array('index')),
	array('label'=>'Manage Shoplists', 'url'=>array('admin')),
);
?>

<h1>Create Shoplists</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>