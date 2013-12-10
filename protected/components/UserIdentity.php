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
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identify the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	* Authenticates a user.
	* The example implementation makes sure if the username and password
	* are both 'demo'.
	* In practical applications, this should be changed to authenticate
	* against some persistent user identity storage (e.g. database).
	* @return boolean whether authentication succeeds.
	*/

	const ERROR_ACCOUNT_NOT_ACTIVE=3;
	private $_id = -1;

	public function authenticate()
	{
		if (strtolower($this->username) == 'demo' && strtolower($this->password) == 'demo'){
			$this->setState('demo', true);
			$this->setState('roles', array('demo','user'));
			//TODO create demo shoppinglist?
			$shoppinglists = array();
			$this->setState('shoppinglists', $shoppinglists);
			$this->errorCode=self::ERROR_NONE;
			
			$this->_id=0;
			$this->setState('lang', Yii::app()->session['lang']);
			$this->setState('nick', 'Demo');
			//todo Set basel as home
			$home_gps = array(47.557473, 7.592926, 'POINT(47.557473 7.592926)');
			$this->setState('home_gps', $home_gps);
			$this->setState('view_distance', 5);
			$this->setState('design','Avocado');
		} else {
			$record=Profiles::model()->findByAttributes(array('PRF_NICK'=>$this->username));
			if($record===null) {
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
			else {
				if($record->PRF_ACTIVE === '0') {
					$this->errorCode=self::ERROR_ACCOUNT_NOT_ACTIVE;
				}
				else if($record->PRF_PW!==crypt($this->password, $record->PRF_PW)) {
					$this->errorCode=self::ERROR_PASSWORD_INVALID;
				}
				else {
					$this->_id=$record->PRF_UID;
					$this->setState('lang', $record->PRF_LANG);
					$this->setState('nick', $record->PRF_NICK);
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
					
					$this->setState('twitterOauthToken', $record->PRF_TWITTER_OAUTH);
					$this->setState('twitterOauthTokenSecret', $record->PRF_TWITTER_OAUTH_TOKEN);
					
					$this->setState('demo', false);
					$this->errorCode=self::ERROR_NONE;
				}
			}
		}
		return !$this->errorCode;
	}

	public function getId() {
		return $this->_id;
	}
}
