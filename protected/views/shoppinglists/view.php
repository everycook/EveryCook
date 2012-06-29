<?php
$this->breadcrumbs=array(
	'Shoppinglists'=>array('index'),
	$model->SHO_ID,
);

$this->menu=array(
	array('label'=>'List Shoppinglists', 'url'=>array('index')),
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
	array('label'=>'Update Shoppinglists', 'url'=>array('update', 'id'=>$model->SHO_ID)),
	array('label'=>'Delete Shoppinglists', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SHO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1>View Shoppinglists #<?php echo $model->SHO_ID; ?></h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item_view',
	'id'=>'shoppinglistView',
)); ?>

