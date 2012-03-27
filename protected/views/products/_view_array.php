<div class="resultArea">
	<?php echo CHtml::link(CHtml::image($this->createUrl('products/displaySavedImage', array('id'=>$data['PRO_ID'], 'ext'=>'png')), '', array('class'=>'product', 'alt'=>$data['PRO_PICTURE_COPYR'], 'title'=>$data['PRO_PICTURE_COPYR'])), array('view', 'id'=>$data['PRO_ID'])); ?>
	
	
	<div class="options">
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['PRO_ID']), array('class'=>'delicious backpic', 'title'=>$this->trans->RECIPES_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['PRO_ID']), array('class'=>'disgusting backpic','title'=>$this->trans->RECIPES_DISGUSTING)); ?><br>
	</div>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['PRO_NAME_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['PRO_ID'])); ?>
		</div>
		
		<?php
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_AVAILABLE_AT) .':</strong> ' . CHtml::encode($data['avail_store']) ."</span><br/>\n";
		echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_YOU, $data['dist_you']); echo '</span><br/>'."\n";
		echo '<span>'; printf($this->trans->PRODUCTS_DISTANCE_TO_HOME, $data['dist_home']); echo '</span><br/>'."\n";
		
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_SUSTAINABILITY) .':</strong> ' . CHtml::encode($data['ECO_DESC_'.Yii::app()->session['lang']]) ."</span><br/>\n";
		echo '<span><strong>' . CHtml::encode($this->trans->PRODUCTS_ETHICAL) .':</strong> ' . CHtml::encode($data['ETH_DESC_'.Yii::app()->session['lang']]) ."</span><br/>\n";
		echo '<span>'; printf($this->trans->PRODUCTS_PACKAGE_SIZE, $data['PRO_PACKAGE_GRAMMS']); echo '</span><br/>'."\n";
		
/*
	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data['PRO_ID']), array('view', 'id'=>$data['PRO_ID'])); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_BARCODE')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_BARCODE']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PACKAGE_GRAMMS')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_PACKAGE_GRAMMS']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</b>
	<?php echo CHtml::encode($data['ING_ID']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ECO')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_ECO']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_ETHIC')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_ETHIC']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PICTURE')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_PICTURE']); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PRO_PICTURE_COPYR')); ?>:</b>
	<?php echo CHtml::encode($data['PRO_PICTURE_COPYR']); ?>
	<br />

	*/ ?>
	</div>
</div>