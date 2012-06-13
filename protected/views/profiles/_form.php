<div class="form">

<input type="hidden" id="LanguageChangeLink" value="<?php echo CController::createUrl('Profiles/LanguageChanged', array_merge($this->getActionParams(), array('action'=>$this->route))); ?>"/>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profiles-form',
	'enableAjaxValidation'=>false,
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
			<?php echo $form->textField($model,'PRF_EMAIL',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'PRF_EMAIL'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_GENDER'); ?>
			<?php echo $form->textField($model,'PRF_GENDER'); ?>
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
			<?php echo $form->textField($model,'PRF_BIRTHDAY'); ?>
			<?php echo $form->error($model,'PRF_BIRTHDAY'); ?>
		</div>

		<?php
			if (isset(Yii::app()->session['Profiles_Backup']) && isset(Yii::app()->session['Profiles_Backup']->PRF_IMG_ETAG)){
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>'backup', 'ext'=>'.png')), '', array('class'=>'profiles cropable'));
			} else if ($model->PRF_UID) {
				echo CHtml::image($this->createUrl('profiles/displaySavedImage', array('id'=>$model->PRF_UID, 'ext'=>'.png')), '', array('class'=>'profiles cropable'));
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
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LAT'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LAT', array('class'=>'cord_lat')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LAT'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'PRF_LOC_GPS_LNG'); ?>
			<?php echo $form->textField($model,'PRF_LOC_GPS_LNG', array('class'=>'cord_lng')); ?>
			<?php echo $form->error($model,'PRF_LOC_GPS_LNG'); ?>
		</div>
		
		<div class="row">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/pics/locate.png" id="setMarkerCurrentGPS"/>
			<span><?php echo $this->trans->PROFILES_SEARCH_CURRENT_POSITION; ?></span>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'PRF_VIEW_DISTANCE'); ?>
			<?php echo $form->textField($model,'PRF_VIEW_DISTANCE'); ?>
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