<?php
$this->breadcrumbs=array(
	'Tools',
);

$this->menu=array(
	array('label'=>'Create Tools', 'url'=>array('create')),
	array('label'=>'Manage Tools', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'tools_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'toolsResult',
)); ?>

<?php $this->endWidget(); ?>

</div>