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
 * This is the model class for table "courses".
 *
 * The followings are the available columns in table 'courses':
 * @property integer $COU_ID
 * @property string $COU_DESC
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Courses extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Courses the static model class
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
		return 'courses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CREATED_BY, CREATED_ON', 'required'),
			array('CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('COU_DESC', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('COU_ID, COU_DESC, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'couToRecs' => array(self::HAS_MANY, 'CouToRec', 'COU_ID', 'order'=>'couToRecs.CTR_ORDER'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'COU_ID' => 'Cou',
			'COU_DESC' => 'Cou Desc',
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

		$criteria->compare('COU_ID',$this->COU_ID);
		$criteria->compare('COU_DESC',$this->COU_DESC,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}