<?php
$this->breadcrumbs=array(
	'Recipe Voting Reasons'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RecipeVotingReasons', 'url'=>array('index')),
	array('label'=>'Manage RecipeVotingReasons', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->trans->TITLE_RECIPEVOTINGREASONS_CREATE; ?></h1>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	)); ?>