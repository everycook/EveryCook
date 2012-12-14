<?php
$this->breadcrumbs=array(
	'Actions Ins',
);

$this->menu=array(
	array('label'=>'Create ActionsIn', 'url'=>array('create')),
	array('label'=>'Manage ActionsIn', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}

$simpleSearch = array(($this->isFancyAjaxRequest)?'chooseActionsIn':'search');
if (isset(Yii::app()->session['ActionsIn']) && isset(Yii::app()->session['ActionsIn']['time'])){
	$simpleSearch=array_merge($simpleSearch,array('newSearch'=>Yii::app()->session['ActionsIn']['time']));
}

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl('advanceChooseActionsIn'); ?>"/>
	<?php
}
?>

<div id="actions-inAdvanceSearch">
<?php  $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'post',
		'id'=>'actions-in_form',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php  if ($model2->query == ''){
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query', 'autofocus'=>'autofocus'));
		} else {
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query'));
		} ?>
		<?php  echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	<div class="f-right">
		<?php  echo CHtml::link($this->trans->GENERAL_SIMPLE_SEARCH, $simpleSearch, array('class'=>'button', 'id'=>'simpleSearch')); ?><br>
	</div>
	<div class="f-center">
		<?php  echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('create','newModel'=>time()), array('class'=>'button', 'id'=>'create')); ?><br>
	</div>
	
	<div class="clearfix"></div>
	
<?php
	/*
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_GROUP, $model, 'GRP_ID', $groupNames, Functions::CHECK_BOX_LIST, 'groupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, Functions::CHECK_BOX_LIST, 'subgroupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STATE, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0);
	//echo searchCriteriaInput($this->trans->INGREDIENTS_NUTRIENT, $model, 'NUT_ID', $nutrientData, Functions::DROP_DOWN_LIST, 'nutrientData', $htmlOptions_type0);
	*/
	/* //example FancyCoose
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->label($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT, 'style'=>'vertical-align: middle;')); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CHOOSE, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect buttonSmall')) ?>
	</div>
	
<?php
*/

/*	
	
	<div class="row">
		<?php echo $form->label($model,'AIN_ID'); ?>
		<?php echo $form->textField($model,'AIN_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AIN_DEFAULT'); ?>
		<?php echo $form->textField($model,'AIN_DEFAULT',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AIN_PREP'); ?>
		<?php echo $form->textField($model,'AIN_PREP',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AIN_DESC_DE_CH'); ?>
		<?php echo $form->textField($model,'AIN_DESC_DE_CH',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AIN_DESC_EN_GB'); ?>
		<?php echo $form->textField($model,'AIN_DESC_EN_GB',array('size'=>60,'maxlength'=>100)); ?>
	</div>

*/
?>

<br />

<?php $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'actions-inResult',
)); ?>

<?php $this->endWidget(); ?>

</div>