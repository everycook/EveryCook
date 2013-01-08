<?php

/**
 * This is the model class for table "tools".
 *
 * The followings are the available columns in table 'tools':
 * @property integer $TOO_ID
 * @property string $TOO_DESC_DE_CH
 * @property string $TOO_DESC_EN_GB
 */
class Tools extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tools the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'tools';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TOO_DESC_DE_CH, TOO_DESC_EN_GB', 'required'),
			array('TOO_DESC_DE_CH, TOO_DESC_EN_GB', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('TOO_ID, TOO_DESC_DE_CH, TOO_DESC_EN_GB', 'safe', 'on'=>'search'),
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
			'TOO_ID' => 'Too',
			'TOO_DESC_DE_CH' => 'Too Desc De Ch',
			'TOO_DESC_EN_GB' => 'Too Desc En Gb',
		);
	}
	
	public function getSearchFields(){
		return array('TOO_ID', 'TOO_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.TOO_ID',$this->TOO_ID);
		$criteria->compare($this->tableName().'.TOO_DESC_DE_CH',$this->TOO_DESC_DE_CH,true);
		$criteria->compare($this->tableName().'.TOO_DESC_EN_GB',$this->TOO_DESC_EN_GB,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('TOO_ID',$this->TOO_ID);
		$criteria->compare('TOO_DESC_DE_CH',$this->TOO_DESC_DE_CH,true);
		$criteria->compare('TOO_DESC_EN_GB',$this->TOO_DESC_EN_GB,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'TOO_ID',
				'desc' => 'TOO_ID DESC',
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