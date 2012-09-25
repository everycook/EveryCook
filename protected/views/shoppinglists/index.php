<?php
$this->breadcrumbs=array(
	'Shoppinglists',
);

$this->menu=array(
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
	array('label'=>'Manage Shoppinglists', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_SHOPPINGLISTS_LIST; ?></h1>
<?php
	//if ($dataProvider->size() > 0){ //TODO: use correct function
		echo CHtml::link($this->trans->SHOPPINGLISTS_SHOW_ALL_AS_ONE, array('showAllAsOne'), array('class'=>'button f-right'));
	//}
?>
<div class="clearfix"></div>
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
