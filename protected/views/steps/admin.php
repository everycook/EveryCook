<?php
$this->breadcrumbs=array(
	'Steps'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Steps', 'url'=>array('index')),
	array('label'=>'Create Steps', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('steps-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Steps</h1>

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
	'id'=>'steps-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'REC_ID',
		'ACT_ID',
		'ING_ID',
		'STE_STEP_NO',
		'STE_GRAMS',
		'STE_CELSIUS',
		/*
		'STE_KPA',
		'STE_RPM',
		'STE_CLOCKWISE',
		'STE_STIR_RUN',
		'STE_STIR_PAUSE',
		'STE_STEP_DURATION',
		'STT_ID',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
