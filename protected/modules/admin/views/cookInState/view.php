<?php
$this->breadcrumbs=array(
	'Cook In States'=>array('index'),
	$model->CIS_ID,
);

$this->menu=array(
	array('label'=>'List CookInState', 'url'=>array('index')),
	array('label'=>'Create CookInState', 'url'=>array('create')),
	array('label'=>'Update CookInState', 'url'=>array('update', 'id'=>$model->CIS_ID)),
	array('label'=>'Delete CookInState', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->CIS_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CookInState', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_COOKINSTATE_VIEW, $model->CIS_ID); ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'COI_ID',
		'CIS_ID',
		'CIS_DESC',
	),
)); ?>
