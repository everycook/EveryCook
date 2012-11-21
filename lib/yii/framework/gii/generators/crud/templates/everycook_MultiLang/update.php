<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$transKey = strtoupper($this->modelClass);
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'View <?php echo $this->modelClass; ?>', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<?php echo '<h1><?php printf($this->trans->TITLE_' . $transKey . '_UPDATE, $model->' . $this->tableSchema->primaryKey . '); ?></h1>'; ?>

<?php echo "<?php echo \$this->renderPartial('_form', array(\r\n\t'model'=>\$model,\r\n\t)); ?>"; ?>