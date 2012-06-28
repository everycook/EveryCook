<?php
$this->breadcrumbs=array(
	'Meals',
);

$this->menu=array(
	array('label'=>'Create Meals', 'url'=>array('create')),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1>Meals ShoppingList</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_shoppingList',
	'htmlOptions'=>array(
		'class'=>'list-view',
	),
)); ?>

<div class="row buttons">
	<?php echo CHtml::link($this->trans->GENERAL_EDIT, array('meals/mealPlanner', 'id'=>$_GET['id']), array('class'=>'button')); ?>
	<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('meals/mealList'), array('class'=>'button')); ?>
</div>