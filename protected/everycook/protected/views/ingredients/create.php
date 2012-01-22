<?php
$this->breadcrumbs=array(
	'Ingredients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ingredients', 'url'=>array('index')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);
?>

<h1>Create Ingredients</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>