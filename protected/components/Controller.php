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
	
	public $isFancyAjaxRequest = false;
	
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
			
			$clientScripts = Yii::app()->getClientScript();
			$output=$this->processOutput($output);
			/*
			//from framework/web/CClientScript
			if($clientScripts->hasScripts) {
				//$clientScripts->renderCoreScripts();

				if(!empty($clientScripts->scriptMap))
					$clientScripts->remapScripts();
				
				$clientScripts->unifyScripts();
				
				$clientScripts->renderHead($output);
				if($clientScripts->enableJavaScript) {
					$clientScripts->renderBodyBegin($output);
					$clientScripts->renderBodyEnd($output);
				}
			}
			*/
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
			$this->render($view, $data);
		}
	}
}