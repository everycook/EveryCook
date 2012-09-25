<?php
$this->breadcrumbs=array(
	'Ethical Criterias'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EthicalCriteria', 'url'=>array('index')),
	array('label'=>'Manage EthicalCriteria', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_ETHICALCRITERIA_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>