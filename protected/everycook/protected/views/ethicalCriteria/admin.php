<?php
$this->breadcrumbs=array(
	'Ethical Criterias'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List EthicalCriteria', 'url'=>array('index')),
	array('label'=>'Create EthicalCriteria', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ethical-criteria-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ethical Criterias</h1>

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
	'id'=>'ethical-criteria-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ETH_ID',
		'ETH_DESC_EN',
		'ETH_DESC_DE',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
