<?php
$this->breadcrumbs=array(
	'Ethical Criterias',
);

$this->menu=array(
	array('label'=>'Create EthicalCriteria', 'url'=>array('create')),
	array('label'=>'Manage EthicalCriteria', 'url'=>array('admin')),
);
?>

<h1>Ethical Criterias</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
