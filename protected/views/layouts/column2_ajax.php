<div id="container">
	<div id="content_left">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div id="content_right">
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
	<div class="clearfix"></div>
</div>
<div id="mainButtons">
<?php $this->widget('ext.widgets.MenuWidget',array(
		'items'=>$this->mainButtons,
	));
?>
	<div class="clearfix"></div>
</div>