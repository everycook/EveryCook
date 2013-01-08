<?php
$this->breadcrumbs=array(
	'Cook In Preps',
);

$this->menu=array(
	array('label'=>'Create CookInPrep', 'url'=>array('create')),
	array('label'=>'Manage CookInPrep', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'cook-in-prep_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'cook-in-prepResult',
)); ?>

<?php $this->endWidget(); ?>

</div>