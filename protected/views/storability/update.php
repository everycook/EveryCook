<?php
$this->breadcrumbs=array(
	'Storabilities'=>array('index'),
	$model->STB_ID=>array('view','id'=>$model->STB_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Storability', 'url'=>array('index')),
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'View Storability', 'url'=>array('view', 'id'=>$model->STB_ID)),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_STORABILITY_UPDATE, $model->STB_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>