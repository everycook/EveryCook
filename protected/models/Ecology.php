<?php

/**
 * This is the model class for table "ecology".
 *
 * The followings are the available columns in table 'ecology':
 * @property integer $ECO_ID
 * @property string $ECO_DESC_EN
 * @property string $ECO_DESC_DE
 */
class Ecology extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ecology the static model class
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
		return 'ecology';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ECO_DESC_EN, ECO_DESC_DE', 'required'),
			array('ECO_DESC_EN, ECO_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ECO_ID, ECO_DESC_EN, ECO_DESC_DE', 'safe', 'on'=>'search'),
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
			'ECO_ID' => 'Eco',
			'ECO_DESC_EN' => 'Eco Desc En',
			'ECO_DESC_DE' => 'Eco Desc De',
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

		$criteria->compare('ECO_ID',$this->ECO_ID);
		$criteria->compare('ECO_DESC_EN',$this->ECO_DESC_EN,true);
		$criteria->compare('ECO_DESC_DE',$this->ECO_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}