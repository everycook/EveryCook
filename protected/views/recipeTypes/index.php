<?php
$this->breadcrumbs=array(
	'Recipe Types',
);

$this->menu=array(
	array('label'=>'Create RecipeTypes', 'url'=>array('create')),
	array('label'=>'Manage RecipeTypes', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_RECIPETYPES_LIST; ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
