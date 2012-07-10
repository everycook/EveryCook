<?php
$this->breadcrumbs=array(
	'Products',
);

$this->menu=array(
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
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
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
<div id="map_canvas" style="height:300px; width:300px; display:none;"></div>
	
<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'productsResult',
)); ?>

<script type="text/javascript">
	loadScript(false, "CH", false, true, false, false);
</script>
<?php $this->endWidget(); ?>

</div>
