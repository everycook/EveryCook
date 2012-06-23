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
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php if($model->isNewRecord){ ?>
	<div class="row buttons">
		<div><?php echo $this->trans->MEALPLANER_COOK_DATE; ?></div>
		<div id="NextMeal" class="button"><?php echo $this->trans->MEALPLANER_NEXT_MEAL; ?></div>
		<div id="OtherDay" class="button"><?php echo $this->trans->MEALPLANER_OTHER_DAY; ?></div>
	</div>
	<div id="mealOptions" style="display:none">
		<?php echo Functions::activeSpecialField($model, 'date', 'date'); ?>
		<?php echo $form->dropDownList($model,'MEA_TYPE', $mealType, array('empty'=>$this->trans->GENERAL_CHOOSE,)); ?>
		<?php echo $form->dropDownList($model,'hour', $hours, array()); ?>
		<?php echo $form->dropDownList($model,'minute', $minutes, array()); ?>
		<div class="row buttons">
			<div id="gotoPeople" class="button"><?php echo $this->trans->MEALPLANER_GOTO_PEOPLE; ?></div>
		</div>
	</div>
	<?php } ?>
	<div id="peopleDetails" class="resultArea" style="display:none">
		<div><?php echo $this->trans->MEALPLANER_HOW_MANY; ?></div>
		<div class="people">
		<?php
			$fieldOptions = array(
				array('amount', $this->trans->MEALPLANER_PEOPLE_AMOUNT, null, array('field_type'=>'number', 'size'=>3)),
				array('gender', $this->trans->MEALPLANER_PEOPLE_GENDER, array('F'=>$this->trans->PROFILES_GENDER_F, 'M'=>$this->trans->PROFILES_GENDER_M), array()),
				array('gda_id_kcal', $this->trans->MEALPLANER_PEOPLE_AGE, array('GDA_F'=>$GDA_Woman, 'GDA_M'=>$GDA_Man), array('multiple_selects'=>true)),
				array(null, $this->trans->MEALPLANER_PEOPLE_GDA, null, array('htmlTag'=>'span','htmlContent'=>sprintf($this->trans->MEALPLANER_PEOPLE_GDA_SUM,'<span class="value">0</span>'))),
			);
			$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'options'=>'Options');
			
			$options = array('new'=>new CouPeopleGDA,'noTitle'=>true);
			echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
		?>
		</div>
		
		<div class="row buttons">
			<div id="gotoCourses" class="button"><?php echo $this->trans->MEALPLANER_GOTO_COURSES; ?></div>
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

















