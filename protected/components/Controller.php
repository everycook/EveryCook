<?php
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
		if (Yii::app()->session['Ingredient'] && Yii::app()->session['Ingredient']['time']){
			$newIngSearch=array('newSearch'=>Yii::app()->session['Ingredient']['time']);
		} else {
			$newIngSearch=array();
		}
		if (Yii::app()->session['Recipe'] && Yii::app()->session['Recipe']['time']){
			$newRecSearch=array('newSearch'=>Yii::app()->session['Recipe']['time']);
		} else {
			$newRecSearch=array();
		}
		
		$this->mainButtons = array(
			array('label'=>$this->trans->BOTL_CONTENT, 'link_id'=>'left', 'url'=>array('recipes/search',$newRecSearch)),
			array('label'=>$this->trans->BOTR_CONTENT, 'link_id'=>'right', 'url'=>array('site/page', array('view'=>'about'))),
			array('label'=>$this->trans->BOTM_CONTENT, 'link_id'=>'middle', 'url'=>array('ingredients/search',$newIngSearch)),
		);
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
			$this->trans->JUMPTO_SHOP_CREATOR => Yii::app()->createUrl('stores/create',array()),
			$this->trans->JUMPTO_SHOP_FINDER => Yii::app()->createUrl('stores/storeFinder',array()),
			$this->trans->JUMPTO_FAVORITE_FOOD => Yii::app()->createUrl('profiles/favoriteFood',array()),
			$this->trans->JUMPTO_FAVORITE_RECIPES => Yii::app()->createUrl('profiles/favoriteRecipes',array()),
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
		if ($this->saveLastAction){
			if (count($_POST) == 0 && $this->route != '' && (substr($this->route, 0, 5) != 'site/')){
				Yii::app()->session['LAST_ACTION'] = $this->route;
				$params = $this->getActionParams();
				if (isset($params['ajaxPaging'])){ // remove "ajaxPaging"-param
					unset($params['ajaxPaging']);
				}
				Yii::app()->session['LAST_ACTION_PARAMS'] = $params;
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
			$url = Yii::app()->session['AFTER_SAVE_ACTION'];
			unset(Yii::app()->session['AFTER_SAVE_ACTION']);
			unset(Yii::app()->session['AFTER_SAVE_FOR']);
			
			if($this->useAjaxLinks && $this->getIsAjaxRequest()){
				echo "{hash:'" . $this->urlToHash($url). "'}";
			} else {
				$this->redirect($url);
			}
			return;
		}
		
		if($this->useAjaxLinks && $this->getIsAjaxRequest()){
			echo "{hash:'" . $this->createUrlHash($url[0], array_splice($url,1)) . "'}";
		} else {
			$this->redirect($url);
		}
	}
	
	public function showLastAction(){
		if (isset(Yii::app()->session['LAST_ACTION']) && isset(Yii::app()->session['LAST_ACTION_PARAMS'])){
			if($this->useAjaxLinks && $this->getIsAjaxRequest()){
				echo "{hash:'" . $this->createUrlHash(Yii::app()->session['LAST_ACTION'], Yii::app()->session['LAST_ACTION_PARAMS']). "'}";
			} else {
				$this->redirect(array_merge(array(Yii::app()->session['LAST_ACTION']), Yii::app()->session['LAST_ACTION_PARAMS']));
			}
		} else if (isset(Yii::app()->session['LAST_ACTION']) && isset(Yii::app()->session['LAST_ACTION_PARAMS'])){
			if($this->useAjaxLinks && $this->getIsAjaxRequest()){
				echo "{hash:'" . $this->createUrlHash(Yii::app()->session['LAST_ACTION']). "'}";
			} else {
				$this->redirect(array(Yii::app()->session['LAST_ACTION']));
			}
		} else {
			if($this->useAjaxLinks && $this->getIsAjaxRequest()){
				echo "{hash:'" . $this->createUrlHash('site/index') . "'}";
			} else {
				$this->redirect(array('site/index'));
			}
		}
	}
	
	public function renderAjax($view, $data=null, $ajaxLayout=null)
	{
		if (!isset(Yii::app()->session['ajaxSession'])){
			Yii::app()->session['ajaxSession'] = true;
		}
		if (isset($_GET['ajaxPaging']) && $_GET['ajaxPaging']){
			if($this->beforeRender($view)) {
				$output=$this->renderPartial('paging',$data,true);
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
				Yii::app()->clientscript->registerCoreScript('yii');
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
				Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/mealplaner.js', CClientScript::POS_HEAD);
				//Yii::app()->clientscript->registerScriptFile($request_baseurl . '/js/jquery.slider.min.js', CClientScript::POS_HEAD);
				//Yii::app()->clientscript->registerCssFile($request_baseurl . '/css/jquery.slider.min.css');
				Yii::app()->clientscript->registerCoreScript('yiiactiveform');
				
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
