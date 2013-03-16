<?php
$this->breadcrumbs=array(
	'Cook In Preps'=>array('index'),
	$model->COI_PREP,
);

$this->menu=array(
	array('label'=>'List CookInPrep', 'url'=>array('index')),
	array('label'=>'Create CookInPrep', 'url'=>array('create')),
	array('label'=>'Update CookInPrep', 'url'=>array('update', 'id'=>$model->COI_PREP)),
	array('label'=>'Delete CookInPrep', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->COI_PREP),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CookInPrep', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_COOKINPREP_VIEW, $model->COI_PREP); ?></h1>
<div class="f-center">
	<?php  echo CHtml::link($this->trans->GENERAL_BACK_TO_SEARCH, array('search'), array('class'=>'button')); ?><br>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'COI_PREP',
		'COI_PREP_DESC',
	),
)); ?>
