<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Meals', 'url'=>array('index')),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_MEALS_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>