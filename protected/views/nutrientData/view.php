<?php
$this->breadcrumbs=array(
	'Nutrient Datas'=>array('index'),
	$model->NUT_ID,
);

$this->menu=array(
	array('label'=>'List NutrientData', 'url'=>array('index')),
	array('label'=>'Create NutrientData', 'url'=>array('create')),
	array('label'=>'Update NutrientData', 'url'=>array('update', 'id'=>$model->NUT_ID)),
	array('label'=>'Delete NutrientData', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->NUT_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NutrientData', 'url'=>array('admin')),
);
?>

<?php
	if (isset(Yii::app()->session['Ingredients']) && isset(Yii::app()->session['Ingredients']['model'])){
		$back_url = array('ingredients/advanceSearch');
	} else {
		$back_url = array('ingredients/search');
	}
	echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); 
	
	if ($ingredientName != null){
		echo '<h1>'. $ingredientName .'</h1>';
	} else {
		echo '<h1>'. $model->NUT_DESC .'</h1>';
	}
?>
<?php
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
		'%',
		'kcal/100 g',
		'%',
		'%',
		'%',
		'%',
		'%',
		'%',
	);
	$units[1] = array(
		'%',
		'%',
		'%',
		'mg/100 g',
		'%',
	);
	$units[3] = array(
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'μg/100 g',
		'μ/100 g',
		'μ/100 g',
		'μ dietary folate equivalents/100 g',
		'mg/100 g',
		'μ/100 g',
		'IU/100 g',
		'μ retinol activity equivalents/100g',
		'μ/100 g',
		'μ/100 g',
		'μ/100 g',
		'μ/100 g',
		'μ/100 g',
		'μ/100 g',
		'alpha-tocopherol',
		'μ/100 g',
		'IU/100 g',
		'phylloquinone',
	);
	$units[2] = array(
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'mg/100 g',
		'μg/100 g',
	);
	
	echo '<div class="nutrientTable">';
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
				echo '<span class="value">'; printf('%1.2f',$model->$nut_field); echo '</span>';
				echo '<span class="unit">' . $units[$group][$field] . '</span>';
				echo '</div>';
			}
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
?>
<div class="clearfix"></div>
<?php echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center'));  ?>
