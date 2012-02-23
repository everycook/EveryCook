<?php
$this->breadcrumbs=array(
	'Steps2s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Steps2', 'url'=>array('index')),
	array('label'=>'Manage Steps2', 'url'=>array('admin')),
);
?>

<h1>Create Steps2</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>