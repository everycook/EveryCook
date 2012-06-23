<?php
$this->breadcrumbs=array(
	'Recipes',
);

$this->menu=array(
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);
?>

<h1>Recipes</h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
