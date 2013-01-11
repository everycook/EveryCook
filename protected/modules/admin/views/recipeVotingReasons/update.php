<?php
$this->breadcrumbs=array(
	'Recipe Voting Reasons'=>array('index'),
	$model->RVR_ID=>array('view','id'=>$model->RVR_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RecipeVotingReasons', 'url'=>array('index')),
	array('label'=>'Create RecipeVotingReasons', 'url'=>array('create')),
	array('label'=>'View RecipeVotingReasons', 'url'=>array('view', 'id'=>$model->RVR_ID)),
	array('label'=>'Manage RecipeVotingReasons', 'url'=>array('admin')),
);
?>

<h1><?php printf($this->trans->TITLE_RECIPEVOTINGREASONS_UPDATE, $model->RVR_ID); ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>