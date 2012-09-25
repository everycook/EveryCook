<?php
$this->breadcrumbs=array(
	'Producers'=>array('index'),
	$model->PRD_ID=>array('view','id'=>$model->PRD_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Producers', 'url'=>array('index')),
	array('label'=>'Create Producers', 'url'=>array('create')),
	array('label'=>'View Producers', 'url'=>array('view', 'id'=>$model->PRD_ID)),
	array('label'=>'Manage Producers', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_PRODUCERS_UPDATE, $model->PRD_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>