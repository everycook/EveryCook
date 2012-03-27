<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('products/uploadImage',array('id'=>$model->PRO_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('products/displaySavedImage', array('id'=>'backup', 'ext'=>'png')); ?>"/>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); 
	if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
	}
	?>

	
	<?php foreach($this->allLanguages as $lang){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_NAME_'.$lang, array('label'=>$this->trans->__get('PRODUCTS_DESCRIPTION_'.$lang))); ?>
		<?php echo $form->textField($model,'PRO_NAME_'.$lang,array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'PRO_NAME_'.$lang); ?>
	</div>
	<?php } ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_BARCODE',array('label'=>$this->trans->PRODUCTS_BARCODE)); ?>
		<?php echo $form->textField($model,'PRO_BARCODE'); ?>
		<?php echo $form->error($model,'PRO_BARCODE'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_PACKAGE_GRAMMS',array('label'=>$this->trans->PRODUCTS_PACKAGE_GRAMMS)); ?>
		<?php echo $form->textField($model,'PRO_PACKAGE_GRAMMS'); ?>
		<?php echo $form->error($model,'PRO_PACKAGE_GRAMMS'); ?>
	</div>

	<?php
	if ($model->ingredient && $model->ingredient->__get('ING_TITLE_'.Yii::app()->session['lang'])){
		$IngredientDescription = $model->ingredient->__get('ING_TITLE_'.Yii::app()->session['lang']);
	} else {
		$IngredientDescription = $this->trans->PRODUCTS_SEARCH_CHOOSE;
	}
	?>
	
	<div class="row" id="ingredient">
		<?php echo $form->label($model,'ING_ID',array('label'=>$this->trans->PRODUCTS_INGREDIENT)); ?>
		<?php echo $form->hiddenField($model,'ING_ID', array('id'=>'ING_ID')); ?>
		<?php echo CHtml::link($IngredientDescription, array('ingredients/chooseIngredient'), array('class'=>'fancyChoose IngredientSelect')) ?>
	</div>

	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->PRODUCTS_SEARCH_CHOOSE);
	
	echo Functions::createInput($this->trans->PRODUCTS_SUSTAINABILITY, $model, 'PRO_ECO', $ecology, Functions::DROP_DOWN_LIST, 'ecology', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->PRODUCTS_ETHICAL, $model, 'PRO_ETHIC', $ethicalCriteria, Functions::DROP_DOWN_LIST, 'ethicalCriteria', $htmlOptions_type0, $form);
	?>
	
	<?php
		if (Yii::app()->session['Product_Backup'] && Yii::app()->session['Product_Backup']->PRO_PICTURE_ETAG){
			echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>'backup', 'ext'=>'png')), '', array('class'=>'product cropable', 'alt'=>$model->PRO_PICTURE_COPYR, 'title'=>$model->PRO_PICTURE_COPYR));
		} else if ($model->ING_ID) {
			echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$model->PRO_ID, 'ext'=>'png')), '', array('class'=>'product cropable', 'alt'=>$model->PRO_PICTURE_COPYR, 'title'=>$model->PRO_PICTURE_COPYR));
		}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<?php echo $form->FileField($model,'filename'); ?>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRO_PICTURE_COPYR'); ?>
		<?php echo $form->textField($model,'PRO_PICTURE_COPYR',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'PRO_PICTURE_COPYR'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->