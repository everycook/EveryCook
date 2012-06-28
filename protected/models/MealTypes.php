<?php

/**
 * This is the model class for table "meal_types".
 *
 * The followings are the available columns in table 'meal_types':
 * @property integer $MTY_ID
 * @property string $MTY_DESC_EN_GB
 * @property string $MTY_DESC_DE_CH
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class MealTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MealTypes the static model class
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
		return 'meal_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MTY_DESC_EN_GB, MTY_DESC_DE_CH, CREATED_BY, CREATED_ON', 'required'),
			array('CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('MTY_DESC_EN_GB, MTY_DESC_DE_CH', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('MTY_ID, MTY_DESC_EN_GB, MTY_DESC_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'MTY_ID' => 'Mty',
			'MTY_DESC_EN_GB' => 'Mty Desc En Gb',
			'MTY_DESC_DE_CH' => 'Mty Desc De Ch',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('MTY_ID',$this->MTY_ID);
		$criteria->compare('MTY_DESC_EN_GB',$this->MTY_DESC_EN_GB,true);
		$criteria->compare('MTY_DESC_DE_CH',$this->MTY_DESC_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}