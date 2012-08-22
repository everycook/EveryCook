<div class="cookAssistant">
	<div class="meta">
		<div class=""><?php printf('Course %d<br/>%d Recipes', $info->courseNr, count($info->steps)); ?></div>
		<div class="finishTime">Finished in:<br/><span><?php echo $info->finishedIn ?></span></div>
		<input type="hidden" name="finishTime" value="<?php echo $info->finishedIn ?>"/>
		<div class="f-right">Overview</div>
		<span class="clearfix"></span>
	</div>
	<div class="recipeSteps">
	<?php
		foreach($info->steps as $step){
			echo '<div class="recipeStep">';
				echo '<div class="stepHeader">';
					echo '<div class="title"><div>' . $step->recipeName . '</div></div>';
					echo '<div class="finishTime' . (($step->inTime)?'':' toLate') . '"><div>' . 'Finished in: <span>'  . $step->finishedIn . '</span></div></div>';
					echo '<input type="hidden" name="finishTime" value="' . $step->finishedIn . '"/>';
					echo '<div class="nextTime"><div>' . 'Next Step in: <span>'  . $step->nextStepIn . '</span></div></div>';
					echo '<input type="hidden" name="nextTime" value="' . $step->nextStepIn . '"/>';
					echo '<span class="clearfix"></span>';
				echo '</div>';
				echo '<div class="stepInfo">';	
					echo '<div class="action" style="background-pos: -' . ($step->stepDuration - $step->nextStepIn) . 'px 0px">';
						echo '<div class="actionInner' . (($step->ingredientId != 0)?' withPic':'') . '">';
							if ($step->ingredientId != 0){
								echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$step->ingredientId, 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$step->ingredientCopyright, 'title'=>$step->ingredientCopyright));
							}
							echo '<div class="actionText">' . $step->actionText . '</div>';
							echo '<span class="clearfix"></span>';
						echo '</div>';
					echo '</div>';
					if ($step->mustWait){
						echo '<div class="nextStep"><div></div></div>';
					} else {
						echo CHtml::link('<div></div>', array('next', 'recipeNr'=>$step->recipeNr), array('class'=>'nextStep'));
					}
					echo '<span class="clearfix"></span>';
				echo '</div>';
			echo '</div>';
		}
	?>
	</div>
</div>
