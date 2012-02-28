<?php
$this->breadcrumbs=array(
	'Ingredients',
);

$this->menu=array(
	array('label'=>'Create Ingredients', 'url'=>array('create')),
	array('label'=>'Manage Ingredients', 'url'=>array('admin')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->INGREDIENTS_CREATE, 'link_id'=>'middle_single', 'url'=>array('ingredients/create',array())),
	);
//}

Yii::app()->clientScript->registerScript('AjaxSubmit', "
jQuery('form').submit(function(){
jQuery.ajax({'type':'get', 'url':jQuery(this).attr('action') + '?' + jQuery(this).serialize(),'success':function(html){
jQuery(\"#changable_content\").html('');
jQuery(\"#changable_content\").append(html);
}});
return false;
}); 
");
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
	<div class="f-left search">
		<?php echo $form->textField($model2,'query', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/lupe.jpg', array('class'=>'search_button', 'title'=>$this->trans->INGREDIENTS_SEARCH)); ?>
	</div>
	
	<div class="f-right">
		<?php echo CHtml::ajaxlink($this->trans->INGREDIENTS_ADVANCE_SEARCH, array('ingredients/advanceSearch'), array('update'=>'#changable_content'), array('class'=>'button')); ?><br>
	</div>
	
	<div class="clearfix"></div>
	
<?php $this->endWidget(); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
)); ?>
</div>