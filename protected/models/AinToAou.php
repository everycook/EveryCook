<?php

/**
 * This is the model class for table "ain_to_aou".
 *
 * The followings are the available columns in table 'ain_to_aou':
 * @property integer $AIN_ID
 * @property integer $COI_ID
 * @property integer $ATA_NO
 * @property integer $AOU_ID
 * @property integer $ATA_COI_PREP
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class AinToAou extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AinToAou the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'ain_to_aou';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('AIN_ID, COI_ID, ATA_NO, AOU_ID, ATA_COI_PREP, CREATED_BY, CREATED_ON', 'required'),
			array('AIN_ID, COI_ID, ATA_NO, AOU_ID, ATA_COI_PREP, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AIN_ID, COI_ID, ATA_NO, AOU_ID, ATA_COI_PREP, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			/*'actionsIn' => array(self::BELONGS_TO, 'ActionsIn', 'AIN_ID'),*/
			'cookIn' => array(self::BELONGS_TO, 'CookIn', 'COI_ID'),
			'actionsOut' => array(self::BELONGS_TO, 'ActionsOut', 'AOU_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'AIN_ID' => 'Ain',
			'COI_ID' => 'Coi',
			'ATA_NO' => 'Ata No',
			'AOU_ID' => 'Aou',
			'ATA_COI_PREP' => 'Ata Coi Prep',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}
	
	public function getSearchFields(){
		return array('AIN_ID');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.AIN_ID',$this->AIN_ID);
		$criteria->compare($this->tableName().'.COI_ID',$this->COI_ID);
		$criteria->compare($this->tableName().'.ATA_NO',$this->ATA_NO);
		$criteria->compare($this->tableName().'.AOU_ID',$this->AOU_ID);
		$criteria->compare($this->tableName().'.ATA_COI_PREP',$this->ATA_COI_PREP);
		$criteria->compare($this->tableName().'.CREATED_BY',$this->CREATED_BY);
		$criteria->compare($this->tableName().'.CREATED_ON',$this->CREATED_ON);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('AIN_ID',$this->AIN_ID);
		$criteria->compare('COI_ID',$this->COI_ID);
		$criteria->compare('ATA_NO',$this->ATA_NO);
		$criteria->compare('AOU_ID',$this->AOU_ID);
		$criteria->compare('ATA_COI_PREP',$this->ATA_COI_PREP);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
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