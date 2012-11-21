<?php
$this->breadcrumbs=array(
	'Step Types'=>array('index'),
	$model->STT_ID=>array('view','id'=>$model->STT_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List StepTypes', 'url'=>array('index')),
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'View StepTypes', 'url'=>array('view', 'id'=>$model->STT_ID)),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_STEPTYPES_UPDATE, $model->STT_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>