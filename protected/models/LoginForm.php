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
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public function getAttributeLabel($attribute) {
		$label = Yii::app()->controller->trans->__get('FIELD_' . $attribute);
		if ($label != null){
			return $label;
		} else if (isset($label)){
			return 'FIELD_' . $attribute . ' is empty.';
		} else {
			//return parent::getAttributeLabel($attribute);
			return 'FIELD_' . $attribute . ' not defined.';
		}
	}
	
	public $LIF_NICKNAME;
	public $LIF_PASSWORD;
	public $LIF_REMEMBER;

	private $_identity;
	public $errorCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('LIF_NICKNAME, LIF_PASSWORD', 'required'),
			// rememberMe needs to be a boolean
			array('LIF_REMEMBER', 'boolean'),
			// password needs to be authenticated
			array('LIF_PASSWORD', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'LIF_REMEMBER'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->LIF_NICKNAME,$this->LIF_PASSWORD);
			if(!$this->_identity->authenticate()) {
				if($this->_identity->errorCode === 3) {
					$this->addError('LIF_NICKNAME', Yii::app()->controller->trans->LOGIN_NOT_ACTIVATED . '<br/>' . sprintf(Yii::app()->controller->trans->LOGIN_NOT_ACTIVATED_RESEND, Yii::app()->createUrl('profiles/resendActivationMail', array('nick'=>$this->LIF_NICKNAME))));
				} else {
					$this->addError('LIF_PASSWORD', Yii::app()->controller->trans->LOGIN_ERROR);
				}
			}
			$this->errorCode = $this->_identity->errorCode;
		} else {
			$this->errorCode = -1;
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->LIF_NICKNAME,$this->LIF_PASSWORD);
			$this->_identity->authenticate();
			$this->errorCode = $this->_identity->errorCode;
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->LIF_REMEMBER ? 3600*24*7 : 0; // 7 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
