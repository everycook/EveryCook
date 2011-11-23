<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $PRF_UID
 * @property string $PRF_FIRSTNAME
 * @property string $PRF_LASTNAME
 * @property string $PRF_NICK
 * @property string $PRF_EMAIL
 * @property string $PRF_PW
 * @property string $PRF_LOC_GPS
 * @property string $PRF_LIKES_I
 * @property string $PRF_LIKES_R
 * @property string $PRF_NOTLIKES_I
 * @property string $PRF_NOTLIKES_R
 * @property string $PRF_SHOPLISTS
 */
class Profiles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Profiles the static model class
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
		return 'profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRF_FIRSTNAME, PRF_LASTNAME, PRF_NICK, PRF_EMAIL, PRF_LOC_GPS', 'length', 'max'=>100),
			array('PRF_PW', 'length', 'max'=>256),
			array('PRF_LIKES_I, PRF_LIKES_R, PRF_NOTLIKES_I, PRF_NOTLIKES_R, PRF_SHOPLISTS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRF_UID, PRF_FIRSTNAME, PRF_LASTNAME, PRF_NICK, PRF_EMAIL, PRF_PW, PRF_LOC_GPS, PRF_LIKES_I, PRF_LIKES_R, PRF_NOTLIKES_I, PRF_NOTLIKES_R, PRF_SHOPLISTS', 'safe', 'on'=>'search'),
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
			'PRF_UID' => 'Prf Uid',
			'PRF_FIRSTNAME' => 'Prf Firstname',
			'PRF_LASTNAME' => 'Prf Lastname',
			'PRF_NICK' => 'Prf Nick',
			'PRF_EMAIL' => 'Prf Email',
			'PRF_PW' => 'Prf Pw',
			'PRF_LOC_GPS' => 'Prf Loc Gps',
			'PRF_LIKES_I' => 'Prf Likes I',
			'PRF_LIKES_R' => 'Prf Likes R',
			'PRF_NOTLIKES_I' => 'Prf Notlikes I',
			'PRF_NOTLIKES_R' => 'Prf Notlikes R',
			'PRF_SHOPLISTS' => 'Prf Shoplists',
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

		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('PRF_FIRSTNAME',$this->PRF_FIRSTNAME,true);
		$criteria->compare('PRF_LASTNAME',$this->PRF_LASTNAME,true);
		$criteria->compare('PRF_NICK',$this->PRF_NICK,true);
		$criteria->compare('PRF_EMAIL',$this->PRF_EMAIL,true);
		$criteria->compare('PRF_PW',$this->PRF_PW,true);
		$criteria->compare('PRF_LOC_GPS',$this->PRF_LOC_GPS,true);
		$criteria->compare('PRF_LIKES_I',$this->PRF_LIKES_I,true);
		$criteria->compare('PRF_LIKES_R',$this->PRF_LIKES_R,true);
		$criteria->compare('PRF_NOTLIKES_I',$this->PRF_NOTLIKES_I,true);
		$criteria->compare('PRF_NOTLIKES_R',$this->PRF_NOTLIKES_R,true);
		$criteria->compare('PRF_SHOPLISTS',$this->PRF_SHOPLISTS,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}