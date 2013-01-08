<?php
$this->breadcrumbs=array(
	'Tools'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tools', 'url'=>array('index')),
	array('label'=>'Manage Tools', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_TOOLS_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>