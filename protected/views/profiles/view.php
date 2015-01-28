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

$this->breadcrumbs=array(
	'Profiles'=>array('index'),
	$model->PRF_UID,
);

$this->menu=array(
	array('label'=>'List Profiles', 'url'=>array('index')),
	array('label'=>'Create Profiles', 'url'=>array('create')),
	array('label'=>'Update Profiles', 'url'=>array('update', 'id'=>$model->PRF_UID)),
	array('label'=>'Delete Profiles', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PRF_UID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Profiles', 'url'=>array('admin')),
);
?>
<div class="profileView">
<?php
$editLink = '';
if ($edit){
	$editLink = ' <a href="#" class="editFieldLink actionlink">' . $this->trans->GENERAL_EDIT . '</a>';
	?>
	<input type="hidden" id="updateProfileLink" value="<?php echo $this->createUrl('profiles/updateProfile', array('id'=>$model->PRF_UID)); ?>"/>
	<?php echo $this->renderPartial('buttons', array('model'=>$model, 'professional'=>$professional)); ?>
	<div class="form">
	<?php
}
?>
<div class="shortInfo">
	<?php
		if ($edit && isset(Yii::app()->session['Profiles_Backup']) && isset(Yii::app()->session['Profiles_Backup']->PRF_IMG_ETAG)){
			echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), '', array('class'=>'profile' .(($model->imagechanged)?' cropable':'')));
		} else if ($model->PRF_UID) {
			echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$model->PRF_UID, 'ext'=>'.png')), '', array('class'=>'profile'));
		} else {
			echo CHtml::image(Yii::app()->request->baseUrl . '/pics/unknown.png', '', array('class'=>'profile'));
		}
	?>
	<h1><?php echo $model->PRF_FIRSTNAME . " " . $model->PRF_LASTNAME; ?></h1>
	<div class="workingTitle<?php if($edit){echo ' editField" data-field="PRF_WORK_TITLE" data-placeholder="' . $this->trans->PROFILES_VIEW_WORK_TITLE_PLACEHOLDER;} ?>"><?php echo $model->PRF_WORK_TITLE; echo $editLink;?></div>
	<div class="workingLocation<?php if($edit){echo ' editField" data-field="PRF_WORK_LOCATION" data-placeholder="' . $this->trans->PROFILES_VIEW_WORK_LOCATION_PLACEHOLDER;} ?>"><?php echo $model->PRF_WORK_LOCATION; echo $editLink;?></div>
	<div class="cusines<?php if($edit){echo ' editField" data-field="PRF_CUT_IDS" data-child-class="cusine" data-field-type="select2" data-placeholder="' . $this->trans->PROFILES_VIEW_CUSINES_PLACEHOLDER . '" data-save="' . $this->trans->GENERAL_SAVE . '" data-scriptprefix="selectCusines' ;} ?>">
	<?php foreach($cusines as $cusine){
		echo '<div class="cusine" data-value="' . $cusine["id"] . '">' . $cusine["name"] . '</div>';
	}?>
	<?php echo $editLink;?></div>
	<div class="clearfix"></div>
</div>
<div class="detailInfo">
	<div class="title"><?php echo $this->trans->FIELD_PRF_PHILOSOPHY; ?></div>
	<div class="text<?php if($edit){echo ' editField" data-field="PRF_PHILOSOPHY" data-field-type="area" data-placeholder="' . $this->trans->PROFILES_VIEW_PHILOSOPHY_PLACEHOLDER;} ?>"><?php echo $model->PRF_PHILOSOPHY; echo $editLink;?></div>
	
	<div class="title"><?php echo $this->trans->FIELD_PRF_EXPERIENCE; ?></div>
	<div class="text<?php if($edit){echo ' editField" data-field="PRF_EXPERIENCE" data-field-type="area" data-placeholder="' . $this->trans->PROFILES_VIEW_EXPERIENCE_PLACEHOLDER;} ?>"><?php echo $model->PRF_EXPERIENCE; echo $editLink;?></div>
	
	<div class="title"><?php echo $this->trans->FIELD_PRF_AWARDS; ?></div>
	<div class="text<?php if($edit){echo ' editField" data-field="PRF_AWARDS" data-field-type="area" data-placeholder="' . $this->trans->PROFILES_VIEW_AWARDS_PLACEHOLDER;} ?>"><?php echo $model->PRF_AWARDS; echo $editLink;?></div>
</div>
<div class="recipes otherItems">
	<?php
	echo '<div class="otherItemsTitle">'.sprintf($this->trans->PROFILES_MATCHING_RECIPES, $model->PRF_FIRSTNAME . " " . $model->PRF_LASTNAME).'</div>';
	if ($recipesAmount == 0){
		echo '<span class="noItems">'.$this->trans->PROFILES_NO_MATCHING_RECIPES .'</span>';
	} else {
		/*
		if ($recipesAmount> ProfilesController::RECIPES_AMOUNT){?>
			<input name="recipe" class="imgIndex" type="hidden" value="0" />
			<input name="amount" class="imgIndexAmount" type="hidden" value="<?php echo ProfilesController::RECIPES_AMOUNT; ?>" />
			<div class="up-arrow"><div class="up1"></div><div class="up2"></div></div>
			<?php
			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe = {};';
		}
		*/
		$index = 0;
		foreach($recipes as $recipe){
			if ($index < ProfilesController::RECIPES_AMOUNT){
				echo '<div class="item">';
					echo CHtml::link($recipe['REC_NAME_' . Yii::app()->session['lang']], array('recipes/view', 'id'=>$recipe['REC_ID']), array('class'=>'title'));
					echo '<div class="small_img">';
						echo CHtml::link(CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')), $recipe['REC_NAME_' . Yii::app()->session['lang']], array('class'=>'recipe', 'title'=>$recipe['REC_NAME_' . Yii::app()->session['lang']])), array('recipes/view', 'id'=>$recipe['REC_ID']));
						echo '<div class="img_auth">';
						if ($recipe['REC_IMG_ETAG'] == '') { echo '&nbsp;'; } else {echo '© by ' . $recipe['REC_IMG_AUTH']; }
						echo '</div>';
					echo '</div>';
				echo '</div>';
// 				if ($recipesAmount > ProfilesController::RECIPES_AMOUNT){
// 					$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.idx' . $index . ' = {img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$recipe['REC_ID'])).'", auth:"'.$recipe['REC_IMG_AUTH'].'", name:"'.$recipe['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
// 				}
// 			} else {
// 				$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.idx' . $index . ' = {img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$recipe['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$recipe['REC_ID'])).'", auth:"'.$recipe['REC_IMG_AUTH'].'", name:"'.$recipe['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'};';
			}
			++$index;
		}
// 		if ($recipesAmount > ProfilesController::RECIPES_AMOUNT){
// 			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.nextPreloadIndex = '.$index.';';
// 			$preloadedInfoResetScript .= "\r\n".'glob.preloadedInfo.recipe.prevPreloadIndex = -1;';
// 			echo '<div class="down-arrow"><div class="down1"></div><div class="down2"></div></div>';
// 		}
	}
	?>
</div>

<?php 
if ($edit){
	echo '<div>';
}
?>
</div>
<?php
//echo '<script>' . $preloadedInfoResetScript . "\r\n".'</script>';
 ?>