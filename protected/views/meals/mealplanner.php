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
?>
<div class="form">

<div class="hidden" id="peopleConfig">
<?php
	echo CHtml::hiddenField('rowsJSON', CJSON::encode($couPeopleGDA));
	echo CHtml::hiddenField('errorJSON', CJSON::encode($this->errorFields));
?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'meals-form',
	'enableAjaxValidation'=>false,
	//'htmlOptions'=>array('class'=>'noAjax'),
)); ?>

	<?php 
		echo $form->errorSummary($model);
		if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
		}
	?>
	
	<div class="row buttons" <?php if(!$model->isNewRecord){echo 'style="display:none"';} ?>>
		<div><?php echo $this->trans->MEALPLANNER_COOK_DATE; ?></div>
		<div id="NextMeal" class="button"><?php echo $this->trans->MEALPLANNER_NEXT_MEAL; ?></div>
		<div id="OtherDay" class="button"><?php echo $this->trans->MEALPLANNER_OTHER_DAY; ?></div>
	</div>
	<div id="mealOptions" style="display:none">
		<div><?php echo $this->trans->MEALPLANNER_COOK_DATE; ?></div>
		<?php echo Functions::activeSpecialField($model, 'date', 'date'); ?>
		<?php echo $form->dropDownList($model,'MTY_ID', $mealType, array('empty'=>$this->trans->GENERAL_CHOOSE,)); ?>
		<?php echo $form->dropDownList($model,'hour', $hours, array()); ?>
		<?php echo $form->dropDownList($model,'minute', $minutes, array()); ?>
		<div class="row buttons">
			<div id="gotoPeople" class="button"<?php if(!$model->isNewRecord){echo 'style="display:none"';} ?>><?php echo $this->trans->MEALPLANNER_GOTO_PEOPLE; ?></div>
			<div id="backToCourses" class="button" <?php if($model->isNewRecord){echo 'style="display:none"';} ?>><?php echo $this->trans->MEALPLANNER_BACK_TO_COURSES; ?></div>
		</div>
	</div>
	<div id="peopleDetails" class="resultArea" style="display:none">
		<div id="peopleDetailsContent">
			<div><?php echo $this->trans->MEALPLANNER_HOW_MANY; ?></div>
			<div class="people">
			<?php
				$fieldOptions = array(
					array('amount', $this->trans->MEALPLANNER_PEOPLE_AMOUNT, null, array('field_type'=>'number', 'size'=>3)),
					array('gender', $this->trans->MEALPLANNER_PEOPLE_GENDER, array('F'=>$this->trans->PROFILES_GENDER_F, 'M'=>$this->trans->PROFILES_GENDER_M), array()),
					array('gda_id_kcal', $this->trans->MEALPLANNER_PEOPLE_AGE, array('GDA_F'=>$GDA_Woman, 'GDA_M'=>$GDA_Man), array('multiple_selects'=>0)),
					array(null, $this->trans->MEALPLANNER_PEOPLE_GDA, null, array('htmlTag'=>'span','htmlContent'=>sprintf($this->trans->MEALPLANNER_PEOPLE_GDA_SUM,'<span class="value">0</span>'))),
				);
				$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'options'=>'Options');
				$options = array('new'=>new CouPeopleGDA, 'newValues'=>array('amount'=>1),'noTitle'=>true);
				echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
			?>
			</div>
			
			<div class="row buttons">
				<div id="gotoCourses" class="button" <?php if(!$model->isNewRecord){echo 'style="display:none"';} ?>><?php echo $this->trans->MEALPLANNER_GOTO_COURSES; ?></div>
				<div id="cancelPeople" class="button" <?php if($model->isNewRecord){echo 'style="display:none"';} ?>><?php echo $this->trans->GENERAL_CANCEL; ?></div>
				<div id="useForCourse" class="button f-right" <?php if($model->isNewRecord){echo 'style="display:none"';} ?>><?php echo $this->trans->MEALPLANNER_USE_PEOPLE_FOR_COURSE; ?></div>
			</div>
		</div>
	</div>
	<div style="display:none">
		<div id="removeRecipeContent" class="form">
			<span><?php echo $this->trans->MEALPLANNER_SELECT_RECIPE_TO_DELETE; ?></span><br>
			<select></select>
			<div class="row buttons">
				<span class="button" id="cancelDelete"><?php echo $this->trans->GENERAL_CANCEL; ?></span>
				<span class="button" id="deleteRecipe"><?php echo $this->trans->GENERAL_DELETE; ?></span>
			</div>
		</div>
	</div>
	
	<?php $this->renderPartial('_view',array(
		'data'=>$model,
		'elemID'=>'courseDetails',
		'hideElem'=>$model->isNewRecord,
		'editMode'=>true,
	)); ?>
	
<?php $this->endWidget(); ?>

</div><!-- form -->