<?php

/**
 * This is the model class for table "group_names".
 *
 * The followings are the available columns in table 'group_names':
 * @property integer $GRP_ID
 * @property string $GRP_DESC_EN
 * @property string $GRP_DESC_DE
 */
class GroupNames extends CActiveRecord
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
			array('GRP_DESC_EN, GRP_DESC_DE', 'required'),
			array('GRP_DESC_EN, GRP_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('GRP_ID, GRP_DESC_EN, GRP_DESC_DE', 'safe', 'on'=>'search'),
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
			'GRP_DESC_EN' => 'Grp Desc En',
			'GRP_DESC_DE' => 'Grp Desc De',
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
		$criteria->compare('GRP_DESC_EN',$this->GRP_DESC_EN,true);
		$criteria->compare('GRP_DESC_DE',$this->GRP_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}