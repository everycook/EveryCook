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
 * This is the model class for table "cooking_stove".
 *
 * The followings are the available columns in table 'cooking_stove':
 * @property integer $COS_ID
 * @property string $COS_MANUFACTURE
 * @property string $COS_MODEL
 * @property integer $COS_LEVELS
 * @property string $COS_CONTINIOUS
 * @property string $COS_MAESUREMENT
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class CookingStove extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CookingStove the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'cooking_stove';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('COS_MANUFACTURE, COS_MODEL, COS_LEVELS, COS_CONTINIOUS, COS_MAESUREMENT, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'required'),
			array('COS_LEVELS, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('COS_MANUFACTURE, COS_MODEL, COS_MAESUREMENT', 'length', 'max'=>100),
			array('COS_CONTINIOUS', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('COS_ID, COS_MANUFACTURE, COS_MODEL, COS_LEVELS, COS_CONTINIOUS, COS_MAESUREMENT, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'COS_ID' => 'Cos',
			'COS_MANUFACTURE' => 'Cos Manufacture',
			'COS_MODEL' => 'Cos Model',
			'COS_LEVELS' => 'Cos Levels',
			'COS_CONTINIOUS' => 'Cos Continious',
			'COS_MAESUREMENT' => 'Cos Maesurement',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('COS_ID', 'COS_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.COS_ID',$this->COS_ID);
		$criteria->compare($this->tableName().'.COS_MANUFACTURE',$this->COS_MANUFACTURE,true);
		$criteria->compare($this->tableName().'.COS_MODEL',$this->COS_MODEL,true);
		$criteria->compare($this->tableName().'.COS_LEVELS',$this->COS_LEVELS);
		$criteria->compare($this->tableName().'.COS_CONTINIOUS',$this->COS_CONTINIOUS,true);
		$criteria->compare($this->tableName().'.COS_MAESUREMENT',$this->COS_MAESUREMENT,true);
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

		$criteria->compare('COS_ID',$this->COS_ID);
		$criteria->compare('COS_MANUFACTURE',$this->COS_MANUFACTURE,true);
		$criteria->compare('COS_MODEL',$this->COS_MODEL,true);
		$criteria->compare('COS_LEVELS',$this->COS_LEVELS);
		$criteria->compare('COS_CONTINIOUS',$this->COS_CONTINIOUS,true);
		$criteria->compare('COS_MAESUREMENT',$this->COS_MAESUREMENT,true);
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
				'asc' => 'COS_ID',
				'desc' => 'COS_ID DESC',
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