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
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $PRF_UID
 * @property string $PRF_FIRSTNAME
 * @property string $PRF_LASTNAME
 * @property string $PRF_NICK
 * @property string $PRF_GENDER
 * @property integer $PRF_BIRTHDAY
 * @property string $PRF_EMAIL
 * @property string $PRF_LANG
 * @property string $PRF_IMG_FILENAME
 * @property string $PRF_IMG_ETAG
 * @property string $PRF_PW
 * @property double $PRF_LOC_GPS_LAT
 * @property double $PRF_LOC_GPS_LNG
 * @property string $PRF_LOC_GPS_POINT
 * @property string $PRF_LIKES_I
 * @property string $PRF_LIKES_R
 * @property string $PRF_LIKES_P
 * @property string $PRF_LIKES_S
 * @property string $PRF_NOTLIKES_I
 * @property string $PRF_NOTLIKES_R
 * @property string $PRF_NOTLIKES_P
 * @property string $PRF_SHOPLISTS
 * @property integer $PRF_VIEW_DISTANCE
 * @property string $PRF_DESIGN
 * @property string $PRF_ROLES
 * @property integer $PRF_ACTIVE
 * @property string $PRF_RND
 * @property string $PRF_EVERYCOOP_IP
 * @property string $PRF_TWITTER_OAUTH_TOKEN
 * @property string $PRF_TWITTER_OAUTH_TOKEN_SECRET
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Profiles extends ActiveRecordECPriv
{
	/**
	* Private Attributes
	*/
	public $new_pw;
	public $pw_repeat;
	public $verifyCaptcha;
	
	public $filename;
	public $imagechanged;
	public $birthday_day;
	public $birthday_month;
	public $birthday_year;
	
	public function attributeNames(){
		$names = parent::attributeNames();
		return array_merge($names, array('new_pw','pw_repeat','birthday_day', 'birthday_month', 'birthday_year'));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return Profiles the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'profiles';
	}
	
	public function afterFind(){
		if (isset($this->PRF_BIRTHDAY) && $this->PRF_BIRTHDAY != ''){
			$this->birthday_day = date('d', $this->PRF_BIRTHDAY);
			$this->birthday_month = date('m', $this->PRF_BIRTHDAY);
			$this->birthday_year = date('Y', $this->PRF_BIRTHDAY);
		} else {
			$this->birthday_day = '';
			$this->birthday_month = '';
			$this->birthday_year = '';
		}
		parent::afterFind();
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRF_NICK, PRF_EMAIL, PRF_PW, PRF_LANG', 'required'),
			array('PRF_BIRTHDAY, PRF_VIEW_DISTANCE, PRF_ACTIVE, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('PRF_LOC_GPS_LAT, PRF_LOC_GPS_LNG', 'numerical'),
			array('PRF_FIRSTNAME, PRF_LASTNAME, PRF_NICK, PRF_EMAIL, PRF_ROLES, PRF_RND, PRF_EVERYCOOP_IP', 'length', 'max'=>100),
			array('PRF_GENDER', 'length', 'max'=>1),
			array('PRF_LANG', 'length', 'max'=>10),
			array('PRF_IMG_FILENAME', 'length', 'max'=>250),
			array('PRF_IMG_ETAG, PRF_TWITTER_OAUTH_TOKEN PRF_TWITTER_OAUTH_TOKEN_SECRET', 'length', 'max'=>50),
			array('PRF_PW', 'length', 'max'=>256),
			array('PRF_DESIGN', 'length', 'max'=>20),
			array('PRF_IMG_FILENAME, PRF_IMG_ETAG', 'required', 'on'=>'withPic'),
			array('new_pw, pw_repeat, birthday_day, birthday_month, birthday_year, PRF_LOC_GPS_POINT, PRF_LIKES_I, PRF_LIKES_R, PRF_LIKES_P, PRF_LIKES_S, PRF_NOTLIKES_I, PRF_NOTLIKES_R, PRF_NOTLIKES_P, PRF_SHOPLISTS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRF_UID, PRF_FIRSTNAME, PRF_LASTNAME, PRF_NICK, PRF_GENDER, PRF_BIRTHDAY, PRF_EMAIL, PRF_LANG, PRF_IMG_FILENAME, PRF_IMG_ETAG, PRF_PW, PRF_LOC_GPS_LAT, PRF_LOC_GPS_LNG, PRF_LOC_GPS_POINT, PRF_LIKES_I, PRF_LIKES_R, PRF_LIKES_P, PRF_LIKES_S, PRF_NOTLIKES_I, PRF_NOTLIKES_R, PRF_NOTLIKES_P, PRF_SHOPLISTS, PRF_VIEW_DISTANCE, PRF_DESIGN, PRF_ROLES, PRF_ACTIVE, PRF_RND, PRF_EVERYCOOP_IP, PRF_TWITTER_OAUTH_TOKEN, PRF_TWITTER_OAUTH_TOKEN_SECRET, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
			
			// register
			//array('pw_repeat','safe'),
			array('pw_repeat, verifyCaptcha', 'required', 'on'=>'register'),
			array('pw_repeat', 'compare', 'compareAttribute'=>'PRF_PW', 'on'=>'register'),
			array('PRF_NICK, PRF_EMAIL','unique', 'on'=>'register'),
			array('PRF_NICK, PRF_EMAIL','unique', 'on'=>'update'),
			array('pw_repeat', 'required', 'on'=>'pw_change'),
			array('pw_repeat', 'compare', 'compareAttribute'=>'PRF_PW', 'on'=>'pw_change'),
			array('PRF_NICK, PRF_EMAIL','unique', 'on'=>'pw_change'),
			//array('verifyCaptcha', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
			//array('verifyCaptcha', 'CaptchaExtendedValidator', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'PRF_UID' => 'Prf Uid',
			'PRF_FIRSTNAME' => 'Prf Firstname',
			'PRF_LASTNAME' => 'Prf Lastname',
			'PRF_NICK' => 'Prf Nick',
			'PRF_GENDER' => 'Prf Gender',
			'PRF_BIRTHDAY' => 'Prf Birthday',
			'PRF_EMAIL' => 'Prf Email',
			'PRF_LANG' => 'Prf Lang',
			'PRF_IMG_FILENAME' => 'Prf Img Filename',
			'PRF_IMG_ETAG' => 'Prf Img Etag',
			'PRF_PW' => 'Prf Pw',
			'pw_repeat' => 'pw repeat',
			'PRF_LOC_GPS_LAT' => 'Prf Loc Gps Lat',
			'PRF_LOC_GPS_LNG' => 'Prf Loc Gps Lng',
			'PRF_LOC_GPS_POINT' => 'Prf Loc Gps Point',
			'PRF_LIKES_I' => 'Prf Likes I',
			'PRF_LIKES_R' => 'Prf Likes R',
			'PRF_LIKES_P' => 'Prf Likes P',
			'PRF_LIKES_S' => 'Prf Likes S',
			'PRF_NOTLIKES_I' => 'Prf Notlikes I',
			'PRF_NOTLIKES_R' => 'Prf Notlikes R',
			'PRF_NOTLIKES_P' => 'Prf Notlikes P',
			'PRF_SHOPLISTS' => 'Prf Shoplists',
			'PRF_VIEW_DISTANCE' => 'Prf View Distance',
			'PRF_DESIGN' => 'Prf Design',
			'PRF_ROLES' => 'Prf Roles',
			//'PRF_ACTIVE' => 'Prf Active',
			//'PRF_RND' => 'Prf Rnd',
			'PRF_EVERYCOOP_IP' => 'Prf Everycoop Ip',
			'PRF_TWITTER_OAUTH_TOKEN' => 'Prf Twitter Oauth Token',
			'PRF_TWITTER_OAUTH_TOKEN_SECRET' => 'Prf Twitter Oauth Token Secret',
			//'CREATED_BY' => 'Created By',
			//'CREATED_ON' => 'Created On',
			//'CHANGED_BY' => 'Changed By',
			//'CHANGED_ON' => 'Changed On',
			'verifyCode'=>'Verification Code',
		);
	}
	
	public function getSearchFields(){
		return array('PRF_UID', 'PRF_NICK', 'PRF_FIRSTNAME', 'PRF_LASTNAME');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.PRF_FIRSTNAME',$this->PRF_FIRSTNAME,true);
		$criteria->compare($this->tableName().'.PRF_LASTNAME',$this->PRF_LASTNAME,true);
		$criteria->compare($this->tableName().'.PRF_NICK',$this->PRF_NICK,true);
		$criteria->compare($this->tableName().'.PRF_GENDER',$this->PRF_GENDER,true);
		$criteria->compare($this->tableName().'.PRF_BIRTHDAY',$this->PRF_BIRTHDAY);
		$criteria->compare($this->tableName().'.PRF_EMAIL',$this->PRF_EMAIL,true);
		$criteria->compare($this->tableName().'.PRF_LANG',$this->PRF_LANG,true);
		$criteria->compare($this->tableName().'.PRF_IMG_FILENAME',$this->PRF_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.PRF_IMG_ETAG',$this->PRF_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.PRF_PW',$this->PRF_PW,true);
		$criteria->compare($this->tableName().'.PRF_LOC_GPS_LAT',$this->PRF_LOC_GPS_LAT);
		$criteria->compare($this->tableName().'.PRF_LOC_GPS_LNG',$this->PRF_LOC_GPS_LNG);
		$criteria->compare($this->tableName().'.PRF_LOC_GPS_POINT',$this->PRF_LOC_GPS_POINT,true);
		$criteria->compare($this->tableName().'.PRF_LIKES_I',$this->PRF_LIKES_I,true);
		$criteria->compare($this->tableName().'.PRF_LIKES_R',$this->PRF_LIKES_R,true);
		$criteria->compare($this->tableName().'.PRF_LIKES_P',$this->PRF_LIKES_P,true);
		$criteria->compare($this->tableName().'.PRF_LIKES_S',$this->PRF_LIKES_S,true);
		$criteria->compare($this->tableName().'.PRF_NOTLIKES_I',$this->PRF_NOTLIKES_I,true);
		$criteria->compare($this->tableName().'.PRF_NOTLIKES_R',$this->PRF_NOTLIKES_R,true);
		$criteria->compare($this->tableName().'.PRF_NOTLIKES_P',$this->PRF_NOTLIKES_P,true);
		$criteria->compare($this->tableName().'.PRF_SHOPLISTS',$this->PRF_SHOPLISTS,true);
		$criteria->compare($this->tableName().'.PRF_VIEW_DISTANCE',$this->PRF_VIEW_DISTANCE);
		$criteria->compare($this->tableName().'.PRF_DESIGN',$this->PRF_DESIGN,true);
		$criteria->compare($this->tableName().'.PRF_ROLES',$this->PRF_ROLES,true);
		$criteria->compare($this->tableName().'.PRF_ACTIVE',$this->PRF_ACTIVE);
		$criteria->compare($this->tableName().'.PRF_RND',$this->PRF_RND,true);
		$criteria->compare($this->tableName().'.PRF_EVERYCOOP_IP',$this->PRF_EVERYCOOP_IP,true);
		$criteria->compare($this->tableName().'.PRF_TWITTER_OAUTH_TOKEN',$this->PRF_TWITTER_OAUTH_TOKEN,true);
		$criteria->compare($this->tableName().'.PRF_TWITTER_OAUTH_TOKEN_SECRET',$this->PRF_TWITTER_OAUTH_TOKEN_SECRET,true);
		$criteria->compare($this->tableName().'.CREATED_BY',$this->CREATED_BY);
		$criteria->compare($this->tableName().'.CREATED_ON',$this->CREATED_ON);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
		
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('PRF_FIRSTNAME',$this->PRF_FIRSTNAME,true);
		$criteria->compare('PRF_LASTNAME',$this->PRF_LASTNAME,true);
		$criteria->compare('PRF_NICK',$this->PRF_NICK,true);
		$criteria->compare('PRF_GENDER',$this->PRF_GENDER,true);
		$criteria->compare('PRF_BIRTHDAY',$this->PRF_BIRTHDAY);
		$criteria->compare('PRF_EMAIL',$this->PRF_EMAIL,true);
		$criteria->compare('PRF_LANG',$this->PRF_LANG,true);
		$criteria->compare('PRF_IMG_FILENAME',$this->PRF_IMG_FILENAME,true);
		$criteria->compare('PRF_IMG_ETAG',$this->PRF_IMG_ETAG,true);
		$criteria->compare('PRF_PW',$this->PRF_PW,true);
		$criteria->compare('PRF_LOC_GPS_LAT',$this->PRF_LOC_GPS_LAT);
		$criteria->compare('PRF_LOC_GPS_LNG',$this->PRF_LOC_GPS_LNG);
		$criteria->compare('PRF_LOC_GPS_POINT',$this->PRF_LOC_GPS_POINT,true);
		$criteria->compare('PRF_LIKES_I',$this->PRF_LIKES_I,true);
		$criteria->compare('PRF_LIKES_R',$this->PRF_LIKES_R,true);
		$criteria->compare('PRF_LIKES_P',$this->PRF_LIKES_P,true);
		$criteria->compare('PRF_LIKES_S',$this->PRF_LIKES_S,true);
		$criteria->compare('PRF_NOTLIKES_I',$this->PRF_NOTLIKES_I,true);
		$criteria->compare('PRF_NOTLIKES_R',$this->PRF_NOTLIKES_R,true);
		$criteria->compare('PRF_NOTLIKES_P',$this->PRF_NOTLIKES_P,true);
		$criteria->compare('PRF_SHOPLISTS',$this->PRF_SHOPLISTS,true);
		$criteria->compare('PRF_VIEW_DISTANCE',$this->PRF_VIEW_DISTANCE);
		$criteria->compare('PRF_DESIGN',$this->PRF_DESIGN,true);
		$criteria->compare('PRF_ROLES',$this->PRF_ROLES,true);
		//$criteria->compare('PRF_ACTIVE',$this->PRF_ACTIVE);
		//$criteria->compare('PRF_RND',$this->PRF_RND,true);
		$criteria->compare('PRF_EVERYCOOP_IP',$this->PRF_EVERYCOOP_IP,true);
		$criteria->compare('PRF_TWITTER_OAUTH_TOKEN',$this->PRF_TWITTER_OAUTH_TOKEN,true);
		$criteria->compare('PRF_TWITTER_OAUTH_TOKEN_SECRET',$this->PRF_TWITTER_OAUTH_TOKEN_SECRET,true);
		//$criteria->compare('CREATED_BY',$this->CREATED_BY);
		//$criteria->compare('CREATED_ON',$this->CREATED_ON);
		//$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		//$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'PRF_UID',
				'desc' => 'PRF_UID DESC',
			),
		*/
			'*',
		);
		return $sort;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getCriteria(),
			'sort'=>$this->getSort(),
		));
	}
}
