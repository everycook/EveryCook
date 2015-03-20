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

<div id="recipeSearchArea">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
	'htmlOptions'=>array('class'=>($this->isFancyAjaxRequest)?'fancyForm':''),
)); ?>
	<div class="f-left search">
		<?php
		echo '<div class="search_query">';
		echo Functions::specialField('query', $query, 'search', array('class'=>'search_query'));
		echo '</div>';
		
		Yii::app()->clientScript->registerScript('searchRecipe_typeahead', "
		$('#recipeSearchArea div.search input.search_query').typeahead({
			highlight: true,
		}, {
			name: 'recipes',
			displayKey: 'name',
			source: glob.typeahead.recipes.ttAdapter(),
			templates: {
				suggestion: function(data){
					return data.name + '<span style=\"color:lightgrey\"> (' + data.src + ')</span>';
				}
			},
		});
		");
		
		/*
		echo CHtml::hiddenField('query', $query, array('id'=>'searchRecipe', 'data-placeholder'=>$this->trans->RECIPES_TYPE_A_DISH));
		
		$this->widget('ext.select2.ESelect2', array(
			'target' => '#searchRecipe',
			'config' => array (
				'multiple' => true,
				'minimumInputLength' => 1,
				'formatInputTooShort' => null,
				'openOnEnter' => false,
				'placeholder'=>$this->trans->RECIPES_TYPE_A_DISH,
				'ajax' => 'js:glob.select2.searchRecipeAjax',
				'initSelection' =>'js:glob.select2.searchRecipeInitSelection',
				'formatResult' => 'js:glob.select2.searchRecipeFormatResult', // omitted for brevity, see the source of this page
				'formatSelection' => 'js:glob.select2.searchRecipeFormatSelection', // omitted for brevity, see the source of this page
				//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
				'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
				'escapeMarkup' => 'js:function (m) { return m; }', // we do not want to escape markup since we are displaying html in results
				'createSearchChoice' => 'js:glob.select2.createSearchChoice',
			)
		));
		*/
		echo CHtml::imageButton(Yii::app()->request->baseUrl . '/pics/search.png', array('class'=>'search_button', 'title'=>$this->trans->GENERAL_SEARCH));
		?>
	</div>
	<?php
	
	if(count($collations)>0){
		if(isset($collations['_max'])){
			$value = $collations['_max'];
		} else {
			$value = key($collations);
		}
		$others = $collations;
		$dym = $value;
	} else if(count($suggestions)>0){
		$suggestions=reset($suggestions); //get first word only
		if(isset($suggestions['_max'])){
			$value = $suggestions['_max'];
		} else {
			$value = key($suggestions);
		}
		$others = $suggestions;
		$dym = $value;
	}
	if (isset($dym) && $dym != '_max'){
		unset($others['_max']);
		unset($others[$value]);
		function strrnatcmp($a, $b){
			return strnatcmp($b, $a);
		};
		uasort ($others, "strrnatcmp");
		$others = array_keys($others);
		if (count($others)>0){
			echo '<div id="didYouMean"><span class="title">' . $this->trans->GENERAL_DID_YOU_MEAN . '</span>';
			echo '<span class="text">' . $dym . '</span>';
			foreach($others as $other){
				echo '<span class="text">' . $other . '</span>';
			}
			echo '</div>';
		} else {
			echo '<div id="didYouMean"><span class="title">' . $this->trans->GENERAL_DID_YOU_MEAN . '</span><span class="text">' . $dym . '</span></div>';
		}
	} 
	?>
<?php /*if (!$this->isFancyAjaxRequest){ ?>	
	<div class="f-right">
		<?php echo CHtml::link($this->trans->GENERAL_ADVANCE_SEARCH, array($advanceURL, $newRecSearch), array('class'=>'button')); ?><br>
	</div>
<?php }*/ ?>
	<div class="clearfix"></div>
	<div id="recipeResultArea">
		<div id="filters">
		<?php
		$facetsArray = $facets->toArray();
		foreach($activeFacets as $name=>$facetOptions){
			$name = strtolower($name);
			if(isset($facetsArray[$name])){
				/* @var $facet ASolrFacet */
				$facet = $facetsArray[$name];
				$values = array();
				if ($facet->type == 'facet_fields'){
					foreach($facet->toArray() as $value=>$amount){
						$values[$value] = $value . ' ('.$amount.')';
					}
				} else if ($facet->type == 'facet_ranges'){
					foreach($facet->toArray()['counts'] as $value=>$amount){
						$upperValue = $value + $facet->toArray()['gap']-1;
						$values[$value . '-' . $upperValue] = $value . ' - ' . $upperValue . ' ('.$amount.')';
					}
				}
				unset($values['_undefined_property_name']);
				unset($values['']);
				if (count($values) > 0){
					echo '<div class="filter">';
						echo '<div class="title">' . $facetOptions['title'] . '</div>';
						echo '<div class="values">';
							echo CHtml::checkBoxList($facet->name, $selectedFacets[$facet->name], $values, array('class'=>'value'));
						echo '</div>';
					echo '</div>';
				}
			}
		}
		if ($this->debug){
			/* @var $facet ASolrFacet */
			foreach($facets->toArray() as $name=>$facet){
				if (!isset($activeFacets[$facet->name])){ //otherwiese already outputed above
					$values = array();
					if ($facet->type == 'facet_fields'){
						foreach($facet->toArray() as $value=>$amount){
							$values[$value] = $value . ' ('.$amount.')';
						}
					} else if ($facet->type == 'facet_ranges'){
						foreach($facet->toArray()['counts'] as $value=>$amount){
							$upperValue = $value + $facet->toArray()['gap']-1;
							$values[$value . '-' . $upperValue] = $value . ' - ' . $upperValue . ' ('.$amount.')';
						}
					} else if ($facet->type == 'facet_dates'){
						foreach($facet->toArray()['counts'] as $value=>$amount){
							$values[$value] = $value . ' - ' . ($value .'+' . $facet->toArray()['gap']) . ' ('.$amount.')';
						}
					} else if ($facet->type == 'facet_queries'){
						//TODO: how are these values?
						foreach($facet->toArray() as $value=>$amount){
							$values[$value] = $value;
						}
					}
					unset($values['_undefined_property_name']);
					unset($values['']);
					if (count($values) > 0){	
						echo '<div class="filter">';
							echo '<div class="title">' . $facet->name . '</div>';
							echo '<div class="values">';
								echo CHtml::checkBoxList($facet->name,'', $values, array('class'=>'value'));
							echo '</div>';
						echo '</div>';
					}
				}
			}
		}
		?>
		<?php /*
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
							'formatInputTooShort' => null,
							'openOnEnter' => false,
							'placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT,
							'ajax' => 'js:glob.select2.searchIngredientAjax',
							'initSelection' =>'js:glob.select2.searchIngredientInitSelection',
							'formatResult' => 'js:glob.select2.searchIngredientFormatResult', // omitted for brevity, see the source of this page
							'formatSelection' => 'js:glob.select2.searchIngredientFormatSelection', // omitted for brevity, see the source of this page
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
							'formatInputTooShort' => null,
							'openOnEnter' => false,
							'placeholder'=>$this->trans->RECIPES_TYPE_AN_INGREDIENT,
							'ajax' => 'js:glob.select2.searchIngredientAjax',
							'initSelection' =>'js:glob.select2.searchIngredientInitSelection',
							'formatResult' => 'js:glob.select2.searchIngredientFormatResult', // omitted for brevity, see the source of this page
							'formatSelection' => 'js:glob.select2.searchIngredientFormatSelection', // omitted for brevity, see the source of this page
							//'dropdownCssClass' => 'search_query', // apply css that makes the dropdown taller
							'containerCssClass' => 'search_query', // apply css that makes the dropdown taller
							'escapeMarkup' => 'js:function (m) { return m; }' // we do not want to escape markup since we are displaying html in results
						)
					));
					?>
				</div>
			</div>
		*/ ?>
		</div>
		<div id="recipeOrderBy"><?php
			echo '<span>' . $this->trans->GENERAL_ORDER_BY . '</span>';
			//TODO Output $sort->attributes
			//echo CHtml::dropDownList('orderby', $selectedOrderBy, $possibleOrderBys);
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
