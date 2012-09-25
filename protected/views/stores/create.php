<?php
$this->breadcrumbs=array(
	'Stores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_STORES_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'supplier'=>$supplier,
	'storeType'=>$storeType,
	'countrys'=>$countrys,
)); ?>