<?php

/**
 * RegisterForm class.
 * RegForm is the data structure for keeping
 * profile form data. It is used by the 'register' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $firstname;
	public $lastname;
	public $nick;
	public $email;
	public $password;
	public $password2;
	public $gender;
	public $birthday;
	public $lang;
	public $img;
	public $gps;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('firstname, lastname, nick, email, password, password2, lang', 'required'),
			// rememberMe needs to be a boolean
			//array('rememberMe', 'boolean'),
			//array('email', 'email'),
			// password needs to be authenticated
			//array('password2', 'compare'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			//'rememberMe'=>'Remember me next time',
		);
	}
}
