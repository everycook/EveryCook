<?php
$this->breadcrumbs=array(
	'Actions Outs'=>array('index'),
	$model->AOU_ID=>array('view','id'=>$model->AOU_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActionsOut', 'url'=>array('index')),
	array('label'=>'Create ActionsOut', 'url'=>array('create')),
	array('label'=>'View ActionsOut', 'url'=>array('view', 'id'=>$model->AOU_ID)),
	array('label'=>'Manage ActionsOut', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_ACTIONSOUT_UPDATE, $model->AOU_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'stepTypes'=>$stepTypes,
	'tools'=>$tools
	)); ?>