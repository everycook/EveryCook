<?php
$this->breadcrumbs=array(
	'Ecologys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ecology', 'url'=>array('index')),
	array('label'=>'Manage Ecology', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_ECOLOGY_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>