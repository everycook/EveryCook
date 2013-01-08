<?php

/**
 * This is the model class for table "actions_out".
 *
 * The followings are the available columns in table 'actions_out':
 * @property integer $AOU_ID
 * @property integer $STT_ID
 * @property integer $TOO_ID
 * @property string $AOU_PREP
 * @property integer $AOU_DURATION
 * @property integer $AOU_DUR_PRO
 * @property integer $AOU_CIS_CHANGE
 * @property string $AOU_DESC_DE_CH
 * @property string $AOU_DESC_EN_GB
 */
class ActionsOut extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActionsOut the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'actions_out';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STT_ID, TOO_ID, AOU_DURATION, AOU_DUR_PRO, AOU_CIS_CHANGE, AOU_DESC_DE_CH, AOU_DESC_EN_GB', 'required'),
			array('STT_ID, TOO_ID, AOU_DURATION, AOU_DUR_PRO, AOU_CIS_CHANGE', 'numerical', 'integerOnly'=>true),
			array('AOU_PREP', 'length', 'max'=>1),
			array('AOU_DESC_DE_CH, AOU_DESC_EN_GB', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AOU_ID, STT_ID, TOO_ID, AOU_PREP, AOU_DURATION, AOU_DUR_PRO, AOU_CIS_CHANGE, AOU_DESC_DE_CH, AOU_DESC_EN_GB', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'stepType' => array(self::BELONGS_TO, 'StepTypes', 'STT_ID'),
			'tool' => array(self::BELONGS_TO, 'Tools', 'TOO_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'AOU_ID' => 'Aou',
			'STT_ID' => 'Stt',
			'TOO_ID' => 'Too',
			'AOU_PREP' => 'Aou Prep',
			'AOU_DURATION' => 'Aou Duration',
			'AOU_DUR_PRO' => 'Aou Dur Pro',
			'AOU_CIS_CHANGE' => 'Cis',
			'AOU_DESC_DE_CH' => 'Aou Desc De Ch',
			'AOU_DESC_EN_GB' => 'Aou Desc En Gb',
		);
	}
	
	public function getSearchFields(){
		return array('AOU_ID', 'AOU_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.AOU_ID',$this->AOU_ID);
		$criteria->compare($this->tableName().'.STT_ID',$this->STT_ID);
		$criteria->compare($this->tableName().'.TOO_ID',$this->TOO_ID);
		$criteria->compare($this->tableName().'.AOU_PREP',$this->AOU_PREP,true);
		$criteria->compare($this->tableName().'.AOU_DURATION',$this->AOU_DURATION);
		$criteria->compare($this->tableName().'.AOU_DUR_PRO',$this->AOU_DUR_PRO);
		$criteria->compare($this->tableName().'.AOU_CIS_CHANGE',$this->AOU_CIS_CHANGE);
		$criteria->compare($this->tableName().'.AOU_DESC_DE_CH',$this->AOU_DESC_DE_CH,true);
		$criteria->compare($this->tableName().'.AOU_DESC_EN_GB',$this->AOU_DESC_EN_GB,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('AOU_ID',$this->AOU_ID);
		$criteria->compare('STT_ID',$this->STT_ID);
		$criteria->compare('TOO_ID',$this->TOO_ID);
		$criteria->compare('AOU_PREP',$this->AOU_PREP,true);
		$criteria->compare('AOU_DURATION',$this->AOU_DURATION);
		$criteria->compare('AOU_DUR_PRO',$this->AOU_DUR_PRO);
		$criteria->compare('AOU_CIS_CHANGE',$this->AOU_CIS_CHANGE);
		$criteria->compare('AOU_DESC_DE_CH',$this->AOU_DESC_DE_CH,true);
		$criteria->compare('AOU_DESC_EN_GB',$this->AOU_DESC_EN_GB,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'AOU_ID',
				'desc' => 'AOU_ID DESC',
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