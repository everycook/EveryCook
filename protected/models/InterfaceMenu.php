<?php

/**
 * This is the model class for table "interface_menu".
 *
 * The followings are the available columns in table 'interface_menu':
 * @property string $IME_LANG
 * @property string $IME_LANGNAME
 * @property string $IME_LANGSEL
 * @property string $IME_SETTINGS
 * @property string $IME_LOGIN
 * @property string $IME_FORM_LOGIN_USER
 * @property string $IME_FORM_LOGIN_PASS
 * @property string $IME_REGISTER
 * @property string $IME_FORM_LOGIN_ERROR
 * @property string $IME_FORM_REGISTER_FIRSTNAME
 * @property string $IME_FORM_REGISTER_LASTNAME
 * @property string $IME_FORM_REGISTER_EMAIL
 * @property string $IME_FORM_REGISTER_PASSWORD_TEST
 * @property string $IME_FORM_REGISTER_ERROR_FIRSTNAME
 * @property string $IME_FORM_REGISTER_ERROR_LASTNAME
 * @property string $IME_FORM_REGISTER_ERROR_USER
 * @property string $IME_FORM_REGISTER_ERROR_EMAIL
 * @property string $IME_FORM_REGISTER_ERROR_PASSWORD
 * @property string $IME_FORM_REGISTER_ERROR_PASSWORD_TEST
 * @property string $IME_FORM_REGISTER_ERROR_USER_EXIST
 * @property string $IME_FORM_REGISTER_ERROR_EMAIL_EXIST
 * @property string $IME_FORM_REGISTER_ERROR_FIELDS
 */
class InterfaceMenu extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return InterfaceMenu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'interface_menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IME_LANG, IME_LANGNAME', 'required'),
			array('IME_LANG', 'length', 'max'=>3),
			array('IME_LANGNAME', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IME_LANG, IME_LANGNAME', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'IME_LANG' => 'Ime Lang',
			'IME_LANGNAME' => 'Ime Langname',
			'IME_LANGSEL' => 'Ime Langsel',
			'IME_SETTINGS' => 'Ime Settings',
			'IME_LOGIN' => 'Ime Login',
			'IME_FORM_LOGIN_USER' => 'Ime Form Login User',
			'IME_FORM_LOGIN_PASS' => 'Ime Form Login Pass',
			'IME_REGISTER' => 'Ime Register',
			'IME_FORM_LOGIN_ERROR' => 'Ime Form Login Error',
			'IME_FORM_REGISTER_FIRSTNAME' => 'Ime Form Register Firstname',
			'IME_FORM_REGISTER_LASTNAME' => 'Ime Form Register Lastname',
			'IME_FORM_REGISTER_EMAIL' => 'Ime Form Register Email',
			'IME_FORM_REGISTER_PASSWORD_TEST' => 'Ime Form Register Password Test',
			'IME_FORM_REGISTER_ERROR_FIRSTNAME' => 'Ime Form Register Error Firstname',
			'IME_FORM_REGISTER_ERROR_LASTNAME' => 'Ime Form Register Error Lastname',
			'IME_FORM_REGISTER_ERROR_USER' => 'Ime Form Register Error User',
			'IME_FORM_REGISTER_ERROR_EMAIL' => 'Ime Form Register Error Email',
			'IME_FORM_REGISTER_ERROR_PASSWORD' => 'Ime Form Register Error Password',
			'IME_FORM_REGISTER_ERROR_PASSWORD_TEST' => 'Ime Form Register Error Password Test',
			'IME_FORM_REGISTER_ERROR_USER_EXIST' => 'Ime Form Register Error User Exist',
			'IME_FORM_REGISTER_ERROR_EMAIL_EXIST' => 'Ime Form Register Error Email Exist',
			'IME_FORM_REGISTER_ERROR_FIELDS' => 'Ime Form Register Error Fields',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('IME_LANG',$this->IME_LANG,true);
		$criteria->compare('IME_LANGNAME',$this->IME_LANGNAME,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}