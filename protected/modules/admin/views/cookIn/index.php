<?php
$this->breadcrumbs=array(
	'Cook Ins',
);

$this->menu=array(
	array('label'=>'Create CookIn', 'url'=>array('create')),
	array('label'=>'Manage CookIn', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}
?>

<h1><?php echo $this->trans->TITLE_COOKIN_LIST; ?></h1>
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
)); ?>
