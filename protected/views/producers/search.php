<?php
$this->breadcrumbs=array(
	'Producers',
);

$this->menu=array(
	array('label'=>'Create Producers', 'url'=>array('create')),
	array('label'=>'Manage Producers', 'url'=>array('admin')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('producers/create',array())),
	);
//}
if ($this->isFancyAjaxRequest){
	?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('producers/chooseProducer'); ?>"/>
	<?php
}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'producers_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="clearfix"></div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'producerResult',
)); ?>

<?php $this->endWidget(); ?>
</div>