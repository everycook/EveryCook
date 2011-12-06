<?php

/**
 * This is the model class for table "ethical_criteria".
 *
 * The followings are the available columns in table 'ethical_criteria':
 * @property integer $ETH_ID
 * @property string $ETH_DESC_EN
 * @property string $ETH_DESC_DE
 */
class EthicalCriteria extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EthicalCriteria the static model class
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
		return 'ethical_criteria';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ETH_DESC_EN, ETH_DESC_DE', 'required'),
			array('ETH_DESC_EN, ETH_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ETH_ID, ETH_DESC_EN, ETH_DESC_DE', 'safe', 'on'=>'search'),
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
			'ETH_ID' => 'Eth',
			'ETH_DESC_EN' => 'Eth Desc En',
			'ETH_DESC_DE' => 'Eth Desc De',
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

		$criteria->compare('ETH_ID',$this->ETH_ID);
		$criteria->compare('ETH_DESC_EN',$this->ETH_DESC_EN,true);
		$criteria->compare('ETH_DESC_DE',$this->ETH_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}