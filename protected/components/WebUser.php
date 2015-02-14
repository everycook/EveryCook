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

class WebUser extends CWebUser {
	private $needReloginToken = false;
	private $previousLoggedInUser = -1;
	
	public function init() {
		parent::init();
		//Set default user values/states
		if (!$this->hasState('view_distance') || $this->view_distance == ''){
			$this->setState('view_distance',5);
		}
		if (!$this->hasState('design') || $this->design == ''){
			$this->setState('design','Aubergine');
		}
		if (!$this->hasState('shoppinglists') || $this->shoppinglists == null){
			$this->setState('shoppinglists',array());
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
	public function checkAccess($operation,$params=array(),$allowCaching=true){
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
	public function loginRequired(){
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
			//$request->redirect($url);
			Yii::app()->controller->forwardTo(array($url), false);
		}
		else
			throw new CHttpException(403,Yii::t('yii','Login Required'));
	}
	
	public function addShoppingListId($SHO_ID){
		$shoppinglists = Yii::app()->user->shoppinglists;
		if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
			$shoppinglists = $SHO_ID;
			$values = array($SHO_ID);
		} else {
			//no dupplicates
			$values = $shoppinglists;
			$values[] = $SHO_ID;
			$values = array_unique($values);
			//sort($values, SORT_NUMERIC);
			$shoppinglists = implode(';', $values);
		}
		
		Yii::app()->dbp->createCommand()->update(Profiles::model()->tableName(), array('PRF_SHOPLISTS'=>$shoppinglists), 'PRF_UID = :id', array(':id'=>Yii::app()->user->id));
		Yii::app()->user->shoppinglists = $values;
	}
	
	public function login($identity,$duration=0){
		if($duration>0 && $this->allowAutoLogin){
			$this->needReloginToken = true;
		} else {
			$this->needReloginToken = false;
		}
		
		parent::login($identity, $duration);
		
	}

	/**
	 * This method is called before logging in a user.
	 * You may override this method to provide additional security check.
	 * For example, when the login is cookie-based, you may want to verify
	 * that the user ID together with a random token in the states can be found
	 * in the database. This will prevent hackers from faking arbitrary
	 * identity cookies even if they crack down the server private key.
	 * @param mixed $id the user ID. This is the same as returned by {@link getId()}.
	 * @param array $states a set of name-value pairs that are provided by the user identity.
	 * @param boolean $fromCookie whether the login is based on cookie
	 * @return boolean whether the user should be logged in
	 * @since 1.1.3
	 */
	protected function beforeLogin($id,$states,$fromCookie){
		if ($fromCookie) {
			if(count($states) > 3){
				return false;
			}
			
			//if not from cookie this is tested in UserIdendity on call of authenticate
			$record = Profiles::model()->findByPk($id);
			if($record===null) {
				return false;
			}
			if(!isset($states['token']) || !isset($record->PRF_RELOGIN_TOKEN) || $record->PRF_RELOGIN_TOKEN == '' || $record->PRF_RELOGIN_TOKEN != $states['token']){
				return false;
			}
			
			if($record->PRF_ACTIVE === '0') {
				return false; //ERROR_ACCOUNT_NOT_ACTIVE
			} else if($record->PRF_ACTIVE < 0) {
				return false; //ERROR_ACCOUNT_BLOCKED
			}
		}
		return true;
	}

	/**
	 * Changes the current user with the specified identity information.
	 * This method is called by {@link login} and {@link restoreFromCookie}
	 * when the current user needs to be populated with the corresponding
	 * identity information. Derived classes may override this method
	 * by retrieving additional user-related information. Make sure the
	 * parent implementation is called first.
	 * @param mixed $id a unique identifier for the user
	 * @param string $name the display name for the user
	 * @param array $states identity states
	 */
	protected function changeIdentity($id,$name,$states){
		parent::changeIdentity($id, $name, $states);
		if ($this->needReloginToken){
			if (!isset($states['token']) || $states['token'] == null){
				$token = md5(rand(100000, 99999) . $id . '.' . $name . '.' . Yii::app()->getId() . '.' . time());
				$updated = Yii::app()->dbp->createCommand()->update(Profiles::model()->tableName(), array('PRF_RELOGIN_TOKEN'=>$token), 'PRF_UID=:id', array(':id'=>$id));
				if ($updated>0){
					$this->setState('token', $token);
				}
			}
		}
	}
	
	/**
	 * Retrieves identity states from persistent storage and saves them as an array.
	 * @return array the identity states
	 */
	protected function saveIdentityStates(){
		//only return values that should be added to relogin Cookie
		//also change 'if(count($states) > 3){' check on beforeLogin, if added some values
		$states=array();
		$states['lang']=$this->getState('lang');
		$states['design']=$this->getState('design');
		$states['token']=$this->getState('token');
		return $states;
	}
	
	/**
	 * This method is called after the user is successfully logged in.
	 * You may override this method to do some postprocessing (e.g. log the user
	 * login IP and time; load the user permission information).
	 * @param boolean $fromCookie whether the login is based on cookie.
	 * @since 1.1.3
	 */
	protected function afterLogin($fromCookie) {
		$id = $this->getId();
		$record = Profiles::model()->findByPk($id);
		if($record!=null) {
			$this->setState('nick', $record->PRF_NICK);
			$this->setState('email', $record->PRF_EMAIL);
			Yii::app()->session['lang'] = $record->PRF_LANG;
			$home_gps = array($record->PRF_LOC_GPS_LAT, $record->PRF_LOC_GPS_LNG, $record->PRF_LOC_GPS_POINT);
			$this->setState('home_gps', $home_gps);
			$this->setState('view_distance', $record->PRF_VIEW_DISTANCE);
			$this->setState('design', $record->PRF_DESIGN);
			$this->setState('roles', explode(';', strtolower($record->PRF_ROLES)));
			
			if (!isset($record->PRF_SHOPLISTS) || $record->PRF_SHOPLISTS == null || $record->PRF_SHOPLISTS == ''){
				$shoppinglists = array();
			} else {
				$shoppinglists = explode(';', $record->PRF_SHOPLISTS);
			}
			$this->setState('shoppinglists', $shoppinglists);
			
			$this->setState('twitterOauthToken', $record->PRF_TWITTER_OAUTH_TOKEN);
			$this->setState('twitterOauthTokenSecret', $record->PRF_TWITTER_OAUTH_TOKEN_SECRET);
			
			
			$this->setState('demo', false);
			
		} else if ($id == 0 && strtolower($this->getName()) == "demo"){
			$this->setState('nick', 'Demo');
			$this->setState('email', 'example@example.com');
			//todo Set basel as home
			$home_gps = array(47.557473, 7.592926, 'POINT(47.557473 7.592926)');
			$this->setState('home_gps', $home_gps);
			$this->setState('view_distance', 5);
			$this->setState('roles', array('demo','user'));
			//TODO create demo shoppinglist?
			$shoppinglists = array();
			$this->setState('shoppinglists', $shoppinglists);
			
			$this->setState('demo', true);
		}
	}


	/**
	 * This method is invoked when calling {@link logout} to log out a user.
	 * If this method return false, the logout action will be cancelled.
	 * You may override this method to provide additional check before
	 * logging out a user.
	 * @return boolean whether to log out the user
	 * @since 1.1.3
	 */
	protected function beforeLogout()
	{
		$this->previousLoggedInUser = $this->getId();
		return true;
	}
	
	/**
	 * This method is invoked right after a user is logged out.
	 * You may override this method to do some extra cleanup work for the user.
	 * @since 1.1.3
	 */
	protected function afterLogout(){
		//remove Token from user.
		if($this->previousLoggedInUser > 0){
			$updated = Yii::app()->dbp->createCommand()->update(Profiles::model()->tableName(), array('PRF_RELOGIN_TOKEN'=>''), 'PRF_UID=:id', array(':id'=>$this->previousLoggedInUser));
		}
	}
}