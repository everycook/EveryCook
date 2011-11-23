<?php
$this->breadcrumbs=array(
	'Step Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StepTypes', 'url'=>array('index')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);
?>

<h1>Create StepTypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>