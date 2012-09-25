<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Manage NutrientData', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_MEALS_CREATE; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>