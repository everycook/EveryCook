<?php
$this->breadcrumbs=array(
	'Profiles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Profiles', 'url'=>array('index')),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>

<h1>Create Profiles</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>