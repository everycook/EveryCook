<?php
$this->breadcrumbs=array(
	'Steps2s'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Steps2', 'url'=>array('index')),
	array('label'=>'Create Steps2', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('steps2-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Steps2s</h1>

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
	'id'=>'steps2-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'STE_ID',
		'REC_ID',
		'ACT_ID',
		'ING_ID',
		'STE_STEP_NO',
		'STE_GRAMS',
		/*
		'STE_T_BOTTOM',
		'STE_T_LID',
		'STE_T_STEAM',
		'STE_BAR',
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
