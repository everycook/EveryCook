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


	$this->widget('application.extensions.fancybox.EFancyBox', array(
		'target'=>'a.fancyChoose',
		'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
		)
	);
?>
<input type="hidden" id="SubGroupSearchLink" value="<?php echo $this->createUrl('ingredients/getSubGroupSearch'); ?>"/>

<div id="ingredientsAdvanceSearch">
<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
		'id'=>'search_form',
	)); ?>
	<div class="f-left search">
		<?php echo $form->textField($model2,'query', array('class'=>'search_query')); ?>
		<?php echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/lupe.jpg', array('class'=>'search_button', 'title'=>$this->trans->INGREDIENTS_SEARCH)); ?>
	</div>
	
	<div class="clearfix"></div>
	
<?php 
	$htmlOptions_type0 = array('empty'=>$this->trans->INGREDIENTS_SEARCH_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_GROUP, $model, 'ING_GROUP', $groupNames, Functions::CHECK_BOX_LIST, 'groupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'ING_SUBGROUP', $subgroupNames, Functions::CHECK_BOX_LIST, 'subgroupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'ING_STORABILITY', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ING_CONVENIENCE', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STATE, $model, 'ING_STATE', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0);
	//echo searchCriteriaInput($this->trans->INGREDIENTS_NUTRIENT, $model, 'NUT_ID', $nutrientData, Functions::DROP_DOWN_LIST, 'nutrientData', $htmlOptions_type0);
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->label($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT)); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID')); ?>
		<?php echo CHtml::link($this->trans->INGREDIENTS_SEARCH_CHOOSE, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect')) ?>
	</div>
	
<?php /*
	<div class="row">
		<?php echo $form->label($model,'ING_ID'); ?>
		<?php echo $form->textField($model,'ING_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_CREATED'); ?>
		<?php echo $form->textField($model,'ING_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_CHANGED'); ?>
		<?php echo $form->textField($model,'ING_CHANGED'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_PICTURE'); ?>
		<?php echo $form->textField($model,'ING_PICTURE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'ING_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_TITLE_EN'); ?>
		<?php echo $form->textField($model,'ING_TITLE_EN',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ING_TITLE_DE'); ?>
		<?php echo $form->textField($model,'ING_TITLE_DE',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($this->trans->INGREDIENTS_SEARCH); ?>
	</div>
*/ ?>

<?php $this->endWidget(); ?>
<br />

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	/*'ajaxUpdate'=>'ingredientsAdvanceSearch',*/
)); ?>
</div>