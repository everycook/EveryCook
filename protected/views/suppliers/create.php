<?php
$this->breadcrumbs=array(
	'Suppliers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Suppliers', 'url'=>array('index')),
	array('label'=>'Manage Suppliers', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_SUPPLIERS_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>