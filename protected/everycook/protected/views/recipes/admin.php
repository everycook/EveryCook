<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('recipes-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Recipes</h1>

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
	'id'=>'recipes-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'REC_ID',
		'REC_CREATED',
		'REC_CHANGED',
		'REC_PICTURE',
		'REC_PICTURE_AUTH',
		'REC_TYPE',
		/*
		'REC_TITLE_EN',
		'REC_TITLE_DE',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
