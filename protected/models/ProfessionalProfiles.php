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
 * This is the model class for table "professional_profiles".
 *
 * The followings are the available columns in table 'professional_profiles':
 * @property integer $PRF_UID
 * @property string $PRF_FIRSTNAME
 * @property string $PRF_LASTNAME
 * @property string $PRF_LANG
 * @property string $PRF_IMG_FILENAME
 * @property string $PRF_IMG_ETAG
 * @property string $PRF_WORK_TITLE
 * @property string $PRF_WORK_LOCATION
 * @property string $PRF_CUT_IDS
 * @property string $PRF_PHILOSOPHY
 * @property string $PRF_EXPERIENCE
 * @property string $PRF_AWARDS
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class ProfessionalProfiles extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProfessionalProfiles the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'professional_profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRF_UID, PRF_LANG', 'required'),
			array('PRF_UID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('PRF_FIRSTNAME, PRF_LASTNAME', 'length', 'max'=>100),
			array('PRF_LANG', 'length', 'max'=>10),
			array('PRF_IMG_FILENAME', 'length', 'max'=>250),
			array('PRF_IMG_ETAG', 'length', 'max'=>40),
			array('PRF_WORK_TITLE, PRF_WORK_LOCATION, PRF_CUT_IDS', 'length', 'max'=>200),
			array('PRF_PHILOSOPHY, PRF_EXPERIENCE, PRF_AWARDS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRF_UID, PRF_FIRSTNAME, PRF_LASTNAME, PRF_LANG, PRF_IMG_FILENAME, PRF_IMG_ETAG, PRF_WORK_TITLE, PRF_WORK_LOCATION, PRF_CUT_IDS, PRF_PHILOSOPHY, PRF_EXPERIENCE, PRF_AWARDS, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'PRF_UID' => 'Prf Uid',
			'PRF_FIRSTNAME' => 'Prf Firstname',
			'PRF_LASTNAME' => 'Prf Lastname',
			'PRF_LANG' => 'Prf Lang',
			'PRF_IMG_FILENAME' => 'Prf Img Filename',
			'PRF_IMG_ETAG' => 'Prf Img Etag',
			'PRF_WORK_TITLE' => 'Prf Work Title',
			'PRF_WORK_LOCATION' => 'Prf Work Location',
			'PRF_CUT_IDS' => 'Prf Cut Ids',
			'PRF_PHILOSOPHY' => 'Prf Philosophy',
			'PRF_EXPERIENCE' => 'Prf Experience',
			'PRF_AWARDS' => 'Prf Awards',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('PRF_UID', 'PRF_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.PRF_FIRSTNAME',$this->PRF_FIRSTNAME,true);
		$criteria->compare($this->tableName().'.PRF_LASTNAME',$this->PRF_LASTNAME,true);
		$criteria->compare($this->tableName().'.PRF_LANG',$this->PRF_LANG,true);
		$criteria->compare($this->tableName().'.PRF_IMG_FILENAME',$this->PRF_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.PRF_IMG_ETAG',$this->PRF_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.PRF_WORK_TITLE',$this->PRF_WORK_TITLE,true);
		$criteria->compare($this->tableName().'.PRF_WORK_LOCATION',$this->PRF_WORK_LOCATION,true);
		$criteria->compare($this->tableName().'.PRF_CUT_IDS',$this->PRF_CUT_IDS,true);
		$criteria->compare($this->tableName().'.PRF_PHILOSOPHY',$this->PRF_PHILOSOPHY,true);
		$criteria->compare($this->tableName().'.PRF_EXPERIENCE',$this->PRF_EXPERIENCE,true);
		$criteria->compare($this->tableName().'.PRF_AWARDS',$this->PRF_AWARDS,true);
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

		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('PRF_FIRSTNAME',$this->PRF_FIRSTNAME,true);
		$criteria->compare('PRF_LASTNAME',$this->PRF_LASTNAME,true);
		$criteria->compare('PRF_LANG',$this->PRF_LANG,true);
		$criteria->compare('PRF_IMG_FILENAME',$this->PRF_IMG_FILENAME,true);
		$criteria->compare('PRF_IMG_ETAG',$this->PRF_IMG_ETAG,true);
		$criteria->compare('PRF_WORK_TITLE',$this->PRF_WORK_TITLE,true);
		$criteria->compare('PRF_WORK_LOCATION',$this->PRF_WORK_LOCATION,true);
		$criteria->compare('PRF_CUT_IDS',$this->PRF_CUT_IDS,true);
		$criteria->compare('PRF_PHILOSOPHY',$this->PRF_PHILOSOPHY,true);
		$criteria->compare('PRF_EXPERIENCE',$this->PRF_EXPERIENCE,true);
		$criteria->compare('PRF_AWARDS',$this->PRF_AWARDS,true);
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
				'asc' => 'PRF_UID',
				'desc' => 'PRF_UID DESC',
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