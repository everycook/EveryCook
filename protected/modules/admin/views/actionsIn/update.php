<?php
$this->breadcrumbs=array(
	'Actions Ins'=>array('index'),
	$model->AIN_ID=>array('view','id'=>$model->AIN_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActionsIn', 'url'=>array('index')),
	array('label'=>'Create ActionsIn', 'url'=>array('create')),
	array('label'=>'View ActionsIn', 'url'=>array('view', 'id'=>$model->AIN_ID)),
	array('label'=>'Manage ActionsIn', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_ACTIONSIN_UPDATE, $model->AIN_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>