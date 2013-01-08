<?php
$this->breadcrumbs=array(
	'Tools'=>array('index'),
	$model->TOO_ID=>array('view','id'=>$model->TOO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tools', 'url'=>array('index')),
	array('label'=>'Create Tools', 'url'=>array('create')),
	array('label'=>'View Tools', 'url'=>array('view', 'id'=>$model->TOO_ID)),
	array('label'=>'Manage Tools', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_TOOLS_UPDATE, $model->TOO_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>