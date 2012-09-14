<div <?php if(isset($elemID)){ echo 'id="' . $elemID . '" '; } if(isset($hideElem) && $hideElem){ echo 'style="display:none" '; } ?> class="resultArea mealView">
	<?php
	echo '<div>';
		if(isset($editMode) && $editMode){
			echo '<div class="slider_holder">' . Functions::activeSpecialField($data, 'MEA_PERC_GDA', 'range', array('min'=>0,'max'=>100)) . '</div>';
			printf($this->trans->MEALPLANNER_MEAL_GDA,'<span class="value">' . $data->MEA_PERC_GDA . '</span>');
			$inputName = Functions::resolveMultiArrayName($data, array('MEA_ID'));
			echo CHtml::hiddenField($inputName, $data->MEA_ID);
		} else {
			printf($this->trans->MEALPLANNER_MEAL_GDA,'<span>' . $data->MEA_PERC_GDA . '</span>');
		}
	echo '</div>';
	?>
	<div style="display:table; width: 100%; position: relative;">
	<div style="display:table-row;">
		<div class="mealOverview">
			<?php
			if (isset($data->mealType)){
				echo '<div>' . $data->mealType->__get('MTY_DESC_'.Yii::app()->session['lang']) . '</div>';
			} else {
				echo '<div></div>';
			}
			echo '<div>' . $data['date'] . '</div>';
			echo '<div>' . $data['hour'] . ':' . $data['minute'] . '</div>';
			if(isset($editMode) && $editMode){
				echo '<div class="button">' . $this->trans->GENERAL_EDIT . '</div>';
			}
			?>
		</div>
		<div class="meal_courses">
			<?php
			$meaToCous_index=0;
			foreach($data->meaToCous as $meaToCou) {
				echo '<div class="meal_course">';
					echo '<div>';
					if(isset($editMode) && $editMode){
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'COU_DESC'));
						////echo CHtml::label($meaToCou->course->getAttributeLabel('COU_DESC'), $inputName);
						//echo $meaToCou->course->getAttributeLabel('COU_DESC') . ': ';
						//echo CHtml::textField($inputName, $meaToCou->course->COU_DESC, array('style'=>'width: 20em;'));
						echo '<br>';
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_PERC_MEAL'));
						echo '<div class="slider_holder">' . Functions::specialField($inputName, $meaToCou->MTC_PERC_MEAL, 'range', array('min'=>0,'max'=>100)) . '</div>';
						printf($this->trans->MEALPLANNER_COURSE_GDA,'<span class="value">' . $meaToCou->MTC_PERC_MEAL . '</span>');
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'COU_ID'));
						echo CHtml::hiddenField($inputName, $meaToCou->course->COU_ID);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_EAT_PERS'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_EAT_PERS);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_KCAL_DAY_TOTAL'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_KCAL_DAY_TOTAL);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_EAT_ADULTS'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_EAT_ADULTS);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_EAT_CHILDREN'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_EAT_CHILDREN);
					} else {
						//echo '<span>' . $meaToCou->course->COU_DESC . '</span><br>';
						printf($this->trans->MEALPLANNER_COURSE_GDA,'<span>' . $meaToCou->MTC_PERC_MEAL . '</span>');
					}
					echo '</div>';
					echo '<div class="cou_recipes">';
						$couToRecs_index=0;
						foreach($meaToCou->course->couToRecs as $couToRec) {
							$recipe = $couToRec->recipe;
							if (isset($recipe) && $recipe != null){
								echo '<div class="cou_recipe">';
									if(isset($editMode) && $editMode){
										$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'couToRecs', $couToRecs_index, 'REC_ID'));
										echo CHtml::hiddenField($inputName, $recipe['REC_ID']);
									}
									echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'cou_recipe', 'alt'=>$recipe['REC_IMG_AUTH'], 'title'=>$recipe['REC_IMG_AUTH']));
									echo '<br>';
									echo '<span class="title">' . $recipe['REC_NAME_'.Yii::app()->session['lang']] . '</span><br>';
									if(isset($editMode) && $editMode){
										$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'couToRecs', $couToRecs_index, 'CTR_REC_PROC'));
										echo '<div class="slider_holder">' . Functions::specialField($inputName, $couToRec->CTR_REC_PROC, 'range', array('min'=>0,'max'=>100)) . '</div>';
										echo '<span class="value">' . $couToRec->CTR_REC_PROC . '</span>%';
									} else {
										echo '<span>' . $couToRec->CTR_REC_PROC . '</span>%';
									}
								echo '</div>';
								
								++$couToRecs_index;
							}
						}
						if ($meaToCou->MTC_EAT_CHILDREN>0){
							if ($meaToCou->MTC_EAT_ADULTS>0){
								$text = sprintf($this->trans->MEALPLANNER_EATING_PEOPLE,$meaToCou->MTC_EAT_ADULTS,$meaToCou->MTC_EAT_CHILDREN);
							} else {
								$text = sprintf($this->trans->MEALPLANNER_EATING_PEOPLE_CHILD,$meaToCou->MTC_EAT_CHILDREN);
							}
						} else {
							$text = sprintf($this->trans->MEALPLANNER_EATING_PEOPLE_ADULT,$meaToCou->MTC_EAT_ADULTS);
						}
						if(isset($editMode) && $editMode){
							echo '<div style="display: table-cell; vertical-align: top;">';
								echo CHtml::link($text, '#peopleDetailsContent', array('class'=>'button PeopleSelect'));
								echo CHtml::link($this->trans->MEALPLANNER_ADD_RECIPE, array('recipes/chooseRecipe'), array('class'=>'button fancyChoose RecipeSelect'));
								echo CHtml::link($this->trans->MEALPLANNER_REMOVE_RECIPE, '#removeRecipeContent', array('class'=>'button RecipeRemove'));
								echo '<input type="hidden" class="fancyValue"/>';
							echo '</div>';
						} else {
							echo '<div style="display: table-cell; vertical-align: top;">';
								echo '<span>' . $text . '</span>';
							echo '</div>';
						}
					echo '</div>';
				echo '</div>';
				++$meaToCous_index;
			} ?>
			<?php if(isset($editMode) && $editMode){ ?>
			<div id="addCourse" class="button"><?php echo $this->trans->MEALPLANNER_ADD_COURSE; ?></div>
			<?php } ?>
		</div>
	</div>
	</div>
	<div class="clearfix"></div>
	<div class="row buttons">
		<?php
		if(isset($editMode) && $editMode){
			echo CHtml::submitButton($this->trans->MEALPLANNER_SAVE_TO_SHOPPINGLIST, array('name'=>'saveToShoppingList', 'class'=>'button'));
			?>
			<div class="f-right">
				<?php
				echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel'));
				echo CHtml::submitButton($data->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('name'=>'save', 'class'=>'button'));
				?>
			</div>
		<?php
		} else {
			if (isset($data->SHO_ID) && $data->SHO_ID != 0){
				echo CHtml::link($this->trans->MEALPLANNER_SAVE_TO_SHOPPINGLIST, array('shoppinglists/view', 'id'=>$data->SHO_ID), array('class'=>'button f-left'));
			} else {
				echo CHtml::link($this->trans->MEALPLANNER_SAVE_TO_SHOPPINGLIST, array('meals/createShoppingList', 'id'=>$data->MEA_ID), array('class'=>'button f-left'));
			}
			?>
			<div class="f-right">
				<?php echo CHtml::link($this->trans->GENERAL_EDIT, array('meals/mealplanner', 'id'=>$data->MEA_ID), array('class'=>'button')); ?>
			</div>
			<div class="f-center">
				<?php echo CHtml::link($this->trans->MEALPLANNER_COOKASSISTANT, array('cookAssistant/start', 'id'=>$data->MEA_ID), array('class'=>'button')); ?>
			</div>
		<?php } ?>
	</div>
</div>