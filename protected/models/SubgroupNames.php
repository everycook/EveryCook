<?php

/**
 * This is the model class for table "subgroup_names".
 *
 * The followings are the available columns in table 'subgroup_names':
 * @property integer $SGR_ID
 * @property integer $GRP_ID
 * @property string $SGR_DESC_EN_GB
 * @property string $SGR_DESC_DE_CH
 */
class SubgroupNames extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SubgroupNames the static model class
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
		return 'subgroup_names';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('GRP_ID, SGR_DESC_EN_GB', 'required'),
			array('GRP_ID', 'numerical', 'integerOnly'=>true),
			array('SGR_DESC_EN_GB, SGR_DESC_DE_CH', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SGR_ID, GRP_ID, SGR_DESC_EN_GB, SGR_DESC_DE_CH', 'safe', 'on'=>'search'),
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
			'SGR_ID' => 'Sgr',
			'GRP_ID' => 'Grp',
			'SGR_DESC_EN_GB' => 'Sgr Desc En Gb',
			'SGR_DESC_DE_CH' => 'Sgr Desc De Ch',
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

		$criteria->compare('SGR_ID',$this->SGR_ID);
		$criteria->compare('GRP_ID',$this->GRP_ID);
		$criteria->compare('SGR_DESC_EN_GB',$this->SGR_DESC_EN_GB,true);
		$criteria->compare('SGR_DESC_DE_CH',$this->SGR_DESC_DE_CH,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
