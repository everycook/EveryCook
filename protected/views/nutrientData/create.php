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

<h1>Create NutrientData</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>