<?php

/**
 * This is the model class for table "storability".
 *
 * The followings are the available columns in table 'storability':
 * @property integer $STORAB_ID
 * @property string $STORAB_DESC_EN
 * @property string $STORAB_DESC_DE
 */
class Storability extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Storability the static model class
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
		return 'storability';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STORAB_DESC_EN, STORAB_DESC_DE', 'required'),
			array('STORAB_DESC_EN, STORAB_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STORAB_ID, STORAB_DESC_EN, STORAB_DESC_DE', 'safe', 'on'=>'search'),
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
			'STORAB_ID' => 'Storab',
			'STORAB_DESC_EN' => 'Storab Desc En',
			'STORAB_DESC_DE' => 'Storab Desc De',
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

		$criteria->compare('STORAB_ID',$this->STORAB_ID);
		$criteria->compare('STORAB_DESC_EN',$this->STORAB_DESC_EN,true);
		$criteria->compare('STORAB_DESC_DE',$this->STORAB_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}