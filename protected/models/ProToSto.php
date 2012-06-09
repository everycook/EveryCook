<?php

/**
 * This is the model class for table "pro_to_sto".
 *
 * The followings are the available columns in table 'pro_to_sto':
 * @property integer $PRO_ID
 * @property integer $SUP_ID
 * @property integer $STY_ID
 */
class ProToSto extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProToSto the static model class
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
		return 'pro_to_sto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRO_ID, SUP_ID, STY_ID', 'required'),
			array('PRO_ID, SUP_ID, STY_ID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRO_ID, SUP_ID, STY_ID', 'safe', 'on'=>'search'),
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
			'PRO_ID' => 'Pro',
			'SUP_ID' => 'Sup',
			'STY_ID' => 'Sty',
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

		$criteria->compare('PRO_ID',$this->PRO_ID);
		$criteria->compare('SUP_ID',$this->SUP_ID);
		$criteria->compare('STY_ID',$this->STY_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}