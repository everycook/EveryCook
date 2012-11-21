<?php

class WebUser extends CWebUser {
	public function init() {
		parent::init();
		//Set default user values/states
		if (!$this->hasState('view_distance') || $this->view_distance == ''){
			$this->setState('view_distance',5);
		}
		if (!$this->hasState('design') || $this->design == ''){
			$this->setState('design','Avocado');
		}
	}
	
	/**
	 * Performs access check for this user.
	 * 
	 * CAccessControlFilter Class will run this for each "role"(parameter operation, others are empty) in  CAccessRule->rules array of a rule to check.
	 * 
	 * @param string $operation the name of the operation that need access check.
	 * @param array $params name-value pairs that would be passed to business rules associated
	 * with the tasks and roles assigned to the user.
	 * @param boolean $allowCaching whether to allow caching the result of access check.
	 * This parameter has been available since version 1.0.5. When this parameter
	 * is true (default), if the access check of an operation was performed before,
	 * its result will be directly returned when calling this method to check the same operation.
	 * If this parameter is false, this method will always call {@link CAuthManager::checkAccess}
	 * to obtain the up-to-date access result. Note that this caching is effective
	 * only within the same request.
	 * @return boolean whether the operations can be performed by this user.
	 */
	public function checkAccess($operation,$params=array(),$allowCaching=true)
	{
		/*
		if($allowCaching && $params===array() && isset($this->_access[$operation]))
			return $this->_access[$operation];
		else
			return $this->_access[$operation]=Yii::app()->getAuthManager()->checkAccess($operation,$this->getId(),$params);
		*/
		return isset($this->roles) && !empty($this->roles) && in_array(strtolower($operation), $this->roles);
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