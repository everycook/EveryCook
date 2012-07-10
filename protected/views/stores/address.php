<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'address-form',
	'enableAjaxValidation'=>false,
	'action'=>'#',
)); ?>

	<div class="hint"><?php
		if (isset($_GET['errorCode'])){
			$status = $_GET['errorCode'];
			if ($status == -1) {
				echo 'Geolocation failed. Please enter your current address by hand and press "Address To GPS" button.';
			} else if ($status == -2) {
				echo 'Your Browser do not support Geolocation. Please enter your current address by hand and press "Address To GPS" button.';
			}
		}
	?></div>
	<div class="row">
		<?php echo $form->label($model,'STO_STREET'); ?>
		<?php echo $form->textField($model,'STO_STREET',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_STREET'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_HOUSE_NO'); ?>
		<?php echo $form->textField($model,'STO_HOUSE_NO',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'STO_HOUSE_NO'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_ZIP'); ?>
		<?php echo $form->textField($model,'STO_ZIP'); ?>
		<?php echo $form->error($model,'STO_ZIP'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STO_CITY'); ?>
		<?php echo $form->textField($model,'STO_CITY',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_CITY'); ?>
	</div>

	<?php
	$htmlOptions_type0 = array('empty'=>$this->trans->GENERAL_CHOOSE);
	echo Functions::createInput($this->trans->STORES_COUNTRY, $model, 'STO_COUNTRY', $countrys, Functions::DROP_DOWN_LIST, 'countrys', $htmlOptions_type0, $form);
	?>

	<div class="row">
		<?php echo $form->label($model,'STO_STATE'); ?>
		<?php echo $form->textField($model,'STO_STATE',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'STO_STATE'); ?>
	</div>
		
	<div class="row">
		<?php
		echo $form->labelEx($model,'STO_GPS_LAT', array('label'=>$this->trans->GENERAL_LAT));
		$value = null;
		if (isset($_GET['lat'])){
			$value = $_GET['lat'];
		}
		echo $form->textField($model,'STO_GPS_LAT', array('class'=>'cord_lat', 'value'=>$value));
		echo $form->error($model,'STO_GPS_LAT');
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model,'STO_GPS_LNG', array('label'=>$this->trans->GENERAL_LNG));
		$value = null;
		if (isset($_GET['lng'])){
			$value = $_GET['lng'];
		}
		echo $form->textField($model,'STO_GPS_LNG', array('class'=>'cord_lng', 'value'=>$value));
		echo $form->error($model,'STO_GPS_LNG');
		?>
	</div>

	<div class="buttons">
		<?php echo CHtml::button('Address to GPS', array('id'=>'Address_to_GPS', 'class'=>'button')); ?>
		<?php echo CHtml::button('GPS to Address', array('id'=>'GPS_to_Address', 'class'=>'button')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_CANCEL, '#', array('class'=>'button closeFancy')); ?>
		<?php echo CHtml::link($this->trans->GENERAL_SAVE, '#', array('class'=>'button', 'id'=>'useLocation')); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->