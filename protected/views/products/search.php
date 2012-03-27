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
		array('label'=>$this->trans->PRODUCTS_CREATE, 'link_id'=>'middle_single', 'url'=>array('products/create',array('ing_id'=>'test'))),
	);
//}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'id'=>'products_form',
		'method'=>'post',
	)); ?>
	<?php  if(!isset($_GET['ing_id'])){ ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->PRODUCTS_SEARCH)); ?>
	</div>
	<?php 
	}
	/*
	<div class="f-right">
		<?php echo CHtml::link($this->trans->INGREDIENTS_ADVANCE_SEARCH, array('products/advanceSearch','newSearch'=>true), array('class'=>'button', 'id'=>'advanceSearch')); ?><br>
	</div>
	*/
	
	if (Yii::app()->session['Ingredient'] && Yii::app()->session['Ingredient']['model']){
		$back_url = array('ingredients/advanceSearch');
	} else {
		$back_url = array('ingredients/search');
	}
	echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); 
	?>
	
	<div class="clearfix"></div>
	
<?php $this->endWidget(); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'productsResult',
)); ?>
<?php echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); ?>
</div>
