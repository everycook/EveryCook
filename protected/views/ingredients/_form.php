<?php
$link = $this->createUrl('ingredients/getSubGroupForm');
Yii::app()->clientScript->registerScript('SubGroupNames', "
jQuery('#groupNames select').change(function(){
jQuery.ajax({'type':'get','url':'" . $link . "/?id=' + jQuery('#groupNames select').attr('value'),'success':function(html){jQuery('#subgroupNames select').replaceWith(html);}});
});
");

$this->widget('application.extensions.fancybox.EFancyBox', array(
    'target'=>'a.fancyChoose',
    'config'=>array('autoScale' => true, 'autoDimensions'=> true, 'centerOnScroll'=> true, ),
    )
);
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ingredients-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); 
	$htmlOptions_type0 = array('empty'=>$this->trans->INGREDIENTS_SEARCH_CHOOSE);
	$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
	
	//already includes in controler!
	//require_once('functions.php');

?>
	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PRF_UID'); ?>
		<?php echo $form->textField($model,'PRF_UID'); ?>
		<?php echo $form->error($model,'PRF_UID'); ?>
	</div>
	
<?php /*
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
	
	<?php foreach($this->allLanguages as $lang){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'ING_TITLE_'.$lang); ?>
		<?php echo $form->textField($model,'ING_TITLE_'.$lang,array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ING_TITLE_'.$lang); ?>
	</div>
	<?php } ?>
	
	<?php
	echo createInput($this->trans->INGREDIENTS_GROUP, $model, 'ING_GROUP', $groupNames, 0, 'groupNames', $htmlOptions_type0, $from);
	echo createInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'ING_SUBGROUP', $subgroupNames, 0, 'subgroupNames', $htmlOptions_type0, $from);
	echo createInput($this->trans->INGREDIENTS_STORABILITY, $model, 'ING_STORABILITY', $storability, 0, 'storability', $htmlOptions_type0, $from);
	echo createInput($this->trans->INGREDIENTS_CONVENIENCE, $model, 'ING_CONVENIENCE', $ingredientConveniences, 0, 'ingredientConveniences', $htmlOptions_type0, $from);
	echo createInput($this->trans->INGREDIENTS_STATE, $model, 'ING_STATE', $ingredientStates, 0, 'ingredientStates', $htmlOptions_type0, $from);
	
	if ($model->nutrientData && $model->nutrientData->NUT_DESC){
		$NutruentDescription = $model->nutrientData->NUT_DESC;
	} else {
		$NutruentDescription = $this->trans->INGREDIENTS_SEARCH_CHOOSE;
	}
	?>
	
	<div class="row" id="nutrientData">
		<?php echo $form->labelEx($model,'NUT_ID',array('label'=>$this->trans->INGREDIENTS_NUTRIENT)); ?>
		<?php echo $form->hiddenField($model,'NUT_ID', array('id'=>'NUT_ID')); ?>
		<?php echo CHtml::link($NutruentDescription, array('nutrientData/chooseNutrientData'), array('class'=>'fancyChoose NutrientDataSelect')) ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_DENSITY'); ?>
		<?php echo $form->textField($model,'ING_DENSITY'); ?>
		<?php echo $form->error($model,'ING_DENSITY'); ?>
	</div>

	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$model->ING_ID, 'ext'=>'png')), '', array('class'=>'ingredient', 'alt'=>$model->ING_PICTURE_AUTH, 'title'=>$model->ING_PICTURE_AUTH)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<!-- < ?php echo $form->textField($model,'filename'); ?> -->
		<?php echo $form->FileField($model,'filename'); ?>
		<!-- < ?php echo CHtml::activeFileField($model, 'filename'); ?> -->
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ING_PICTURE_AUTH'); ?>
		<?php echo $form->textField($model,'ING_PICTURE_AUTH',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'ING_PICTURE_AUTH'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->