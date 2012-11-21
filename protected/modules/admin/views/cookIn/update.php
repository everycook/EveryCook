<?php
$this->breadcrumbs=array(
	'Cook Ins'=>array('index'),
	$model->COI_ID=>array('view','id'=>$model->COI_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List CookIn', 'url'=>array('index')),
	array('label'=>'Create CookIn', 'url'=>array('create')),
	array('label'=>'View CookIn', 'url'=>array('view', 'id'=>$model->COI_ID)),
	array('label'=>'Manage CookIn', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_COOKIN_UPDATE, $model->COI_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'tools'=>$tools,
	)); ?>