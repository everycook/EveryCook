<?php
$this->breadcrumbs=array(
	'Shoppinglists'=>array('index'),
	$SHO_ID,
);

$this->menu=array(
	array('label'=>'List Shoppinglists', 'url'=>array('index')),
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
	array('label'=>'Update Shoppinglists', 'url'=>array('update', 'id'=>$SHO_ID)),
	array('label'=>'Delete Shoppinglists', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$SHO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1>View Shoppinglists #<?php echo $SHO_ID; ?></h1>

<?php
	if (isset($MEA_ID) && $MEA_ID != 0){
		if (isset($mealChanged) && $mealChanged){
			echo '<div class="">' . $this->trans->SHOPPINGLIST_MEAL_CHANGED_SHOULD_RECREATE . '</div>';
		}
		echo CHtml::link($this->trans->SHOPPINGLIST_RECREATE_FROM_MEAL, array('meals/createShoppingList', 'id'=>$MEA_ID), array('class'=>'button f-center'));
	}
?>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item_view',
	'id'=>'shoppinglistView',
)); ?>

