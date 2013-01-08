<?php
$this->breadcrumbs=array(
	'Cook In States'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CookInState', 'url'=>array('index')),
	array('label'=>'Manage CookInState', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_COOKINSTATE_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>