<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2><?php echo $this->trans->GENERAL_ERRORPAGE_TITLE . ' ' . $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>