<?php
$this->breadcrumbs=array(
	'Actions Outs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActionsOut', 'url'=>array('index')),
	array('label'=>'Manage ActionsOut', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_ACTIONSOUT_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'stepTypes'=>$stepTypes,
	'tools'=>$tools
	)); ?>