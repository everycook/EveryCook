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
 * This is the model class for table "recipes".
 *
 * The followings are the available columns in table 'recipes':
 * @property integer $REC_ID
 * @property integer $PRF_UID
 * @property string $REC_IMG_FILENAME
 * @property string $REC_IMG_AUTH
 * @property string $REC_IMG_ETAG
 * @property integer $RET_ID
 * @property integer $REC_KCAL
 * @property string $REC_HAS_ALLERGY_INFO
 * @property string $REC_SUMMARY
 * @property string $REC_APPROVED
 * @property integer $REC_SERVING_COUNT
 * @property string $REC_WIKI_LINK
 * @property string $REC_IS_PRIVATE
 * @property integer $REC_COMPLEXITY
 * @property integer $CUT_ID
 * @property integer $CST_ID
 * @property double $REC_CUSINE_GPS_LAT
 * @property double $REC_CUSINE_GPS_LNG
 * @property string $REC_TOOLS
 * @property string $REC_SYNONYM_EN_GB
 * @property string $REC_SYNONYM_DE_CH
 * @property string $REC_NAME_EN_GB
 * @property string $REC_NAME_DE_CH
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Recipes extends ActiveRecordEC
{
	public $filename;
	public $imagechanged;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Recipes the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'recipes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RET_ID, REC_SERVING_COUNT, REC_NAME_EN_GB, CREATED_BY, CREATED_ON', 'required'),
			array('REC_IMG_AUTH', 'required', 'on'=>'withPic'),
			array('REC_ID, PRF_UID, RET_ID, REC_KCAL, REC_SERVING_COUNT, REC_COMPLEXITY, CUT_ID, CST_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('REC_CUSINE_GPS_LAT, REC_CUSINE_GPS_LNG', 'numerical'),
			array('REC_IMG_FILENAME', 'length', 'max'=>250),
			array('REC_IMG_AUTH', 'length', 'max'=>30),
			array('REC_IMG_ETAG', 'length', 'max'=>40),
			array('REC_HAS_ALLERGY_INFO, REC_APPROVED, REC_IS_PRIVATE', 'length', 'max'=>1),
			array('REC_SUMMARY, REC_WIKI_LINK, REC_SYNONYM_EN_GB, REC_SYNONYM_DE_CH', 'length', 'max'=>200),
			array('REC_TOOLS, REC_NAME_EN_GB, REC_NAME_DE_CH', 'length', 'max'=>100),
			array('RET_ID, steps', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, PRF_UID, REC_IMG_FILENAME, REC_IMG_AUTH, REC_IMG_ETAG, RET_ID, REC_KCAL, REC_HAS_ALLERGY_INFO, REC_SUMMARY, REC_APPROVED, REC_SERVING_COUNT, REC_WIKI_LINK, REC_IS_PRIVATE, REC_COMPLEXITY, CUT_ID, CST_ID, REC_CUSINE_GPS_LAT, REC_CUSINE_GPS_LNG, REC_TOOLS, REC_SYNONYM_EN_GB, REC_SYNONYM_DE_CH, REC_NAME_EN_GB, REC_NAME_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'recipeTypes' => array(self::BELONGS_TO, 'RecipeTypes', 'RET_ID'),
			'recToCois' => array(self::HAS_MANY, 'RecToCoi', 'REC_ID'),
			'steps' => array(self::HAS_MANY, 'Steps', 'REC_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'REC_ID' => 'Rec',
			'PRF_UID' => 'Prf Uid',
			'REC_IMG_FILENAME' => 'Rec Img Filename',
			'REC_IMG_AUTH' => 'Rec Img Auth',
			'REC_IMG_ETAG' => 'Rec Img Etag',
			'RET_ID' => 'Ret',
			'REC_KCAL' => 'Rec Kcal',
			'REC_HAS_ALLERGY_INFO' => 'Rec Has Allergy Info',
			'REC_SUMMARY' => 'Rec Summary',
			'REC_APPROVED' => 'Rec Approved',
			'REC_SERVING_COUNT' => 'Rec Serving Count',
			'REC_WIKI_LINK' => 'Rec Wiki Link',
			'REC_IS_PRIVATE' => 'Rec Is Private',
			'REC_COMPLEXITY' => 'Rec Complexity',
			'CUT_ID' => 'Cut',
			'CST_ID' => 'Cst',
			'REC_CUSINE_GPS_LAT' => 'Rec Cusine Gps Lat',
			'REC_CUSINE_GPS_LNG' => 'Rec Cusine Gps Lng',
			'REC_TOOLS' => 'Rec Tools',
			'REC_SYNONYM_EN_GB' => 'Rec Synonym En Gb',
			'REC_SYNONYM_DE_CH' => 'Rec Synonym De Ch',
			'REC_NAME_EN_GB' => 'Rec Name En Gb',
			'REC_NAME_DE_CH' => 'Rec Name De Ch',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}

	public function getSearchFields(){
		return array('REC_ID', 'REC_NAME_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.REC_IMG_FILENAME',$this->REC_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.REC_IMG_AUTH',$this->REC_IMG_AUTH,true);
		$criteria->compare($this->tableName().'.REC_IMG_ETAG',$this->REC_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.RET_ID',$this->RET_ID);
		$criteria->compare($this->tableName().'.REC_KCAL',$this->REC_KCAL);
		$criteria->compare($this->tableName().'.REC_HAS_ALLERGY_INFO',$this->REC_HAS_ALLERGY_INFO,true);
		$criteria->compare($this->tableName().'.REC_SUMMARY',$this->REC_SUMMARY,true);
		$criteria->compare($this->tableName().'.REC_APPROVED',$this->REC_APPROVED,true);
		$criteria->compare($this->tableName().'.REC_SERVING_COUNT',$this->REC_SERVING_COUNT);
		$criteria->compare($this->tableName().'.REC_WIKI_LINK',$this->REC_WIKI_LINK,true);
		$criteria->compare($this->tableName().'.REC_IS_PRIVATE',$this->REC_IS_PRIVATE,true);
		$criteria->compare($this->tableName().'.REC_COMPLEXITY',$this->REC_COMPLEXITY);
		$criteria->compare($this->tableName().'.CUT_ID',$this->CUT_ID);
		$criteria->compare($this->tableName().'.CST_ID',$this->CST_ID);
		$criteria->compare($this->tableName().'.REC_CUSINE_GPS_LAT',$this->REC_CUSINE_GPS_LAT);
		$criteria->compare($this->tableName().'.REC_CUSINE_GPS_LNG',$this->REC_CUSINE_GPS_LNG);
		$criteria->compare($this->tableName().'.REC_TOOLS',$this->REC_TOOLS,true);
		$criteria->compare($this->tableName().'.REC_SYNONYM_EN_GB',$this->REC_SYNONYM_EN_GB,true);
		$criteria->compare($this->tableName().'.REC_SYNONYM_DE_CH',$this->REC_SYNONYM_DE_CH,true);
		$criteria->compare($this->tableName().'.REC_NAME_EN_GB',$this->REC_NAME_EN_GB,true);
		$criteria->compare($this->tableName().'.REC_NAME_DE_CH',$this->REC_NAME_DE_CH,true);
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

		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('REC_IMG_FILENAME',$this->REC_IMG_FILENAME,true);
		$criteria->compare('REC_IMG_AUTH',$this->REC_IMG_AUTH,true);
		$criteria->compare('REC_IMG_ETAG',$this->REC_IMG_ETAG,true);
		$criteria->compare('RET_ID',$this->RET_ID);
		$criteria->compare('REC_KCAL',$this->REC_KCAL);
		$criteria->compare('REC_HAS_ALLERGY_INFO',$this->REC_HAS_ALLERGY_INFO,true);
		$criteria->compare('REC_SUMMARY',$this->REC_SUMMARY,true);
		$criteria->compare('REC_APPROVED',$this->REC_APPROVED,true);
		$criteria->compare('REC_SERVING_COUNT',$this->REC_SERVING_COUNT);
		$criteria->compare('REC_WIKI_LINK',$this->REC_WIKI_LINK,true);
		$criteria->compare('REC_IS_PRIVATE',$this->REC_IS_PRIVATE,true);
		$criteria->compare('REC_COMPLEXITY',$this->REC_COMPLEXITY);
		$criteria->compare('CUT_ID',$this->CUT_ID);
		$criteria->compare('CST_ID',$this->CST_ID);
		$criteria->compare('REC_CUSINE_GPS_LAT',$this->REC_CUSINE_GPS_LAT);
		$criteria->compare('REC_CUSINE_GPS_LNG',$this->REC_CUSINE_GPS_LNG);
		$criteria->compare('REC_TOOLS',$this->REC_TOOLS,true);
		$criteria->compare('REC_SYNONYM_EN_GB',$this->REC_SYNONYM_EN_GB,true);
		$criteria->compare('REC_SYNONYM_DE_CH',$this->REC_SYNONYM_DE_CH,true);
		$criteria->compare('REC_NAME_EN_GB',$this->REC_NAME_EN_GB,true);
		$criteria->compare('REC_NAME_DE_CH',$this->REC_NAME_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		//Add with conditions for relations
		$criteria->with = array('steps' => array('with' => 'ingredient', 'with' => 'stepType'));
		$criteria->with = array('recipeTypes');
		
		$criteria->compare('ING_ID',$this->steps->ingredient,true);
		$criteria->compare('STT_ID',$this->steps->stepType,true);
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		/*
		$sort->attributes = array(
			'nutrientData' => array(
				'asc' => 'NUT_ID',
				'desc' => 'NUT_ID DESC',
			),
			'groupNames' => array(
				'asc' => 'GRP_ID',
				'desc' => 'GRP_ID DESC',
			),
			'subgroupNames' => array(
				'asc' => 'SGR_ID',
				'desc' => 'SGR_ID DESC',
			),
			'ingredientConveniences' => array(
				'asc' => 'ICO_ID',
				'desc' => 'ICO_ID DESC',
			),
			'storability' => array(
				'asc' => 'STB_ID',
				'desc' => 'STB_ID DESC',
			),
			'*',
		);
		*/
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
