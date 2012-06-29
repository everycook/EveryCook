<?php
$this->breadcrumbs=array(
	'Shoppinglists'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Shoppinglists', 'url'=>array('index')),
	array('label'=>'Create Shoppinglists', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('shoppinglists-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Shoppinglists</h1>

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
	'id'=>'shoppinglists-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'SHO_ID',
		'SHO_DATE',
		'SHO_INGREDIENTS',
		'SHO_WEIGHTS',
		'SHO_PRODUCTS',
		'SHO_QUANTITIES',
		/*
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
