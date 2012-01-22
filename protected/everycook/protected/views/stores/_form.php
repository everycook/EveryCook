<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stores-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'STO_LOC_GPS'); ?>
		<?php echo $form->textField($model,'STO_LOC_GPS',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_LOC_GPS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STO_LOC_ADDR'); ?>
		<?php echo $form->textField($model,'STO_LOC_ADDR',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'STO_LOC_ADDR'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SUP_ID'); ?>
		<!--<?php echo $form->textField($model,'SUP_ID'); ?> -->
		<!--  STL Added dropDown  -->
		<?php $test = CHtml::listData(Suppliers::model()->findall(), 'SUP_ID', 'SUP_NAME');
		while (current($test)) {
            $test[key($test)]=key($test).' '.$test[key($test)];
            next($test);
		}
		echo $form->dropDownList($model,'SUP_ID', $test, array('empty'=>'select Supplier')); ?>
		<?php echo $form->error($model,'SUP_ID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->