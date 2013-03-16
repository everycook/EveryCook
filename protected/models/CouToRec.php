<?php

/**
 * This is the model class for table "cou_to_rec".
 *
 * The followings are the available columns in table 'cou_to_rec':
 * @property integer $COU_ID
 * @property integer $CTR_ORDER
 * @property integer $REC_ID
 * @property integer $CTR_REC_PROC
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class CouToRec extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CouToRec the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'cou_to_rec';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('COU_ID, CTR_ORDER, REC_ID, CTR_REC_PROC, CREATED_BY, CREATED_ON', 'required'),
			array('COU_ID, CTR_ORDER, REC_ID, CTR_REC_PROC, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('COU_ID, REC_ID, CTR_REC_PROC', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('COU_ID, CTR_ORDER, REC_ID, CTR_REC_PROC, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'recipe' => array(self::BELONGS_TO, 'Recipes', 'REC_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'COU_ID' => 'Cou',
			'CTR_ORDER' => 'Ctr Order',
			'REC_ID' => 'Rec',
			'CTR_REC_PROC' => 'Ctr Rec Proc',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('COU_ID');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.COU_ID',$this->COU_ID);
		$criteria->compare($this->tableName().'.CTR_ORDER',$this->CTR_ORDER);
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.CTR_REC_PROC',$this->CTR_REC_PROC);
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

		$criteria->compare('COU_ID',$this->COU_ID);
		$criteria->compare('CTR_ORDER',$this->CTR_ORDER);
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('CTR_REC_PROC',$this->CTR_REC_PROC);
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
				'asc' => 'COU_ID',
				'desc' => 'COU_ID DESC',
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