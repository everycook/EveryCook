<?php
$this->breadcrumbs=array(
	'Cook In Preps'=>array('index'),
	$model->COI_PREP=>array('view','id'=>$model->COI_PREP),
	'Update',
);

$this->menu=array(
	array('label'=>'List CookInPrep', 'url'=>array('index')),
	array('label'=>'Create CookInPrep', 'url'=>array('create')),
	array('label'=>'View CookInPrep', 'url'=>array('view', 'id'=>$model->COI_PREP)),
	array('label'=>'Manage CookInPrep', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_COOKINPREP_UPDATE, $model->COI_PREP); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>