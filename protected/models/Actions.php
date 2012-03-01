<?php

/**
 * This is the model class for table "actions".
 *
 * The followings are the available columns in table 'actions':
 * @property integer $ACT_ID
 * @property integer $PRF_UID
 * @property string $ACT_CREATED
 * @property string $ACT_CHANGED
 * @property string $ACT_PICTURE
 * @property string $ACT_PICTURE_AUTH
 * @property string $ACT_DESC_EN
 * @property string $ACT_DESC_DE
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
			array('ACT_CREATED', 'required'),
			array('PRF_UID', 'numerical', 'integerOnly'=>true),
			array('ACT_PICTURE_AUTH', 'length', 'max'=>30),
			array('ACT_CHANGED, ACT_PICTURE, ACT_DESC_EN, ACT_DESC_DE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ACT_ID, PRF_UID, ACT_CREATED, ACT_CHANGED, ACT_PICTURE, ACT_PICTURE_AUTH, ACT_DESC_EN, ACT_DESC_DE', 'safe', 'on'=>'search'),
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
			'ACT_CREATED' => 'Act Created',
			'ACT_CHANGED' => 'Act Changed',
			'ACT_PICTURE' => 'Act Picture',
			'ACT_PICTURE_AUTH' => 'Act Picture Auth',
			'ACT_DESC_EN' => 'Act Desc En',
			'ACT_DESC_DE' => 'Act Desc De',
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
		$criteria->compare('ACT_CREATED',$this->ACT_CREATED,true);
		$criteria->compare('ACT_CHANGED',$this->ACT_CHANGED,true);
		$criteria->compare('ACT_PICTURE',$this->ACT_PICTURE,true);
		$criteria->compare('ACT_PICTURE_AUTH',$this->ACT_PICTURE_AUTH,true);
		$criteria->compare('ACT_DESC_EN',$this->ACT_DESC_EN,true);
		$criteria->compare('ACT_DESC_DE',$this->ACT_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}