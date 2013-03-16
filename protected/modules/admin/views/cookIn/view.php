<?php
$this->breadcrumbs=array(
	'Cook Ins'=>array('index'),
	$model->COI_ID,
);

$this->menu=array(
	array('label'=>'List CookIn', 'url'=>array('index')),
	array('label'=>'Create CookIn', 'url'=>array('create')),
	array('label'=>'Update CookIn', 'url'=>array('update', 'id'=>$model->COI_ID)),
	array('label'=>'Delete CookIn', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->COI_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CookIn', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_COOKIN_VIEW, $model->COI_ID); ?></h1>
<div class="f-center">
	<?php  echo CHtml::link($this->trans->GENERAL_BACK_TO_SEARCH, array('search'), array('class'=>'button')); ?><br>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'COI_ID',
		'TOO_ID',
		'COI_DESC_DE_CH',
		'COI_DESC_EN_GB',
	),
)); ?>
