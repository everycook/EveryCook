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
 * This is the model class for table "ingredients".
 *
 * The followings are the available columns in table 'ingredients':
 * @property integer $ING_ID
 * @property integer $PRF_UID
 * @property integer $NUT_ID
 * @property integer $GRP_ID
 * @property integer $SGR_ID
 * @property integer $IST_ID
 * @property integer $ICO_ID
 * @property integer $ORI_ID
 * @property integer $STB_ID
 * @property integer $CND_ID
 * @property integer $TGR_ID
 * @property integer $ING_MIN_TEMP
 * @property integer $ING_MAX_TEMP
 * @property string $ING_FREEZER
 * @property double $ING_DENSITY
 * @property string $ING_IMG_FILENAME
 * @property string $ING_IMG_AUTH
 * @property string $ING_IMG_ETAG
 * @property string $ING_HAS_ALLERGY_INFO
 * @property string $ING_NEED_PEELING
 * @property string $ING_NEED_WASH
 * @property integer $ING_SCALE_PRECISION
 * @property string $ING_APPROVED
 * @property string $ING_WIKI_LINK
 * @property integer $ING_WEIGHT_SMALL
 * @property integer $ING_WEIGHT_BIG
 * @property string $ING_SYNONYM_EN_GB
 * @property string $ING_SYNONYM_DE_CH
 * @property string $ING_NAME_EN_GB
 * @property string $ING_NAME_DE_CH
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Ingredients extends ActiveRecordEC
{
	public $filename;
	public $imagechanged;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ingredients the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'ingredients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('GRP_ID, IST_ID, ICO_ID, ORI_ID, STB_ID, CND_ID, TGR_ID, ING_FREEZER, ING_NAME_EN_GB, CREATED_BY, CREATED_ON', 'required'),
			array('PRF_UID, NUT_ID, GRP_ID, SGR_ID, IST_ID, ICO_ID, ORI_ID, STB_ID, CND_ID, TGR_ID, ING_MIN_TEMP, ING_MAX_TEMP, ING_SCALE_PRECISION, ING_WEIGHT_SMALL, ING_WEIGHT_BIG, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('ING_IMG_AUTH', 'required', 'on'=>'withPic'),
			array('ING_DENSITY', 'numerical'),
			array('ING_FREEZER, ING_HAS_ALLERGY_INFO, ING_NEED_PEELING, ING_NEED_WASH, ING_APPROVED', 'length', 'max'=>1),
			array('ING_IMG_FILENAME', 'length', 'max'=>250),
			array('ING_IMG_AUTH', 'length', 'max'=>30),
			array('ING_IMG_ETAG', 'length', 'max'=>40),
			array('ING_WIKI_LINK, ING_SYNONYM_EN_GB, ING_SYNONYM_DE_CH', 'length', 'max'=>200),
			array('ING_NAME_EN_GB, ING_NAME_DE_CH', 'length', 'max'=>100),
			array('CHANGED_ON, ING_IMG_ETAG, nutrientData, groupNames, subgroupNames, ingredientConveniences, storability, ingredientStates', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ING_ID, PRF_UID, NUT_ID, GRP_ID, SGR_ID, IST_ID, ICO_ID, ORI_ID, STB_ID, CND_ID, TGR_ID, ING_MIN_TEMP, ING_MAX_TEMP, ING_FREEZER, ING_DENSITY, ING_IMG_FILENAME, ING_IMG_AUTH, ING_IMG_ETAG, ING_HAS_ALLERGY_INFO, ING_NEED_PEELING, ING_NEED_WASH, ING_SCALE_PRECISION, ING_APPROVED, ING_WIKI_LINK, ING_WEIGHT_SMALL, ING_WEIGHT_BIG, ING_SYNONYM_EN_GB, ING_SYNONYM_DE_CH, ING_NAME_EN_GB, ING_NAME_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'nutrientData' => array(self::BELONGS_TO, 'NutrientData', 'NUT_ID'),
			'groupNames' => array(self::BELONGS_TO, 'GroupNames', 'GRP_ID'),
			'subgroupNames' => array(self::BELONGS_TO, 'SubgroupNames', 'SGR_ID'),
			'origins' => array(self::BELONGS_TO, 'Origins', 'ORI_ID'),
			'ingredientConveniences' => array(self::BELONGS_TO, 'IngredientConveniences', 'ICO_ID'),
			'storability' => array(self::BELONGS_TO, 'Storability', 'STB_ID'),
			'ingredientStates' => array(self::BELONGS_TO, 'IngredientStates', 'IST_ID'),
			'conditions' => array(self::BELONGS_TO, 'Conditions', 'CND_ID'),
			'tempGroups' => array(self::BELONGS_TO, 'TempGroups', 'TGR_ID'),
			'ingToIngs' => array(self::HAS_MANY, 'IngToIng', 'ING_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'ING_ID' => 'Ing',
			'PRF_UID' => 'Prf Uid',
			'NUT_ID' => 'Nut',
			'GRP_ID' => 'Ing Group',
			'SGR_ID' => 'Ing Subgroup',
			'IST_ID' => 'Ing State',
			'ICO_ID' => 'Ing Convenience',
			'ORI_ID' => 'Ori',
			'STB_ID' => 'Ing Storability',
			'CND_ID' => 'ing Condition',
			'TGR_ID' => 'img temp group',
			'ING_MIN_TEMP' => 'Ing Min Temp',
			'ING_MAX_TEMP' => 'Ing Max Temp',
			'ING_FREEZER' => 'Ing Freezer',
			'ING_DENSITY' => 'Ing Density',
			'ING_IMG_FILENAME' => 'Ing Img Filename',
			'ING_IMG_AUTH' => 'Ing Img Auth',
			'ING_IMG_ETAG' => 'Ing Img Etag',
			'ING_HAS_ALLERGY_INFO' => 'Ing Has Allergy Info',
			'ING_NEED_PEELING' => 'Ing Need Peeling',
			'ING_NEED_WASH' => 'Ing Need Wash',
			'ING_SCALE_PRECISION' => 'Ing Scale Precision',
			'ING_APPROVED' => 'Ing Approved',
			'ING_WIKI_LINK' => 'Ing Wiki Link',
			'ING_WEIGHT_SMALL' => 'Ing Weight Small',
			'ING_WEIGHT_BIG' => 'Ing Weight Big',
			'ING_SYNONYM_EN_GB' => 'Ing Synonym En Gb',
			'ING_SYNONYM_DE_CH' => 'Ing Synonym De Ch',
			'ING_NAME_EN_GB' => 'Ing Name En Gb',
			'ING_NAME_DE_CH' => 'Ing Name De Ch',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('ING_ID', 'ING_NAME_' . Yii::app()->session['lang']);
	}
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;

		$criteria->compare($this->tableName().'.ING_ID',$this->ING_ID);
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.NUT_ID',$this->NUT_ID);
		$criteria->compare($this->tableName().'.GRP_ID',$this->GRP_ID);
		$criteria->compare($this->tableName().'.SGR_ID',$this->SGR_ID);
		$criteria->compare($this->tableName().'.IST_ID',$this->IST_ID);
		$criteria->compare($this->tableName().'.ICO_ID',$this->ICO_ID);
		$criteria->compare($this->tableName().'.ORI_ID',$this->ORI_ID);
		$criteria->compare($this->tableName().'.STB_ID',$this->STB_ID);
		$criteria->compare($this->tableName().'.CND_ID',$this->CND_ID);
		$criteria->compare($this->tableName().'.TGR_ID',$this->TGR_ID);
		$criteria->compare($this->tableName().'.ING_MIN_TEMP',$this->ING_MIN_TEMP);
		$criteria->compare($this->tableName().'.ING_MAX_TEMP',$this->ING_MAX_TEMP);
		$criteria->compare($this->tableName().'.ING_FREEZER',$this->ING_FREEZER,true);
		$criteria->compare($this->tableName().'.ING_DENSITY',$this->ING_DENSITY);
		$criteria->compare($this->tableName().'.ING_IMG_FILENAME',$this->ING_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.ING_IMG_AUTH',$this->ING_IMG_AUTH,true);
		$criteria->compare($this->tableName().'.ING_IMG_ETAG',$this->ING_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.ING_HAS_ALLERGY_INFO',$this->ING_HAS_ALLERGY_INFO,true);
		$criteria->compare($this->tableName().'.ING_NEED_PEELING',$this->ING_NEED_PEELING,true);
		$criteria->compare($this->tableName().'.ING_NEED_WASH',$this->ING_NEED_WASH,true);
		$criteria->compare($this->tableName().'.ING_SCALE_PRECISION',$this->ING_SCALE_PRECISION);
		$criteria->compare($this->tableName().'.ING_APPROVED',$this->ING_APPROVED,true);
		$criteria->compare($this->tableName().'.ING_WIKI_LINK',$this->ING_WIKI_LINK,true);
		$criteria->compare($this->tableName().'.ING_WEIGHT_SMALL',$this->ING_WEIGHT_SMALL);
		$criteria->compare($this->tableName().'.ING_WEIGHT_BIG',$this->ING_WEIGHT_BIG);
		$criteria->compare($this->tableName().'.ING_SYNONYM_EN_GB',$this->ING_SYNONYM_EN_GB,true);
		$criteria->compare($this->tableName().'.ING_SYNONYM_DE_CH',$this->ING_SYNONYM_DE_CH,true);
		$criteria->compare($this->tableName().'.ING_NAME_EN_GB',$this->ING_NAME_EN_GB,true);
		$criteria->compare($this->tableName().'.ING_NAME_DE_CH',$this->ING_NAME_DE_CH,true);
		$criteria->compare($this->tableName().'.CREATED_BY',$this->CREATED_BY);
		$criteria->compare($this->tableName().'.CREATED_ON',$this->CREATED_ON);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
		
		/*
		$criteria->compare('nutrient_data.NUT_DESC',$this->nutrientData->NUT_DESC,true);
		$criteria->compare('group_names.GRP_DESC_DE',$this->groupNames->GRP_DESC_DE,true);
		$criteria->compare('subgroup_names.SGR_DESC_DE',$this->subgroupNames->SGR_DESC_DE,true);
		$criteria->compare('ingredient_conveniences.ICO_DESC_DE',$this->ingredientConveniences->ICO_DESC_DE,true);
		$criteria->compare('storability.STB_DESC_DE',$this->storability->STB_DESC_DE,true);
		*/
		return $criteria;
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;

		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('NUT_ID',$this->NUT_ID);
		$criteria->compare('GRP_ID',$this->GRP_ID);
		$criteria->compare('SGR_ID',$this->SGR_ID);
		$criteria->compare('IST_ID',$this->IST_ID);
		$criteria->compare('ICO_ID',$this->ICO_ID);
		$criteria->compare('ORI_ID',$this->ORI_ID);
		$criteria->compare('STB_ID',$this->STB_ID);
		$criteria->compare('CND_ID',$this->CND_ID);
		$criteria->compare('TGR_ID',$this->TGR_ID);
		$criteria->compare('ING_MIN_TEMP',$this->ING_MIN_TEMP);
		$criteria->compare('ING_MAX_TEMP',$this->ING_MAX_TEMP);
		$criteria->compare('ING_FREEZER',$this->ING_FREEZER,true);
		$criteria->compare('ING_DENSITY',$this->ING_DENSITY);
		$criteria->compare('ING_IMG_FILENAME',$this->ING_IMG_FILENAME,true);
		$criteria->compare('ING_IMG_AUTH',$this->ING_IMG_AUTH,true);
		$criteria->compare('ING_IMG_ETAG',$this->ING_IMG_ETAG,true);
		$criteria->compare('ING_HAS_ALLERGY_INFO',$this->ING_HAS_ALLERGY_INFO,true);
		$criteria->compare('ING_NEED_PEELING',$this->ING_NEED_PEELING,true);
		$criteria->compare('ING_NEED_WASH',$this->ING_NEED_WASH,true);
		$criteria->compare('ING_SCALE_PRECISION',$this->ING_SCALE_PRECISION);
		$criteria->compare('ING_APPROVED',$this->ING_APPROVED,true);
		$criteria->compare('ING_WIKI_LINK',$this->ING_WIKI_LINK,true);
		$criteria->compare('ING_WEIGHT_SMALL',$this->ING_WEIGHT_SMALL);
		$criteria->compare('ING_WEIGHT_BIG',$this->ING_WEIGHT_BIG);
		$criteria->compare('ING_SYNONYM_EN_GB',$this->ING_SYNONYM_EN_GB,true);
		$criteria->compare('ING_SYNONYM_DE_CH',$this->ING_SYNONYM_DE_CH,true);
		$criteria->compare('ING_NAME_EN_GB',$this->ING_NAME_EN_GB,true);
		$criteria->compare('ING_NAME_DE_CH',$this->ING_NAME_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		
		$criteria->with = array('nutrientData' => array());
		$criteria->with = array('groupNames' => array());
		$criteria->with = array('subgroupNames' => array());
		$criteria->with = array('ingredientConveniences' => array());
		$criteria->with = array('storability' => array());
		$criteria->with = array('ingredientStates' => array());
		
		/*
		$criteria->compare('NUT_DESC',$this->nutrientData->NUT_DESC,true);
		$criteria->compare('GRP_DESC_DE',$this->groupNames->GRP_DESC_DE,true);
		$criteria->compare('SGR_DESC_DE',$this->subgroupNames->SGR_DESC_DE,true);
		$criteria->compare('ICO_DESC_DE',$this->ingredientConveniences->ICO_DESC_DE,true);
		$criteria->compare('STB_DESC_DE',$this->storability->STB_DESC_DE,true);
		$criteria->compare('IST_DESC_DE',$this->storability->IST_DESC_DE,true);
		*/
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
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
