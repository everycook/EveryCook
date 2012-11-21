<?php
$this->breadcrumbs=array(
	'Cook In Preps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CookInPrep', 'url'=>array('index')),
	array('label'=>'Manage CookInPrep', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_COOKINPREP_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>