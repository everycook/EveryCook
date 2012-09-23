<?php

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
			if(!$this->_identity->authenticate())
			if($this->_identity->errorCode === 3) {
				$this->addError('LIF_NICKNAME', Yii::app()->controller->trans->LOGIN_NOT_ACTIVATED . '<br/>' . sprintf(Yii::app()->controller->trans->LOGIN_NOT_ACTIVATED_RESEND, Yii::app()->createUrl('profiles/resendActivationMail', array('nick'=>$this->LIF_NICKNAME))));
			} else {
				$this->addError('LIF_PASSWORD', Yii::app()->controller->trans->LOGIN_ERROR);
			}
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
