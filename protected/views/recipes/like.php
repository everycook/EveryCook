<?php
$this->breadcrumbs=array(
	'Recipes',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
);

?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
	'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
)); ?>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'id'=>'recipesResult',
)); ?>

<?php $this->endWidget(); ?>
</div>
