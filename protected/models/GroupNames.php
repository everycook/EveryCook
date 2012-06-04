<?php

/**
 * This is the model class for table "group_names".
 *
 * The followings are the available columns in table 'group_names':
 * @property integer $GRP_ID
 * @property string $GRP_DESC_EN_GB
 * @property string $GRP_DESC_DE_CH
 * @property integer $CREATED_BY
 * @property string $CREATED_ON
 * @property integer $CHANGED_BY
 * @property string $CHANGED_ON
 */
class GroupNames extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GroupNames the static model class
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
		return 'group_names';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('GRP_DESC_EN_GB, GRP_DESC_DE_CH, CREATED_BY, CREATED_ON', 'required'),
			array('CREATED_BY, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('GRP_DESC_EN_GB, GRP_DESC_DE_CH', 'length', 'max'=>100),
			array('CHANGED_ON', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('GRP_ID, GRP_DESC_EN_GB, GRP_DESC_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'GRP_ID' => 'Grp',
			'GRP_DESC_EN_GB' => 'Grp Desc En Gb',
			'GRP_DESC_DE_CH' => 'Grp Desc De Ch',
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

		$criteria->compare('GRP_ID',$this->GRP_ID);
		$criteria->compare('GRP_DESC_EN_GB',$this->GRP_DESC_EN_GB,true);
		$criteria->compare('GRP_DESC_DE_CH',$this->GRP_DESC_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
