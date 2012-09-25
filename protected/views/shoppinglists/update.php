<?php
$this->breadcrumbs=array(
	'Shoppinglists'=>array('index'),
	$model->SHO_ID=>array('view','id'=>$model->SHO_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Shoppinglists', 'url'=>array('index')),
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
	array('label'=>'View Shoppinglists', 'url'=>array('view', 'id'=>$model->SHO_ID)),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_SHOPPINGLISTS_UPDATE, $model->SHO_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>