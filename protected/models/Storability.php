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
 * This is the model class for table "storability".
 *
 * The followings are the available columns in table 'storability':
 * @property integer $STB_ID
 * @property string $STB_DESC_EN_GB
 * @property string $STB_DESC_DE_CH
 */
class Storability extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Storability the static model class
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
		return 'storability';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STB_DESC_EN_GB, STB_DESC_DE_CH', 'required'),
			array('STB_DESC_EN_GB, STB_DESC_DE_CH', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STB_ID, STB_DESC_EN_GB, STB_DESC_DE_CH', 'safe', 'on'=>'search'),
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
			'STB_ID' => 'Stb',
			'STB_DESC_EN_GB' => 'Stb Desc En Gb',
			'STB_DESC_DE_CH' => 'Stb Desc De Ch',
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

		$criteria->compare('STB_ID',$this->STB_ID);
		$criteria->compare('STB_DESC_EN_GB',$this->STB_DESC_EN_GB,true);
		$criteria->compare('STB_DESC_DE_CH',$this->STB_DESC_DE_CH,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
