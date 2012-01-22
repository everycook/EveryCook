<?php
$this->breadcrumbs=array(
	'Ingredients2s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ingredients2', 'url'=>array('index')),
	array('label'=>'Manage Ingredients2', 'url'=>array('admin')),
);
?>

<h1>Create Ingredients2</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>