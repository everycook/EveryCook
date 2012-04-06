<?php

/**
 * This is the model class for table "actions".
 *
 * The followings are the available columns in table 'actions':
 * @property integer $ACT_ID
 * @property integer $PRF_UID
 * @property integer $CREATED_BY
 * @property string $CREATED_ON
 * @property integer $CHANGED_BY
 * @property string $CHANGED_ON
 * @property string $ACT_IMG
 * @property string $ACT_IMG_AUTH
 * @property string $ACT_DESC_EN_GB
 * @property string $ACT_DESC_DE_CH
 */
class Actions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Actions the static model class
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
		return 'actions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CREATED_BY, CREATED_ON', 'required'),
			array('PRF_UID, CREATED_BY, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('ACT_IMG_AUTH', 'length', 'max'=>30),
			array('CHANGED_ON, ACT_IMG, ACT_DESC_EN_GB, ACT_DESC_DE_CH', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ACT_ID, PRF_UID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON, ACT_IMG, ACT_IMG_AUTH, ACT_DESC_EN_GB, ACT_DESC_DE_CH', 'safe', 'on'=>'search'),
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
			'ACT_ID' => 'Act',
			'PRF_UID' => 'Prf Uid',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
			'ACT_IMG' => 'Act Img',
			'ACT_IMG_AUTH' => 'Act Img Auth',
			'ACT_DESC_EN_GB' => 'Act Desc En Gb',
			'ACT_DESC_DE_CH' => 'Act Desc De Ch',
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

		$criteria->compare('ACT_ID',$this->ACT_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON,true);
		$criteria->compare('ACT_IMG',$this->ACT_IMG,true);
		$criteria->compare('ACT_IMG_AUTH',$this->ACT_IMG_AUTH,true);
		$criteria->compare('ACT_DESC_EN_GB',$this->ACT_DESC_EN_GB,true);
		$criteria->compare('ACT_DESC_DE_CH',$this->ACT_DESC_DE_CH,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}