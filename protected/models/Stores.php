<?php

/**
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property integer $STO_ID
 * @property string $STO_LOC_GPS
 * @property string $STO_LOC_ADDR
 * @property integer $SUP_ID
 */
class Stores extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Stores the static model class
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
		return 'stores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SUP_ID', 'required'),
			array('SUP_ID', 'numerical', 'integerOnly'=>true),
			array('STO_LOC_GPS', 'length', 'max'=>100),
			array('STO_LOC_ADDR', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STO_ID, STO_LOC_GPS, STO_LOC_ADDR, SUP_ID', 'safe', 'on'=>'search'),
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
			'STO_ID' => 'Sto',
			'STO_LOC_GPS' => 'Sto Loc Gps',
			'STO_LOC_ADDR' => 'Sto Loc Addr',
			'SUP_ID' => 'Sup',
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

		$criteria->compare('STO_ID',$this->STO_ID);
		$criteria->compare('STO_LOC_GPS',$this->STO_LOC_GPS,true);
		$criteria->compare('STO_LOC_ADDR',$this->STO_LOC_ADDR,true);
		$criteria->compare('SUP_ID',$this->SUP_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}