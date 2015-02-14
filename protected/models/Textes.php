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
 * This is the model class for table "textes".
 *
 * The followings are the available columns in table 'textes':
 * @property string $TXT_NAME
 * @property string $EN_GB
 * @property string $DE_CH
 * @property string $FR_FR
 */
class Textes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Textes the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'textes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TXT_NAME, EN_GB, DE_CH', 'required'),
			array('TXT_NAME', 'length', 'max'=>100),
			array('EN_GB, DE_CH, FR_FR', 'length', 'max'=>300),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('TXT_NAME, EN_GB, DE_CH, FR_FR', 'safe', 'on'=>'search'),
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
		return self::attributeLabelsStatic();
	}
	public static function attributeLabelsStatic(){
		return array(
			'TXT_NAME' => 'Text ID',
			'EN_GB' => 'Text English (EN_GB)',
			'DE_CH' => 'Text Deutsch (DE_CH)',
			'FR_FR' => 'Text Französisch (FR_FR)',
		);
	}
	
	public function getSearchFields(){
		return array('TXT_NAME', 'EN_GB', 'DE_CH', 'FR_FR');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.TXT_NAME',$this->TXT_NAME,true);
		$criteria->compare($this->tableName().'.EN_GB',$this->EN_GB,true);
		$criteria->compare($this->tableName().'.DE_CH',$this->DE_CH,true);
		$criteria->compare($this->tableName().'.FR_FR',$this->FR_FR,true);
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('TXT_NAME',$this->TXT_NAME,true);
		$criteria->compare('EN_GB',$this->EN_GB,true);
		$criteria->compare('DE_CH',$this->DE_CH,true);
		$criteria->compare('FR_FR',$this->FR_FR,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'TXT_NAME',
				'desc' => 'TXT_NAME DESC',
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