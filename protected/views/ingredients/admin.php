<?php
$this->breadcrumbs=array(
	'Ingredients'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Ingredients', 'url'=>array('index')),
	array('label'=>'Create Ingredients', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ingredients-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ingredients</h1>

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
	'id'=>'ingredients-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ING_ID',
		'PRF_UID',
		'ING_CREATED',
		'ING_CHANGED',
		'NUT_ID',
		'GRP_ID',
		array(
			'name'=>'nutrientData',
			'value'=>'$data->nutrientData->NUT_DESC',
		),
		array(
			'name'=>'groupNames',
			'value'=>'$data->groupNames->GRP_DESC_DE',
		),
		array(
			'name'=>'subgroupNames',
			'value'=>'$data->subgroupNames->SGR_DESC_DE',
		),
		array(
			'name'=>'ingredientConveniences',
			'value'=>'$data->ingredientConveniences->ICO_DESC_DE',
		),
		array(
			'name'=>'storability',
			'value'=>'$data->storability->STB_DESC_DE',
		),
		/*
		'SGR_ID',
		'IST_ID',
		'ICO_ID',
		'STB_ID',
		'ING_DENSITY',
		'ING_IMG',
		'ING_IMG_AUTH',
		'ING_NAME_EN',
		'ING_NAME_DE',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
