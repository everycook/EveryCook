<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div id="index_div_mf_t">
		<span id="mf_t">
			<div id="content">
				<?php echo $content; ?>
			</div><!-- content -->
		</span>
	</div>
	<div id="index_div_mf">
		<span id="mf">
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
		</span>
	</div>
</div>
<?php $this->endContent(); ?>