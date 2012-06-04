<?php

/**
 * This is the model class for table "shoplists".
 *
 * The followings are the available columns in table 'shoplists':
 * @property integer $SHO_ID
 * @property integer $SHO_DATE
 * @property string $SHO_PRODUCTS
 * @property string $SHO_QUANTITIES
 * @property integer $CREATED_BY
 * @property string $CREATED_ON
 * @property integer $CHANGED_BY
 * @property string $CHANGED_ON
 */
class Shoplists extends ActiveRecordECPriv
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
			array('SHO_DATE, CREATED_BY, CREATED_ON', 'required'),
			array('SHO_DATE, CREATED_BY, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('SHO_PRODUCTS, SHO_QUANTITIES, CHANGED_ON', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SHO_ID, SHO_DATE, SHO_PRODUCTS, SHO_QUANTITIES, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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

		$criteria->compare('SHO_ID',$this->SHO_ID);
		$criteria->compare('SHO_DATE',$this->SHO_DATE);
		$criteria->compare('SHO_PRODUCTS',$this->SHO_PRODUCTS,true);
		$criteria->compare('SHO_QUANTITIES',$this->SHO_QUANTITIES,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
