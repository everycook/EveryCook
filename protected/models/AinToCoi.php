<?php

/**
 * This is the model class for table "ain_to_coi".
 *
 * The followings are the available columns in table 'ain_to_coi':
 * @property integer $AIN_ID
 * @property integer $COI_ID
 */
class AinToCoi extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AinToCoi the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'ain_to_coi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('AIN_ID, COI_ID', 'required'),
			array('AIN_ID, COI_ID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AIN_ID, COI_ID', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'actionsIn' => array(self::BELONGS_TO, 'ActionsIn', 'AIN_ID'),
			'cookIn' => array(self::BELONGS_TO, 'CookIn', 'COI_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'AIN_ID' => 'Ain',
			'COI_ID' => 'Coi',
		);
	}
	
	public function getSearchFields(){
		return array('AIN_ID');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.AIN_ID',$this->AIN_ID);
		$criteria->compare($this->tableName().'.COI_ID',$this->COI_ID);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('AIN_ID',$this->AIN_ID);
		$criteria->compare('COI_ID',$this->COI_ID);
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