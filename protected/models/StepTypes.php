<?php

/**
 * This is the model class for table "step_types".
 *
 * The followings are the available columns in table 'step_types':
 * @property integer $STT_ID
 * @property string $STT_DEFAULT
 * @property string $STT_REQUIRED
 * @property string $STT_DESC_EN_GB
 * @property string $STT_DESC_DE_CH
 */
class StepTypes extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StepTypes the static model class
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
		return 'step_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STT_DEFAULT, STT_REQUIRED, STT_DESC_EN_GB', 'required'),
			array('STT_DEFAULT, STT_REQUIRED', 'length', 'max'=>200),
			array('STT_DESC_DE_CH', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STT_ID, STT_DEFAULT, STT_REQUIRED, STT_DESC_EN_GB, STT_DESC_DE_CH', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'STT_ID' => 'Stt',
			'STT_DEFAULT' => 'Stt Default',
			'STT_REQUIRED' => 'Stt Required',
			'STT_DESC_EN_GB' => 'Stt Desc En Gb',
			'STT_DESC_DE_CH' => 'Stt Desc De Ch',
		);
	}
	
	public function getSearchFields(){
		return array('STT_ID', 'STT_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.STT_ID',$this->STT_ID);
		$criteria->compare($this->tableName().'.STT_DEFAULT',$this->STT_DEFAULT,true);
		$criteria->compare($this->tableName().'.STT_REQUIRED',$this->STT_REQUIRED,true);
		$criteria->compare($this->tableName().'.STT_DESC_EN_GB',$this->STT_DESC_EN_GB,true);
		$criteria->compare($this->tableName().'.STT_DESC_DE_CH',$this->STT_DESC_DE_CH,true);
		
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('STT_ID',$this->STT_ID);
		$criteria->compare('STT_DEFAULT',$this->STT_DEFAULT,true);
		$criteria->compare('STT_REQUIRED',$this->STT_REQUIRED,true);
		$criteria->compare('STT_DESC_EN_GB',$this->STT_DESC_EN_GB,true);
		$criteria->compare('STT_DESC_DE_CH',$this->STT_DESC_DE_CH,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
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
