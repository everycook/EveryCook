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
<div class="form">
<input type="hidden" id="OpenFancyLink" value="<?php echo $this->createUrl('chooseStores'); ?>"/>
<input type="hidden" id="StoreLocationsLink" value="<?php echo $this->createUrl('stores/getStoresInRange'); ?>"/>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-finder-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    
)); ?>
	<div class="mapDetails">
		<?php echo $form->errorSummary($model); 
			if ($this->errorText){
				echo '<div class="errorSummary">';
				echo $this->errorText;
				echo '</div>';
			}
		?>
		
		<div class="row">
			<?php echo CHtml::label($this->trans->STORES_ASSIGN_NEAR_YOU,'STO_MAP'); ?>
			<?php echo CHtml::ListBox('STO_MAP','',array(),array('size'=>4)); ?>
		</div>
		
		<div class="row" id="storeSearch">
			<?php echo $form->label($model2,'query',array('label'=>$this->trans->GENERAL_SEARCH)); ?>
			<?php /*echo $form->textField($model2,'query',$this->trans->STORES_ASSIGN_ADDRESS_OR_NAME, array('class'=>'emptyOnEnter'));*/ ?>
			<?php echo Functions::activeSpecialField($model2, 'query', 'search', array('class'=>'search_query notUrl', 'placeholder'=>$this->trans->STORES_ASSIGN_ADDRESS_OR_NAME)); ?>
			<?php echo $form->hiddenField($model,'PRO_ID',array('id'=>'STO_SEARCH', 'class'=>'fancyValue')); ?>
			<?php echo CHtml::image(Yii::app()->request->baseUrl . '/pics/search.png', $this->trans->GENERAL_SEARCH, array('class'=>'search_button openFancyBySubmit fancyChoose StoresSelect')); ?>
		</div>
		
		<?php
		$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
		
		echo Functions::createInput($this->trans->STORES_ASSIGN_SUPPLIER, $model, 'SUP_ID', $supplier, Functions::DROP_DOWN_LIST, 'supplier', $htmlOptions_type0, $form);
		echo Functions::createInput($this->trans->STORES_ASSIGN_STORE_TYPE, $model, 'STY_ID', $storeType, Functions::DROP_DOWN_LIST, 'storeType', $htmlOptions_type0, $form);
		?>
	</div>
	<div id="map_canvas" style="height:600px; width:600px;"></div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
  loadScript(false, "CH", false, true, false, true);
</script>

</div><!-- form -->