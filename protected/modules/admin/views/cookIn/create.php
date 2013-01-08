<?php
$this->breadcrumbs=array(
	'Cook Ins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CookIn', 'url'=>array('index')),
	array('label'=>'Manage CookIn', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_COOKIN_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'tools'=>$tools,
	)); ?>