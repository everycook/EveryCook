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
<input type="hidden" id="SubGroupFormLink" value="<?php echo $this->createUrl('ingredients/getSubGroupForm'); ?>"/>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('ingredients/uploadImage',array('id'=>$model->ING_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingredients_form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); 

	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
?>
	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	?>

	
<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ING_CREATED'); ?>
		<?php echo $form->textField($model,'ING_CREATED'); ?>
		<?php echo $form->error($model,'ING_CREATED'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_CHANGED'); ?>
		<?php echo $form->textField($model,'ING_CHANGED'); ?>
		<?php echo $form->error($model,'ING_CHANGED'); ?>
	</div>
	*/ ?>
	
	<?php foreach($this->allLanguages as $lang=>$name){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_NAME_'.$lang); ?>
		<?php echo $form->textField($model,'ING_NAME_'.$lang,array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_NAME_'.$lang); ?>
	</div>
	<?php } ?>
	
	<?php
	echo Functions::createInput(null, $model, 'GRP_ID', $groupNames, Functions::DROP_DOWN_LIST, 'groupNames', $htmlOptions_type0, $form);
	if ($model->GRP_ID){
		echo Functions::createInput(null, $model, 'SGR_ID', $subgroupNames, Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_type0, $form);
	} else {
		$htmlOptions_subGroup = array('empty'=>$this->trans->INGREDIENTS_CHOOSE_GROUP_FIRST);
		echo Functions::createInput(null, $model, 'SGR_ID', array(), Functions::DROP_DOWN_LIST, 'subgroupNames', $htmlOptions_subGroup, $form);
	}
	echo Functions::createInput(null, $model, 'STB_ID', $storability, Functions::DROP_DOWN_LIST, 'storability', $htmlOptions_type0, $form);
	echo Functions::createInput(null, $model, 'ICO_ID', $ingredientConveniences, Functions::DROP_DOWN_LIST, 'ingredientConveniences', $htmlOptions_type0, $form);
	echo Functions::createInput(null, $model, 'IST_ID', $ingredientStates, Functions::DROP_DOWN_LIST, 'ingredientStates', $htmlOptions_type0, $form);
	
	if ($model->nutrientData && $model->nutrientData->NUT_DESC){
		$NutrientDescription = $model->nutrientData->NUT_DESC;
	} else {
		$NutrientDescription = $this->trans->GENERAL_CHOOSE;
	}
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->labelEx($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT/*, 'style'=>'vertical-align: middle;'*/)); ?>
		<div class="imageTip">
		<?php
		echo $this->trans->TIP_NUT_ID . '<br>';
		echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID', 'class'=>'fancyValue'));
		echo CHtml::link($NutrientDescription, array('nutrientData/chooseNutrientData', 'query'=>$model->ING_NAME_EN_GB), array('class'=>'fancyChoose NutrientDataSelect buttonSmall'));
		?>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
		<?php echo $form->error($model,'ING_DENSITY'); ?>
	</div>
	
	<?php
		if (isset(Yii::app()->session['Ingredients_Backup']) && isset(Yii::app()->session['Ingredients_Backup']->ING_IMG_ETAG)){
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), $model->__get('ING_NAME_' . Yii::app()->session['lang']), array('class'=>'ingredient' .(($model->imagechanged)?' cropable':''), 'title'=>$model->__get('ING_NAME_' . Yii::app()->session['lang'])));
		} else if ($model->ING_ID && isset($model->ING_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ING_ID, 'ext'=>'.png')), $model->__get('ING_NAME_' . Yii::app()->session['lang']), array('class'=>'ingredient', 'title'=>$model->__get('ING_NAME_' . Yii::app()->session['lang'])));
		}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<div class="imageTip">
		<?php
		echo $this->trans->TIP_OWN_IMAGE . '<br>';
		echo $form->FileField($model,'filename'). '<br>' . "\r\n";
		echo $form->error($model,'filename') . "\r\n";
		echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
		printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('ING_NAME_EN_GB'));//'ING_NAME_'.Yii::app()->session['lang']
		echo '<br>' . $this->trans->TIP_FLICKR_LINK . '<input type="text" name="flickr_link" class="flickr_link"/> <div class="buttonSmall loadFromFlickr">' . $this->trans->TIP_FLICKR_LINK_LOAD . '</div>'
		?>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_IMG_AUTH'); ?>
		<?php echo $form->textField($model,'ING_IMG_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'ING_IMG_AUTH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_NEED_PEELING'); ?>
		<?php echo $form->textField($model,'ING_NEED_PEELING',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'ING_NEED_PEELING'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_NEED_WASH'); ?>
		<?php echo $form->textField($model,'ING_NEED_WASH',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'ING_NEED_WASH'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_WIKI_LINK'); ?>
		<?php echo $form->textField($model,'ING_WIKI_LINK',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'ING_WIKI_LINK'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_WEIGHT_SMALL'); ?>
		<?php echo $form->textField($model,'ING_WEIGHT_SMALL'); ?>
		<?php echo $form->error($model,'ING_WEIGHT_SMALL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_WEIGHT_BIG'); ?>
		<?php echo $form->textField($model,'ING_WEIGHT_BIG'); ?>
		<?php echo $form->error($model,'ING_WEIGHT_BIG'); ?>
	</div>

	<?php foreach($this->allLanguages as $lang=>$name){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_SYNONYM_'.$lang); ?>
		<?php echo $form->textField($model,'ING_SYNONYM_'.$lang,array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'ING_SYNONYM_'.$lang); ?>
	</div>
	<?php } ?>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->