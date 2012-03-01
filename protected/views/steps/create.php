<?php
$this->breadcrumbs=array(
	'Steps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Steps', 'url'=>array('index')),
	array('label'=>'Manage Steps', 'url'=>array('admin')),
);
?>

<h1>Create Steps</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'stepTypes'=>$stepTypes,
	'actions'=>$actions,
	'ingredients'=>$ingredients,
)); ?>