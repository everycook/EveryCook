<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	$model->NUT_ID=>array('view','id'=>$model->NUT_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Create NutrientData', 'url'=>array('create')),
	array('label'=>'View NutrientData', 'url'=>array('view', 'id'=>$model->NUT_ID)),
	array('label'=>'Manage NutrientData', 'url'=>array('admin')),
);
?>

<h1>Update NutrientData <?php echo $model->NUT_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>