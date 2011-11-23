<?php
$this->breadcrumbs=array(
	'Ingredient States',
);

$this->menu=array(
	array('label'=>'Create IngredientStates', 'url'=>array('create')),
	array('label'=>'Manage IngredientStates', 'url'=>array('admin')),
);
?>

<h1>Ingredient States</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
