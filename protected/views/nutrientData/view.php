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
	if (isset(Yii::app()->session['Ingredient']) && isset(Yii::app()->session['Ingredient']['model'])){
		$back_url = array('ingredients/advanceSearch');
	} else {
		$back_url = array('ingredients/search');
	}
	echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center')); 
	
	if ($ingredientName != null){
		echo '<h1>'. $ingredientName .'</h1>';
	}
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'NUT_ID',
		'NUT_DESC',
		'NUT_WATER',
		'NUT_ENERG',
		'NUT_PROT',
		'NUT_LIPID',
		'NUT_ASH',
		'NUT_CARB',
		'NUT_FIBER',
		'NUT_SUGAR',
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
		'NUT_FA_SAT',
		'NUT_FA_MONO',
		'NUT_FA_POLY',
		'NUT_CHOLEST',
		'NUT_REFUSE',
	),
)); ?>

<?php echo CHtml::link(CHtml::encode($this->trans->PRODUCTS_BACK_TO_INGREDIENTS), $back_url, array('class'=>'button f-center'));  ?>
