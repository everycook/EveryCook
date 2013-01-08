<?php

/**
 * This is the model class for table "cook_in_state".
 *
 * The followings are the available columns in table 'cook_in_state':
 * @property integer $COI_ID
 * @property integer $CIS_ID
 * @property string $CIS_DESC
 */
class CookInState extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CookInState the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'cook_in_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('COI_ID, CIS_ID, CIS_DESC', 'required'),
			array('COI_ID, CIS_ID', 'numerical', 'integerOnly'=>true),
			array('CIS_DESC', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('COI_ID, CIS_ID, CIS_DESC', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'cookIn' => array(self::BELONGS_TO, 'CookIn', 'COI_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'COI_ID' => 'Coi',
			'CIS_ID' => 'Cis',
			'CIS_DESC' => 'Cis Desc',
		);
	}
	
	public function getSearchFields(){
		return array('COI_ID', 'CIS_DESC');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.COI_ID',$this->COI_ID);
		$criteria->compare($this->tableName().'.CIS_ID',$this->CIS_ID);
		$criteria->compare($this->tableName().'.CIS_DESC',$this->CIS_DESC,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('COI_ID',$this->COI_ID);
		$criteria->compare('CIS_ID',$this->CIS_ID);
		$criteria->compare('CIS_DESC',$this->CIS_DESC,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'COI_ID',
				'desc' => 'COI_ID DESC',
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