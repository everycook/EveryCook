<?php

/**
 * This is the model class for table "ingredient_states".
 *
 * The followings are the available columns in table 'ingredient_states':
 * @property integer $IST_ID
 * @property string $IST_DESC_EN_GB
 * @property string $IST_DESC_DE_CH
 */
class IngredientStates extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return IngredientStates the static model class
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
		return 'ingredient_states';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IST_DESC_EN_GB', 'required'),
			array('IST_DESC_EN_GB, IST_DESC_DE_CH', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IST_ID, IST_DESC_EN_GB, IST_DESC_DE_CH', 'safe', 'on'=>'search'),
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
			'IST_ID' => 'Ist',
			'IST_DESC_EN_GB' => 'Ist Desc En Gb',
			'IST_DESC_DE_CH' => 'Ist Desc De Ch',
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

		$criteria->compare('IST_ID',$this->IST_ID);
		$criteria->compare('IST_DESC_EN_GB',$this->IST_DESC_EN_GB,true);
		$criteria->compare('IST_DESC_DE_CH',$this->IST_DESC_DE_CH,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}