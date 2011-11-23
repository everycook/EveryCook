<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Create NutrientData', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('nutrient-data-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Nutrient Datas</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'nutrient-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'NUT_ID',
		'NUT_DESC',
		'NUT_WATER',
		'NUT_ENERG',
		'NUT_PROT',
		'NUT_LIPID',
		/*
		'NUT_ASH',
		'NUT_CARB',
		'NUT_FIBER',
		'NUT_SUGAR',
		'NUT_CALC',
		'NUT_IRON',
		'NUT_MAGN',
		'NUT_PHOS',
		'NUT_POTAS',
		'NUT_SODIUM',
		'NUT_ZINC',
		'NUT_COPP',
		'NUT_MANG',
		'NUT_SELEN',
		'NUT_VIT_C',
		'NUT_THIAM',
		'NUT_RIBOF',
		'NUT_NIAC',
		'NUT_PANTO',
		'NUT_VIT_B6',
		'NUT_FOLAT_TOT',
		'NUT_FOLIC',
		'NUT_FOLATE_FD',
		'NUT_FOLATE_DFE',
		'NUT_CHOLINE',
		'NUT_VIT_B12',
		'NUT_VIT_A_IU',
		'NUT_VIT_A_RAE',
		'NUT_RETINOL',
		'NUT_ALPHA_CAROT',
		'NUT_BETA_CAROT',
		'NUT_BETA_CRYPT',
		'NUT_LYCOP',
		'NUT_LUT_ZEA',
		'NUT_VIT_E',
		'NUT_VIT_D',
		'NUT_VIT_D_IU',
		'NUT_VIT_K',
		'NUT_FA_SAT',
		'NUT_FA_MONO',
		'NUT_FA_POLY',
		'NUT_CHOLEST',
		'NUT_REFUSE',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
