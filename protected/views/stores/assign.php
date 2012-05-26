<div class="form">
<input type="hidden" id="OpenFancyLink" value="<?php echo $this->createUrl('chooseStores'); ?>"/>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-assign-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($model); 
		if ($this->errorText){
			echo '<div class="errorSummary">';
			echo $this->errorText;
			echo '</div>';
		}
	?>

	<div class="row" id="product">
		<?php echo $form->label($model,'PRO_ID',array('label'=>$this->trans->STORES_ASSIGN_PRODUCT)); ?>
		<?php echo $form->hiddenField($model,'PRO_ID',array('id'=>'PRO_ID', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::link($productName, array('product/chooseProduct'), array('class'=>'fancyChoose ProductSelect')) ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label($this->trans->STORES_ASSIGN_NEAR_YOU,'STO_MAP'); ?>
		<?php echo CHtml::ListBox('STO_MAP','',$nearShops,array('size'=>4)); ?>
		<div class="map"></div>
	</div>
	
	<div class="row" id="storeSearch">
		<?php echo $form->label($model2,'query',array('label'=>$this->trans->GENERAL_SEARCH)); ?>
		<?php /*echo $form->textField($model2,'query',$this->trans->STORES_ASSIGN_ADDRESS_OR_NAME, array('class'=>'emptyOnEnter'));*/ ?>
		<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query emptyOnEnter')); ?>
		<?php echo $form->hiddenField($model,'PRO_ID',array('id'=>'STO_SEARCH', 'class'=>'fancyValue')); ?>
		<?php echo CHtml::image(Yii::app()->request->baseUrl . '/pics/search.png', $this->trans->GENERAL_SEARCH, array('class'=>'search_button openFancyBySubmit fancyChoose StoresSelect')); ?>
	</div>
	
	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	
	echo Functions::createInput($this->trans->STORES_ASSIGN_SUPPLIER, $model, 'SUP_ID', $supplier, Functions::DROP_DOWN_LIST, 'supplier', $htmlOptions_type0, $form);
	echo Functions::createInput($this->trans->STORES_ASSIGN_STORE_TYPE, $model, 'STY_ID', $storeType, Functions::DROP_DOWN_LIST, 'storeType', $htmlOptions_type0, $form);
	?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($this->trans->GENERAL_SAVE, array('name'=>'save', 'class'=>'button')); ?>
		<?php echo CHtml::submitButton($this->trans->STORES_ASSIGN_SAVE_ADD_NEXT, array('name'=>'saveAddNext', 'class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->