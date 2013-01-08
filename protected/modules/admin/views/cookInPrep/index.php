<?php
$this->breadcrumbs=array(
	'Cook In Preps',
);

$this->menu=array(
	array('label'=>'Create CookInPrep', 'url'=>array('create')),
	array('label'=>'Manage CookInPrep', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}
?>

<h1><?php echo $this->trans->TITLE_COOKINPREP_LIST; ?></h1>

<div class="f-center">
	<?php  echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('create','newModel'=>time()), array('class'=>'button', 'id'=>'create')); ?><br>
</div>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
)); ?>
