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
<div class="cookAssistant">
	<div class="meta">
		<div class=""><?php printf($this->trans->COOKASISSTANT_COURSE_RECIPES, $info->courseNr+1, count($info->steps)); ?></div>
		<div class="finishTime"><?php echo $this->trans->COOKASISSTANT_FINISHED_AT; ?><br/><span><?php echo $info->finishedIn ?></span></div>
		<input type="hidden" name="finishTime" id="finishTime" value="<?php echo $info->finishedIn ?>"/>
		<input type="hidden" name="timeDiff" id="timeDiff" value="<?php echo $info->timeDiffMax ?>"/>
		<input type="hidden" name="started" id="started" value="<?php echo $info->started ?>"/>
		<?php echo CHtml::link('<div>'.$this->trans->COOKASISSTANT_OVERVIEW.'</div>', array('overview'), array('class'=>'f-right')); ?>
		<span class="clearfix"></span>
	</div>
	<div class="recipeSteps">
	<?php
		$i=0; 
		foreach($info->steps as $mealStep){
			$cookWith = '';
			$coowWithType = $info->cookWith[$mealStep->recipeNr][0];
			if ($coowWithType==CookAssistantController::COOK_WITH_LOCAL){
				$cookWith = $_SERVER['HTTP_HOST'];
			} else if ($coowWithType==CookAssistantController::COOK_WITH_IP){
				$cookWith = $info->cookWith[$mealStep->recipeNr][2];
			}
			echo '<div class="recipeStep">';
				echo '<input type="hidden" name="cookWith" value="' . ($cookWith) . '"/>';
				echo '<input type="hidden" name="stepNr" value="' . $mealStep->stepNr . '"/>';
				echo '<div class="stepHeader">';
					echo '<div class="title"><div>' . $mealStep->recipeName . '</div></div>';
					echo '<div class="finishTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . (($mealStep->stepNr != -1)?$this->trans->COOKASISSTANT_FINISHED_AT:$this->trans->COOKASISSTANT_START_AT) . ' <span>'  .  '</span></div></div>'; // $mealStep->finishedAt .
					echo '<input type="hidden" name="finishTime" value="' . $mealStep->finishedIn . '"/>';
					echo '<input type="hidden" name="lowestFinishTime" value="' . $mealStep->lowestFinishedIn . '"/>';
					if ($cookWith!=''){
						echo '<div class="temp"><div><div>' . $this->trans->COOKASISSTANT_TEMPERATURE . ' <span class="temp">'.$mealStep->currentTemp.'</span>°C</div><div>' . $this->trans->COOKASISSTANT_PRESSURE . ' <span class="press">'.$mealStep->currentPress.'</span>pa</div></div></div>';
					}
					if (!$mealStep->endReached){
						echo '<div class="nextTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . $this->trans->COOKASISSTANT_NEXT_STEP_IN . ' <span>'  . '</span></div></div>'; // $mealStep->nextStepIn . 
					} else {
						if (isset($info->courseFinished[$info->courseNr]) && $info->courseFinished[$info->courseNr] === true)
						if (!isset($info->voted[$mealStep->recipeNr]) || $info->voted[$mealStep->recipeNr]===0) {
							echo '<div class="nextTime"><div>' . $this->trans->COOKASISSTANT_VOTE_FOR_RECIPE . '</div></div>';
						}
					}
					echo '<input type="hidden" name="nextTime" value="' . $mealStep->nextStepIn . '"/>';
					echo '<input type="hidden" name="nextTimeTotal" value="' . $mealStep->nextStepTotal . '"/>';
					
					echo '<span class="clearfix"></span>';
					echo '<input type="hidden" name="UpdateCookAssistantLink" value="' . $this->createUrl('cookAssistant/updateState', array('recipeNr'=>$mealStep->recipeNr)) . '"/>';
				echo '</div>';
				echo '<div class="stepInfo">';	
					echo '<div class="action">';
						echo '<div class="progress" style="width:' .($mealStep->percent*100). '%"></div>';
						if ($mealStep->ingredientId != 0){
							echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$mealStep->ingredientId, 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$mealStep->ingredientCopyright, 'title'=>$mealStep->ingredientCopyright));
						}
						echo '<div class="actionInner' . (($mealStep->ingredientId != 0)?' withPic':'') . '">';
							echo '<div class="actionMainText">' . $mealStep->mainActionText . '</div>';
							echo '<div class="actionText">' . $mealStep->actionText . '</div>';
							echo '<span class="clearfix"></span>';
						echo '</div>';
					echo '</div>';
					if (!$mealStep->endReached){
						echo CHtml::link('<div></div>', array('next', 'recipeNr'=>$mealStep->recipeNr, 'step'=>$mealStep->stepNr), array('class'=>'nextStep' . (($mealStep->stepNr == -1)?' startStep':'') . (($mealStep->autoClick)?' autoClick':'') . (($mealStep->mustWait)?' mustWait':'') . (($mealStep->stepType == CookAssistantController::SCALE)?' isWeightStep':'')));
					} else  {
						if (isset($info->courseFinished[$info->courseNr]) && $info->courseFinished[$info->courseNr] === true){
							$course = $info->meal->meaToCous[$info->courseNr]->course;
							if (!isset($info->voted[$mealStep->recipeNr]) || $info->voted[$mealStep->recipeNr]===0) {
								echo '<div class="nextStep">';
								echo CHtml::link($this->trans->COOKASISSTANT_VOTE_GOOD, array('vote', 'recipeNr'=>$mealStep->recipeNr, 'value'=>1), array('class'=>'button vote noAjax first'));
								echo CHtml::link($this->trans->COOKASISSTANT_VOTE_BAD, array('vote', 'recipeNr'=>$mealStep->recipeNr, 'value'=>-1), array('class'=>'button vote noAjax'));
								echo CHtml::link($this->trans->COOKASISSTANT_CHANGE_RECIPE, array('recipes/update', 'id'=>$course->couToRecs[$mealStep->recipeNr]->recipe->REC_ID), array('class'=>'button changeRecipe', 'style'=>'display:none;'));
								echo '</div>';
							} else if (isset($info->voted[$mealStep->recipeNr]) && $info->voted[$mealStep->recipeNr]===-1) {
								echo '<div class="nextStep">';
								echo CHtml::link($this->trans->COOKASISSTANT_CHANGE_RECIPE, array('recipes/update', 'id'=>$course->couToRecs[$mealStep->recipeNr]->recipe->REC_ID), array('class'=>'button changeRecipe'));
								echo '</div>';
							} else {
								echo '<div class="nextStep"><span></span></div>';
							}
						} else {
							echo '<div class="nextStep"><span></span></div>';
						}
					}
					echo '<span class="clearfix"></span>';
				echo '</div>';
			echo '</div>';
			++$i;
		}
	?>
	</div>
</div>