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
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property integer $STO_ID
 * @property string $STO_NAME
 * @property string $STO_STREET
 * @property string $STO_HOUSE_NO
 * @property string $STO_ZIP
 * @property string $STO_CITY
 * @property string $STO_COUNTRY
 * @property string $STO_STATE
 * @property integer $STY_ID
 * @property double $STO_GPS_LAT
 * @property double $STO_GPS_LNG
 * @property string $STO_GPS_POINT
 * @property string $STO_PHONE
 * @property string $STO_IMG
 * @property string $STO_IMG_FILENAME
 * @property string $STO_IMG_AUTH
 * @property string $STO_IMG_ETAG
 * @property integer $SUP_ID
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Stores extends ActiveRecordEC
{
	public $filename;
	public $imagechanged;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Stores the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'stores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STO_NAME, STO_STREET, STO_ZIP, STO_CITY, STO_COUNTRY, STO_GPS_LAT, STO_GPS_LNG, STO_GPS_POINT, STY_ID, SUP_ID, CREATED_BY, CREATED_ON', 'required'),
			array('STO_IMG_AUTH, STO_IMG_ETAG', 'required', 'on'=>'withPic'),
			array('STY_ID, SUP_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('STO_GPS_LAT, STO_GPS_LNG', 'numerical'),
			array('STO_NAME, STO_STREET, STO_CITY, STO_STATE, STO_IMG_AUTH', 'length', 'max'=>100),
			array('STO_HOUSE_NO, STO_PHONE', 'length', 'max'=>20),
			array('STO_ZIP', 'length', 'max'=>10),
			array('STO_COUNTRY', 'length', 'max'=>2),
			array('STO_IMG_FILENAME', 'length', 'max'=>250),
			array('STO_IMG_ETAG', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STO_ID, STO_NAME, STO_STREET, STO_HOUSE_NO, STO_ZIP, STO_CITY, STO_COUNTRY, STO_STATE, STY_ID, STO_GPS_LAT, STO_GPS_LNG, STO_GPS_POINT, STO_PHONE, STO_IMG, STO_IMG_FILENAME, STO_IMG_AUTH, STO_IMG_ETAG, SUP_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'STO_ID' => 'Sto',
			'STO_NAME' => 'Sto Name',
			'STO_STREET' => 'Sto Street',
			'STO_HOUSE_NO' => 'Sto House No',
			'STO_ZIP' => 'Sto Zip',
			'STO_CITY' => 'Sto City',
			'STO_COUNTRY' => 'Sto Country',
			'STO_STATE' => 'Sto State',
			'STY_ID' => 'Sty',
			'STO_GPS_LAT' => 'Sto Gps Lat',
			'STO_GPS_LNG' => 'Sto Gps Lng',
			'STO_GPS_POINT' => 'Sto Gps Point',
			'STO_PHONE' => 'Sto Phone',
			'STO_IMG_FILENAME' => 'Sto Img Filename',
			'STO_IMG_AUTH' => 'Sto Img Auth',
			'STO_IMG_ETAG' => 'Sto Img Etag',
			'SUP_ID' => 'Sup',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('STO_ID', 'STO_NAME', 'STO_STREET');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.STO_ID',$this->STO_ID);
		$criteria->compare($this->tableName().'.STO_NAME',$this->STO_NAME,true);
		$criteria->compare($this->tableName().'.STO_STREET',$this->STO_STREET,true);
		$criteria->compare($this->tableName().'.STO_HOUSE_NO',$this->STO_HOUSE_NO,true);
		$criteria->compare($this->tableName().'.STO_ZIP',$this->STO_ZIP,true);
		$criteria->compare($this->tableName().'.STO_CITY',$this->STO_CITY,true);
		$criteria->compare($this->tableName().'.STO_COUNTRY',$this->STO_COUNTRY,true);
		$criteria->compare($this->tableName().'.STO_STATE',$this->STO_STATE,true);
		$criteria->compare($this->tableName().'.STY_ID',$this->STY_ID);
		$criteria->compare($this->tableName().'.STO_GPS_LAT',$this->STO_GPS_LAT);
		$criteria->compare($this->tableName().'.STO_GPS_LNG',$this->STO_GPS_LNG);
		$criteria->compare($this->tableName().'.STO_GPS_POINT',$this->STO_GPS_POINT,true);
		$criteria->compare($this->tableName().'.STO_PHONE',$this->STO_PHONE,true);
		$criteria->compare($this->tableName().'.STO_IMG_FILENAME',$this->STO_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.STO_IMG_AUTH',$this->STO_IMG_AUTH,true);
		$criteria->compare($this->tableName().'.STO_IMG_ETAG',$this->STO_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.SUP_ID',$this->SUP_ID);
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

		$criteria->compare('STO_ID',$this->STO_ID);
		$criteria->compare('STO_NAME',$this->STO_NAME,true);
		$criteria->compare('STO_STREET',$this->STO_STREET,true);
		$criteria->compare('STO_HOUSE_NO',$this->STO_HOUSE_NO,true);
		$criteria->compare('STO_ZIP',$this->STO_ZIP,true);
		$criteria->compare('STO_CITY',$this->STO_CITY,true);
		$criteria->compare('STO_COUNTRY',$this->STO_COUNTRY,true);
		$criteria->compare('STO_STATE',$this->STO_STATE,true);
		$criteria->compare('STY_ID',$this->STY_ID);
		$criteria->compare('STO_GPS_LAT',$this->STO_GPS_LAT);
		$criteria->compare('STO_GPS_LNG',$this->STO_GPS_LNG);
		$criteria->compare('STO_GPS_POINT',$this->STO_GPS_POINT,true);
		$criteria->compare('STO_PHONE',$this->STO_PHONE,true);
		$criteria->compare('STO_IMG_FILENAME',$this->STO_IMG_FILENAME,true);
		$criteria->compare('STO_IMG_AUTH',$this->STO_IMG_AUTH,true);
		$criteria->compare('STO_IMG_ETAG',$this->STO_IMG_ETAG,true);
		$criteria->compare('SUP_ID',$this->SUP_ID);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'STO_ID',
				'desc' => 'STO_ID DESC',
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