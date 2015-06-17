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
<?php 
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/feedback.css');
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui.css');
// Yii::app()->clientScript->registerScript('dragging', '
// 	$(function() {
// 		/*
// 		$("#feedback").dialog({
// 			draggable: true,
// 			height: 200,
// 		});
// 		*/
		
//     	var feedback = $("#feedback");
// 		feedback
// 			//.draggable()
// 			//.resizable()
// 			.find(".title").click(function(){
// 				feedback.toggleClass("collapsed");
// // 				var feedback = $("#feedback");
// // 				feedback.css("bottom","").css("right","").css("top",$(window).height() - 40).css("left",$(window).width() - feedback.width()).css("position","absolute");
// // 				feedback.animate({
// // 						height: "8em",
// // 						top: $(window).height() - 200,
// // 					}, 200
// // 				);
// 			});
// 	});
// ', CClientScript::POS_HEAD);
?>
<div id="feedback" class="ui-widget-content collapsed form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'feedbacks_form_simple',
	'enableAjaxValidation'=>false,
	'action'=>array("site/feedback"),
	'htmlOptions'=>array('class'=>'noAjax'),
)); ?>
	<p class="title"><?php echo $this->trans->FEEDBACKS_TITLE; ?></p>
	
	<?php
	//echo '<p class="note">' . $this->trans->CREATE_REQUIRED . '</p>';
	echo $form->errorSummary($model);
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	?>
	
	<div class="row feedback_title">
		<?php //echo $form->labelEx($model,'FEE_TITLE'); ?>
		<?php echo CHtml::activeTextField($model,'FEE_TITLE',array('placeholder'=>$this->trans->FEEDBACKS_PLACEHOLDER_TITLE)); ?>
		<?php echo $form->error($model,'FEE_TITLE'); ?>
	</div>
	<div class="row feedback_text">
		<?php //echo $form->labelEx($model,'FEE_TEXT'); ?>
		<?php echo $form->textArea($model,'FEE_TEXT',array('rows'=>6, 'cols'=>30, 'placeholder'=>$this->trans->FEEDBACKS_PLACEHOLDER)); ?>
		<?php echo $form->error($model,'FEE_TEXT'); ?>
	</div>
	<div class="row feedback_email">
		<?php //echo $form->labelEx($model,'FEE_EMAIL'); ?>
		<?php echo Functions::activeSpecialField($model,'FEE_EMAIL','email',array('placeholder'=>$this->trans->FEEDBACKS_PLACEHOLDER_EMAIL)); ?>
		<?php echo $form->error($model,'FEE_EMAIL'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($this->trans->FEEDBACKS_SUBMIT); ?>
		<div class="button type feedback_expand"><?php echo $this->trans->FEEDBACKS_TYPE_EXPANDED; ?></div>
		<div class="button type feedback_normal"><?php echo $this->trans->FEEDBACKS_TYPE_NORMAL; ?></div>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->