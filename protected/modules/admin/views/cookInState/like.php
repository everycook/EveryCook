<?php
$this->breadcrumbs=array(
	'Cook In States',
);

$this->menu=array(
	array('label'=>'Create CookInState', 'url'=>array('create')),
	array('label'=>'Manage CookInState', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'cook-in-state_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'cook-in-stateResult',
)); ?>

<?php $this->endWidget(); ?>

</div>