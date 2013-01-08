<?php
$this->breadcrumbs=array(
	'Cook Ins',
);

$this->menu=array(
	array('label'=>'Create CookIn', 'url'=>array('create')),
	array('label'=>'Manage CookIn', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'cook-in_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'cook-inResult',
)); ?>

<?php $this->endWidget(); ?>

</div>