<?php
$this->breadcrumbs=array(
	'Recipe Voting Reasons',
);

$this->menu=array(
	array('label'=>'Create RecipeVotingReasons', 'url'=>array('create')),
	array('label'=>'Manage RecipeVotingReasons', 'url'=>array('admin')),
);
?>


<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'recipe-voting-reasons_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'recipe-voting-reasonsResult',
)); ?>

<?php $this->endWidget(); ?>

</div>