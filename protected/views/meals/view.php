<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	$model->MEA_ID,
);

$this->menu=array(
	array('label'=>'List Meals', 'url'=>array('index')),
	array('label'=>'Create Meals', 'url'=>array('create')),
	array('label'=>'Update Meals', 'url'=>array('update', 'id'=>$model->MEA_ID)),
	array('label'=>'Delete Meals', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->MEA_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Meals', 'url'=>array('admin')),
);
?>

<h1>View Meals #<?php echo $model->MEA_ID; ?></h1>

<div class="list-view form">
	<?php $this->renderPartial('_view',array('data'=>$model)); ?>
</div>