<?php

/**
 * This is the model class for table "ingredient_states".
 *
 * The followings are the available columns in table 'ingredient_states':
 * @property integer $STATE_ID
 * @property string $STATE_DESC_EN
 * @property string $STATE_DESC_DE
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
			array('STATE_DESC_EN', 'required'),
			array('STATE_DESC_EN, STATE_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STATE_ID, STATE_DESC_EN, STATE_DESC_DE', 'safe', 'on'=>'search'),
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
			'STATE_ID' => 'State',
			'STATE_DESC_EN' => 'State Desc En',
			'STATE_DESC_DE' => 'State Desc De',
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

		$criteria->compare('STATE_ID',$this->STATE_ID);
		$criteria->compare('STATE_DESC_EN',$this->STATE_DESC_EN,true);
		$criteria->compare('STATE_DESC_DE',$this->STATE_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}