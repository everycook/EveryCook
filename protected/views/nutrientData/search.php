<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Create NutrientData', 'url'=>array('create')),
);

if ($this->isFancyAjaxRequest){
	/*
	$this->widget('application.extensions.fancybox.EFancyBox', array(
		'target'=>'a.fancyChoose',
		'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
		)
	);*/
	/*jQuery(\"#nutrientDataSearch\").replaceWith(html);*/
	$link = $this->createUrl('nutrientData/chooseNutrientData');
	/*Yii::app()->clientScript->registerScript('ajaxSearch', "*/
	/*jQuery.ajax({'type':'post', 'url':'<?php echo $link; ?>','data':jQuery('#nutrientData_form').serialize(),'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});*/
	?>
	<script type="text/javascript">
	jQuery('#nutrientData_form').bind('submit', function(){
		jQuery.ajax({'type':'get', 'url':'<?php echo $link; ?>?' + jQuery('#nutrientData_form').serialize(),'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	jQuery('.button.NutrientDataSelect').bind('click', function(){
		jQuery('#NUT_ID').attr('value', jQuery(this).attr('href'));
		jQuery('.fancyChoose.NutrientDataSelect').html(jQuery(this).parent().children('a:not(.button):first').html());
		jQuery.fancybox.close();
		return false;
	});
	</script>
	<?php	
	/*");*/
}
?>

<div id="nutrientDataSearch">
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'nutrientData_form',
		'method'=>'get',
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->NUTRIENT_DATA_SEARCH)); ?>
	</div>

	<?php if (!$this->getIsAjaxRequest()){ ?>
	<div class="f-right">
		<?php echo CHtml::link($this->trans->INGREDIENTS_ADVANCE_SEARCH, array('nutrientData/advanceSearch'), array('class'=>'button')); ?><br>
	</div>
	<?php } ?>
	
	<div class="clearfix"></div>
	
<?php $this->endWidget(); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>'nutrientDataSearch',
)); ?>
</div>