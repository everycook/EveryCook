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

if ($this->isFancyAjaxRequest){
	$link = $this->createUrl('ingredients/chooseIngredient');
	?>
	<script type="text/javascript">
	jQuery('#ingredients_form').bind('submit', function(){
		jQuery.ajax({'type':'post', 'url':'<?php echo $link; ?>','data':jQuery('#ingredients_form').serialize(),'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	jQuery('.button.IngredientSelect').bind('click', function(){
		elem = jQuery('.activeFancyField');
		if (elem.length == 0){
			elem = jQuery('.fancyChoose').siblings('input');
		}
		elem.attr('value', jQuery(this).attr('href'));
		elem.siblings('.fancyChoose.IngredientSelect').html(jQuery(this).parent().find('.name:first a').html());
		jQuery.fancybox.close();
		return false;
	});
	jQuery('#advanceSearch.button').bind('click', function(){
		var url = jQuery(this).attr('href');
		if (url.indexOf('#')===0){
			url = glob.hashToUrl(url.substr(1));
		}
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(html){jQuery.fancybox({'content':html});}});
		return false;
	});
	</script>
	<?php
}

$ingSearch = array(($this->isFancyAjaxRequest)?'ingredients/advanceChooseIngredient':'ingredients/advanceSearch');
if (Yii::app()->session['Ingredient'] && Yii::app()->session['Ingredient']['time']){
	$ingSearch=array_merge($ingSearch,array('newSearch'=>Yii::app()->session['Ingredient']['time']));
}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'ingredients_form',
		'method'=>'post',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->INGREDIENTS_SEARCH)); ?>
	</div>
	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->INGREDIENTS_ADVANCE_SEARCH, $ingSearch, array('class'=>'button', 'id'=>'advanceSearch')); ?><br>
	</div>
	
	<div class="clearfix"></div>
	
<?php $this->endWidget(); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'ingredientResult',
)); ?>
</div>