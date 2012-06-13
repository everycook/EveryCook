<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'nutrient-data-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ID'); ?>
		<?php echo $form->textField($model,'NUT_ID',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'NUT_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_DESC'); ?>
		<?php echo $form->textField($model,'NUT_DESC',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'NUT_DESC'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_WATER'); ?>
		<?php echo $form->textField($model,'NUT_WATER'); ?>
		<?php echo $form->error($model,'NUT_WATER'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ENERG'); ?>
		<?php echo $form->textField($model,'NUT_ENERG'); ?>
		<?php echo $form->error($model,'NUT_ENERG'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_PROT'); ?>
		<?php echo $form->textField($model,'NUT_PROT'); ?>
		<?php echo $form->error($model,'NUT_PROT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_LIPID'); ?>
		<?php echo $form->textField($model,'NUT_LIPID'); ?>
		<?php echo $form->error($model,'NUT_LIPID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ASH'); ?>
		<?php echo $form->textField($model,'NUT_ASH'); ?>
		<?php echo $form->error($model,'NUT_ASH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_CARB'); ?>
		<?php echo $form->textField($model,'NUT_CARB'); ?>
		<?php echo $form->error($model,'NUT_CARB'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FIBER'); ?>
		<?php echo $form->textField($model,'NUT_FIBER'); ?>
		<?php echo $form->error($model,'NUT_FIBER'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_SUGAR'); ?>
		<?php echo $form->textField($model,'NUT_SUGAR'); ?>
		<?php echo $form->error($model,'NUT_SUGAR'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_CALC'); ?>
		<?php echo $form->textField($model,'NUT_CALC'); ?>
		<?php echo $form->error($model,'NUT_CALC'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_IRON'); ?>
		<?php echo $form->textField($model,'NUT_IRON'); ?>
		<?php echo $form->error($model,'NUT_IRON'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_MAGN'); ?>
		<?php echo $form->textField($model,'NUT_MAGN'); ?>
		<?php echo $form->error($model,'NUT_MAGN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_PHOS'); ?>
		<?php echo $form->textField($model,'NUT_PHOS'); ?>
		<?php echo $form->error($model,'NUT_PHOS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_POTAS'); ?>
		<?php echo $form->textField($model,'NUT_POTAS'); ?>
		<?php echo $form->error($model,'NUT_POTAS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_SODIUM'); ?>
		<?php echo $form->textField($model,'NUT_SODIUM'); ?>
		<?php echo $form->error($model,'NUT_SODIUM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ZINC'); ?>
		<?php echo $form->textField($model,'NUT_ZINC'); ?>
		<?php echo $form->error($model,'NUT_ZINC'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_COPP'); ?>
		<?php echo $form->textField($model,'NUT_COPP'); ?>
		<?php echo $form->error($model,'NUT_COPP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_MANG'); ?>
		<?php echo $form->textField($model,'NUT_MANG'); ?>
		<?php echo $form->error($model,'NUT_MANG'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_SELEN'); ?>
		<?php echo $form->textField($model,'NUT_SELEN'); ?>
		<?php echo $form->error($model,'NUT_SELEN'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_C'); ?>
		<?php echo $form->textField($model,'NUT_VIT_C'); ?>
		<?php echo $form->error($model,'NUT_VIT_C'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_THIAM'); ?>
		<?php echo $form->textField($model,'NUT_THIAM'); ?>
		<?php echo $form->error($model,'NUT_THIAM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_RIBOF'); ?>
		<?php echo $form->textField($model,'NUT_RIBOF'); ?>
		<?php echo $form->error($model,'NUT_RIBOF'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_NIAC'); ?>
		<?php echo $form->textField($model,'NUT_NIAC'); ?>
		<?php echo $form->error($model,'NUT_NIAC'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_PANTO'); ?>
		<?php echo $form->textField($model,'NUT_PANTO'); ?>
		<?php echo $form->error($model,'NUT_PANTO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_B6'); ?>
		<?php echo $form->textField($model,'NUT_VIT_B6'); ?>
		<?php echo $form->error($model,'NUT_VIT_B6'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FOLAT_TOT'); ?>
		<?php echo $form->textField($model,'NUT_FOLAT_TOT'); ?>
		<?php echo $form->error($model,'NUT_FOLAT_TOT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FOLIC'); ?>
		<?php echo $form->textField($model,'NUT_FOLIC'); ?>
		<?php echo $form->error($model,'NUT_FOLIC'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FOLATE_FD'); ?>
		<?php echo $form->textField($model,'NUT_FOLATE_FD'); ?>
		<?php echo $form->error($model,'NUT_FOLATE_FD'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FOLATE_DFE'); ?>
		<?php echo $form->textField($model,'NUT_FOLATE_DFE'); ?>
		<?php echo $form->error($model,'NUT_FOLATE_DFE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_CHOLINE'); ?>
		<?php echo $form->textField($model,'NUT_CHOLINE'); ?>
		<?php echo $form->error($model,'NUT_CHOLINE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_B12'); ?>
		<?php echo $form->textField($model,'NUT_VIT_B12'); ?>
		<?php echo $form->error($model,'NUT_VIT_B12'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_A_IU'); ?>
		<?php echo $form->textField($model,'NUT_VIT_A_IU'); ?>
		<?php echo $form->error($model,'NUT_VIT_A_IU'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_A_RAE'); ?>
		<?php echo $form->textField($model,'NUT_VIT_A_RAE'); ?>
		<?php echo $form->error($model,'NUT_VIT_A_RAE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_RETINOL'); ?>
		<?php echo $form->textField($model,'NUT_RETINOL'); ?>
		<?php echo $form->error($model,'NUT_RETINOL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_ALPHA_CAROT'); ?>
		<?php echo $form->textField($model,'NUT_ALPHA_CAROT'); ?>
		<?php echo $form->error($model,'NUT_ALPHA_CAROT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_BETA_CAROT'); ?>
		<?php echo $form->textField($model,'NUT_BETA_CAROT'); ?>
		<?php echo $form->error($model,'NUT_BETA_CAROT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_BETA_CRYPT'); ?>
		<?php echo $form->textField($model,'NUT_BETA_CRYPT'); ?>
		<?php echo $form->error($model,'NUT_BETA_CRYPT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_LYCOP'); ?>
		<?php echo $form->textField($model,'NUT_LYCOP'); ?>
		<?php echo $form->error($model,'NUT_LYCOP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_LUT_ZEA'); ?>
		<?php echo $form->textField($model,'NUT_LUT_ZEA'); ?>
		<?php echo $form->error($model,'NUT_LUT_ZEA'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_E'); ?>
		<?php echo $form->textField($model,'NUT_VIT_E'); ?>
		<?php echo $form->error($model,'NUT_VIT_E'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_D'); ?>
		<?php echo $form->textField($model,'NUT_VIT_D'); ?>
		<?php echo $form->error($model,'NUT_VIT_D'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_D_IU'); ?>
		<?php echo $form->textField($model,'NUT_VIT_D_IU'); ?>
		<?php echo $form->error($model,'NUT_VIT_D_IU'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_VIT_K'); ?>
		<?php echo $form->textField($model,'NUT_VIT_K'); ?>
		<?php echo $form->error($model,'NUT_VIT_K'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FA_SAT'); ?>
		<?php echo $form->textField($model,'NUT_FA_SAT'); ?>
		<?php echo $form->error($model,'NUT_FA_SAT'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FA_MONO'); ?>
		<?php echo $form->textField($model,'NUT_FA_MONO'); ?>
		<?php echo $form->error($model,'NUT_FA_MONO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_FA_POLY'); ?>
		<?php echo $form->textField($model,'NUT_FA_POLY'); ?>
		<?php echo $form->error($model,'NUT_FA_POLY'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_CHOLEST'); ?>
		<?php echo $form->textField($model,'NUT_CHOLEST'); ?>
		<?php echo $form->error($model,'NUT_CHOLEST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NUT_REFUSE'); ?>
		<?php echo $form->textField($model,'NUT_REFUSE'); ?>
		<?php echo $form->error($model,'NUT_REFUSE'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->