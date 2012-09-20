<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Create NutrientData', 'url'=>array('create')),
);

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('nutrientData/chooseNutrientData'); ?>"/>
<?php
}
?>

<div id="nutrientDataSearch" class="scrollArea">
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'nutrientData_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>

	<?php if (!$this->getIsAjaxRequest()){ ?>
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, array('nutrientData/advanceSearch'), array('class'=>'button')); ?><br>
	</div>
	<?php } ?>
	
	<div class="clearfix"></div>
<?php $this->endWidget(); ?>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	'id'=>'nutrientDataResult',
)); ?>
</div>