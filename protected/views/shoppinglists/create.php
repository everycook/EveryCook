<?php
$this->breadcrumbs=array(
	'Shoppinglists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Shoppinglists', 'url'=>array('index')),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_SHOPPINGLISTS_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>