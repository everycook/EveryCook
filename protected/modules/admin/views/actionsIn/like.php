<?php
$this->breadcrumbs=array(
	'Actions Ins',
);

$this->menu=array(
	array('label'=>'Create ActionsIn', 'url'=>array('create')),
	array('label'=>'Manage ActionsIn', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'actions-in_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'actions-inResult',
)); ?>

<?php $this->endWidget(); ?>

</div>