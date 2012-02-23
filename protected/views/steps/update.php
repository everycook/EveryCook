<?php
$this->breadcrumbs=array(
	'Steps2s'=>array('index'),
	$model->STE_ID=>array('view','id'=>$model->STE_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Steps2', 'url'=>array('index')),
	array('label'=>'Create Steps2', 'url'=>array('create')),
	array('label'=>'View Steps2', 'url'=>array('view', 'id'=>$model->STE_ID)),
	array('label'=>'Manage Steps2', 'url'=>array('admin')),
);
?>

<h1>Update Steps2 <?php echo $model->STE_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>