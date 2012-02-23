<?php

/**
 * This is the model class for table "recipes".
 *
 * The followings are the available columns in table 'recipes':
 * @property integer $REC_ID
 * @property string $REC_CREATED
 * @property string $REC_CHANGED
 * @property string $REC_PICTURE
 * @property string $REC_PICTURE_AUTH
 * @property integer $REC_TYPE
 * @property string $REC_TITLE_EN
 * @property string $REC_TITLE_DE
 */
class Recipes extends CActiveRecord
{
	public $filename;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Recipes the static model class
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
		return 'recipes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('REC_CREATED, REC_TYPE, REC_TITLE_EN', 'required'),
			array('REC_TYPE', 'numerical', 'integerOnly'=>true),
			array('REC_PICTURE_AUTH', 'length', 'max'=>30),
			array('REC_TITLE_EN, REC_TITLE_DE', 'length', 'max'=>100),
			array('REC_CHANGED, REC_PICTURE, steps', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, REC_CREATED, REC_CHANGED, REC_PICTURE, REC_PICTURE_AUTH, REC_TYPE, REC_TITLE_EN, REC_TITLE_DE', 'safe', 'on'=>'search'),
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
			'recipeTypes' => array(self::BELONGS_TO, 'recipeTypes', 'REC_TYPE'),
			'steps' => array(self::HAS_MANY, 'Steps', 'REC_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'REC_ID' => 'Rec',
			'REC_CREATED' => 'Rec Created',
			'REC_CHANGED' => 'Rec Changed',
			'REC_PICTURE' => 'Rec Picture',
			'REC_PICTURE_AUTH' => 'Rec Picture Auth',
			'REC_TYPE' => 'Rec Type',
			'REC_TITLE_EN' => 'Rec Title En',
			'REC_TITLE_DE' => 'Rec Title De',
		);
	}

	public function getSearchFields(){
		return array('REC_ID', 'REC_TITLE_' . Yii::app()->session['lang']);
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;

		$criteria->compare('t.REC_ID',$this->REC_ID);
		$criteria->compare('t.REC_CREATED',$this->REC_CREATED,true);
		$criteria->compare('t.REC_CHANGED',$this->REC_CHANGED,true);
		$criteria->compare('t.REC_PICTURE',$this->REC_PICTURE,true);
		$criteria->compare('t.REC_PICTURE_AUTH',$this->REC_PICTURE_AUTH,true);
		$criteria->compare('t.REC_TYPE',$this->REC_TYPE);
		$criteria->compare('t.REC_TITLE_EN',$this->REC_TITLE_EN,true);
		$criteria->compare('t.REC_TITLE_DE',$this->REC_TITLE_DE,true);

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
		*/
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