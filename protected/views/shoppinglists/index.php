<?php
$this->breadcrumbs=array(
	'Shoppinglists',
);

$this->menu=array(
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1>Shoppinglists</h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
