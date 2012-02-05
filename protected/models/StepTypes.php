<?php

/**
 * This is the model class for table "step_types".
 *
 * The followings are the available columns in table 'step_types':
 * @property integer $STT_ID
 * @property string $STT_DESC_EN
 * @property string $STT_DESC_DE
 */
class StepTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StepTypes the static model class
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
		return 'step_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STT_DESC_EN', 'required'),
			array('STT_DESC_DE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('STT_ID, STT_DESC_EN, STT_DESC_DE', 'safe', 'on'=>'search'),
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
			'STT_ID' => 'Stt',
			'STT_DESC_EN' => 'Stt Desc En',
			'STT_DESC_DE' => 'Stt Desc De',
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

		$criteria->compare('STT_ID',$this->STT_ID);
		$criteria->compare('STT_DESC_EN',$this->STT_DESC_EN,true);
		$criteria->compare('STT_DESC_DE',$this->STT_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}