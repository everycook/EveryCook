<?php
$this->breadcrumbs=array(
	'Steps'=>array('index'),
	$model->REC_ID=>array('view','id'=>$model->REC_ID,'id2'=>$model->STE_STEP_NO),
	'Update',
);

$this->menu=array(
	array('label'=>'List Steps', 'url'=>array('index')),
	array('label'=>'Create Steps', 'url'=>array('create')),
	array('label'=>'View Steps', 'url'=>array('view', 'id'=>$model->REC_ID)),
	array('label'=>'Manage Steps', 'url'=>array('admin')),
);
?>

<h1>Update Steps <?php echo $model->REC_ID; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'stepTypes'=>$stepTypes,
	'actions'=>$actions,
	'ingredients'=>$ingredients,
)); ?>