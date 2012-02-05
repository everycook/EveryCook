<?php
$this->breadcrumbs=array(
	'Ethical Criterias'=>array('index'),
	$model->ETH_ID=>array('view','id'=>$model->ETH_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List EthicalCriteria', 'url'=>array('index')),
	array('label'=>'Create EthicalCriteria', 'url'=>array('create')),
	array('label'=>'View EthicalCriteria', 'url'=>array('view', 'id'=>$model->ETH_ID)),
	array('label'=>'Manage EthicalCriteria', 'url'=>array('admin')),
);
?>

<h1>Update EthicalCriteria <?php echo $model->ETH_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>