<?php
class ESelect2 extends CWidget
{
	// @ string the id of the widget, since version 1.6
	public $id;
	// @ string the taget element on DOM
	public $target;
	// @ array of config settings for select2
	public $config=array();
	
	// function to init the widget
	public function init()
	{
		// if not informed will generate Yii defaut generated id, since version 1.6
		if(!isset($this->id))
			$this->id=$this->getId();
		// publish the required assets
		$this->publishAssets();
	}
	
	// function to run the widget
    public function run()
    {
		$config = CJavaScript::encode($this->config);
		Yii::app()->clientScript->registerScript($this->getId(), "
			$('$this->target').select2($config);
		");
	}
	
	// function to publish and register assets on page 
	public function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			Yii::app()->clientScript->registerCoreScript('jquery');
			
			//Yii::app()->clientScript->registerScriptFile($baseUrl . '/select2.min.js', CClientScript::POS_HEAD);
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/select2.js', CClientScript::POS_HEAD);
			$language = strtolower(substr(Yii::app()->session['lang'], 0, 2));
			if ($language != 'en'){
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/select2_locale_' . $language . '.js', CClientScript::POS_HEAD);
			}
			Yii::app()->clientScript->registerCssFile($baseUrl . '/select2.css');
		} else {
			throw new Exception('ESelect2 - Error: Couldn\'t find assets to publish.');
		}
	}
}