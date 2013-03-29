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
 * This is the model class for table "recipe_votings".
 *
 * The followings are the available columns in table 'recipe_votings':
 * @property integer $RVO_ID
 * @property integer $PRF_UID
 * @property integer $MEA_ID
 * @property integer $COU_ID
 * @property integer $REC_ID
 * @property integer $RVO_COOK_DATE
 * @property integer $RVO_VALUE
 * @property integer $RVR_ID
 * @property string $RVO_REASON
 */
class RecipeVotings extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RecipeVotings the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'recipe_votings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MEA_ID, COU_ID, REC_ID, RVO_COOK_DATE, RVO_VALUE', 'required'),
			array('PRF_UID, MEA_ID, COU_ID, REC_ID, RVO_COOK_DATE, RVO_VALUE, RVR_ID', 'numerical', 'integerOnly'=>true),
			array('RVO_REASON', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('RVO_ID, PRF_UID, MEA_ID, COU_ID, REC_ID, RVO_COOK_DATE, RVO_VALUE, RVR_ID, RVO_REASON', 'safe', 'on'=>'search'),
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
			'RVO_ID' => 'Rvo',
			'PRF_UID' => 'Prf Uid',
			'MEA_ID' => 'Mea',
			'COU_ID' => 'Cou',
			'REC_ID' => 'Rec',
			'RVO_COOK_DATE' => 'Rvo Cook Date',
			'RVO_VALUE' => 'Rvo Value',
			'RVR_ID' => 'Rvr',
			'RVO_REASON' => 'Rvo Reason',
		);
	}
	
	public function getSearchFields(){
		return array('RVO_ID', 'RVO_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.RVO_ID',$this->RVO_ID);
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.MEA_ID',$this->MEA_ID);
		$criteria->compare($this->tableName().'.COU_ID',$this->COU_ID);
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.RVO_COOK_DATE',$this->RVO_COOK_DATE);
		$criteria->compare($this->tableName().'.RVO_VALUE',$this->RVO_VALUE);
		$criteria->compare($this->tableName().'.RVR_ID',$this->RVR_ID);
		$criteria->compare($this->tableName().'.RVO_REASON',$this->RVO_REASON,true);
		
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('RVO_ID',$this->RVO_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('MEA_ID',$this->MEA_ID);
		$criteria->compare('COU_ID',$this->COU_ID);
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('RVO_COOK_DATE',$this->RVO_COOK_DATE);
		$criteria->compare('RVO_VALUE',$this->RVO_VALUE);
		$criteria->compare('RVR_ID',$this->RVR_ID);
		$criteria->compare('RVO_REASON',$this->RVO_REASON,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'RVO_ID',
				'desc' => 'RVO_ID DESC',
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