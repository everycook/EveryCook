<?php
$this->breadcrumbs=array(
	'Step Types'=>array('index'),
	$model->STT_ID,
);

$this->menu=array(
	array('label'=>'List StepTypes', 'url'=>array('index')),
	array('label'=>'Create StepTypes', 'url'=>array('create')),
	array('label'=>'Update StepTypes', 'url'=>array('update', 'id'=>$model->STT_ID)),
	array('label'=>'Delete StepTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->STT_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StepTypes', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_STEPTYPES_VIEW, $model->STT_ID); ?></h1>
<div class="f-center">
	<?php  echo CHtml::link($this->trans->GENERAL_BACK_TO_SEARCH, array('search'), array('class'=>'button')); ?><br>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'STT_ID',
		'STT_DEFAULT',
		'STT_REQUIRED',
		'STT_DESC_EN_GB',
		'STT_DESC_DE_CH',
		'CREATED_BY',
		'CREATED_ON',
		'CHANGED_BY',
		'CHANGED_ON',
	),
)); ?>
