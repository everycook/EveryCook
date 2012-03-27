<?php
$this->breadcrumbs=array(
	'Recipes',
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
);

//if ($this->validSearchPerformed){
	$this->mainButtons = array(
		array('label'=>$this->trans->RECIPES_CREATE, 'link_id'=>'middle_single', 'url'=>array('recipes/create',array())),
	);
//}

if (Yii::app()->session['Recipe'] && Yii::app()->session['Recipe']['time']){
	$newRecSearch=array('newSearch'=>Yii::app()->session['Recipe']['time']);
} else {
	$newRecSearch=array();
}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->RECIPES_SEARCH)); ?>
	</div>
	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->RECIPES_ADVANCE_SEARCH, array('recipes/advanceSearch', $newRecSearch), array('class'=>'button')); ?><br>
	</div>
	
	<div class="clearfix"></div>	
<?php $this->endWidget(); ?>

<?php /*$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'REC_ID'); ?>
		<?php echo $form->textField($model,'REC_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CREATED'); ?>
		<?php echo $form->textField($model,'REC_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_CHANGED'); ?>
		<?php echo $form->textField($model,'REC_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_PICTURE'); ?>
		<?php echo $form->textField($model,'REC_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'REC_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TYPE'); ?>
		<?php echo $form->textField($model,'REC_TYPE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TITLE_EN'); ?>
		<?php echo $form->textField($model,'REC_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_TITLE_DE'); ?>
		<?php echo $form->textField($model,'REC_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); */?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'id'=>'recipeResult',
)); ?>
</div>