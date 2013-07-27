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
		<?php if ($info->finishedIn > 0){ ?>
			<div class="finishTime"><?php echo $this->trans->COOKASISSTANT_FINISHED_AT; ?><br/><span><?php echo date('H:i:s', time() + $info->finishedIn); ?></span></div>
		<?php } ?>
		<input type="hidden" name="finishTime" id="finishTime" value="<?php echo $info->finishedIn ?>"/>
		<input type="hidden" name="timeDiff" id="timeDiff" value="<?php echo $info->timeDiffMax ?>"/>
		<input type="hidden" name="started" id="started" value="<?php echo $info->started ?>"/>
		<?php
			if ($info->started){ 
				echo CHtml::link('<div>'.$this->trans->COOKASISSTANT_CONTINUE.'</div>', array('index'), array('class'=>'f-right'));
				if (!$info->allFinished){
					echo CHtml::link('<div>'.$this->trans->COOKASISSTANT_ABORT.'</div>', array('abort'), array('class'=>'f-right', 'id'=>'abortCooking'));
				}
			}
		?>
		<span class="clearfix"></span>
	</div>
	<div class="recipeConfigs">
	<?php
		$allCookWithSet = true;
		$i=0;
		echo '<form id="cookassistant-form" method="post" action="' . $this->createUrl('save') . '">';
		foreach($info->steps as $mealStep){
			if (count($info->cookWith[$i])>1){
				$cookWith = ($info->cookWith[$i][0]!=CookAssistantController::COOK_WITH_OTHER)?1:0;
			} else {
				$allCookWithSet = false;
				$cookWith = 0;
			}
			echo '<div class="recipeConfig">';
				echo '<div class="stepHeader">';
					echo '<div class="title"><div>' . $mealStep->recipeName . '</div></div>';
					if (count($info->cookWith[$i])>1){
						echo '<div class="totalTime"><div>' . $this->trans->COOKASISSTANT_TOTAL_TIME . ' <span>'  . date('H:i:s', $info->totalTime[$i]-3600) . '</span></div></div>';
						echo '<div class="prepareTime"><div>' . $this->trans->COOKASISSTANT_PREPARE_TIME . ' <span>'  . date('H:i:s', $info->prepareTime[$i]-3600) . '</span></div></div>';
						echo '<div class="cookTime"><div>' . $this->trans->COOKASISSTANT_COOK_TIME . ' <span>'  . date('H:i:s', $info->cookTime[$i]-3600) . '</span></div></div>';
						if ($mealStep->stepNr != -1 && !$mealStep->endReached){
							echo '<div class="finishTime' . (($mealStep->inTime)?'':' toLate') . '"><div>' . (($mealStep->stepNr != -1)?$this->trans->COOKASISSTANT_FINISHED_AT:$this->trans->COOKASISSTANT_START_AT) . ' <span>' . date('H:i:s', time() + $mealStep->finishedIn) . '</span></div></div>';
						}
						if ($mealStep->endReached){
							echo '<div class="endReached"><div>' . $this->trans->COOKASISSTANT_RECIPE_END_REACED  . '</div></div>';
						}
					} else {
						echo '<div class="selectCookIn">' . $this->trans->COOKASISSTANT_SELECT_COOK_IN_FOR_TIME . '</div>';
					}
					echo '<span class="clearfix"></span>';
				echo '</div>';
				
				
				if (!$info->started){
					echo '<div class="stepHeader">';
						$course = $info->meal->meaToCous[$info->courseNr]->course;
						$recipe = $course->couToRecs[$mealStep->recipeNr]->recipe;
						$everycookAvailable = false;
						$optionShown = false;
						foreach($recipe->recToCois as $recToCoi){
							if ($recToCoi->COI_ID == CookAssistantController::COOK_WITH_EVERYCOOK_COI){
								$everycookAvailable = true;
								$activ = isset(Yii::app()->params['isDevice']) && Yii::app()->params['isDevice'] === true;
								//if (isset(Yii::app()->params['isDevice']) && Yii::app()->params['isDevice'] === true){
									echo '<div>';
									echo CHtml::label($this->trans->__GET('COOKASISSTANT_COOK_WITH_'.$recToCoi->COI_ID),'cookwith_'.$i.'_local',array());
									$isSelected = count($info->cookWith[$i])>1 && $info->cookWith[$i][1] == $recToCoi->COI_ID;
									if ($activ){
										$optionShown = true;
										echo CHtml::radioButton('cookwith['.$i.']', $isSelected, array('value'=>$recToCoi->COI_ID, 'id'=>'cookwith_'.$i.'_'.$recToCoi->COI_ID));
									} else {
										echo CHtml::radioButton('cookwith['.$i.']', $isSelected, array('value'=>$recToCoi->COI_ID, 'id'=>'cookwith_'.$i.'_'.$recToCoi->COI_ID, 'disabled'=>'disabled'));
									}
									echo '</div>';
								//}
								/*
								$activ = isset(Yii::app()->params['localNetwork']) && Yii::app()->params['localNetwork'] === true;
								//if (isset(Yii::app()->params['localNetwork']) && Yii::app()->params['localNetwork'] === true){
									echo '<div>';
									echo CHtml::label($this->trans->COOKASISSTANT_COOK_WITH_REMOTE_MACHINE,'cookwith_'.$i.'_remote',array());
									
									if ($activ){
										$optionShown = true;
										echo CHtml::radioButton('cookwith['.$i.']', count($info->cookWith[$i])>1 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP, array('value'=>'remote', 'id'=>'cookwith_'.$i.'_remote'));
										echo CHtml::textField('remoteip['.$i.']', (count($info->cookWith[$i])>2 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP)?$info->cookWith[$i][2]:CookAssistantController::COOK_WITH_IP_DEFAULT, array());
									} else {
										echo CHtml::radioButton('cookwith['.$i.']', count($info->cookWith[$i])>1 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP, array('value'=>'remote', 'id'=>'cookwith_'.$i.'_remote', 'disabled'=>'disabled'));
										echo CHtml::textField('remoteip['.$i.']', (count($info->cookWith[$i])>2 && $info->cookWith[$i][0]==CookAssistantController::COOK_WITH_IP)?$info->cookWith[$i][2]:CookAssistantController::COOK_WITH_IP_DEFAULT, array('disabled'=>'disabled'));
									}
									echo '</div>';
								//}
								*/
							} else {
								echo '<div>';
								echo CHtml::label($this->trans->__GET('COOKASISSTANT_COOK_WITH_'.$recToCoi->COI_ID),'cookwith_'.$i.'_local',array());
								$isSelected = count($info->cookWith[$i])>1 && $info->cookWith[$i][1] == $recToCoi->COI_ID;
								echo CHtml::radioButton('cookwith['.$i.']', $isSelected, array('value'=>$recToCoi->COI_ID, 'id'=>'cookwith_'.$i.'_'.$recToCoi->COI_ID));
								echo '</div>';
								$optionShown = true;
							}
						}
						if ($optionShown === false){
							echo '<div>';
							if ($everycookAvailable === true){
								echo $this->trans->COOKASISSTANT_COOK_WITH_NONE_NOT_DEVICE;
							} else {
								echo $this->trans->COOKASISSTANT_COOK_WITH_NONE;
							}
							echo '</div>';
						} else if ($everycookAvailable === true){
							if (!isset(Yii::app()->params['isDevice']) || Yii::app()->params['isDevice'] === false){
								echo '<div>';
								echo $this->trans->COOKASISSTANT_COOK_WITH_NOT_DEVICE;
								echo '</div>';
							/*} else if (!isset(Yii::app()->params['localNetwork']) || Yii::app()->params['localNetwork'] === false){
								echo '<div>';
								echo $this->trans->COOKASISSTANT_COOK_WITH_NOT_DEVICE;
								echo '</div>';*/
							}
						}
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWith) . '"/>';
				} else {
					echo '<div class="stepHeader">';
						if ($info->cookWith[$i][0] != CookAssistantController::COOK_WITH_IP){
							echo '<div>' . $this->trans->__GET('COOKASISSTANT_COOK_WITH_'.$info->cookWith[$i][1]) . '</div>';
						} else {
							echo '<div>' . sprintf($this->trans->__GET('COOKASISSTANT_COOK_WITH_'.$info->cookWith[$i][1].'_REMOTE'), $info->cookWith[$i][2]) .  '</div>';
						}
					echo '</div>';
					echo '<input type="hidden" name="withEveryCook" value="' . ($cookWith) . '"/>';
				
					//if ($mealStep->stepNr != -1 ){
						echo '<div class="actionText">' . $this->trans->COOKASISSTANT_OVERVIEW_CURRENT_STEP . ' ' . $mealStep->actionText . '</div>';
					//}
				}
			echo '</div>';
			++$i;
		}
		if (!$info->started){
			echo '<div class="buttons">';
				//echo CHtml::submitButton('change CookWith settings');
				echo '<div class="button submit">' . $this->trans->COOKASISSTANT_CHANGE_COOK_WITH . '</div>';
				if ($allCookWithSet){
					echo CHtml::link($this->trans->COOKASISSTANT_START, array('index'), array('class'=>'button'));
				}
			echo '</div>';
		}
		echo "</form>"
	?>
	</div>
</div>
<script type="text/javascript">
	jQuery('#metaNav').show();
</script>