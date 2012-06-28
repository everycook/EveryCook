<?php

/**
 * This is the model class for table "30608_ecprivate.mea_to_cou".
 *
 * The followings are the available columns in table '30608_ecprivate.mea_to_cou':
 * @property integer $MEA_ID
 * @property integer $MTC_ORDER
 * @property integer $COU_ID
 * @property double $MTC_PERC_MEAL
 * @property string $MTC_EAT_PERS
 * @property integer $MTC_KCAL_DAY_TOTAL
 * @property integer $MTC_EAT_ADULTS
 * @property integer $MTC_EAT_CHILDREN
 
 */
class MeaToCou extends ActiveRecordECSimple
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MeaToCou the static model class
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
		return 'mea_to_cou';
	}

   /**
    * @return CDbConnection
    */
   public function getDbConnection(){
       return Yii::app()->dbp;
   }
   
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MEA_ID, MTC_ORDER, COU_ID, MTC_PERC_MEAL, MTC_EAT_PERS, MTC_KCAL_DAY_TOTAL, MTC_EAT_ADULTS, MTC_EAT_CHILDREN', 'required'),
			array('MEA_ID, MTC_ORDER, COU_ID, MTC_PERC_MEAL, MTC_KCAL_DAY_TOTAL, MTC_EAT_ADULTS, MTC_EAT_CHILDREN', 'numerical', 'integerOnly'=>true),
			array('MEA_ID, MTC_ORDER, COU_ID, MTC_PERC_MEAL, MTC_EAT_PERS, MTC_KCAL_DAY_TOTAL, MTC_EAT_ADULTS, MTC_EAT_CHILDREN', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('MEA_ID, MTC_ORDER, COU_ID, MTC_PERC_MEAL, MTC_EAT_PERS, MTC_KCAL_DAY_TOTAL, MTC_EAT_ADULTS, MTC_EAT_CHILDREN', 'safe', 'on'=>'search'),
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
			//'course' => array(self::HAS_ONE, 'Courses', 'COU_ID', 'through'=>'test'),
			//'test' => array(self::BELONGS_TO, 'MeaToCou', 'MEA_ID, MTC_ORDER'),
			'course' => array(self::HAS_ONE, 'Courses', 'COU_ID'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'MEA_ID' => 'Mea',
			'MTC_ORDER' => 'Mtc Order',
			'COU_ID' => 'Cou',
			'MTC_PERC_MEAL' => 'Mtc Perc Meal',
			'MTC_EAT_PERS' => 'Mtc Eat Pers',
			'MTC_KCAL_DAY_TOTAL' => 'Mtc Kcal Day Total',
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

		$criteria->compare('MEA_ID',$this->MEA_ID);
		$criteria->compare('MTC_ORDER',$this->MTC_ORDER);
		$criteria->compare('COU_ID',$this->COU_ID);
		$criteria->compare('MTC_PERC_MEAL',$this->MTC_PERC_MEAL);
		$criteria->compare('MTC_EAT_PERS',$this->MTC_EAT_PERS,true);
		$criteria->compare('MTC_KCAL_DAY_TOTAL',$this->MTC_KCAL_DAY_TOTAL);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}