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
	const ERROR_ACCOUNT_BLOCKED=4;
	private $_id = -1;

	public function authenticate()
	{
		if (strtolower($this->username) == 'demo' && strtolower($this->password) == 'demo'){
			$this->_id=0;
			$this->setState('lang', Yii::app()->session['lang']);
			$this->setState('design','Aubergine');
			$this->setState('token', 'none');
			$this->errorCode=self::ERROR_NONE;
		} else {
			$record=Profiles::model()->findByAttributes(array('PRF_NICK'=>$this->username));
			if($record===null) {
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
			else {
				if($record->PRF_PW!==crypt($this->password, $record->PRF_PW)) {
					$this->errorCode=self::ERROR_PASSWORD_INVALID;
				} else if($record->PRF_ACTIVE === '0') {
					$this->errorCode=self::ERROR_ACCOUNT_NOT_ACTIVE;
				} else if($record->PRF_ACTIVE < 0) {
					$this->errorCode=self::ERROR_ACCOUNT_BLOCKED;
				}
				else {
					$this->_id=$record->PRF_UID;
					$this->setState('lang', $record->PRF_LANG);
					$this->setState('design', $record->PRF_DESIGN);
					$this->setState('token', $record->PRF_RELOGIN_TOKEN);
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
