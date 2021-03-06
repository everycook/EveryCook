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
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('uploadImage',array('id'=>$model->CUT_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>
<div class="form">

<div class="mapDetails">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'cusine-types_form',
		'enableAjaxValidation'=>false,
		'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
	    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
	)); ?>
	
		<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>
		<?php
		echo $form->errorSummary($model);
		if ($this->errorText){
				echo '<div class="errorSummary">';
				echo $this->errorText;
				echo '</div>';
		}
		
		foreach($this->allLanguages as $lang=>$name){
		echo '<div class="row">'."\r\n";
			echo $form->labelEx($model,'CUT_DESC_'.$lang) ."\r\n";
			echo $form->textField($model,'CUT_DESC_'.$lang,array('size'=>60,'maxlength'=>100)) ."\r\n";
			echo $form->error($model,'CUT_DESC_'.$lang) ."\r\n";
		echo '</div>'."\r\n";
		}
		?>
	
	
	<?php
		if (isset(Yii::app()->session[$this->createBackup]) && isset(Yii::app()->session[$this->createBackup]->CUT_IMG_ETAG)){
			echo CHtml::image($this->createUrl('displaySavedImage', array('id'=>'backup', 'ext'=>'.png', 'rand'=>rand())), '', array('class'=>'ingredient'));
		} else if ($model->CUT_ID && isset($model->CUS_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('displaySavedImage', array('id'=>$model->CUT_ID, 'ext'=>'.png')), '', array('class'=>'ingredient'));
		}
	?>
		<div class="row">
			<?php echo $form->labelEx($model,'filename') ?>
			<div class="imageTip">
			<?php
			echo $this->trans->TIP_OWN_IMAGE . '<br>';
			echo $form->FileField($model,'filename', array('class'=>'noCrop')). '<br>' . "\r\n";
			echo $form->error($model,'filename') . "\r\n";
			?>
			</div>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'CUT_GPS_LAT'); ?>
			<?php echo $form->textField($model,'CUT_GPS_LAT'); ?>
			<?php echo $form->error($model,'CUT_GPS_LAT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CUT_GPS_LNG'); ?>
			<?php echo $form->textField($model,'CUT_GPS_LNG'); ?>
			<?php echo $form->error($model,'CUT_GPS_LNG'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CUT_GOOGLE_REGION'); ?>
			<?php echo $form->textField($model,'CUT_GOOGLE_REGION',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'CUT_GOOGLE_REGION'); ?>
		</div>

		<div class="buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
			<?php echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>
<div id="map_container" class="region">
	<div id="places_search">
		<strong><?php echo $this->trans->CUSINE_TYPE_SELECT_LOCATION; ?></strong>
		<input type="text" name="regionQuery" id="regionQuery"/><br>
		<div class="button" id="regionByQuery"><?php echo $this->trans->STORES_SEARCH_BY_QUERY; ?></div>
		<div class="button" id="regionByRange"><?php echo $this->trans->STORES_SEARCH_BY_RANGE; ?></div>
	</div>
	<div id="map_canvas" style="height:30em; width:30em;"></div>
	<div id="places_results" ></div>
</div>
<div class="clearfix"></div>

<script type="text/javascript">
	loadScript(false, "CH", false, false, true, true, true);
</script>

</div><!-- form -->