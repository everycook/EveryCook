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
<div class="cookAssistantVote form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipeVotings-form',
	'enableAjaxValidation'=>false,
	/*'action'=>Yii::app()->createUrl('voteReason', array('RVO_ID'=>$model->RVO_ID)),*/
	'action'=>$this->createUrl('voteReason', array('RVO_ID'=>$model->RVO_ID)),
)); ?>
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo Functions::createInput(null, $model, 'RVR_ID', $reasons, Functions::DROP_DOWN_LIST, 'reasons', $htmlOptions_type0, $form);
	?>
	
	<div class="row" id="other_reason" style="display: none;">
		<?php echo $form->labelEx($model,'RVO_REASON'); ?>
		<?php echo $form->textField($model,'RVO_REASON',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'RVO_REASON'); ?>
	</div>
	
	<div class="buttons">
		<div class="button" id="saveReason"><?php echo $this->trans->COOKASISSTANT_VOTE_REASON_SAVE; ?></div>
	</div>
	
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
