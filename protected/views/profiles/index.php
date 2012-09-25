<?php
$this->breadcrumbs=array(
	'Profiles',
);

$this->menu=array(
	array('label'=>'Create Profiles', 'url'=>array('create')),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_PROFILES_LIST; ?></h1>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
