<?php
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

if (isset(Yii::app()->session['Recipes']) && isset(Yii::app()->session['Recipes']['time'])){
	$newRecSearch=array('newSearch'=>Yii::app()->session['Recipes']['time']);
} else {
	$newRecSearch=array();
}
if ($this->isFancyAjaxRequest){ ?>
	<input type="hidden" id="FancyChooseSubmitLink" value="<?php echo $this->createUrl($this->route); ?>"/>
	<?php
	if ($this->isTemplateChoose){
		$advanceURL = 'recipes/advanceChooseRecipe';
	} else {
		$advanceURL = 'recipes/advanceChooseTemplateRecipe';
	}
} else {
	$advanceURL = 'recipes/advanceSearch';
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
<?php /*if (!$this->isFancyAjaxRequest){ ?>	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, array($advanceURL, $newRecSearch), array('class'=>'button')); ?><br>
	</div>
<?php }*/ ?>
	<div class="clearfix"></div>
	<div id="recipeResultArea">
		<div id="filters">
		<?php /*
			<div class="filter">
				<div class="title"><?php echo $this->trans->RECIPES_CHEF; ?></div>
				<div class="values">
				<?php echo CHtml::checkBoxList('autor', $filters['selectedAutor'], $filters['possibleAutor'], array('class'=>'value')); ?>
				</div>
			</div>
		*/ ?>
			<div class="filter">
				<div class="title"><?php echo $this->trans->RECIPES_TYPE; ?></div>
				<div class="values">
				<?php echo CHtml::checkBoxList('type', $filters['selectedTypes'], $filters['possibleTypes'], array('class'=>'value')); ?>
				</div>
			</div>
		<?php /*
			<div class="filter">
				<div class="title"><?php echo $this->trans->RECIPES_BATCHES; ?></div>
				<div class="values">
				<?php echo CHtml::checkBoxList('batches', $filters['selectedBatches'], $filters['possibleBatches'], array('class'=>'value')); ?>
				</div>
			</div>
		*/ ?>
			<div class="filter">
				<div class="title"><?php echo $this->trans->RECIPES_INGREDIENTS; ?></div>
				<div class="values">
					<?php echo $this->trans->RECIPES_CONTAINS; ?><br/>
					<?php
					echo CHtml::hiddenField('ing_id', $filters['ing_id'], array('id'=>'ingredients_contain'));
					
					$this->widget('ext.select2.ESelect2', array(
						'target' => '#ingredients_contain',
						'config' => array (
							'multiple' => true,
							'minimumInputLength' => 1,
							'placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT,
							'ajax' => 'js:glob.select2.searchFridgeAjax',
							'initSelection' =>'js:glob.select2.searchFridgeInitSelection',
							'formatResult' => 'js:glob.select2.searchFridgeFormatResult', // omitted for brevity, see the source of this page
							'formatSelection' => 'js:glob.select2.searchFridgeFormatSelection', // omitted for brevity, see the source of this page
							//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
							'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
							'escapeMarkup' => 'js:function (m) { return m; }' // we do not want to escape markup since we are displaying html in results
						)
					));
					?>
					<br/>
					<?php echo $this->trans->RECIPES_NOT_CONTAINS; ?><br/>
					<?php
					echo CHtml::hiddenField('ing_id_not', $filters['ing_id_not'], array('id'=>'ingredients_not_contain'));
					
					$this->widget('ext.select2.ESelect2', array(
						'target' => '#ingredients_not_contain',
						'config' => array (
							'multiple' => true,
							'minimumInputLength' => 1,
							'placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT,
							'ajax' => 'js:glob.select2.searchFridgeAjax',
							'initSelection' =>'js:glob.select2.searchFridgeInitSelection',
							'formatResult' => 'js:glob.select2.searchFridgeFormatResult', // omitted for brevity, see the source of this page
							'formatSelection' => 'js:glob.select2.searchFridgeFormatSelection', // omitted for brevity, see the source of this page
							//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
							'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
							'escapeMarkup' => 'js:function (m) { return m; }' // we do not want to escape markup since we are displaying html in results
						)
					));
					?>
				</div>
			</div>
			<div class="filter">
				<div class="title"><?php echo CHtml::encode($this->trans->FIELD_CUT_ID); ?></div>
				<div class="values">
				<?php echo CHtml::checkBoxList('typeOfCusine', $filters['selectedTypeOfCusine'], $filters['possibleTypeOfCusine'], array('class'=>'value')); ?>
				</div>
			</div>
		</div>
		<div id="recipeOrderBy"><?php
			echo '<span>' . $this->trans->GENERAL_ORDER_BY . '</span>';
			echo CHtml::dropDownList('orderby', $selectedOrderBy, $possibleOrderBys);
		?>
		</div>
		<?php $this->widget('AjaxPagingListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view_array',
			'id'=>'recipesResult',
		)); ?>
		<?php $this->endWidget(); ?>

<?php /*
//"advanceSearch" input fields
$form=$this->beginWidget('CActiveForm', array(
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
		<?php echo $form->label($model,'REC_IMG_FILENAME'); ?>
		<?php echo $form->textField($model,'REC_IMG_FILENAME'); ?>
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
	</div>
</div>
