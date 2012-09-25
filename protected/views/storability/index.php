<?php
$this->breadcrumbs=array(
	'Storabilities',
);

$this->menu=array(
	array('label'=>'Create Storability', 'url'=>array('create')),
	array('label'=>'Manage Storability', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_STORABILITY_LIST; ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
