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
		array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('recipes/create',array('newModel'=>time()))),
	);
//}

if (isset(Yii::app()->session['Recipe']) && isset(Yii::app()->session['Recipe']['time'])){
	$newRecSearch=array('newSearch'=>Yii::app()->session['Recipe']['time']);
} else {
	$newRecSearch=array();
}
if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('recipes/chooseRecipe'); ?>"/>
	<?php
}
?>

<div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
	'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
)); ?>
	<div class="f-left search">
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, array('recipes/advanceSearch', $newRecSearch), array('class'=>'button')); ?><br>
	</div>
	
	<div class="clearfix"></div>

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
		<?php echo $form->label($model,'REC_IMG'); ?>
		<?php echo $form->textField($model,'REC_IMG'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'REC_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RET_ID'); ?>
		<?php echo $form->textField($model,'RET_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_NAME_EN'); ?>
		<?php echo $form->textField($model,'REC_NAME_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'REC_NAME_DE'); ?>
		<?php echo $form->textField($model,'REC_NAME_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); */?>

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'id'=>'recipesResult',
)); ?>

<?php $this->endWidget(); ?>
</div>
