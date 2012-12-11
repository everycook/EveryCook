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
			$cookWith = ($info->cookWith[$i][0]!=CookAssistantController::COOK_WITH_OTHER)?1:0;
			echo '<div class="recipeStep">';
				echo '<input type="hidden" name="cookWith" value="' . ($cookWith) . '"/>';
				echo '<div class="stepHeader">';
					echo '<div class="title"><div>' . $mealStep->recipeName . '</div></div>';
					echo '<div class="finishTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . (($mealStep->stepNr != -1)?$this->trans->COOKASISSTANT_FINISHED_AT:$this->trans->COOKASISSTANT_START_AT) . ' <span>'  .  '</span></div></div>'; // $mealStep->finishedAt .
					echo '<input type="hidden" name="finishTime" value="' . $mealStep->finishedIn . '"/>';
					echo '<input type="hidden" name="lowestFinishTime" value="' . $mealStep->lowestFinishedIn . '"/>';
					if ($cookWith!=0){
						echo '<div class="temp"><div><div>' . $this->trans->COOKASISSTANT_TEMPERATURE . ' <span class="temp">'.$mealStep->currentTemp.'</span>°C</div><div>' . $this->trans->COOKASISSTANT_PRESSURE . ' <span class="press">'.$mealStep->currentPress.'</span>pa</div></div></div>';
					}
					if (!$mealStep->endReached){
						echo '<div class="nextTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . $this->trans->COOKASISSTANT_NEXT_STEP_IN . ' <span>'  . '</span></div></div>'; // $mealStep->nextStepIn . 
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
						echo '<div class="nextStep"><span></span></div>';
					}
					echo '<span class="clearfix"></span>';
				echo '</div>';
			echo '</div>';
			++$i;
		}
	?>
	</div>
</div>