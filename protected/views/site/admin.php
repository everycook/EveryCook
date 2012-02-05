<?php
$this->menu=array(
	array('label'=>'ecology', 'url'=>array('ecology/index')),
	array('label'=>'ethicalCriteria', 'url'=>array('ethicalCriteria/index')),
	array('label'=>'ingredientConveniences', 'url'=>array('ingredientConveniences/index')),
	array('label'=>'ingredientStates', 'url'=>array('ingredientStates/index')),
	array('label'=>'nutrientData', 'url'=>array('nutrientData/index')),
	array('label'=>'producers', 'url'=>array('producers/index')),
	array('label'=>'profiles', 'url'=>array('profiles/index')),
	array('label'=>'recipes', 'url'=>array('recipes/index')),
	array('label'=>'recipeTypes', 'url'=>array('recipeTypes/index')),
	array('label'=>'shoplists', 'url'=>array('shoplists/index')),
	array('label'=>'storability', 'url'=>array('storability/index')),
	array('label'=>'stores', 'url'=>array('stores/index')),
	array('label'=>'subgroupNames', 'url'=>array('subgroupNames/index')),
	array('label'=>'suppliers', 'url'=>array('suppliers/index'))
);

$this->pageTitle=Yii::app()->name; ?>



<div class="container">
	<div class="span-19">
		<div id="content">
			<h1>Welcome to admin page</h1>
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	</div>
</div>