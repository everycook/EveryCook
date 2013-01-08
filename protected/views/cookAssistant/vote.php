
<div class="cookAssistantVote form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recipesVoting-form',
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
