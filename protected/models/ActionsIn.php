<?php

/**
 * This is the model class for table "actions_in".
 *
 * The followings are the available columns in table 'actions_in':
 * @property integer $AIN_ID
 * @property string $AIN_DESC_DE_CH
 * @property string $AIN_DESC_EN_GB
 */
class ActionsIn extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActionsIn the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'actions_in';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('AIN_DESC_DE_CH, AIN_DESC_EN_GB', 'required'),
			array('AIN_ID', 'numerical', 'integerOnly'=>true),
			array('AIN_DESC_DE_CH, AIN_DESC_EN_GB', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AIN_ID, AIN_DESC_DE_CH, AIN_DESC_EN_GB', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ainToCois' => array(self::HAS_MANY, 'AinToCoi', 'AIN_ID'),
			'ainToAous' => array(self::HAS_MANY, 'AinToAou', 'AIN_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'AIN_ID' => 'Ain',
			'AIN_DESC_DE_CH' => 'Ain Desc De Ch',
			'AIN_DESC_EN_GB' => 'Ain Desc En Gb',
		);
	}
	
	public function getSearchFields(){
		return array('AIN_ID', 'AIN_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.AIN_ID',$this->AIN_ID);
		$criteria->compare($this->tableName().'.AIN_DESC_DE_CH',$this->AIN_DESC_DE_CH,true);
		$criteria->compare($this->tableName().'.AIN_DESC_EN_GB',$this->AIN_DESC_EN_GB,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('AIN_ID',$this->AIN_ID);
		$criteria->compare('AIN_DESC_DE_CH',$this->AIN_DESC_DE_CH,true);
		$criteria->compare('AIN_DESC_EN_GB',$this->AIN_DESC_EN_GB,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'AIN_ID',
				'desc' => 'AIN_ID DESC',
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