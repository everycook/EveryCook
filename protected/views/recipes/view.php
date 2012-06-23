<?php
$this->breadcrumbs=array(
	'Recipes'=>array('index'),
	$model->REC_ID,
);

$this->menu=array(
	array('label'=>'List Recipes', 'url'=>array('index')),
	array('label'=>'Create Recipes', 'url'=>array('create')),
	array('label'=>'Update Recipes', 'url'=>array('update', 'id'=>$model->REC_ID)),
	array('label'=>'Delete Recipes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->REC_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Recipes', 'url'=>array('admin')),
);

$this->mainButtons = array(
	array('label'=>$this->trans->GENERAL_EDIT, 'link_id'=>'middle_single', 'url'=>array('recipes/update',$this->getActionParams())),
);
?>

<div class="detailView">
	<?php
	if (isset(Yii::app()->session['Recipe'])){
		if (isset(Yii::app()->session['Recipe']['model'])){
			echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/advancesearch'), array('class'=>'button'));
		} else {
			echo CHtml::link($this->trans->SEARCH_BACK_TO_RESULTS, array('recipes/search'), array('class'=>'button'));
		}
	}
	?><br>
	<br>
	<div class="options">
		<?php echo CHtml::link('+', array('user/addrecipes', 'id'=>$model->REC_ID), array('class'=>'button addRecipe', 'title'=>$this->trans->RECIPES_ADD)); ?><br>
		<div class="ingredients">
			<?php echo CHtml::encode($this->trans->RECIPES_INGREDIENTS_NEEDED); ?>
			<ul>
			<?php foreach($model->steps as $step){
				if ($step->ingredient != null){
					echo '<li>' . CHtml::link($step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']), array('ingredients/view', 'id'=>$step->ingredient->ING_ID), array('title'=>$this->trans->RECIPES_TOOLTIP_OPEN_INGREDIENT)) . '</li>';
				}
			}
			?>
			</ul>
		</div>
		<?php echo CHtml::link('&nbsp;', array('delicious', 'id'=>$model->REC_ID), array('class'=>'delicious_big noAjax backpic last', 'title'=>$this->trans->GENERAL_DELICIOUS)); ?>
		<?php echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$model->REC_ID), array('class'=>'disgusting_big noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING)); ?>
	</div>
	<div class="details">
		<div class="name">
			<?php echo CHtml::link(CHtml::encode($model->__get('REC_NAME_' . Yii::app()->session['lang'])), array('view', 'id'=>$model->REC_ID)); ?>
		</div>
		<?php echo CHtml::image($this->createUrl('recipes/displaySavedImage', array('id'=>$model->REC_ID, 'ext'=>'.png')), '', array('class'=>'recipe', 'alt'=>$model->REC_IMG_AUTH, 'title'=>$model->REC_IMG_AUTH)); ?><br />
		
		<b><?php echo CHtml::encode($this->trans->RECIPES_TYPE); ?>:</b>
		<?php echo CHtml::encode($model->recipeTypes->__get('RET_DESC_' . Yii::app()->session['lang'])); ?>
		<br /><br />
		
		<?php 
			$i = 1;
			foreach($model->steps as $step){
				echo '<div class="step">';
				echo '<span class="stepNo">' . $i . '.</span> ';
				if ($step->stepType){
					echo '<span class="stepType">' . $step->stepType->__get('STT_DESC_' . Yii::app()->session['lang']) . ':</span> ';
				}
				if (isset($step->action) && $step->action != null){
					$text = $step->action->__get('ACT_DESC_' . Yii::app()->session['lang']);
					if (isset($step->ingredient) && $step->ingredient != null){
						$replText = '<span class="igredient">' . $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']) . '</span> ';
						if ($step->STE_GRAMS){
							$replText .= '<span class="amount">' . $step->STE_GRAMS . 'g' . '</span> ';
						}
						$text = str_replace('#objectofaction#', $replText, $text);
					}
					echo '<span class="action">' . $text . '</span>';
				}
				echo '</div>';
				$i++;
			}
		?>
		<br />
		<br />
	</div>
	<div class="clearfix"></div>
	
	<?php
	if ($nutrientData != null){
		/*
		'NUT_ID',
		'NUT_DESC',
		*/
		$fields = array();
		$fields[0] = array(
			'NUT_WATER',
			'NUT_ENERG',
			'NUT_PROT',
			'NUT_LIPID',
			'NUT_ASH',
			'NUT_CARB',
			'NUT_FIBER',
			'NUT_SUGAR',
		);
		$fields[1] = array(
			'NUT_FA_SAT',
			'NUT_FA_MONO',
			'NUT_FA_POLY',
			'NUT_CHOLEST',
			'NUT_REFUSE',
		);
		$fields[3] = array(
			'NUT_VIT_C',
			'NUT_THIAM',
			'NUT_RIBOF',
			'NUT_NIAC',
			'NUT_PANTO',
			'NUT_VIT_B6',
			'NUT_FOLAT_TOT',
			'NUT_FOLIC',
			'NUT_FOLATE_FD',
			'NUT_FOLATE_DFE',
			'NUT_CHOLINE',
			'NUT_VIT_B12',
			'NUT_VIT_A_IU',
			'NUT_VIT_A_RAE',
			'NUT_RETINOL',
			'NUT_ALPHA_CAROT',
			'NUT_BETA_CAROT',
			'NUT_BETA_CRYPT',
			'NUT_LYCOP',
			'NUT_LUT_ZEA',
			'NUT_VIT_E',
			'NUT_VIT_D',
			'NUT_VIT_D_IU',
			'NUT_VIT_K',
		);
		$fields[2] = array(
			'NUT_CALC',
			'NUT_IRON',
			'NUT_MAGN',
			'NUT_PHOS',
			'NUT_POTAS',
			'NUT_SODIUM',
			'NUT_ZINC',
			'NUT_COPP',
			'NUT_MANG',
			'NUT_SELEN',
		);
		
		
		$units = array();
		$units[0] = array(
			'g',
			'kcal',
			'g',
			'g',
			'g',
			'g',
			'g',
			'g',
		);
		$units[1] = array(
			'g',
			'g',
			'g',
			'mg',
			'g',
		);
		$units[3] = array(
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'µg',
			'µ',
			'µ',
			'µ dietary folate equivalents',
			'mg',
			'µ',
			'IU',
			'µ retinol activity equivalents',
			'µ',
			'µ',
			'µ',
			'µ',
			'µ',
			'µ',
			'alpha-tocopherol',
			'µ',
			'IU',
			'phylloquinone',
		);
		$units[2] = array(
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'mg',
			'µg',
		);
		
		echo '<div class="f-left">';
		for($group=0; $group<count($fields); $group++){
			if ($group == 3){
				echo '</div>';
				echo '<div class="f-left">';
			}
			echo '<div class="nutrientDataGroup">';
				for($field=0; $field<count($fields[$group]); $field++){
					$nut_field = $fields[$group][$field];
					echo '<div class="nutrient_row' . (($field == count($fields[$group])-1)?' last':'') . '">';
					echo '<span class="name">' . CHtml::encode($this->trans->__get('FIELD_'.$nut_field)) . '</span>';
					echo '<span class="value">'; printf('%1.2f',$nutrientData->$nut_field); echo '</span>';
					echo '<span class="unit">' . $units[$group][$field] . '</span>';
					echo '</div>';
				}
			echo '</div>';
		}
		echo '</div>';
	}
?>
<div class="clearfix"></div>

</div>

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'REC_ID',
		'REC_CREATED',
		'REC_CHANGED',
		'REC_IMG',
		'REC_IMG_AUTH',
		'RET_ID',
		'REC_NAME_EN',
		'REC_NAME_DE',
	),
)); */ ?>
