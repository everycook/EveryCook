<?php

/**
 * This is the model class for table "ingredients".
 *
 * The followings are the available columns in table 'ingredients':
 * @property integer $ING_ID
 * @property integer $PRF_UID
 * @property string $ING_CREATED
 * @property string $ING_CHANGED
 * @property integer $NUT_ID
 * @property integer $ING_GROUP
 * @property integer $ING_SUBGROUP
 * @property integer $ING_STATE
 * @property integer $ING_CONVENIENCE
 * @property integer $ING_STORABILITY
 * @property double $ING_DENSITY
 * @property string $ING_PICTURE
 * @property string $ING_PICTURE_AUTH
 * @property string $ING_TITLE_EN
 * @property string $ING_TITLE_DE
 */
class Ingredients extends CActiveRecord
{
	public $filename;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ingredients the static model class
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
		return 'ingredients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ING_CREATED, ING_GROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY, ING_TITLE_EN', 'required'), // ING_SUBGROUP,
			array('PRF_UID, NUT_ID, ING_GROUP, ING_SUBGROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY', 'numerical', 'integerOnly'=>true),
			array('ING_DENSITY', 'numerical'),
			array('ING_PICTURE_AUTH', 'length', 'max'=>30),
			array('ING_TITLE_EN, ING_TITLE_DE', 'length', 'max'=>100),
			array('ING_CHANGED, ING_PICTURE, nutrientData, groupNames, subgroupNames, ingredientConveniences, storability, ingredientStates', 'safe'),
			//array('ING_CHANGED, ING_PICTURE, NUT_DESC, GRP_DESC_DE, SUBGRP_DESC_DE, CONV_DESC_DE, STORAB_DESC_DE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ING_ID, PRF_UID, ING_CREATED, ING_CHANGED, NUT_ID, ING_GROUP, ING_SUBGROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY, ING_DENSITY, ING_PICTURE, ING_PICTURE_AUTH, ING_TITLE_EN, ING_TITLE_DE', 'safe', 'on'=>'search'),
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
			'nutrientData' => array(self::BELONGS_TO, 'NutrientData', 'NUT_ID'),
			'groupNames' => array(self::BELONGS_TO, 'GroupNames', 'ING_GROUP'),
			'subgroupNames' => array(self::BELONGS_TO, 'SubgroupNames', 'ING_SUBGROUP'),
			'ingredientConveniences' => array(self::BELONGS_TO, 'IngredientConveniences', 'ING_CONVENIENCE'),
			'storability' => array(self::BELONGS_TO, 'Storability', 'ING_STORABILITY'),
			'ingredientStates' => array(self::BELONGS_TO, 'ingredientStates', 'ING_STATE'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ING_ID' => 'Ing',
			'PRF_UID' => 'Prf Uid',
			'ING_CREATED' => 'Ing Created',
			'ING_CHANGED' => 'Ing Changed',
			'NUT_ID' => 'Nut',
			'ING_GROUP' => 'Ing Group',
			'ING_SUBGROUP' => 'Ing Subgroup',
			'ING_STATE' => 'Ing State',
			'ING_CONVENIENCE' => 'Ing Convenience',
			'ING_STORABILITY' => 'Ing Storability',
			'ING_DENSITY' => 'Ing Density',
			'ING_PICTURE' => 'Ing Picture',
			'ING_PICTURE_AUTH' => 'Ing Picture Auth',
			'ING_TITLE_EN' => 'Ing Title En',
			'ING_TITLE_DE' => 'Ing Title De',
		);
	}
	
	public function getSearchFields(){
		return array('ING_ID', 'ING_TITLE_' . Yii::app()->session['lang']);
	}
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;

		$criteria->compare($this->tableName().'.ING_ID',$this->ING_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('ING_CREATED',$this->ING_CREATED,true);
		$criteria->compare('ING_CHANGED',$this->ING_CHANGED,true);
		$criteria->compare($this->tableName().'.NUT_ID',$this->NUT_ID);
		$criteria->compare($this->tableName().'.ING_GROUP',$this->ING_GROUP);
		$criteria->compare($this->tableName().'.ING_SUBGROUP',$this->ING_SUBGROUP);
		$criteria->compare($this->tableName().'.ING_STATE',$this->ING_STATE);
		$criteria->compare($this->tableName().'.ING_CONVENIENCE',$this->ING_CONVENIENCE);
		$criteria->compare($this->tableName().'.ING_STORABILITY',$this->ING_STORABILITY);
		$criteria->compare('ING_DENSITY',$this->ING_DENSITY);
		//$criteria->compare('ING_PICTURE',$this->ING_PICTURE,true);
		$criteria->compare('ING_PICTURE_AUTH',$this->ING_PICTURE_AUTH,true);
		$criteria->compare('ING_TITLE_EN',$this->ING_TITLE_EN,true);
		$criteria->compare('ING_TITLE_DE',$this->ING_TITLE_DE,true);
		
		/*
		$criteria->compare('nutrient_data.NUT_DESC',$this->nutrientData->NUT_DESC,true);
		$criteria->compare('group_names.GRP_DESC_DE',$this->groupNames->GRP_DESC_DE,true);
		$criteria->compare('subgroup_names.SUBGRP_DESC_DE',$this->subgroupNames->SUBGRP_DESC_DE,true);
		$criteria->compare('ingredient_conveniences.CONV_DESC_DE',$this->ingredientConveniences->CONV_DESC_DE,true);
		$criteria->compare('storability.STORAB_DESC_DE',$this->storability->STORAB_DESC_DE,true);
		*/
		return $criteria;
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;

		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('ING_CREATED',$this->ING_CREATED,true);
		$criteria->compare('ING_CHANGED',$this->ING_CHANGED,true);
		$criteria->compare('NUT_ID',$this->NUT_ID);
		$criteria->compare('ING_GROUP',$this->ING_GROUP);
		$criteria->compare('ING_SUBGROUP',$this->ING_SUBGROUP);
		$criteria->compare('ING_STATE',$this->ING_STATE);
		$criteria->compare('ING_CONVENIENCE',$this->ING_CONVENIENCE);
		$criteria->compare('ING_STORABILITY',$this->ING_STORABILITY);
		$criteria->compare('ING_DENSITY',$this->ING_DENSITY);
		//$criteria->compare('ING_PICTURE',$this->ING_PICTURE,true);
		$criteria->compare('ING_PICTURE_AUTH',$this->ING_PICTURE_AUTH,true);
		$criteria->compare('ING_TITLE_EN',$this->ING_TITLE_EN,true);
		$criteria->compare('ING_TITLE_DE',$this->ING_TITLE_DE,true);
		
		$criteria->with = array('nutrientData' => array());
		$criteria->with = array('groupNames' => array());
		$criteria->with = array('subgroupNames' => array());
		$criteria->with = array('ingredientConveniences' => array());
		$criteria->with = array('storability' => array());
		$criteria->with = array('ingredientStates' => array());
		
		/*
		$criteria->compare('NUT_DESC',$this->nutrientData->NUT_DESC,true);
		$criteria->compare('GRP_DESC_DE',$this->groupNames->GRP_DESC_DE,true);
		$criteria->compare('SUBGRP_DESC_DE',$this->subgroupNames->SUBGRP_DESC_DE,true);
		$criteria->compare('CONV_DESC_DE',$this->ingredientConveniences->CONV_DESC_DE,true);
		$criteria->compare('STORAB_DESC_DE',$this->storability->STORAB_DESC_DE,true);
		$criteria->compare('STATE_DESC_DE',$this->storability->STATE_DESC_DE,true);
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
				'asc' => 'ING_GROUP',
				'desc' => 'ING_GROUP DESC',
			),
			'subgroupNames' => array(
				'asc' => 'ING_SUBGROUP',
				'desc' => 'ING_SUBGROUP DESC',
			),
			'ingredientConveniences' => array(
				'asc' => 'ING_CONVENIENCE',
				'desc' => 'ING_CONVENIENCE DESC',
			),
			'storability' => array(
				'asc' => 'ING_STORABILITY',
				'desc' => 'ING_STORABILITY DESC',
			),
			'*',
		);
		return $sort;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getCriteria(),
			'sort'=>$this->getSort(),
		));
	}
}