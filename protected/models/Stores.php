<?php

/**
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property integer $STO_ID
 * @property string $STO_NAME
 * @property string $STO_STREET
 * @property string $STO_HOUSE_NO
 * @property integer $STO_ZIP
 * @property string $STO_CITY
 * @property integer $STO_COUNTRY
 * @property string $STO_STATE
 * @property integer $STY_ID
 * @property string $STO_GPS
 * @property string $STO_PHONE
 * @property string $STO_IMG
 * @property integer $SUP_ID
 * @property integer $CREATED_BY
 * @property string $CREATED_ON
 * @property integer $CHANGED_BY
 * @property string $CHANGED_ON
 */
class Stores extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Stores the static model class
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
		return 'stores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STO_NAME, STO_HOUSE_NO, STO_ZIP, STO_CITY, STO_COUNTRY, STO_STATE, STY_ID, SUP_ID, CREATED_BY, CREATED_ON', 'required'),
			array('STO_ZIP, STO_COUNTRY, STY_ID, SUP_ID, CREATED_BY, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('STO_NAME, STO_STREET, STO_CITY, STO_STATE, STO_GPS', 'length', 'max'=>100),
			array('STO_HOUSE_NO, STO_PHONE', 'length', 'max'=>20),
			array('STO_IMG, CHANGED_ON', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STO_ID, STO_NAME, STO_STREET, STO_HOUSE_NO, STO_ZIP, STO_CITY, STO_COUNTRY, STO_STATE, STY_ID, STO_GPS, STO_PHONE, STO_IMG, SUP_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'STO_ID' => 'Sto',
			'STO_NAME' => 'Sto Name',
			'STO_STREET' => 'Sto Street',
			'STO_HOUSE_NO' => 'Sto House No',
			'STO_ZIP' => 'Sto Zip',
			'STO_CITY' => 'Sto City',
			'STO_COUNTRY' => 'Sto Country',
			'STO_STATE' => 'Sto State',
			'STY_ID' => 'Sty',
			'STO_GPS' => 'Sto Gps',
			'STO_PHONE' => 'Sto Phone',
			'STO_IMG' => 'Sto Img',
			'SUP_ID' => 'Sup',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
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

		$criteria->compare('STO_ID',$this->STO_ID);
		$criteria->compare('STO_NAME',$this->STO_NAME,true);
		$criteria->compare('STO_STREET',$this->STO_STREET,true);
		$criteria->compare('STO_HOUSE_NO',$this->STO_HOUSE_NO,true);
		$criteria->compare('STO_ZIP',$this->STO_ZIP);
		$criteria->compare('STO_CITY',$this->STO_CITY,true);
		$criteria->compare('STO_COUNTRY',$this->STO_COUNTRY);
		$criteria->compare('STO_STATE',$this->STO_STATE,true);
		$criteria->compare('STY_ID',$this->STY_ID);
		$criteria->compare('STO_GPS',$this->STO_GPS,true);
		$criteria->compare('STO_PHONE',$this->STO_PHONE,true);
		$criteria->compare('STO_IMG',$this->STO_IMG,true);
		$criteria->compare('SUP_ID',$this->SUP_ID);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}