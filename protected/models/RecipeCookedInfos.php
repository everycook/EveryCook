<?php

/**
 * This is the model class for table "recipe_cooked_infos".
 *
 * The followings are the available columns in table 'recipe_cooked_infos':
 * @property integer $RCI_ID
 * @property integer $PRF_UID
 * @property integer $MEA_ID
 * @property integer $COU_ID
 * @property integer $REC_ID
 * @property integer $RCI_COOK_DATE
 * @property string $RCI_JSON
 */
class RecipeCookedInfos extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RecipeCookedInfos the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'recipe_cooked_infos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MEA_ID, COU_ID, REC_ID, RCI_COOK_DATE, RCI_JSON', 'required'),
			array('PRF_UID, MEA_ID, COU_ID, REC_ID, RCI_COOK_DATE', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('RCI_ID, PRF_UID, MEA_ID, COU_ID, REC_ID, RCI_COOK_DATE, RCI_JSON', 'safe', 'on'=>'search'),
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
			'RCI_ID' => 'Rci',
			'PRF_UID' => 'Prf Uid',
			'MEA_ID' => 'Mea',
			'COU_ID' => 'Cou',
			'REC_ID' => 'Rec',
			'RCI_COOK_DATE' => 'Rci Cook Date',
			'RCI_JSON' => 'Rci Json',
		);
	}
	
	public function getSearchFields(){
		return array('RCI_ID', 'RCI_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.RCI_ID',$this->RCI_ID);
		$criteria->compare($this->tableName().'.PRF_UID',$this->PRF_UID);
		$criteria->compare($this->tableName().'.MEA_ID',$this->MEA_ID);
		$criteria->compare($this->tableName().'.COU_ID',$this->COU_ID);
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.RCI_COOK_DATE',$this->RCI_COOK_DATE);
		$criteria->compare($this->tableName().'.RCI_JSON',$this->RCI_JSON,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('RCI_ID',$this->RCI_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('MEA_ID',$this->MEA_ID);
		$criteria->compare('COU_ID',$this->COU_ID);
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('RCI_COOK_DATE',$this->RCI_COOK_DATE);
		$criteria->compare('RCI_JSON',$this->RCI_JSON,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'RCI_ID',
				'desc' => 'RCI_ID DESC',
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