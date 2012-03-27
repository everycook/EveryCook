<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $PRO_ID
 * @property integer $PRO_BARCODE
 * @property integer $PRO_PACKAGE_GRAMMS
 * @property integer $ING_ID
 * @property integer $PRO_ECO
 * @property integer $PRO_ETHIC
 * @property string $PRO_PICTURE
 * @property string $PRO_PICTURE_COPYR
 */
class Products extends CActiveRecord
{
	public $filename;
	public $imagechanged;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Products the static model class
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
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRO_ECO, PRO_ETHIC, PRO_NAME_EN', 'required'),
			array('PRO_PICTURE_COPYR', 'required', 'on'=>'withPic'),
			array('PRO_BARCODE, PRO_PACKAGE_GRAMMS, ING_ID, PRO_ECO, PRO_ETHIC', 'numerical', 'integerOnly'=>true),
			array('PRO_PICTURE_COPYR', 'length', 'max'=>30),
			array('PRO_PICTURE_ETAG', 'length', 'max'=>40),
			array('PRO_NAME_EN, PRO_NAME_DE', 'length', 'max'=>60),
			array('PRO_PICTURE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRO_ID, PRO_BARCODE, PRO_PACKAGE_GRAMMS, ING_ID, PRO_ECO, PRO_ETHIC, PRO_PICTURE, PRO_PICTURE_ETAG, PRO_PICTURE_COPYR, PRO_NAME_EN, PRO_NAME_DE', 'safe', 'on'=>'search'),
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
			'ingredient' => array(self::BELONGS_TO, 'Ingredients', 'ING_ID'),
			'ecology' => array(self::BELONGS_TO, 'Ecology', 'PRO_ECO'),
			'ethicalCriteria' => array(self::BELONGS_TO, 'EthicalCriteria', 'PRO_ETHIC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'PRO_ID' => 'Pro',
			'PRO_BARCODE' => 'Pro Barcode',
			'PRO_PACKAGE_GRAMMS' => 'Pro Package Gramms',
			'ING_ID' => 'Ing',
			'PRO_ECO' => 'Pro Eco',
			'PRO_ETHIC' => 'Pro Ethic',
			'PRO_PICTURE' => 'Pro Picture',
			'PRO_PICTURE_COPYR' => 'Pro Picture Copyr',
		);
	}

	public function getSearchFields(){
		return array('PRO_ID', 'PRO_NAME_' . Yii::app()->session['lang']);
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;
		
		$criteria->compare('PRO_ID',$this->PRO_ID);
		$criteria->compare('PRO_BARCODE',$this->PRO_BARCODE);
		$criteria->compare('PRO_PACKAGE_GRAMMS',$this->PRO_PACKAGE_GRAMMS);
		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('PRO_ECO',$this->PRO_ECO);
		$criteria->compare('PRO_ETHIC',$this->PRO_ETHIC);
		$criteria->compare('PRO_PICTURE',$this->PRO_PICTURE,true);
		$criteria->compare('PRO_PICTURE_ETAG',$this->PRO_PICTURE_ETAG,true);
		$criteria->compare('PRO_PICTURE_COPYR',$this->PRO_PICTURE_COPYR,true);
		
		
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('ACT_ID',$this->ACT_ID);
		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('STE_STEP_NO',$this->STE_STEP_NO);
		$criteria->compare('STE_GRAMS',$this->STE_GRAMS);
		$criteria->compare('STE_CELSIUS',$this->STE_CELSIUS);
		$criteria->compare('STE_KPA',$this->STE_KPA);
		$criteria->compare('STE_RPM',$this->STE_RPM);
		$criteria->compare('STE_CLOCKWISE',$this->STE_CLOCKWISE);
		$criteria->compare('STE_STIR_RUN',$this->STE_STIR_RUN);
		$criteria->compare('STE_STIR_PAUSE',$this->STE_STIR_PAUSE);
		$criteria->compare('STE_STEP_DURATION',$this->STE_STEP_DURATION);
		$criteria->compare('STT_ID',$this->STT_ID);
		
		//$criteria->with = array('ingredient' => array('nutrientData'));
		$criteria->with = array('ingredient');
		
		$criteria->compare('ING_ID',$this->ingredient,true);
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
	
		
		return $sort;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getCriteria(),
			'sort'=>$this->getSort(),
		));
	}
}