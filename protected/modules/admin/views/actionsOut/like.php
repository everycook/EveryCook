<?php
$this->breadcrumbs=array(
	'Actions Outs',
);

$this->menu=array(
	array('label'=>'Create ActionsOut', 'url'=>array('create')),
	array('label'=>'Manage ActionsOut', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'actions-out_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'actions-outResult',
)); ?>

<?php $this->endWidget(); ?>

</div>