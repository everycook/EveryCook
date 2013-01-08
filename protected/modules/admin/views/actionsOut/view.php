<?php
$this->breadcrumbs=array(
	'Actions Outs'=>array('index'),
	$model->AOU_ID,
);

$this->menu=array(
	array('label'=>'List ActionsOut', 'url'=>array('index')),
	array('label'=>'Create ActionsOut', 'url'=>array('create')),
	array('label'=>'Update ActionsOut', 'url'=>array('update', 'id'=>$model->AOU_ID)),
	array('label'=>'Delete ActionsOut', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->AOU_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ActionsOut', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_ACTIONSOUT_VIEW, $model->AOU_ID); ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'AOU_ID',
		'STT_ID',
		'TOO_ID',
		'AOU_PREP',
		'AOU_DURATION',
		'AOU_DUR_PRO',
		'AOU_CIS_CHANGE',
		'AOU_DESC_DE_CH',
		'AOU_DESC_EN_GB',
	),
)); ?>
