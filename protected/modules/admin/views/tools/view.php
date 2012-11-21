<?php
$this->breadcrumbs=array(
	'Tools'=>array('index'),
	$model->TOO_ID,
);

$this->menu=array(
	array('label'=>'List Tools', 'url'=>array('index')),
	array('label'=>'Create Tools', 'url'=>array('create')),
	array('label'=>'Update Tools', 'url'=>array('update', 'id'=>$model->TOO_ID)),
	array('label'=>'Delete Tools', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->TOO_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tools', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_TOOLS_VIEW, $model->TOO_ID); ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'TOO_ID',
		'TOO_DESC_DE_CH',
		'TOO_DESC_EN_GB',
	),
)); ?>
