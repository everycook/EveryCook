<?php

/**
 * This is the model class for table "ingredients".
 *
 * The followings are the available columns in table 'ingredients':
 * @property integer $ING_ID
 * @property integer $PRF_UID
 * @property string $ING_CREATED
 * @property string $ING_CHANGED
 * @property integer $NUT_ID
 * @property integer $ING_GROUP
 * @property integer $ING_SUBGROUP
 * @property integer $ING_STATE
 * @property integer $ING_CONVENIENCE
 * @property integer $ING_STORABILITY
 * @property double $ING_DENSITY
 * @property string $ING_PICTURE
 * @property string $ING_PICTURE_AUTH
 * @property string $ING_TITLE_EN
 * @property string $ING_TITLE_DE
 */
class Ingredients2 extends CActiveRecord
{
	public $filename;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ingredients2 the static model class
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
		return 'ingredients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ING_CREATED, ING_GROUP, ING_SUBGROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY, ING_TITLE_EN', 'required'),
			array('PRF_UID, NUT_ID, ING_GROUP, ING_SUBGROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY', 'numerical', 'integerOnly'=>true),
			array('ING_DENSITY', 'numerical'),
			array('ING_PICTURE_AUTH', 'length', 'max'=>30),
			array('ING_TITLE_EN, ING_TITLE_DE', 'length', 'max'=>100),
			array('ING_CHANGED, ING_PICTURE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ING_ID, PRF_UID, ING_CREATED, ING_CHANGED, NUT_ID, ING_GROUP, ING_SUBGROUP, ING_STATE, ING_CONVENIENCE, ING_STORABILITY, ING_DENSITY, ING_PICTURE, ING_PICTURE_AUTH, ING_TITLE_EN, ING_TITLE_DE', 'safe', 'on'=>'search'),
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
			'ING_ID' => 'Ing',
			'PRF_UID' => 'Prf Uid',
			'ING_CREATED' => 'Ing Created',
			'ING_CHANGED' => 'Ing Changed',
			'NUT_ID' => 'Nut',
			'ING_GROUP' => 'Ing Group',
			'ING_SUBGROUP' => 'Ing Subgroup',
			'ING_STATE' => 'Ing State',
			'ING_CONVENIENCE' => 'Ing Convenience',
			'ING_STORABILITY' => 'Ing Storability',
			'ING_DENSITY' => 'Ing Density',
			'ING_PICTURE' => 'Ing Picture',
			'ING_PICTURE_AUTH' => 'Ing Picture Auth',
			'ING_TITLE_EN' => 'Ing Title En',
			'ING_TITLE_DE' => 'Ing Title De',
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

		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('ING_CREATED',$this->ING_CREATED,true);
		$criteria->compare('ING_CHANGED',$this->ING_CHANGED,true);
		$criteria->compare('NUT_ID',$this->NUT_ID);
		$criteria->compare('ING_GROUP',$this->ING_GROUP);
		$criteria->compare('ING_SUBGROUP',$this->ING_SUBGROUP);
		$criteria->compare('ING_STATE',$this->ING_STATE);
		$criteria->compare('ING_CONVENIENCE',$this->ING_CONVENIENCE);
		$criteria->compare('ING_STORABILITY',$this->ING_STORABILITY);
		$criteria->compare('ING_DENSITY',$this->ING_DENSITY);
		$criteria->compare('ING_PICTURE',$this->ING_PICTURE,true);
		$criteria->compare('ING_PICTURE_AUTH',$this->ING_PICTURE_AUTH,true);
		$criteria->compare('ING_TITLE_EN',$this->ING_TITLE_EN,true);
		$criteria->compare('ING_TITLE_DE',$this->ING_TITLE_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}