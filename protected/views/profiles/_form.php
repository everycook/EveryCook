<div class="form">
<input type="hidden" id="LanguageChangeLink" value="<?php echo CController::createUrl('Profiles/LanguageChanged', array_merge($this->getActionParams(), array('action'=>$this->route))); ?>"/>
<input type="hidden" id="uploadImageLink" value="<?php echo $this->createUrl('profiles/uploadImage',array('id'=>$model->PRF_UID)); ?>"/>
<input type="hidden" id="imageLink" value="<?php echo $this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')); ?>"/>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl($this->route, array_merge($this->getActionParams(), array('ajaxform'=>true))),
    'htmlOptions'=>array('enctype' => 'multipart/form-data', 'class'=>'ajaxupload'),
)); ?>
	<div class="mapDetails">
		<p class="note"><?php echo $this->trans->CREATE_REQUIRED; ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LANG'); ?>
			<?php echo $form->dropDownList($model,'PRF_LANG', $this->allLanguages, array('empty'=>$this->trans->GENERAL_CHOOSE,)); ?>
			<?php echo $form->error($model,'PRF_LANG'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_NICK'); ?>
			<?php echo $form->textField($model,'PRF_NICK',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_NICK'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_EMAIL'); ?>
			<?php echo Functions::activeSpecialField($model, 'PRF_EMAIL', 'email',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_EMAIL'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_GENDER'); ?>
			<?php echo $form->dropDownList($model,'PRF_GENDER', array('F'=>$this->trans->PROFILES_GENDER_F, 'M'=>$this->trans->PROFILES_GENDER_M), array('empty'=>$this->trans->GENERAL_CHOOSE,)); ?>
			<?php echo $form->error($model,'PRF_GENDER'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_FIRSTNAME'); ?>
			<?php echo $form->textField($model,'PRF_FIRSTNAME',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_FIRSTNAME'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LASTNAME'); ?>
			<?php echo $form->textField($model,'PRF_LASTNAME',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_LASTNAME'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_BIRTHDAY'); ?>
			<?php
				$years = array();
				for ($i=date('Y'); $i>=1900; $i--){
					$years[$i] = $i;
				}
				$months_temp = explode(',',$this->trans->GENERAL_MONTH_NAMES);
				$months = array();
				foreach($months_temp as $index=>$month){
					$months[substr('0'.($index+1),-2)] = $month;
				}
				$days = array();
				for ($i=1; $i<=31; $i++){
					$days[substr('0'.$i,-2)] = $i;
				}
				echo $form->dropDownList($model,'birthday_year', $years, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'year'));
				echo $form->dropDownList($model,'birthday_month', $months, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'month'));
				echo $form->dropDownList($model,'birthday_day', $days, array('empty'=>$this->trans->GENERAL_CHOOSE,'class'=>'day'));
			?>
			<?php echo $form->error($model,'PRF_BIRTHDAY'); ?>
		</div>

		<?php
			if (isset(Yii::app()->session['Profiles_Backup']) && isset(Yii::app()->session['Profiles_Backup']->PRF_IMG_ETAG)){
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')), '', array('class'=>'profiles' .(($model->imagechanged)?' cropable':'')));
			} else if ($model->PRF_UID) {
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$model->PRF_UID, 'ext'=>'.png')), '', array('class'=>'profiles'));
			}
		?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'filename'); ?>
			<?php echo $form->FileField($model,'filename'); ?>
			<?php echo $form->error($model,'filename'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'new_pw'); ?>
			<?php echo $form->textField($model,'new_pw',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'new_pw'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'pw_repeat'); ?>
			<?php echo $form->textField($model,'pw_repeat',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'pw_repeat'); ?>
		</div>
		
		<div class="row">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/locate.png" id="setMarkerCurrentGPS"/>
			<span><?php echo $this->trans->PROFILES_SEARCH_CURRENT_POSITION; ?></span>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LAT'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LAT',array('pattern'=>'-?\d{1,3}\.\d+','class'=>'cord_lat')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LAT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LNG'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LNG',array('pattern'=>'-?\d{1,3}\.\d+','class'=>'cord_lng')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LNG'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_VIEW_DISTANCE'); ?>
			<?php echo Functions::activeSpecialField($model, 'PRF_VIEW_DISTANCE', 'number'); ?>
			<?php echo $form->error($model,'PRF_VIEW_DISTANCE'); ?>
		</div>
		
	<?php /*
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_POINT'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_POINT'); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_POINT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LIKES_I'); ?>
			<?php echo $form->textArea($model,'PRF_LIKES_I',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_LIKES_I'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LIKES_R'); ?>
			<?php echo $form->textArea($model,'PRF_LIKES_R',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_LIKES_R'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LIKES_P'); ?>
			<?php echo $form->textArea($model,'PRF_LIKES_P',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_LIKES_P'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LIKES_S'); ?>
			<?php echo $form->textArea($model,'PRF_LIKES_S',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_LIKES_S'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_NOTLIKES_I'); ?>
			<?php echo $form->textArea($model,'PRF_NOTLIKES_I',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_NOTLIKES_I'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_NOTLIKES_R'); ?>
			<?php echo $form->textArea($model,'PRF_NOTLIKES_R',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_NOTLIKES_R'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_NOTLIKES_P'); ?>
			<?php echo $form->textArea($model,'PRF_NOTLIKES_P',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_NOTLIKES_P'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_SHOPLISTS'); ?>
			<?php echo $form->textArea($model,'PRF_SHOPLISTS',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'PRF_SHOPLISTS'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_ACTIVE'); ?>
			<?php echo $form->textField($model,'PRF_ACTIVE'); ?>
			<?php echo $form->error($model,'PRF_ACTIVE'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_RND'); ?>
			<?php echo $form->textField($model,'PRF_RND',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_RND'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CREATED_BY'); ?>
			<?php echo $form->textField($model,'CREATED_BY'); ?>
			<?php echo $form->error($model,'CREATED_BY'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CREATED_ON'); ?>
			<?php echo $form->textField($model,'CREATED_ON'); ?>
			<?php echo $form->error($model,'CREATED_ON'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CHANGED_BY'); ?>
			<?php echo $form->textField($model,'CHANGED_BY'); ?>
			<?php echo $form->error($model,'CHANGED_BY'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'CHANGED_ON'); ?>
			<?php echo $form->textField($model,'CHANGED_ON'); ?>
			<?php echo $form->error($model,'CHANGED_ON'); ?>
		</div>
		*/ ?>
		<div class="buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? $this->trans->GENERAL_CREATE : $this->trans->GENERAL_SAVE); ?>
		</div>
	</div>
	<strong><?php echo $this->trans->PROFILES_SELECT_HOME; ?></strong>
	<div id="map_canvas" style="height:300px; width:300px;"></div>
	<div class="clearfix"></div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	loadScript(false, "CH", false, false, true, true);
</script>

</div><!-- form -->