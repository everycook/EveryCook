<?php
$this->breadcrumbs=array(
	'Products',
);

$this->menu=array(
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('products/create',array('ing_id'=>$ing_id))),
	);
//}
?>

<div>
	<input type="hidden" id="ProductStoreLocationsLink" value="<?php echo $this->createUrl('stores/getStoresInRangeWithProduct'); ?>"/>
	
	<input type="hidden" id="centerGPSYou" value="<?php if (isset(Yii::app()->session['current_gps'])) {echo Yii::app()->session['current_gps'][0] . ',' . Yii::app()->session['current_gps'][1];} ?>"/>
	<input type="hidden" id="centerGPSHome" value="<?php if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[0])){echo Yii::app()->user->home_gps[0] . ',' . Yii::app()->user->home_gps[1];} ?>"/>
	<input type="hidden" id="viewDistance" value="<?php echo Yii::app()->user->view_distance; ?>"/>
	
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'products_form',
		'method'=>'post',
	)); ?>
	<?php  if($ing_id == null){ ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	<?php 
	}
	/*
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, array('products/advanceSearch','newSearch'=>true), array('class'=>'button', 'id'=>'advanceSearch')); ?><br>
	</div>
	*/
	
	if (isset(Yii::app()->session['Ingredient']) && isset(Yii::app()->session['Ingredient']['model'])){
		$back_url = array('ingredients/advanceSearch');
	} else {
		$back_url = array('ingredients/search');
	}
	echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); 
	?>
	
	<div class="clearfix"></div>
	
<?php $this->endWidget(); ?>
<div id="map_canvas" style="height:300px; width:300px; display:none;"></div>
	
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'productsResult',
)); ?>
<?php echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); ?>

<script type="text/javascript">
	loadScript(false, "CH", false, true, false, false);
</script>
</div>
