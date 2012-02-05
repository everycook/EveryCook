<?php

/**
 * This is the model class for table "ingredient_conveniences".
 *
 * The followings are the available columns in table 'ingredient_conveniences':
 * @property integer $CONV_ID
 * @property string $CONV_DESC_EN
 * @property string $CONV_DESC_DE
 */
class IngredientConveniences extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return IngredientConveniences the static model class
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
		return 'ingredient_conveniences';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CONV_DESC_EN, CONV_DESC_DE', 'required'),
			array('CONV_DESC_EN, CONV_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CONV_ID, CONV_DESC_EN, CONV_DESC_DE', 'safe', 'on'=>'search'),
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
			'CONV_ID' => 'Conv',
			'CONV_DESC_EN' => 'Conv Desc En',
			'CONV_DESC_DE' => 'Conv Desc De',
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

		$criteria->compare('CONV_ID',$this->CONV_ID);
		$criteria->compare('CONV_DESC_EN',$this->CONV_DESC_EN,true);
		$criteria->compare('CONV_DESC_DE',$this->CONV_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}