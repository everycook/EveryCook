<?php
$this->breadcrumbs=array(
	'Nutrient Datas',
);

$this->menu=array(
	array('label'=>'Create NutrientData', 'url'=>array('create')),
	array('label'=>'Manage NutrientData', 'url'=>array('admin')),
);
?>

<h1>Nutrient Datas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
