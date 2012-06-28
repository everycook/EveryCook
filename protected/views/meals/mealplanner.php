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

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); ?>
	
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
					array('gda_id_kcal', $this->trans->MEALPLANNER_PEOPLE_AGE, array('GDA_F'=>$GDA_Woman, 'GDA_M'=>$GDA_Man), array('multiple_selects'=>true)),
					array(null, $this->trans->MEALPLANNER_PEOPLE_GDA, null, array('htmlTag'=>'span','htmlContent'=>sprintf($this->trans->MEALPLANNER_PEOPLE_GDA_SUM,'<span class="value">0</span>'))),
				);
				$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'options'=>'Options');
				
				$options = array('new'=>new CouPeopleGDA,'noTitle'=>true);
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