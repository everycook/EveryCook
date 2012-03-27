<div class="resultArea">
	<!-- STL show image -->
	<?php echo CHtml::link(CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data['ING_ID'], 'ext'=>'png')), '', array('class'=>'ingredient', 'alt'=>$data['ING_PICTURE_AUTH'], 'title'=>$data['ING_PICTURE_AUTH'])), array('view', 'id'=>$data['ING_ID'])); ?>
	
	<?php
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->NUTRIENT_DATA_CHOOSE, $data['ING_ID'], array('class'=>'f-right button IngredientSelect'));
	} else {
		?>
		<div class="options">
			<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['ING_ID']), array('class'=>'delicious backpic', 'title'=>$this->trans->INGREDIENTS_DELICIOUS)); ?>
			<?php echo CHtml::link('&nbsp;', array('recipes/search', 'ing_id'=>$data['ING_ID']), array('class'=>'cookwith backpic', 'title'=>$this->trans->INGREDIENTS_COOK_WITH)); ?>
			<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['ING_ID']), array('class'=>'disgusting backpic last','title'=>$this->trans->INGREDIENTS_DISGUSTING)); ?>
		</div>
		<div class="details">
			<div>
				<?php echo CHtml::link('&nbsp;', array('nutrientData/view', 'id'=>$data['NUT_ID']), array('class'=>'nutrientDataOpen backpic', 'title'=>$this->trans->INGREDIENTS_VIEW_FOOD)); ?>
				<?php echo CHtml::link('&nbsp;', array('products/search', 'ing_id'=>$data['ING_ID']), array('class'=>'productSearch backpic','title'=>$this->trans->INGREDIENTS_VIEW_FOOD)); ?>
			</div>
		</div>
		<?php
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($data['ING_TITLE_'.Yii::app()->session['lang']]), array('view', 'id'=>$data['ING_ID'])); ?>
		</div>
		
		<div class="nutrientInfo">
			<?php if ($data['NUT_ID']){ ?>
			<span><strong><?php echo CHtml::encode($this->trans->INGREDIENTS_FETT); ?>:</strong>
			<?php echo CHtml::encode($data['NUT_CHOLINE']); ?></span>
			
			<span><strong><?php echo CHtml::encode($this->trans->INGREDIENTS_ENERGY); ?>:</strong>
			<?php echo CHtml::encode($data['NUT_ENERG']); ?></span>
			
			<span><strong><?php echo CHtml::encode($this->trans->INGREDIENTS_PROTEIN); ?>:</strong>
			<?php echo CHtml::encode($data['NUT_PROT']); ?></span>
			<?php } else {
				echo '&nbsp;';
			}?>
		</div>
		<div class="shopInfo">
			<?php if($data['pro_count'] != 0 || $data['sto_count'] != 0){ ?>
			<span><?php printf(CHtml::encode($this->trans->INGREDIENTS_PRODUCTS_IN_SHOPS), $data['pro_count'], $data['sto_count']); ?></span>
			<?php } else {
				echo '&nbsp;';
			} ?>
		</div>
		<div class="prodInfo">
<?php /*
		echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_NUTRIENT) .':</strong> ' . CHtml::link(CHtml::encode($data['NUT_DESC']), array('nutrientData/view', 'id'=>$data['NUT_ID'])) ."</span>\n";
	*/ 
		echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_GROUP) .':</strong> ' . CHtml::encode($data['GRP_DESC_'.Yii::app()->session['lang']]) ."</span>\n";
		if ($data['SUBGRP_DESC_'.Yii::app()->session['lang']] != ''){
			echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_SUBGROUP) .':</strong> ' . CHtml::encode($data['SUBGRP_DESC_'.Yii::app()->session['lang']]) ."</span>\n";
		}
		echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_CONVENIENCE) .':</strong> ' . CHtml::encode($data['CONV_DESC_'.Yii::app()->session['lang']]) ."</span>\n";
		echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_STORABILITY) .':</strong> ' . CHtml::encode($data['STORAB_DESC_'.Yii::app()->session['lang']]) ."</span>\n";
		echo '<span><strong>' . CHtml::encode($this->trans->INGREDIENTS_STATE) .':</strong> ' . CHtml::encode($data['STATE_DESC_'.Yii::app()->session['lang']]) ."</span>\n";
		?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<?php
	/*
	
		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_ID')); ?>:</strong>
		<?php echo CHtml::link(CHtml::encode($data->ING_ID), array('view', 'id'=>$data->ING_ID)); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('PRF_UID')); ?>:</strong>
		<?php echo CHtml::encode($data->PRF_UID); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_CREATED')); ?>:</strong>
		<?php echo CHtml::encode($data->ING_CREATED); ?>
		<br />

		<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_CHANGED')); ?>:</strong>
		<?php echo CHtml::encode($data->ING_CHANGED); ?>
		<br />
		
	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_STATE')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_STATE); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_CONVENIENCE')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_CONVENIENCE); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_STORABILITY')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_STORABILITY); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_DENSITY')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_DENSITY); ?>
	<br />
	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_PICTURE')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_PICTURE); ?><br />
	
	
	<!-- STL show image -->
	<?php echo CHtml::image($this->createUrl('ingredients/displaySavedImage', array('id'=>$data->ING_ID)), '', array('class'=>'ingredient')); ?><br />
	
	
	
	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_PICTURE_AUTH')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_PICTURE_AUTH); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_TITLE_EN')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_TITLE_EN); ?>
	<br />

	<strong><?php echo CHtml::encode($data->getAttributeLabel('ING_TITLE_DE')); ?>:</strong>
	<?php echo CHtml::encode($data->ING_TITLE_DE); ?>
	<br />

	*/ ?>

</div>