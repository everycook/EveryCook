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
 * This is the model class for table "shoppinglists".
 *
 * The followings are the available columns in table 'shoppinglists':
 * @property integer $SHO_ID
 * @property integer $SHO_DATE
 * @property string $SHO_RECIPES
 * @property string $SHO_INGREDIENTS
 * @property string $SHO_WEIGHTS
 * @property string $SHO_PRODUCTS
 * @property string $SHO_QUANTITIES
 * @property string $SHO_HAVE_IT
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Shoppinglists extends ActiveRecordECPriv
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Shoppinglists the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'shoppinglists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SHO_DATE, SHO_INGREDIENTS, SHO_WEIGHTS, CREATED_BY, CREATED_ON', 'required'),
			array('SHO_DATE, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('SHO_RECIPES, SHO_PRODUCTS, SHO_QUANTITIES, SHO_HAVE_IT', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SHO_ID, SHO_DATE, SHO_RECIPES, SHO_INGREDIENTS, SHO_WEIGHTS, SHO_PRODUCTS, SHO_QUANTITIES, SHO_HAVE_IT, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'SHO_ID' => 'Sho',
			'SHO_DATE' => 'Sho Date',
			'SHO_RECIPES' => 'Sho Recipes',
			'SHO_INGREDIENTS' => 'Sho Ingredients',
			'SHO_WEIGHTS' => 'Sho Weights',
			'SHO_PRODUCTS' => 'Sho Products',
			'SHO_QUANTITIES' => 'Sho Quantities',
			'SHO_HAVE_IT' => 'Sho Have It',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('SHO_ID', 'SHO_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.SHO_ID',$this->SHO_ID);
		$criteria->compare($this->tableName().'.SHO_DATE',$this->SHO_DATE);
		$criteria->compare($this->tableName().'.SHO_RECIPES',$this->SHO_RECIPES,true);
		$criteria->compare($this->tableName().'.SHO_INGREDIENTS',$this->SHO_INGREDIENTS,true);
		$criteria->compare($this->tableName().'.SHO_WEIGHTS',$this->SHO_WEIGHTS,true);
		$criteria->compare($this->tableName().'.SHO_PRODUCTS',$this->SHO_PRODUCTS,true);
		$criteria->compare($this->tableName().'.SHO_QUANTITIES',$this->SHO_QUANTITIES,true);
		$criteria->compare($this->tableName().'.SHO_HAVE_IT',$this->SHO_HAVE_IT,true);
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

		$criteria->compare('SHO_ID',$this->SHO_ID);
		$criteria->compare('SHO_DATE',$this->SHO_DATE);
		$criteria->compare('SHO_RECIPES',$this->SHO_RECIPES,true);
		$criteria->compare('SHO_INGREDIENTS',$this->SHO_INGREDIENTS,true);
		$criteria->compare('SHO_WEIGHTS',$this->SHO_WEIGHTS,true);
		$criteria->compare('SHO_PRODUCTS',$this->SHO_PRODUCTS,true);
		$criteria->compare('SHO_QUANTITIES',$this->SHO_QUANTITIES,true);
		$criteria->compare('SHO_HAVE_IT',$this->SHO_HAVE_IT,true);
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
				'asc' => 'SHO_ID',
				'desc' => 'SHO_ID DESC',
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