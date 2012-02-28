<div id="container">
	<div id="content_left">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div id="content_right">
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