<?php
$this->breadcrumbs=array(
	'Stores'=>array('index'),
	$model->STO_ID=>array('view','id'=>$model->STO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'View Stores', 'url'=>array('view', 'id'=>$model->STO_ID)),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_STORES_UPDATE, $model->STO_ID); ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'supplier'=>$supplier,
	'storeType'=>$storeType,
	'countrys'=>$countrys,
)); ?>