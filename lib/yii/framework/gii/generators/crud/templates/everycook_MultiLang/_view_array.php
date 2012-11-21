<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
 
$primaryKey = $this->tableSchema->primaryKey;
$tablePrefix = substr($primaryKey, 0, strpos($primaryKey,'_'));
?>

<div class="resultArea">
<?php echo "<?php\n"; ?>
	if ($this->isFancyAjaxRequest){
		echo CHtml::link($this->trans->GENERAL_SELECT, $data['<?php echo $primaryKey; ?>'], array('class'=>'f-right button <?php echo $this->model; ?>Select'));
	} else {
		echo '<div class="options">';
			echo CHtml::link('&nbsp;', array('delicious', 'id'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'delicious noAjax backpic', 'title'=>$this->trans->GENERAL_DELICIOUS));
			//echo CHtml::link('&nbsp;', array('<controller>/<page>', '<?php echo $primaryKey; ?>'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'cookwith backpic', 'title'=>$this->trans->???));
			echo CHtml::link('&nbsp;', array('disgusting', 'id'=>$data['<?php echo $primaryKey; ?>']), array('class'=>'disgusting noAjax backpic last','title'=>$this->trans->GENERAL_DISGUSTING));
		echo '</div>';
	}
	?>
	
	<div class="data">
		<div class="name">
			<?php echo "<?php\n"; ?>
			if ($this->isFancyAjaxRequest){
				echo CHtml::encode($data['<?php echo $tablePrefix; ?>_DESC_' . Yii::app()->session['lang']]);
			} else {
				echo CHtml::link(CHtml::encode($data['<?php echo $tablePrefix; ?>_DESC_' . Yii::app()->session['lang']]), array('view', 'id'=>$data['<?php echo $primaryKey; ?>']));
			}
			?>
		</div>
		
<?php
		//$count=0;
		echo "\t\t<?php /*\n";
		echo "\t\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
		echo "\t\t<?php echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}), array('view', 'id'=>\$data->{$this->tableSchema->primaryKey})); ?>\n\t\t<br />\n\n";
		
		foreach($this->tableSchema->columns as $column)
		{
			if($column->isPrimaryKey)
				continue;
		//	if(++$count==7)
		//		echo "\t<?php /*\n";
			echo "\t\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
			echo "\t\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t\t<br />\n\n";
		}
		//if($count>=7)
			echo "\t\t*/ ?>\n";
		?>
	</div>
	<div class="clearfix"></div>
</div>