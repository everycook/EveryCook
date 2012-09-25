<?php
$this->breadcrumbs=array(
	'Ingredients',
);

$this->menu=array(
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_INGREDIENTS_LIST; ?></h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
