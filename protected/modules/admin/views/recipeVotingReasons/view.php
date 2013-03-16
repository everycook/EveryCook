<?php
$this->breadcrumbs=array(
	'Recipe Voting Reasons'=>array('index'),
	$model->RVR_ID,
);

$this->menu=array(
	array('label'=>'List RecipeVotingReasons', 'url'=>array('index')),
	array('label'=>'Create RecipeVotingReasons', 'url'=>array('create')),
	array('label'=>'Update RecipeVotingReasons', 'url'=>array('update', 'id'=>$model->RVR_ID)),
	array('label'=>'Delete RecipeVotingReasons', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->RVR_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RecipeVotingReasons', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('update',$this->getActionParams())),
);
?>

<h1><?php printf($this->trans->TITLE_RECIPEVOTINGREASONS_VIEW, $model->RVR_ID); ?></h1>
<div class="f-center">
	<?php  echo CHtml::link($this->trans->GENERAL_BACK_TO_SEARCH, array('search'), array('class'=>'button')); ?><br>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'RVR_ID',
		'RVR_DESC_DE_CH',
		'RVR_DESC_EN_GB',
	),
)); ?>
