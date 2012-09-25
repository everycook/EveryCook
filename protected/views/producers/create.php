<?php
$this->breadcrumbs=array(
	'Producers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Producers', 'url'=>array('index')),
	array('label'=>'Manage Producers', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_PRODUCERS_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>