<?php

/**
 * This is the model class for table "cook_in_prep".
 *
 * The followings are the available columns in table 'cook_in_prep':
 * @property integer $COI_PREP
 * @property string $COI_PREP_DESC
 */
class CookInPrep extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CookInPrep the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'cook_in_prep';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('COI_PREP, COI_PREP_DESC', 'required'),
			array('COI_PREP', 'numerical', 'integerOnly'=>true),
			array('COI_PREP_DESC', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('COI_PREP, COI_PREP_DESC', 'safe', 'on'=>'search'),
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
			'COI_PREP' => 'Coi Prep',
			'COI_PREP_DESC' => 'Coi Prep Desc',
		);
	}
	
	public function getSearchFields(){
		return array('COI_PREP', 'COI_PREP_DESC');
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.COI_PREP',$this->COI_PREP);
		$criteria->compare($this->tableName().'.COI_PREP_DESC',$this->COI_PREP_DESC,true);
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('COI_PREP',$this->COI_PREP);
		$criteria->compare('COI_PREP_DESC',$this->COI_PREP_DESC,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'COI_PREP',
				'desc' => 'COI_PREP DESC',
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