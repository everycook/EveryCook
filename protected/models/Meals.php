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
 * This is the model class for table "30608_ecprivate.meals".
 *
 * The followings are the available columns in table '30608_ecprivate.meals':
 * @property integer $MEA_ID
 * @property integer $MEA_DATE
 * @property string $MTY_ID
 * @property double $MEA_PERC_GDA
 * @property integer $PRF_UID
 * @property integer $CREATED_ON
 * @property integer $CREATED_BY
 * @property integer $CHANGED_ON
 * @property integer $CHANGED_BY
 */
class Meals extends ActiveRecordECPriv
{
	public $date;
	public $hour;
	public $minute;
	
	public function attributeNames(){
		$names = parent::attributeNames();
		return array_merge($names, array('date', 'hour', 'minute'));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Meals the static model class
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
		return 'meals';
	}

	public function afterFind(){
		if (isset($this->MEA_DATE) && $this->MEA_DATE != ''){
			$this->date = date('Y-m-d', $this->MEA_DATE);
			$this->hour = date('H', $this->MEA_DATE);
			$this->minute = date('i', $this->MEA_DATE);
		} else {
			$this->date = '';
			$this->hour = '';
			$this->minute = '';
		}
		parent::afterFind();
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MEA_PERC_GDA, CREATED_ON, CREATED_BY', 'required'),
			array('MEA_DATE, PRF_UID, CREATED_ON, CREATED_BY, CHANGED_ON, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('MEA_PERC_GDA, MTY_ID', 'numerical'),
			array('date, hour, minute, CHANGED_ON', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('MEA_ID, MEA_DATE, MTY_ID, MEA_PERC_GDA, PRF_UID, CREATED_ON, CREATED_BY, CHANGED_ON, CHANGED_BY', 'safe', 'on'=>'search'),
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
			'meaToCous' => array(self::HAS_MANY, 'MeaToCou', 'MEA_ID', 'order'=>'meaToCous.MTC_ORDER'),
			'mealType' => array(self::HAS_ONE, 'MealTypes', 'MTY_ID'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'MEA_ID' => 'Mea',
			'MEA_DATE' => 'Mea Date',
			'MTY_ID' => 'Mea Type',
			'MEA_PERC_GDA' => 'Mea Perc Gda',
			'PRF_UID' => 'Prf Uid',
			'CREATED_ON' => 'Created On',
			'CREATED_BY' => 'Created By',
			'CHANGED_ON' => 'Changed On',
			'CHANGED_BY' => 'Changed By',
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

		$criteria->compare('MEA_ID',$this->MEA_ID);
		$criteria->compare('MEA_DATE',$this->MEA_DATE);
		$criteria->compare('MTY_ID',$this->MTY_ID,true);
		$criteria->compare('MEA_PERC_GDA',$this->MEA_PERC_GDA);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}