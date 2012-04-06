<?php

/**
 * This is the model class for table "recipe_types".
 *
 * The followings are the available columns in table 'recipe_types':
 * @property integer $RET_ID
 * @property string $RET_DESC_EN_GB
 * @property string $RET_DESC_DE_CH
 */
class RecipeTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RecipeTypes the static model class
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
		return 'recipe_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RET_DESC_EN_GB, RET_DESC_DE_CH', 'required'),
			array('RET_DESC_EN_GB, RET_DESC_DE_CH', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('RET_ID, RET_DESC_EN_GB, RET_DESC_DE_CH', 'safe', 'on'=>'search'),
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
			'RET_ID' => 'Ret',
			'RET_DESC_EN_GB' => 'Ret Desc En Gb',
			'RET_DESC_DE_CH' => 'Ret Desc De Ch',
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

		$criteria->compare('RET_ID',$this->RET_ID);
		$criteria->compare('RET_DESC_EN_GB',$this->RET_DESC_EN_GB,true);
		$criteria->compare('RET_DESC_DE_CH',$this->RET_DESC_DE_CH,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}