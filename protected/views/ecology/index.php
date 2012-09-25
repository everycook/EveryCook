<?php
$this->breadcrumbs=array(
	'Ecologys',
);

$this->menu=array(
	array('label'=>'Create Ecology', 'url'=>array('create')),
	array('label'=>'Manage Ecology', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_ECOLOGY_LIST; ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
