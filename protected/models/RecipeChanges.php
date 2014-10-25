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
 * This is the model class for table "recipe_changes".
 *
 * The followings are the available columns in table 'recipe_changes':
 * @property integer $REC_ID
 * @property string $RCH_FIELD
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 * @property string $RCH_OLD_VALUE
 * @property string $RCH_NEW_VALUE
 */
class RecipeChanges extends ActiveRecordECChange
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RecipeChanges the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'recipe_changes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('REC_ID, RCH_FIELD, CHANGED_BY, CHANGED_ON', 'required'),
			array('REC_ID, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('RCH_FIELD', 'length', 'max'=>30),
			array('RCH_OLD_VALUE, RCH_NEW_VALUE', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, RCH_FIELD, CHANGED_BY, CHANGED_ON, RCH_OLD_VALUE, RCH_NEW_VALUE', 'safe', 'on'=>'search'),
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
			'REC_ID' => 'Rec',
			'RCH_FIELD' => 'Rch Field',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
			'RCH_OLD_VALUE' => 'Rch Old Value',
			'RCH_NEW_VALUE' => 'Rch New Value',
		);
	}
	
	public function getSearchFields(){
		return array('REC_ID', 'REC_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.RCH_FIELD',$this->RCH_FIELD,true);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
		$criteria->compare($this->tableName().'.RCH_OLD_VALUE',$this->RCH_OLD_VALUE,true);
		$criteria->compare($this->tableName().'.RCH_NEW_VALUE',$this->RCH_NEW_VALUE,true);
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('RCH_FIELD',$this->RCH_FIELD,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		$criteria->compare('RCH_OLD_VALUE',$this->RCH_OLD_VALUE,true);
		$criteria->compare('RCH_NEW_VALUE',$this->RCH_NEW_VALUE,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'REC_ID',
				'desc' => 'REC_ID DESC',
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