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

if(isset($_GET['ajaxform'])){
	Yii::app()->clientScript->registerScript('iFrame_jQuery', "var jQuery = jQuery || window.parent.jQuery;", CClientScript::POS_BEGIN);
}
$request_baseurl = Yii::app()->request->baseUrl;
Yii::app()->clientscript->registerCssFile($request_baseurl . '/css/recipe_creator.css');
$emptySteps = new Steps();
$emptySteps->unsetAttributes();

$coi_ids = array();
foreach ($model->recToCois as $recToCoi){
	if (isset($recToCoi->COI_ID) && $recToCoi->COI_ID > 0){
		$coi_ids[] = $recToCoi->COI_ID;
	}
}
$cookin = '#cookin#';
?>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('recipes/uploadImage',array('id'=>$model->REC_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<input type="hidden" id="stepDetailsLink" value="<?php echo $this->createUrl('recipes/getRecipeInfos'); ?>"/>
<input type="hidden" id="updateSessionValuesLink" value="<?php echo $this->createUrl('recipes/updateSessionValues'); ?>"/>
<input type="hidden" id="updateSessionValueLink" value="<?php echo $this->createUrl('recipes/updateSessionValue'); ?>"/>
<input type="hidden" id="ingredientsChooseLink" value="<?php echo $this->createUrl('ingredients/chooseIngredient'); ?>"/>
<input type="hidden" id="preparedIngredientsChooseLink" value="<?php echo $this->createUrl('ingredients/chooseIngredientInRecipe'); ?>"/>
<input type="hidden" id="actionSuggestionLink" value="<?php echo $this->createUrl('recipes/actionSuggestion'); ?>"/>
<input type="hidden" id="cusineSubTypeLink" value="<?php echo $this->createUrl('recipes/getCusineSubTypes'); ?>"/>
<input type="hidden" id="cusineSubSubTypeLink" value="<?php echo $this->createUrl('recipes/getCusineSubSubTypes'); ?>"/>
<input type="hidden" id="preparedAIN_ID" value="<?php echo Yii::app()->params['PrepareActionId']; ?>"/>
<input type="hidden" id="emptyStepsJSON" value="<?php echo CHtml::encode(CJSON::encode($emptySteps)) ?>"/>
<input type="hidden" id="fieldToCssJSON" value="<?php echo CHtml::encode(CJSON::encode(Steps::getFieldToCssClass())) ?>"/>
<div class="form">

<div class="hidden" id="stepConfig">
<?php
	echo CHtml::hiddenField('rowsJSON', $stepsJSON);
	echo CHtml::hiddenField('ingredientsJSON', CJSON::encode($ingredients));
	echo CHtml::hiddenField('errorJSON', CJSON::encode($this->errorFields));	
?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array(/*'enctype' => 'multipart/form-data', */'class'=>'ajaxupload' . ((count($coi_ids)>0)?' useScreenHeight':'')),
)); ?>
	<?php if (count($coi_ids)>0){ ?>
	<h1 class="name"><?php echo CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])); ?></h1>
	
	<div class="recipeDetailsTitle"><?php echo $this->trans->RECIPES_SHOW_DETAILS; ?> <span class="subText"><?php echo $this->trans->RECIPES_SHOW_DETAILS2; ?></span></div>
	<?php } ?>
	<?php
		echo $form->errorSummary($model);
		if ($this->errorText != ''){
			if (strpos($this->errorText, '<li>')){
				echo '<div class="errorSummary"><p>'.$this->trans->RECIPES_FIX_STEPS.'</p><ul>';
				echo $this->errorText;
				echo '</ul></div>';
			} else {
				echo '<div class="errorSummary">';
				echo $this->errorText;
				echo '</div>';
			}
		}
	?>
	<div class="recipeDetails"<?php if (count($coi_ids)>0){ echo 'style="display: none;"';} ?>>
		<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>
		
		<div class="row">
			<label for="Recipes_Template"><?php echo $this->trans->RECIPE_SELECT_TEMPLATE; ?></label>
			<?php echo CHtml::link($this->trans->GENERAL_CHOOSE, array('recipes/chooseTemplateRecipe'), array('class'=>'fancyChoose RecipeTemplateSelect buttonSmall', 'id'=>'Recipes_Template')) ?>
		</div>
		
		<?php foreach($this->allLanguages as $lang=>$name){ ?>
		<div class="row">
			<?php echo $form->labelEx($model,'REC_NAME_'.strtoupper($lang)); ?>
			<?php echo $form->textField($model,'REC_NAME_'.strtoupper($lang),array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'REC_NAME_'.strtoupper($lang)); ?>
		</div>
		<?php } ?>
		
		<?php /* ?>
		<?php foreach($this->allLanguages as $lang=>$name){ ?>
		<div class="row">
			<?php echo $form->labelEx($model,'REC_SYNONYM_'.strtoupper($lang)); ?>
			<?php echo $form->textField($model,'REC_SYNONYM_'.strtoupper($lang),array('size'=>60,'maxlength'=>200)); ?>
			<?php echo $form->error($model,'REC_SYNONYM_'.strtoupper($lang)); ?>
		</div>
		<?php } ?>
		<?php */ ?>
		
		<?php
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		echo Functions::createInput(null, $model, 'RET_ID', $recipeTypes, Functions::DROP_DOWN_LIST, 'recipeTypes', $htmlOptions_type0, $form);
		?>
		
		<?php
			if (isset(Yii::app()->session['Recipes_Backup']) && isset(Yii::app()->session['Recipes_Backup']->REC_IMG_ETAG)){
				echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), $model->__get('REC_NAME_' . Yii::app()->session['lang']), array('class'=>'recipe' .(($model->imagechanged)?' cropable':''), 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang'])));
			} else if ($model->REC_ID && isset($model->REC_IMG_ETAG)) {
				echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), $model->__get('REC_NAME_' . Yii::app()->session['lang']), array('class'=>'recipe', 'title'=>$model->__get('REC_NAME_' . Yii::app()->session['lang'])));
			}
		?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'REC_SERVING_COUNT'); ?>
			<?php echo Functions::activeSpecialField($model, 'REC_SERVING_COUNT', 'number', array('min'=>1, 'step'=>1)); ?>
			<?php echo $form->error($model,'REC_SERVING_COUNT'); ?>
		</div>
	
	<?php /*
		<div class="row">
			<?php echo $form->labelEx($model,'REC_WIKI_LINK'); ?>
			<?php echo $form->textField($model,'REC_WIKI_LINK',array('size'=>60,'maxlength'=>200)); ?>
			<?php echo $form->error($model,'REC_WIKI_LINK'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'REC_IS_PRIVATE'); ?>
			<?php echo $form->textField($model,'REC_IS_PRIVATE',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'REC_IS_PRIVATE'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'REC_COMPLEXITY'); ?>
			<?php echo Functions::activeSpecialField($model, 'REC_COMPLEXITY', 'number', array('min'=>1, 'step'=>1)); ?>
			<?php echo $form->error($model,'REC_COMPLEXITY'); ?>
		</div>
	*/ ?>
		
		<?php
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		echo Functions::createInput(null, $model, 'DIF_ID', $difficulty, Functions::DROP_DOWN_LIST, 'difficulty', $htmlOptions_type0, $form);
		?>
		
		<?php
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		echo '<div class="row" id="cusineTypes">';
		$fieldName = 'CUT_ID';
		echo Functions::activeLabelEx($model, $fieldName, array('label'=>null)) . ' ';
		echo Functions::dropDownList(Functions::resolveName($model,$fieldName), $model->__get($fieldName), $cusineTypes, $htmlOptions_type0);
		echo $form->error($model, $fieldName);
		$subTypeShown = false;
		if ($model->__isset($fieldName) && $model->__get($fieldName) != '' && count($cusineSubTypes)>0){
			$htmlOptions_type0['style'] = 'display: inline;';
			$subTypeShown = true;
		}
		$fieldName = 'CST_ID';
		echo Functions::dropDownList(Functions::resolveName($model,$fieldName), $model->__get($fieldName), $cusineSubTypes, $htmlOptions_type0);
		echo $form->error($model, $fieldName);
		if ($subTypeShown && $model->__isset($fieldName) && $model->__get($fieldName) != '' && count($cusineSubSubTypes)>0){
			$htmlOptions_type0['style'] = 'display: inline;';
		} else {
			unset($htmlOptions_type0['style']);
		}
		$fieldName = 'CSS_ID';
		echo Functions::dropDownList(Functions::resolveName($model,$fieldName), $model->__get($fieldName), $cusineSubSubTypes, $htmlOptions_type0);
		echo $form->error($model, $fieldName);
		echo '</div>';
		?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'TAG_ID', array('for'=>'s2id_autogen1')); ?>
			<?php
			//echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query'));
			echo CHtml::hiddenField('tags', $tags, array('id'=>'recipeTags'));
			
			$this->widget('ext.select2.ESelect2', array(
				'target' => '#recipeTags',
				'config' => array (
					'multiple' => true,
					'minimumInputLength' => 1,
					'formatInputTooShort' => null,
					'openOnEnter' => false,
					'ajax' => 'js:glob.select2.searchTagAjax',
					'initSelection' =>'js:glob.select2.searchTagInitSelection',
					'formatResult' => 'js:glob.select2.searchTagFormatResult',
					'formatSelection' => 'js:glob.select2.searchTagFormatSelection',
					//'dropdownCssClass' => 'search_query',
					'containerCssClass' => 'search_query',
					'escapeMarkup' => 'js:function (m) { return m; }', // we do not want to escape markup since we are displaying html in results
					'createSearchChoice' => 'js:glob.select2.createTagChoice',
				)
			));
			?>
			<?php echo $form->error($model,'REC_COMPLEXITY'); ?>
		</div>
		
		<?php /*
		<div class="row">
			<?php echo $form->labelEx($model,'REC_CUSINE_GPS_LAT'); ?>
			<?php echo $form->textField($model,'REC_CUSINE_GPS_LAT'); ?>
			<?php echo $form->error($model,'REC_CUSINE_GPS_LAT'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'REC_CUSINE_GPS_LNG'); ?>
			<?php echo $form->textField($model,'REC_CUSINE_GPS_LNG'); ?>
			<?php echo $form->error($model,'REC_CUSINE_GPS_LNG'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'REC_TOOLS'); ?>
			<?php echo $form->textField($model,'REC_TOOLS',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'REC_TOOLS'); ?>
		</div>
		*/ ?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'filename'); ?>
			<div class="imageTip">
			<?php
			echo $this->trans->TIP_OWN_IMAGE . '<br>';
			/*
			echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
			printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('REC_NAME_EN_GB')); //.Yii::app()->session['lang']
			echo '<br>';
			*/
			echo $form->FileField($model,'filename');
			?>
			</div>
			<?php echo $form->error($model,'filename'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'REC_IMG_AUTH'); ?>
			<?php echo $form->textField($model,'REC_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
			<?php echo $form->error($model,'REC_IMG_AUTH'); ?>
		</div>
		
		<div class="row" id="cookIns">
			<?php
			$htmlOptions_type2 = array('empty'=>$this->trans->GENERAL_CHOOSE, 'size'=>8, 'multiple'=>true);
			//echo Functions::createInput(null, $model->recToCois, 'COI_ID', $cookIns, Functions::MULTI_LIST, 'cookIns', $htmlOptions_type2, $form);
			
			echo CHtml::label($this->trans->RECIPE_COOKINS . '<span class="required">*</span>','COI_ID', array('class'=>'required')) . "\r\n";
			//echo CHtml::listBox('COI_ID', $coi_ids, $cookIns, $htmlOptions_type2) . "\r\n";
			
			echo '<ul class="options_choose">';
			echo CHtml::checkBoxList('COI_ID', $coi_ids, $cookIns, $htmlOptions_type2); 
			echo '</ul>';
			echo '<div class="clearfix"></div>';
			//echo $form->error($model,'REC_IMG_AUTH') . "\r\n";
			?>
		</div>
		<div class="buttons">
			<?php echo CHtml::submitButton((count($coi_ids)>0)?$this->trans->RECIPES_UPDATE:$this->trans->RECIPES_START_CREATE, array('class'=>'button', 'name'=>'updateCookIn')); ?>
		</div>
	</div>
	<?php
	if (count($coi_ids)>0){
	?>
	<div id="recipeCreator">
		<div class="leftBar">
		<div class="leftBarTable">
			<div class="ingredientList">
			<?php 
			echo '<div class="clearfix"></div>';
			$ing_index = 0;
			foreach($ingredientDetails as $ingredient){
				$ingName = $ingredient['ING_NAME_' . Yii::app()->session['lang']];
				echo '<div class="ingredientEntry" id="ing' . $ingredient['ING_ID'] . '">';
					echo '<div class="small_img">';
						echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$ingredient['ING_ID'], 'ext'=>'.png')), $ingName, array('class'=>'ingredient', 'title'=>$ingName));
						echo '<div class="img_auth" title="' . (($ingredient['ING_IMG_ETAG'] == '')?'':'© by ' . $ingredient['ING_IMG_AUTH']) . '">';
							if ($ingredient['ING_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $ingredient['ING_IMG_AUTH']; }
						echo '</div>';
					echo '</div>';
					echo '<div class="name" title="' . $ingName . '">' . $ingName . '</div>';
					echo '<div class="amount">' . $ingredientAmount[$ingredient['ING_ID']] . ($ingredientAmount[$ingredient['ING_ID']]==''?'':'g'). '</div>';
					echo CHtml::link($this->trans->INGREDIENTS_MORE_DETAILS, array('ingredients/view', 'id'=>$ingredient['ING_ID']), array('class'=>'moreInfo fancyLink'));
					echo CHtml::hiddenField('ingredients['.$ing_index.'][ING_ID]', $ingredient['ING_ID']);
					echo CHtml::hiddenField('ingredients['.$ing_index.'][AMOUNT]', $ingredientAmount[$ingredient['ING_ID']]);
				echo '</div>';
				$ing_index++;
			}
			
			$emptyIngredient  = '<div class="ingredientEntry" id="ingNew">';
			$emptyIngredient .= '<div class="small_img">';
				$emptyIngredient .= CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>'newIng', 'ext'=>'.png')), 'new', array('class'=>'ingredient', 'title'=>'new'));
				$emptyIngredient .= '<div class="img_auth" title="' . '">';
					$emptyIngredient .= '&nbsp;';
				$emptyIngredient .= '</div>';
			$emptyIngredient .= '</div>';
			$emptyIngredient .= '<div class="name" title="">' . '</div>';
			$emptyIngredient .= '<div class="amount">'.'</div>';
			$emptyIngredient .= CHtml::hiddenField('ingredients['.'newIndex'.'][ING_ID]', 'new');
			$emptyIngredient .= CHtml::hiddenField('ingredients['.'newIndex'.'][AMOUNT]', '');
			$emptyIngredient .= '</div>';
			
			echo '<div class="ingredientEntry newEntry">';
				echo '<div class="small_img">';
					echo CHtml::link('&nbsp;', array('ingredients/chooseIngredient'), array('class'=>'addIngredient fancyChoose IngredientSelect', 'title'=>$this->trans->RECIPES_ADD_INGREDIENT));
					echo CHtml::hiddenField('newIngredient', '', array('class'=>'fancyValue', 'disabled'=>'disabled'));
					echo '<div class="img_auth">';
					echo '</div>';
				echo '</div>';
				echo '<div class="add">' . $this->trans->RECIPES_ADD_INGREDIENT . '</div>';
				//echo '<div id="newIngredientMarkup" style="display:none">' . $emptyIngredient . '</div>';
				echo '<script id="newIngredientMarkup" type="text/template">';
				//echo '/*<![CDATA[*/';
				echo $emptyIngredient;
				//echo '/*]]>*/';
				echo '</script>';
			echo '</div>';
			echo '<div class="clearfix"></div>';
			?>
			</div>
			<div class="steps updateBackend">
				<?php
				echo '<div id="actionsInDetails" style="display:none;">';
				foreach ($actionsInDetails as $details){
					echo $details['desc'];
				}
				echo '</div>';
				?>
				<div class="row">
				<?php
					echo CHtml::label($this->trans->RECIPE_COOKIN_DISPLAY,'cookInDisplay') . "\r\n";
					$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
					$first = 0;
					foreach ($cookInsSelected as $key=>$val){
						if ($first == 0){
							$first = $key;
							$cookin = $val;
							break;
						}
					}
					echo CHtml::dropDownList('cookInDisplay', $first, $cookInsSelected, $htmlOptions_type0) . "\r\n";
				?>
				</div>
				<?php
				$fieldOptions = array(
					array('REC_ID', null, null, array('hidden'=>true)),
					array('STE_STEP_NO', null, null, array('hidden'=>true)),
					array('AIN_ID', $this->trans->RECIPES_ACTION, $actionsIn, array('empty'=>$this->trans->GENERAL_CHOOSE)),
					array('TOO_ID', $this->trans->RECIPES_TOOL, $tools, array('empty'=>$this->trans->GENERAL_CHOOSE)),
					//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('empty'=>$this->trans->GENERAL_CHOOSE)),
					//array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>'#'.$this->createUrlHash('ingredients/chooseIngredient',array()), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect'))),
					array('ING_ID', $this->trans->RECIPES_INGREDIENT, $ingredients, array('fancy'=>true, 'empty'=>$this->trans->GENERAL_CHOOSE, 'url'=>array('ingredients/chooseIngredient'), 'htmlOptions'=>array('class'=>'fancyChoose IngredientSelect buttonSmall'))),
					array('STE_GRAMS', $this->trans->RECIPES_INGREDIENT_AMOUNT, null, array('type_weight'=>'g')),
					array('STE_CELSIUS', $this->trans->RECIPES_CELSIUS, null, array('slider'=>array('min'=>0, 'max'=>200, 'step'=>5))),
					array('STE_KPA', $this->trans->RECIPES_KPA, null, array('slider'=>array('min'=>0, 'max'=>80, 'step'=>40))),
					///array('STE_RPM', $this->trans->RECIPES_RPM, null, array('field_type'=>'number', 'min'=>0, 'max'=>200)),
					array('STE_RPM', $this->trans->RECIPES_RPM, null, array('slider'=>array('min'=>0, 'max'=>200, 'step'=>5))),
					array('STE_CLOCKWISE', $this->trans->RECIPES_CLOCKWISE, null, array('boolean'=>'check')),
					array('STE_STIR_RUN', $this->trans->RECIPES_STIR_RUN, null, array('type_time'=>'m')),
					array('STE_STIR_PAUSE', $this->trans->RECIPES_STIR_PAUSE, null, array('type_time'=>'m')),
					array('STE_STEP_DURATION', $this->trans->RECIPES_STEP_DURATION, null, array('type_time'=>'m')),
					
				);
				$text = array('add'=>$this->trans->GENERAL_ADD, 'remove'=>$this->trans->GENERAL_REMOVE, 'move up'=>'-up-', 'move down'=>'-down-', 'add2'=>$this->trans->GENERAL_ADD, 'options'=>'Options');
				
				$options = array('new'=>new Steps);
				//echo Functions::createInputTable($model->steps, $fieldOptions, $options, $form, $text);
				//echo Functions::createInputTable(array(), $fieldOptions, $options, $form, $text);
				
				echo '<div id="stepList">';
				echo '<div class="items">';
				$i = 1;
				foreach($model->steps as $step){
					//echo '<div class="addstep"></div>';
					echo '<div class="step">';
					echo '<span class="stepNo">' . $i . '</span> ';
					//echo '<span class="actionText">' . $step->getAsHTMLString($cookin) . '</span>';
					//after save/update and there is a save error actionIn object have stil old value -> incorrect text will be shown
					$values = array();
					if(isset($step->ING_ID) && $step->ING_ID != 0){
						$values['ING_NAME'] =  $ingredients[$step->ING_ID];
					}
					//$values['TOO_DESC'] = '?';
					echo '<span class="actionText">' . Steps::getHTMLString($step, $actionsIn[$step['AIN_ID']], $cookin, $values) . '</span>';
					echo CHtml::hiddenField(Functions::resolveArrayName($step,'json',$i),CJSON::encode($step), array('class'=>'json'));
					echo ' <span class="remove">' . $this->trans->GENERAL_REMOVE . '</span>';
					echo '</div>';
					$i++;
				}
				/*
				if ($i == 1){ // if no step added, add a placeholder step
					echo '<div class="step placeholderStep"></div>';
				}
				*/
				echo '</div>';
				echo '<div class="addStepText">' . $this->trans->RECIPES_DRAG_HERE . '</div>';
				echo '</div>';
				//echo '<div class="addstep last"></div>';
				echo '<div class="recycleBin" title="' . $this->trans->GENERAL_REMOVE . '"></div>';
				?>
			</div>
			<div class="actionButtons">
				<div class="buttons">
					<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('class'=>'button')); ?>
					<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
				</div>
			</div>
			</div>
		</div>
		
		<div class="rightBar">
			<div class="rightBarTable">
			<div class="actions">
				<div id="actionList">
					<div class="title"><?php echo $this->trans->RECIPES_ACTIONS_TITLE; ?></div>
					<div class="hint"><?php echo $this->trans->RECIPES_ACTIONS_HINT_ON_ADD_INGREDIENT; ?></div>
					<div class="actionListType selected"><?php echo $this->trans->RECIPES_ACTIONS_SUGGESTION; ?></div>
					<div class="actionListType"><?php echo $this->trans->RECIPES_ACTIONS_MY_LIST; ?></div>
					<div class="actionListType"><?php echo $this->trans->RECIPES_ACTIONS_MOST_USED; ?></div>
					<div class="actionListList selected">
					<?php //Suggestion
						$suggestions = $this->getActionSuggestion(0); 
						$suggestions = $suggestions['suggestions'];
						foreach($suggestions as $value){
							$ain_id = $value['AIN_ID'];
							if (isset($actionsIn[$ain_id])){
								$text = $actionsIn[$ain_id];
								echo '<div class="action" data-id="' . $ain_id . '" data-json="' . CHtml::encode(CJSON::encode($value)) . '">' . $text . '</div>';
							}
						}
					?>
					</div>
					<div class="actionListList">
					<?php //My list
						foreach($actionsIn as $key=>$value){
							echo '<div class="action" data-id="' . $key . '">' . $value . '</div>';
						}
					?>
					</div>
					<div class="actionListList">
					<?php //Most used
						foreach($this->getMostCommon() as $value){
							$ain_id = $value['AIN_ID'];
							if (isset($actionsIn[$ain_id])){
								$text = $actionsIn[$ain_id];
								if (isset($value['STE_STEP_DURATION'])){
									$time = date('H:i:s', $value['STE_STEP_DURATION']-3600);
									$text = str_replace('#time', $time, $text);
								}
								if (isset($value['STE_CELSIUS'])){
									$replText = $value['STE_CELSIUS'] . '°C';
									$text = str_replace('#temp', $replText, $text);
								}
								if (isset($value['STE_KPA'])){
									$replText = $value['STE_KPA'] . 'kpa';
									$text = str_replace('#press', $replText, $text);
								}
								echo '<div class="action" data-id="' . $ain_id . '" data-json="' . CHtml::encode(CJSON::encode($value)) . '">' . $text . '</div>';
							}
						}
					?>
					</div>
					<?php
						echo Functions::dropDownList('actionSelect', '', $actionsIn, array('empty'=>$this->trans->GENERAL_CHOOSE));
						echo '<div id="addAction" class="button f-right">Add</div>';
					?>
				</div>
			</div>
			<div id="propertyList">
				<div class="title"><?php echo $this->trans->RECIPES_PARAMETERS_TITLE; ?></div>
				<?php
				$emptyValue = new Steps();
				//$emptyObject->unsetAttributes(); // clear any default values
				$emptyValue->attributes = array('AIN_ID'=>'',
					'TOO_ID'=>'',
					'ING_ID'=>'0',
					'STE_GRAMS'=>'0',
					'STE_CELSIUS'=>'0',
					'STE_KPA'=>'',
					'STE_RPM'=>'',
					'STE_CLOCKWISE'=>'',
					'STE_STIR_RUN'=>'',
					'STE_STIR_PAUSE'=>'',
					'STE_STEP_DURATION'=>'0',
				);
				echo Functions::createOptionList($fieldOptions, $emptyValue);
				?>
				<div class="button closeButton"><?php echo $this->trans->GLOBAL_CLOSE; ?></div>
			</div>
			</div>
		</div>
	</div>
	<?php } ?>

<?php $this->endWidget(); ?>
</div><!-- form -->
