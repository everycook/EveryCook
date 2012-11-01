

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
			$cookWithEveryCook = ($info->cookWithEveryCook[$i][0]!=CookAssistantController::COOK_WITH_PAN)?1:0;
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
						if (CookAssistantController::DEVICE_PATH != ''){
							echo '<div>';
							echo CHtml::label('cook with machine','cookwith_'.$i.'_local',array());
							echo CHtml::radioButton('cookwith['.$i.']', $info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_LOCAL, array('value'=>'local', 'id'=>'cookwith_'.$i.'_local'));
							echo '</div>';
						}
						echo '<div>';
						echo CHtml::label('cook with remote machine','cookwith_'.$i.'_remote',array());
						echo CHtml::radioButton('cookwith['.$i.']', $info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_IP, array('value'=>'remote', 'id'=>'cookwith_'.$i.'_remote'));
						echo CHtml::textField('remoteip['.$i.']', ($info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_IP)?$info->cookWithEveryCook[$i][1]:'10.0.0.1', array());
						echo '</div>';
						echo '<div>';
						echo CHtml::label('cook with normal pan','cookwith_'.$i.'_pan',array());
						echo CHtml::radioButton('cookwith['.$i.']', $info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_PAN, array('value'=>'pan', 'id'=>'cookwith_'.$i.'_pan'));
						echo '</div>';
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWithEveryCook) . '"/>';
				} else {
					echo '<div class="stepHeader">';
						if ($info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_LOCAL){
							echo '<div>' . 'cook with this everycook' .  '</div>';
						} else if ($info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_IP){
							echo '<div>' . sprintf('cook with remote everycook at ip: %s', $info->cookWithEveryCook[$i][1]) .  '</div>';
						} else if ($info->cookWithEveryCook[$i][0]==CookAssistantController::COOK_WITH_PAN){
							echo '<div>' . 'cook with pan' .  '</div>';
						}
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWithEveryCook) . '"/>';
				
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
