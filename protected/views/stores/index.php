<?php
$this->breadcrumbs=array(
	'Stores',
);

$this->menu=array(
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_STORES_LIST; ?></h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
