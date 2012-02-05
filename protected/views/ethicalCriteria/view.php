<?php
$this->breadcrumbs=array(
	'Ethical Criterias'=>array('index'),
	$model->ETH_ID,
);

$this->menu=array(
	array('label'=>'List EthicalCriteria', 'url'=>array('index')),
	array('label'=>'Create EthicalCriteria', 'url'=>array('create')),
	array('label'=>'Update EthicalCriteria', 'url'=>array('update', 'id'=>$model->ETH_ID)),
	array('label'=>'Delete EthicalCriteria', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ETH_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EthicalCriteria', 'url'=>array('admin')),
);
?>

<h1>View EthicalCriteria #<?php echo $model->ETH_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ETH_ID',
		'ETH_DESC_EN',
		'ETH_DESC_DE',
	),
)); ?>
