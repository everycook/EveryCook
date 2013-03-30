<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$transKey = strtoupper($this->modelClass);
?>
<?php
echo "<?php\n"; ?>
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label',
);\n";
?>

$this->menu=array(
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);

if (!$this->isFancyAjaxRequest){
	//if ($this->validSearchPerformed){
		$this->mainButtons = array(
			array('label'=>$this->trans->GENERAL_CREATE_NEW, 'link_id'=>'middle_single', 'url'=>array('create',array('newModel'=>time()))),
		);
	//}
}

$advanceSearch = array(($this->isFancyAjaxRequest)?'advanceChooseIngredient':'advanceSearch');
if (isset(Yii::app()->session['<?php echo $this->modelClass; ?>']) && isset(Yii::app()->session['<?php echo $this->modelClass; ?>']['time'])){
	$advanceSearch=array_merge($advanceSearch,array('newSearch'=>Yii::app()->session['<?php echo $this->modelClass; ?>']['time']));
}

if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo "<?php"; ?> echo $this->createUrl('advanceChoose<?php echo $this->modelClass; ?>'); ?>"/>
	<?php echo "<?php\n"; ?>
}
?>

<div id="<?php echo $this->class2id($this->modelClass); ?>AdvanceSearch">
<?php echo "<?php"; ?>  $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'post',
		'id'=>'<?php echo $this->class2id($this->modelClass); ?>_form',
		'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
	)); ?>
	<div class="f-left search">
		<?php echo "<?php"; ?>  if ($model2->query == ''){
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query', 'autofocus'=>'autofocus'));
		} else {
			echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query'));
		} ?>
		<?php echo "<?php"; ?>  echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH)); ?>
	</div>
	<div class="f-right">
		<?php echo "<?php"; ?>  echo CHtml::link($this->trans->GENERAL_SIMPLE_SEARCH, $simpleSearch, array('class'=>'button', 'id'=>'simpleSearch')); ?><br>
	</div>
	<div class="f-center">
		<?php echo "<?php"; ?>  echo CHtml::link($this->trans->GENERAL_CREATE_NEW, array('create','newModel'=>time()), array('class'=>'button', 'id'=>'create')); ?><br>
	</div>
	
	<div class="clearfix"></div>
	
<?php echo "<?php\r\n"; ?>
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_GROUP, $model, 'GRP_ID', $groupNames, Functions::CHECK_BOX_LIST, 'groupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, Functions::CHECK_BOX_LIST, 'subgroupNames', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STORABILITY, $model, 'STB_ID', $storability, Functions::CHECK_BOX_LIST, 'storability', $htmlOptions_type1);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0);
	echo Functions::searchCriteriaInput($this->trans->INGREDIENTS_STATE, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0);
	//echo searchCriteriaInput($this->trans->INGREDIENTS_NUTRIENT, $model, 'NUT_ID', $nutrientData, Functions::DROP_DOWN_LIST, 'nutrientData', $htmlOptions_type0);
	
	/* //example FancyCoose
	?>
	
	<div class="row" id="nutrientData">
		<?php echo "<?php"; ?> echo $form->label($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT, 'style'=>'vertical-align: middle;')); ?>
		<?php echo "<?php"; ?> echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue')); ?>
		<?php echo "<?php"; ?> echo CHtml::link($this->trans->GENERAL_CHOOSE, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect buttonSmall')) ?>
	</div>
	
<?php echo "<?php\r\n"; ?>
*/

/*	
	
<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
	<div class="row">
		<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
		<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
	</div>

<?php endforeach; ?>
*/
?>

<br />

<?php echo "<?php"; ?> $this->widget('AjaxPagingListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_array',
	'ajaxUpdate'=>false,
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>Result',
)); ?>

<?php echo "<?php"; ?> $this->endWidget(); ?>

</div>