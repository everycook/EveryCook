<?php
$this->breadcrumbs=array(
	'Ingredient Conveniences',
);

$this->menu=array(
	array('label'=>'Create IngredientConveniences', 'url'=>array('create')),
	array('label'=>'Manage IngredientConveniences', 'url'=>array('admin')),
);
?>

<h1>Ingredient Conveniences</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
