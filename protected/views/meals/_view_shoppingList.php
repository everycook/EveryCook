<div class="resultArea">
	<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'.png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_IMG_AUTH'], 'title'=>$data['ING_IMG_AUTH'])), array('ingredients/view', 'id'=>$data['ING_ID'])); ?>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::encode($data['ING_NAME_'.Yii::app()->session['lang']]); ?>
		</div>
		<div class="ShoppingListInfo">
		<?php
		echo 'meal_gda ', $data['meal_gda'], '<br>',
			'cou_gda ', $data['cou_gda'],'<br>',
			'rec_gda ', $data['rec_gda'],'<br>',
			'rec_kcal ', $data['rec_kcal'],'<br>',
			'rec_proz ', $data['rec_proz'],
			'<br>';
		echo $data['COU_ID'] .': '.  $data['COU_DESC'], '<br>',
		$data['REC_ID'] . ': ' . $data['REC_NAME_'.Yii::app()->session['lang']], '<br>',
		$data['STE_STEP_NO'] . '. ', $data['ING_ID'] .':' . $data['ING_NAME_'.Yii::app()->session['lang']], ' ',$data['STE_GRAMS']. 'g(normal) ', sprintf('%1.2f',$data['ing_amount']) . 'g(effective)';
		
		?>
		</div>
	</div>
	
	<div class="clearfix"></div>
</div>