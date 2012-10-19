<?php
if ($this->route != 'site/index'){
	Functions::browserCheck();
}
?>
<div id="container">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div id="mainButtons">
<?php $this->widget('ext.widgets.MenuWidget',array(
		'items'=>$this->mainButtons,
	));
?>
	<div class="clearfix"></div>
</div>