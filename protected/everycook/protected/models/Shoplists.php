<?php

/**
 * This is the model class for table "shoplists".
 *
 * The followings are the available columns in table 'shoplists':
 * @property integer $SHO_ID
 * @property string $SHO_DATE
 * @property string $SHO_PRODUCTS
 * @property string $SHO_QUANTITIES
 */
class Shoplists extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Shoplists the static model class
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
		return 'shoplists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SHO_DATE', 'required'),
			array('SHO_PRODUCTS, SHO_QUANTITIES', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SHO_ID, SHO_DATE, SHO_PRODUCTS, SHO_QUANTITIES', 'safe', 'on'=>'search'),
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
			'SHO_ID' => 'Sho',
			'SHO_DATE' => 'Sho Date',
			'SHO_PRODUCTS' => 'Sho Products',
			'SHO_QUANTITIES' => 'Sho Quantities',
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

		$criteria->compare('SHO_ID',$this->SHO_ID);
		$criteria->compare('SHO_DATE',$this->SHO_DATE,true);
		$criteria->compare('SHO_PRODUCTS',$this->SHO_PRODUCTS,true);
		$criteria->compare('SHO_QUANTITIES',$this->SHO_QUANTITIES,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}