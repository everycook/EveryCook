

<div class="cookAssistant">
	<div class="meta">
		<div class=""><?php printf('Course %d<br/>%d Recipes', $info->courseNr+1, count($info->steps)); ?></div>
		<div class="finishTime">Finished at:<br/><span><?php echo $info->finishedIn ?></span></div>
		<input type="hidden" name="finishTime" id="finishTime" value="<?php echo $info->finishedIn ?>"/>
		<input type="hidden" name="timeDiff" id="timeDiff" value="<?php echo $info->timeDiffMax ?>"/>
		<input type="hidden" name="started" id="started" value="<?php echo $info->started ?>"/>
		<?php
			if (!$info->started){ ?>
				<?php echo CHtml::link('<div>'.'start'.'</div>', array('index'), array('class'=>'f-right')); ?>
			<?php } else { ?>
				<?php echo CHtml::link('<div>'.'continue'.'</div>', array('index'), array('class'=>'f-right')); ?>
			<?php }
		?>
		<span class="clearfix"></span>
	</div>
	<div class="recipeConfigs">
	<?php
		$i=0;
		echo '<form id="cookassistant-form" method="post" action="' . $this->createUrl('overview') . '">';
		foreach($info->steps as $mealStep){
			$cookWith = (count($info->cookWith[$i])>1 && $info->cookWith[$i][0]!=CookAssistantController::COOK_WITH_OTHER)?1:0;
			echo '<div class="recipeConfig">';
				echo '<div class="stepHeader">';
					echo '<div class="title"><div>' . $mealStep->recipeName . '</div></div>';
					
					echo '<div class="totalTime"><div>' . 'Total Time:' . ' <span>'  . $info->totalTime[$i] .  '</span></div></div>';
					if ($mealStep->stepNr != -1 && !$mealStep->endReached){
						echo '<div class="finishTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . (($mealStep->stepNr != -1)?'Finished at:':'Start at:') . $mealStep->finishedIn . ' <span>'  .  '</span></div></div>';
					}
					if ($mealStep->endReached){
						echo '<div class="endReached"><div>' . 'Recipe end Reached.'  . '</div></div>';
					}
					echo '<span class="clearfix"></span>';
				echo '</div>';
				
				
				if (!$info->started){
					echo '<div class="stepHeader">';
						$recipe = $info->course->couToRecs[$mealStep->recipeNr]->recipe;
						foreach($recipe->recToCois as $recToCoi){
							echo '<div>';
							echo CHtml::label($this->trans->__GET('COOKASISSTANT_COOK_WITH_'.$recToCoi->COI_ID),'cookwith_'.$i.'_local',array());
							$isSelected = count($info->cookWith[$i])>1 && $info->cookWith[$i][1] == $recToCoi->COI_ID;
							echo CHtml::radioButton('cookwith['.$i.']', $isSelected, array('value'=>$recToCoi->COI_ID, 'id'=>'cookwith_'.$i.'_'.$recToCoi->COI_ID));
							echo '</div>';
							if ($recToCoi->COI_ID == CookAssistantController::COOK_WITH_EVERYCOOK_COI){
								if (CookAssistantController::DEVICE_PATH != ''){
									echo '<div>';
									echo CHtml::label($this->trans->COOKASISSTANT_COOK_WITH_REMOTE_MACHINE,'cookwith_'.$i.'_remote',array());
									echo CHtml::radioButton('cookwith['.$i.']', count($info->cookWith[$i])>1 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP, array('value'=>'remote', 'id'=>'cookwith_'.$i.'_remote'));
									echo CHtml::textField('remoteip['.$i.']', (count($info->cookWith[$i])>1 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP)?$info->cookWith[$i][1]:'10.0.0.1', array());
									echo '</div>';
								}
							}
						}
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWith) . '"/>';
				} else {
					echo '<div class="stepHeader">';
						if ($info->cookWith[$i][0]==CookAssistantController::COOK_WITH_LOCAL){
							echo '<div>' . 'cook with this everycook' .  '</div>';
						} else if ($info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP){
							echo '<div>' . sprintf('cook with remote everycook at ip: %s', $info->cookWith[$i][1]) .  '</div>';
						} else if ($info->cookWith[$i][0]==CookAssistantController::COOK_WITH_OTHER){
							echo '<div>' . 'cook with pan' .  '</div>';
						}
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWith) . '"/>';
				
					//if ($mealStep->stepNr != -1 ){
						echo '<div class="actionText">' . 'Current step: ' . $mealStep->actionText . '</div>';
					//}
				}
			echo '</div>';
			++$i;
		}
		if (!$info->started){
			echo '<div class="buttons">';
				echo CHtml::submitButton('change CookWith settings');
			echo '</div>';
		}
		echo "</form>"
	?>
	</div>
</div>
