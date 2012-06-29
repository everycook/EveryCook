<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('products/uploadImage',array('id'=>$model->PRO_ID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('products/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>

<input type="hidden" id="newProducerText" value="<?php echo $this->trans->GENERAL_CHOOSE; ?>"/>
<input type="hidden" id="removeText" value="<?php echo $this->trans->GENERAL_REMOVE; ?>"/>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>

	<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

	<?php echo $form->errorSummary($model); 
	if ($this->errorText){
		echo '<div class="errorSummary">';
		echo $this->errorText;
		echo '</div>';
	}
	?>
	
	<?php
	if (isset($model->ingredient) && $model->ingredient->__isset('ING_NAME_'.Yii::app()->session['lang'])){
		$IngredientDescription = $model->ingredient->__get('ING_NAME_'.Yii::app()->session['lang']);
	} else {
		$IngredientDescription = $this->trans->GENERAL_CHOOSE;
	}
	?>
	
	<div class="row" id="ingredient">
		<?php echo $form->label($model,'ING_ID', array('label'=>$this->trans->PRODUCTS_INGREDIENT)); ?>
		<?php echo $form->hiddenField($model,'ING_ID', array('id'=>'ING_ID', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::link($IngredientDescription, array('ingredients/chooseIngredient'), array('class'=>'fancyChoose IngredientSelect')) ?>
	</div>

	
	<?php foreach($this->allLanguages as $lang=>$name){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_NAME_'.$lang); ?>
		<?php echo $form->textField($model,'PRO_NAME_'.$lang,array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'PRO_NAME_'.$lang); ?>
	</div>
	<?php } ?>
	
	
	<?php
	if ($model->producers && count($model->producers)>0){
		echo '<div class="row" id="producer">';
		echo CHtml::label($this->trans->PRODUCTS_PRODUCERS, 'PRD_ID_0');
		$i = 0;
		echo '<ul id="producerList">';
		foreach($model->producers as $producer){
			echo '<li>';
			echo CHtml::hiddenField('PRD_ID['.$i.']', $model->producers[$i]->PRD_ID, array('id'=>'PRD_ID'.$i, 'class'=>'fancyValue'));
			echo CHtml::link($model->producers[$i]->PRD_NAME, array('producers/chooseProducer'), array('class'=>'fancyChoose ProducerSelect'));
			echo '<span class="buttonSmall remove">' . $this->trans->GENERAL_REMOVE . '<span>';
			echo '</li>';
			$i++;
		}
		echo '</ul><div class="clearfix"></div><span class="buttonSmall add">&nbsp;&nbsp;+&nbsp;&nbsp;</span></div>';
	} else {
		echo '<div class="row" id="producer">';
		echo CHtml::label($this->trans->PRODUCTS_PRODUCERS, 'PRD_ID_0');
		echo '<ul id="producerList">';
		echo '<li>';
		echo CHtml::hiddenField('PRO_ID[0]', '', array('id'=>'PRD_ID_0', 'class'=>'fancyValue'));
		echo CHtml::link($this->trans->GENERAL_CHOOSE, array('producers/chooseProducer'), array('class'=>'fancyChoose ProducerSelect'));
		echo '<span class="buttonSmall remove">' . $this->trans->GENERAL_REMOVE . '<span>';
		echo '</li>';
		echo '</ul><div class="clearfix"></div><span class="buttonSmall add">&nbsp;&nbsp;+&nbsp;&nbsp;</span></div>';
	}
	?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_BARCODE'); ?>
		<?php echo $form->textField($model,'PRO_BARCODE'); ?>
		<?php echo $form->error($model,'PRO_BARCODE'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'PRO_PACKAGE_GRAMMS'); ?>
		<?php echo $form->textField($model,'PRO_PACKAGE_GRAMMS'); ?>
		<?php echo CHtml::dropdownList('PACKAGE_MULT', '1', array('1'=>'g','1000'=>'kg'), array('style'=>'width: 4em;')); ?>
		<?php echo $form->error($model,'PRO_PACKAGE_GRAMMS'); ?>
	</div>
	
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	
	echo Functions::createInput(null, $model, 'ECO_ID', $ecology, Functions::DROP_DOWN_LIST, 'ecology', $htmlOptions_type0, $form);
	echo Functions::createInput(null, $model, 'ETH_ID', $ethicalCriteria, Functions::DROP_DOWN_LIST, 'ethicalCriteria', $htmlOptions_type0, $form);
	?>
	
	<?php
		if (isset(Yii::app()->session['Products_Backup']) && isset(Yii::app()->session['Products_Backup']->PRO_IMG_ETAG)){
			echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')), '', array('class'=>'product' .(($model->imagechanged)?' cropable':''), 'alt'=>$model->PRO_IMG_CR, 'title'=>$model->PRO_IMG_CR));
		} else if ($model->PRO_ID && isset($model->PRO_IMG_ETAG)) {
			echo CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$model->PRO_ID, 'ext'=>'.png')), '', array('class'=>'product', 'alt'=>$model->PRO_IMG_CR, 'title'=>$model->PRO_IMG_CR));
		}
	?>
	
	<div class="row">
		<?php
		echo $form->labelEx($model,'filename');
		/*
		echo '<div class="imageTip">';
		echo $this->trans->TIP_OWN_IMAGE . '<br>';
		echo $this->trans->TIP_FLICKR_IMAGE . '<br>';
		printf($this->trans->TIP_LOOK_ON_FLICKR, $model->__get('PRO_NAME_EN_GB')); //.Yii::app()->session['lang']
		echo '<br>';
		*/
		echo $form->FileField($model,'filename');
		//e3cho '</div>';
		echo $form->error($model,'filename');
		?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PRO_IMG_CR'); ?>
		<?php echo $form->textField($model,'PRO_IMG_CR',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'PRO_IMG_CR'); ?>
	</div>

	<div class="buttons">
		<?php
		echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE, array('name'=>'save', 'class'=>'button'));
		echo CHtml::submitButton($this->trans->PRODUCTS_SAVE_AND_ASSIGN, array('name'=>'saveAddAssing', 'class'=>'button'));
		echo CHtml::link($this->trans->GENERAL_CANCEL, array('cancel'), array('class'=>'button', 'id'=>'cancel'));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->