<?php
$this->breadcrumbs=array(
	'Storabilities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Storability', 'url'=>array('index')),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_STORABILITY_CREATE; ?></h1>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>