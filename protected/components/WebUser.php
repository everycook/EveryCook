<?php

class WebUser extends CWebUser {
	public function init() {
		parent::init();
		//Set default user values/states
		if (!$this->hasState('view_distance') || $this->view_distance == ''){
			$this->setState('view_distance',5);
		}
	}
	
	/**
	 * Redirects the user browser to the login page.
	 * Before the redirection, the current URL (ALSO if it's an AJAX url) will be
	 * kept in {@link returnUrl} so that the user browser may be redirected back
	 * to the current page after successful login. Make sure you set {@link loginUrl}
	 * so that the user browser can be redirected to the specified login URL after
	 * calling this method.
	 * After calling this method, the current request processing will be terminated.
	 */
	public function loginRequired()
	{
		$app=Yii::app();
		$request=$app->getRequest();

		//if(!$request->getIsAjaxRequest())
		if (Yii::app()->getController()->useAjaxLinks){
			$this->setReturnUrl(Controller::urlToUrlWithHash($request->getUrl()));
		} else {
			$this->setReturnUrl($request->getUrl());
		}

		if(($url=$this->loginUrl)!==null)
		{
			if(is_array($url))
			{
				$route=isset($url[0]) ? $url[0] : $app->defaultController;
				$url=$app->createUrl($route,array_splice($url,1));
			}
			$request->redirect($url);
		}
		else
			throw new CHttpException(403,Yii::t('yii','Login Required'));
	}
}