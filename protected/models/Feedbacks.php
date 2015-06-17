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
 * This is the model class for table "feedbacks".
 *
 * The followings are the available columns in table 'feedbacks':
 * @property integer $FEE_ID
 * @property string $FEE_LANG
 * @property string $FEE_TITLE
 * @property string $FEE_TEXT
 * @property string $FEE_EMAIL
 * @property integer $FEE_STATUS
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Feedbacks extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Feedbacks the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'feedbacks';
	}
	

	protected function beforeValidate() {
		if(Yii::app()->user->isGuest) {
			$dateTime = new DateTime();
			$userId = 0; // Yii::app()->user->id
			if($this->getIsNewRecord()) {
				// get UnixTimeStamp
				if ($this->updateChangePointer || !isset($this->CREATED_ON) || $this->CREATED_ON == null){
					$this->CREATED_ON = $dateTime->getTimestamp();
					$this->CREATED_BY = $userId;
				}
			}
				
			if ($this->updateChangePointer){
				if ($this->updateChangeTime){
					$this->CHANGED_ON = $dateTime->getTimestamp();
				}
				$this->CHANGED_BY = $userId;
			}
			return true;
		}
		return parent::beforeValidate();
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FEE_LANG, FEE_TEXT, CREATED_ON, CHANGED_ON', 'required'),
			array('FEE_STATUS, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('FEE_LANG', 'length', 'max'=>8),
			array('FEE_TITLE, FEE_TEXT, FEE_EMAIL', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('FEE_ID, FEE_LANG, FEE_TITLE, FEE_TEXT, FEE_EMAIL, FEE_STATUS, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'FEE_ID' => 'Fee',
			'FEE_LANG' => 'Fee Lang',
			'FEE_TITLE' => 'Fee Title',
			'FEE_TEXT' => 'Fee Text',
			'FEE_EMAIL' => 'Fee Email',
			'FEE_STATUS' => 'Fee Status',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('FEE_ID', 'FEE_TITLE', 'FEE_TEXT');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.FEE_ID',$this->FEE_ID);
		$criteria->compare($this->tableName().'.FEE_LANG',$this->FEE_LANG,true);
		$criteria->compare($this->tableName().'.FEE_TITLE',$this->FEE_TITLE,true);
		$criteria->compare($this->tableName().'.FEE_TEXT',$this->FEE_TEXT,true);
		$criteria->compare($this->tableName().'.FEE_EMAIL',$this->FEE_EMAIL,true);
		$criteria->compare($this->tableName().'.FEE_STATUS',$this->FEE_STATUS);
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

		$criteria->compare('FEE_ID',$this->FEE_ID);
		$criteria->compare('FEE_LANG',$this->FEE_LANG,true);
		$criteria->compare('FEE_TITLE',$this->FEE_TITLE,true);
		$criteria->compare('FEE_TEXT',$this->FEE_TEXT,true);
		$criteria->compare('FEE_EMAIL',$this->FEE_EMAIL,true);
		$criteria->compare('FEE_STATUS',$this->FEE_STATUS);
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
				'asc' => 'FEE_ID',
				'desc' => 'FEE_ID DESC',
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

	public function findFeedbacks($limit=null)
	{
		return $this->findAll(array(
				'order'=>'t.FEE_ID DESC',
				'limit'=>$limit,
		));
	}
}