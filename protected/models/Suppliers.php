<?php

/**
 * This is the model class for table "suppliers".
 *
 * The followings are the available columns in table 'suppliers':
 * @property integer $SUP_ID
 * @property string $SUP_NAME
 */
class Suppliers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Suppliers the static model class
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
		return 'suppliers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SUP_NAME', 'required'),
			array('SUP_NAME', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SUP_ID, SUP_NAME', 'safe', 'on'=>'search'),
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
			'SUP_ID' => 'Sup',
			'SUP_NAME' => 'Sup Name',
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

		$criteria->compare('SUP_ID',$this->SUP_ID);
		$criteria->compare('SUP_NAME',$this->SUP_NAME,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}