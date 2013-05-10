<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	
	public $errorText = '';
	public $errorFields = array();
	
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $mainButtons = array();
	
	public $useAjaxLinks = true;
	
	public $saveLastAction = true;
	
	public $debug = false;
	
	public function useDefaultMainButtons(){
		$mainButtons = array();
		/*
		if (Yii::app()->session['Ingredients'] && Yii::app()->session['Ingredients']['time']){
			$newIngSearch=array('newSearch'=>Yii::app()->session['Ingredients']['time']);
		} else {
			$newIngSearch=array();
		}
		if (Yii::app()->session['Recipes'] && Yii::app()->session['Recipes']['time']){
			$newRecSearch=array('newSearch'=>Yii::app()->session['Recipes']['time']);
		} else {
			$newRecSearch=array();
		}
		$cmsLink = '/cms/' . strtolower(substr(Yii::app()->session['lang'],0,2)) . '/';
		$this->mainButtons = array(
			array('label'=>$this->trans->BOTL_CONTENT, 'link_id'=>'left', 'url'=>array('recipes/search',$newRecSearch)),
			array('label'=>$this->trans->BOTR_CONTENT, 'link_id'=>'right', 'url'=>$cmsLink),
			array('label'=>$this->trans->BOTM_CONTENT, 'link_id'=>'middle', 'url'=>array('ingredients/search',$newIngSearch)),
		);
		*/
	}
	
	public $isFancyAjaxRequest = false;
	
	public $validSearchPerformed = false;
	
	public function getIsAjaxRequest(){
		$params = $this->getActionParams();
		if (isset($params['noajax']) && $params['noajax']){
			return false;
		}
		return $this->isFancyAjaxRequest || Yii::app()->request->isAjaxRequest || (isset($params['ajaxform']) && $params['ajaxform']);
	}
	
	//protected $allLanguages = array('EN_GB'=>'English','DE_CH'=>'Deutsch','FR_FR'=>'Francais');
	protected $allLanguages = array('EN_GB'=>'English','DE_CH'=>'Deutsch');
	
	public function getAllLanguages(){
		return $allLanguages;
	}
	
	public function getJumpTos(){
		return array(
			$this->trans->JUMPTO_SHOP_CREATOR => array(Yii::app()->createUrl('/stores/create',array('newModel'=>time())), ' newModelTime'),
			$this->trans->JUMPTO_SHOP_FINDER => array(Yii::app()->createUrl('/stores/storeFinder',array())),
			$this->trans->JUMPTO_LIKE_INGREDIENT => array(Yii::app()->createUrl('/ingredients/showLike',array())),
			$this->trans->JUMPTO_LIKE_PRODUCTS => array(Yii::app()->createUrl('/products/showLike',array())),
			$this->trans->JUMPTO_LIKE_RECIPES => array(Yii::app()->createUrl('/recipes/showLike',array())),
			$this->trans->JUMPTO_MEALLIST => array(Yii::app()->createUrl('/meals/mealList',array())),
			$this->trans->JUMPTO_MEALPLANNER => array(Yii::app()->createUrl('/meals/mealPlanner',array('newModel'=>time())), ' newModelTime'),
			$this->trans->JUMPTO_SHOPPINGLISTS => array(Yii::app()->createUrl('/shoppinglists/index',array())),
		);
	}
	
	//TODO change this to shared memory, because it need to load this for each pagereload, also if it's static....
	protected static $trans=null;
	public function getTrans()
	{
		return self::$trans;
	}
	
	protected function beforeAction($action)
	{
		if (isset($_GET['setDebug'])){
			Yii::app()->session['debugEnabled'] = $_GET['setDebug'];
		}
		if (isset(Yii::app()->session['debugEnabled'])){
			$this->debug = Yii::app()->session['debugEnabled'];
		}
		if ($this->trans===null){
			// if user isGuest, take session cookie
			if (Yii::app()->user->isGuest){
				// if no language is defined, set default language
				if (!isset(Yii::app()->session['lang'])){
					if (Yii::app()->request->preferredLanguage !== false){
						//get best matching language
						$langToUse = 'EN_GB';
						$pref = strtoupper(Yii::app()->request->preferredLanguage);
						$lang_part = substr($pref,0,2);
						foreach ($this->allLanguages as $key=>$value){
							if ($pref == $key){
								$langToUse = $key;
								break;
							} else if ($lang_part == substr($key,0,2)){
								$langToUse = $key;
							}
						}
						Yii::app()->session['lang'] = $langToUse;
					} else {
						Yii::app()->session['lang'] = 'EN_GB';
					}
				}
				//echo 'load text, guest';
				self::$trans = new Translations(Yii::app()->session['lang']);
				Yii::app()->setLanguage(Yii::app()->session['lang']);
			}

			// if user is registeredUser, load its saved language setting
			else {
				//echo 'load text, user';
				self::$trans=new Translations(Yii::app()->user->lang);
				Yii::app()->session['lang'] = Yii::app()->user->lang;
				Yii::app()->setLanguage(Yii::app()->user->lang);
			}
		}
		
		if($this->trans===null)
			throw new CHttpException(404,'Error loading translation texts.');
		$params = $this->getActionParams();
		if (isset($params) && isset($params['afterSave'])){
			Yii::app()->session['AFTER_SAVE_ACTION'] = urldecode($params['afterSave']);
			Yii::app()->session['AFTER_SAVE_FOR'] = $this->route;
		}
		return parent::beforeAction($action);
	}
	
	protected function afterAction($action){
		if ($this->saveLastAction && !$this->isFancyAjaxRequest){
			if (count($_POST) == 0 && $this->route != '' && (substr($this->route, 0, 5) != 'site/')){
				Yii::app()->session['LAST_ACTION'] = $this->route;
				$params = $this->getActionParams();
				if (isset($params['ajaxPaging'])){ // remove "ajaxPaging"-param
					unset($params['ajaxPaging']);
				}
				if (isset($params['noPrev'])){ // remove ajaxPaging "noPrev"-param
					unset($params['noPrev']);
				}
				if (isset($params['noNext'])){ // remove ajaxPaging "noNext"-param
					unset($params['noNext']);
				}
				Yii::app()->session['LAST_ACTION_PARAMS'] = $params;
				if (!preg_match('/(create|update|assign|mealPlanner)/i', $this->route)){
					Yii::app()->session['LAST_ACTION_NOT_CREATE'] = $this->route;
					Yii::app()->session['LAST_ACTION_NOT_CREATE_PARAMS'] = $params;
				}
			}
			if (isset(Yii::app()->session['AFTER_SAVE_FOR']) && Yii::app()->session['AFTER_SAVE_FOR'] != $this->route){
				unset(Yii::app()->session['AFTER_SAVE_ACTION']);
				unset(Yii::app()->session['AFTER_SAVE_FOR']);
			}
		}
	}
	
	public function getActionParams(){
		$params = parent::getActionParams();
		if (isset($params['_'])){ // remove "noCache"-param
			unset($params['_']);
		}
		return $params;
	}
	
	/**
	 * url = array(route, param=>value, param=>value);
	 */
	public function forwardAfterSave($url){
		if (isset(Yii::app()->session['AFTER_SAVE_FOR']) && Yii::app()->session['AFTER_SAVE_FOR'] == $this->route){
			$url = '/' . Yii::app()->session['AFTER_SAVE_ACTION'];
			unset(Yii::app()->session['AFTER_SAVE_ACTION']);
			unset(Yii::app()->session['AFTER_SAVE_FOR']);
			
			if($this->isFancyAjaxRequest){
				echo "{fancy:'" . $url. "'}";
			} else if($this->useAjaxLinks && $this->getIsAjaxRequest()){
				echo "{hash:'" . $this->urlToHash($url). "'}";
			} else {
				$this->redirect($url);
			}
			return;
		}
		
		$this->forwardTo($url);
	}
	
	public function forwardTo($urlparams){
		if($this->isFancyAjaxRequest){
			echo "{fancy:'" . Yii::app()->createUrl($urlparams[0], array_splice($urlparams,1)). "'}";
		} else  if($this->useAjaxLinks && $this->getIsAjaxRequest()){
			echo "{hash:'" . $this->createUrlHash($urlparams[0], array_splice($urlparams,1)) . "'}";
		} else {
			$this->redirect($urlparams);
		}
	}
	
	public function showLastAction(){
		if (isset(Yii::app()->session['LAST_ACTION']) && isset(Yii::app()->session['LAST_ACTION_PARAMS'])){
			$this->forwardTo(array_merge(array(Yii::app()->session['LAST_ACTION']), Yii::app()->session['LAST_ACTION_PARAMS']));
		} else if (isset(Yii::app()->session['LAST_ACTION']) && isset(Yii::app()->session['LAST_ACTION_PARAMS'])){
			$this->forwardTo(array(Yii::app()->session['LAST_ACTION']));
		} else {
			$this->forwardTo(array('/site/index'));
		}
	}

	public function showLastNotCreateAction(){
		if (isset(Yii::app()->session['LAST_ACTION_NOT_CREATE']) && isset(Yii::app()->session['LAST_ACTION_NOT_CREATE_PARAMS'])){
			$this->forwardTo(array_merge(array('/' . Yii::app()->session['LAST_ACTION_NOT_CREATE']), Yii::app()->session['LAST_ACTION_NOT_CREATE_PARAMS']));
		} else if (isset(Yii::app()->session['LAST_ACTION_NOT_CREATE'])){
			$this->forwardTo(array('/' . Yii::app()->session['LAST_ACTION_NOT_CREATE']));
		} else {
			$this->forwardTo(array('site/index'));
		}
	}
	
	public function renderAjax($view, $data=null, $ajaxLayout=null)
	{
		if (!isset(Yii::app()->session['ajaxSession'])){
			Yii::app()->session['ajaxSession'] = true;
		}
		if (isset($_GET['ajaxPaging']) && $_GET['ajaxPaging']){
			//$this->saveLastAction = false; //TODO: is this correct???
			$pagingView = 'paging';
			if (isset($_GET['ajaxPagingView']) && strlen($_GET['ajaxPagingView'])>0){
				$pagingView = $_GET['ajaxPagingView'];
				unset($_GET['ajaxPagingView']);
			}
			if($this->beforeRender($view)) {
				$output=$this->renderPartial($pagingView,$data,true);
				$this->afterRender($view,$output);
				
				$output=$this->processOutput($output);
				echo $output;
			}
		} else {
			if ($ajaxLayout==null){
				$ajaxLayout = $this->layout.'_ajax';
			}
			if($this->beforeRender($view)) {
				$output=$this->renderPartial($view,$data,true);
				if(($layoutFile=$this->getLayoutFile($ajaxLayout))!==false)
						$output=$this->renderFile($layoutFile,array('content'=>$output),true);

				$this->afterRender($view,$output);
				
				$output=$this->processOutput($output);
				
				if (Yii::app()->request->isAjaxRequest && !$this->isFancyAjaxRequest){
					$json = "{'title':'" . CHtml::encode($this->pageTitle) . "'}";
					echo strlen($json) . $json;
				}
				echo $output;
			}
		}
	}

	public function checkRenderAjax($view, $data=null, $ajaxLayout=null)
	{
		if($this->getIsAjaxRequest())
		{
			$this->renderAjax($view, $data, $ajaxLayout);
		}
		else
		{
			if($this->useAjaxLinks){
				Yii::app()->clientscript->registerCoreScript('bbq');
				Yii::app()->clientscript->registerCoreScript('jquery.ui');
				$baseurl = Yii::app()->clientscript->getPackageBaseUrl('jquery.ui');
				Yii::app()->clientscript->registerCssFile($baseurl . '/jui/css/base/jquery.ui.core.css');
				Yii::app()->clientscript->registerCssFile($baseurl . '/jui/css/base/jquery.ui.theme.css');
				Yii::app()->clientscript->registerCssFile($baseurl . '/jui/css/base/jquery.ui.datepicker.css');
				Yii::app()->clientscript->registerCssFile($baseurl . '/jui/css/base/jquery.ui.slider.css');
				Yii::app()->clientscript->registerCoreScript('yii');
				
				$transScript = Yii::app()->createUrl('site/trans', array('lang'=>Yii::app()->session['lang']));
				Yii::app()->clientscript->registerScriptFile($transScript, CClientScript::POS_HEAD);
				
				$fancyBox = new EFancyBox();
				$fancyBox->publishAssets();
				$request_baseurl = Yii::app()->request->baseUrl ;
				Yii::app()->clientscript->registerScriptFile($request_baseurl. '/js/jquery.iframe-post-form.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/ajax_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/hash_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/rowcontainer_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/design_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/iefix_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/jquery.Jcrop.min.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/imgcrop_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/map_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/mealplanner.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerCoreScript('yiiactiveform');
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/cookasisstant_handling.js', CClientScript::POS_HEAD);
				
				$ziiBaseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets'));
				Yii::app()->clientscript->registerScriptFile($ziiBaseScriptUrl.'/listview'.'/jquery.yiilistview.js',CClientScript::POS_END);
			}
			$this->render($view, $data);
		}
	}
	
	public function createUrlHash($route,$params=array(),$ampersand='&') {
		$url = parent::createUrl($route,$params,$ampersand);
		return $this->urlToHash($url);
	}
	
	public function urlToHash($url) {
		$pos = strpos($url, '/', 1);
		if ($pos !== false){
			$url = substr($url, $pos+1);
		}
		return $url;
	}
	
	public static function urlToUrlWithHash($url) {
		$pos = strpos($url, 'index.php/');
		if ($pos !== false){
			$url = substr($url, 0, $pos+9) . "#" . substr($url, $pos+10);
		}
		return $url;
	}
	public function init(){
		// register class paths for extension captcha extended
		Yii::$classMap = array_merge( Yii::$classMap, array(
			'CaptchaExtendedAction' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedAction.php',
			'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedValidator.php'
		));
	}
}
