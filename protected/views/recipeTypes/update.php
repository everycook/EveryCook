<?php
$this->breadcrumbs=array(
	'Recipe Types'=>array('index'),
	$model->RET_ID=>array('view','id'=>$model->RET_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RecipeTypes', 'url'=>array('index')),
	array('label'=>'Create RecipeTypes', 'url'=>array('create')),
	array('label'=>'View RecipeTypes', 'url'=>array('view', 'id'=>$model->RET_ID)),
	array('label'=>'Manage RecipeTypes', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_RECIPETYPES_UPDATE, $model->RET_ID); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>