<?php
$this->breadcrumbs=array(
	'Actions Ins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActionsIn', 'url'=>array('index')),
	array('label'=>'Manage ActionsIn', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_ACTIONSIN_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>