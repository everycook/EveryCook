<?php
$this->breadcrumbs=array(
	'Step Types',
);

$this->menu=array(
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'step-types_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'step-typesResult',
)); ?>

<?php $this->endWidget(); ?>

</div>