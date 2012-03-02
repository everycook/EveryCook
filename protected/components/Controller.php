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
	
	public function useDefaultMainButtons(){
		$this->mainButtons = array(
			array('label'=>'Rezept Suchen', 'link_id'=>'left', 'url'=>array('recipes/search',array('newSearch'=>true))),
			array('label'=>'Die Kochende Maschiene', 'link_id'=>'right', 'url'=>array('site/page', array('view'=>'about'))),
			array('label'=>'Essen Suchen', 'link_id'=>'middle', 'url'=>array('ingredients/search',array('newSearch'=>true))),
		);
	}
	
	public $isFancyAjaxRequest = false;
	
	public $validSearchPerformed = false;
	
	public function getIsAjaxRequest(){
		return $this->isFancyAjaxRequest || Yii::app()->request->isAjaxRequest;
	}
	
	public $trans=null;
	
	public $allLanguages=array('EN','DE');
	
	protected function beforeAction($action)
	{
		//TODO use language from current user
		if (!isset(Yii::app()->session['lang'])){
			Yii::app()->session['lang'] = 'DE';
		}
		$this->trans=InterfaceMenu::model()->findByPk(Yii::app()->session['lang']);
		if($this->trans===null)
			throw new CHttpException(404,'Error loading translation texts.');
		return parent::beforeAction($action);
	}
	
	public function renderAjax($view, $data=null, $ajaxLayout=null)
	{
		if ($ajaxLayout==null){
			$ajaxLayout = $this->layout.'_ajax';
		}
		if($this->beforeRender($view))
		{
			$output=$this->renderPartial($view,$data,true);
			if(($layoutFile=$this->getLayoutFile($ajaxLayout))!==false)
					$output=$this->renderFile($layoutFile,array('content'=>$output),true);

			$this->afterRender($view,$output);
			
			$output=$this->processOutput($output);
			echo $output;
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
				$fancyBox =  new EFancyBox();
				$fancyBox->publishAssets();
				Yii::app()->clientscript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.iframe-post-form.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile(Yii::app()->request->baseUrl . '/js/ajax_handling.js', CClientScript::POS_HEAD);
				Yii::app()->clientscript->registerScriptFile(Yii::app()->request->baseUrl . '/js/hash_handling.js', CClientScript::POS_HEAD);
			}
			$this->render($view, $data);
		}
	}
	
	/**
	 * Creates a relative URL for the specified action defined in this controller.
	 * @param string $route the URL route. This should be in the format of 'ControllerID/ActionID'.
	 * If the ControllerID is not present, the current controller ID will be prefixed to the route.
	 * If the route is empty, it is assumed to be the current action.
	 * If the controller belongs to a module, the {@link CWebModule::getId module ID}
	 * will be prefixed to the route. (If you do not want the module ID prefix, the route should start with a slash '/'.)
	 * @param array $params additional GET parameters (name=>value). Both the name and value will be URL-encoded.
	 * If the name is '#', the corresponding value will be treated as an anchor
	 * and will be appended at the end of the URL.
	 * @param string $ampersand the token separating name-value pairs in the URL.
	 * @return string the constructed URL
	 */
	 /* //it would also change image links, form submit destinations ....
	public function createUrl($route,$params=array(),$ampersand='&')
	{
		$url = parent::createUrl($route,$params,$ampersand);
		if($this->useAjaxLinks){ //if($this->getIsAjaxRequest()){
			//Change to HashLink
			if (strpos($url, '.png') === false){
				return str_replace('index.php/','index.php#',$url);
			} else {
				return $url;
			}
		} else {
			return $url;
		}
	}
	*/
	
	public function createUrlHash($route,$params=array(),$ampersand='&')
	{
		$url = parent::createUrl($route,$params,$ampersand);
		$pos = strpos($url, 'index.php/');
		if ($pos !== false){
			$url = substr($url, $pos+10);
		}
		return $url;
	}
}