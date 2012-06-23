<div <?php if(isset($elemID)){ echo 'id="' . $elemID . '" '; } if(isset($hideElem) && $hideElem){ echo 'style="display:none" '; } ?> class="resultArea mealView">
	<?php
	echo '<div>';
		echo Functions::activeSpecialField($data, 'MEA_PERC_GDA', 'range', array('min'=>0,'max'=>100));
		printf($this->trans->MEALPLANER_MEAL_GDA,'<span class="value">' . $data->MEA_PERC_GDA . '</span>');
		$inputName = Functions::resolveMultiArrayName($data, array('MEA_ID'));
		echo CHtml::hiddenField($inputName, $data->MEA_ID);
	echo '</div>';
	?>
	<div style="display:table; width: 100%;">
	<div style="display:table-row;">
		<div class="mealOverview">
			<?php
			//$mealType
			echo '<div>' . $data['MEA_TYPE'] . '</div>';
			echo '<div>' . $data['date'] . '</div>';
			echo '<div>' . $data['hour'] . ':' . $data['minute'] . '</div>';
			?>
		</div>
		<div class="meal_courses">
			<?php
			$meaToCous_index=0;
			foreach($data->meaToCous as $meaToCou) {
				echo '<div class="meal_course">';
					echo '<div>';
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_PERC_MEAL'));
						echo Functions::specialField($inputName, $meaToCou->MTC_PERC_MEAL, 'range', array('min'=>0,'max'=>100));
						printf($this->trans->MEALPLANER_COURSE_GDA,'<span class="value">' . $meaToCou->MTC_PERC_MEAL . '</span>');
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'COU_ID'));
						echo CHtml::hiddenField($inputName, $meaToCou->course->COU_ID);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_EAT_PERS'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_EAT_PERS);
						$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'MTC_KCAL_DAY_TOTAL'));
						echo CHtml::hiddenField($inputName, $meaToCou->MTC_KCAL_DAY_TOTAL);
					echo '</div>';
					echo '<div class="cou_recipes">';
						$couToRecs_index=0;
						foreach($meaToCou->course->couToRecs as $couToRec) {
							$recipe = $couToRec->recipe;
							if (isset($recipe) && $recipe != null){
								echo '<div class="cou_recipe">';
									$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'couToRecs', $couToRecs_index, 'REC_ID'));
									echo CHtml::hiddenField($inputName, $recipe['REC_ID']);
									echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), '', array('class'=>'cou_recipe', 'alt'=>$recipe['REC_IMG_AUTH'], 'title'=>$recipe['REC_IMG_AUTH']));
									echo '<br>';
									echo '<span class="title">' . $recipe['REC_NAME_'.Yii::app()->session['lang']] . '</span><br>';
									$inputName = Functions::resolveMultiArrayName($data, array('meaToCous', $meaToCous_index, 'course', 'couToRecs', $couToRecs_index, 'CTR_REC_PROC'));
									echo Functions::specialField($inputName, $couToRec->CTR_REC_PROC, 'range', array('min'=>0,'max'=>100));
									echo '<span class="value">' . $couToRec->CTR_REC_PROC . '</span>%';
								echo '</div>';
								
								++$couToRecs_index;
							}
						}
						if(isset($editMode) && $editMode){
							echo '<div style="display: table-cell; vertical-align: top;">';
								echo CHtml::link($this->trans->MEALPLANER_ADD_RECIPE, array('recipes/chooseRecipe'), array('class'=>'button fancyChoose RecipeSelect'));
								echo '<input type="hidden" class="fancyValue"/>';
							echo '</div>';
						}
					echo '</div>';
				echo '</div>';
				++$meaToCous_index;
			} ?>
			<?php if(isset($editMode) && $editMode){ ?>
			<div id="addCourse" class="button"><?php echo $this->trans->MEALPLANER_ADD_COURSE; ?></div>
			<?php } ?>
		</div>
	</div>
	</div>
	<div class="clearfix"></div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($this->trans->MEALPLANER_SAVE_TO_SHOPPINGLIST, array('name'=>'saveToShoppingList', 'class'=>'button')); ?>
		
		<?php if(isset($editMode) && $editMode){ ?>
		<div class="f-right">
			<?php
			echo CHtml::link($this->trans->GENERAL_CANCEL, array('meals/meallist'), array('class'=>'button', 'id'=>'cancel'));
			echo CHtml::submitButton($data->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('name'=>'save', 'class'=>'button'));
			?>
		</div>
		<?php } else { ?>
		<div class="f-right">
			<?php echo CHtml::link($this->trans->GENERAL_EDIT, array('meals/mealplaner', 'id'=>$data->MEA_ID), array('class'=>'button')); ?>
		</div>
		<?php } ?>
	</div>
</div>