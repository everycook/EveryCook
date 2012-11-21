<?php
$this->breadcrumbs=array(
	'Cook In States'=>array('index'),
	$model->CIS_ID=>array('view','id'=>$model->CIS_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List CookInState', 'url'=>array('index')),
	array('label'=>'Create CookInState', 'url'=>array('create')),
	array('label'=>'View CookInState', 'url'=>array('view', 'id'=>$model->CIS_ID)),
	array('label'=>'Manage CookInState', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_COOKINSTATE_UPDATE, $model->CIS_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>