<?php

/**
 * This is the model class for table "recipes".
 *
 * The followings are the available columns in table 'recipes':
 * @property integer $REC_ID
 * @property integer $PRF_UID
 * @property string $REC_IMG
 * @property string $REC_IMG_AUTH
 * @property string $REC_IMG_ETAG
 * @property integer $RET_ID
 * @property integer $REC_KCAL
 * @property string $REC_NAME_EN_GB
 * @property string $REC_NAME_DE_CH
 * @property integer $CREATED_BY
 * @property string $CREATED_ON
 * @property integer $CHANGED_BY
 * @property string $CHANGED_ON
 */
class Recipes extends ActiveRecordEC
{
	public $filename;
	public $imagechanged;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Recipes the static model class
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
		return 'recipes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RET_ID, REC_NAME_EN_GB, CREATED_BY, CREATED_ON', 'required'),
			array('REC_IMG_AUTH', 'required', 'on'=>'withPic'),
			array('PRF_UID, RET_ID, REC_KCAL, CREATED_BY, CHANGED_BY', 'numerical', 'integerOnly'=>true),
			array('REC_IMG_AUTH', 'length', 'max'=>30),
			array('REC_IMG_ETAG', 'length', 'max'=>40),
			array('REC_NAME_EN_GB, REC_NAME_DE_CH', 'length', 'max'=>100),
			array('RET_ID, REC_IMG, CHANGED_ON, steps', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, PRF_UID, REC_IMG, REC_IMG_AUTH, REC_IMG_ETAG, RET_ID, REC_NAME_EN_GB, REC_NAME_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
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
			'recipeTypes' => array(self::BELONGS_TO, 'RecipeTypes', 'RET_ID'),
			'steps' => array(self::HAS_MANY, 'Steps', 'REC_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'REC_ID' => 'Rec',
			'PRF_UID' => 'Prf Uid',
			'REC_IMG' => 'Rec Img',
			'REC_IMG_AUTH' => 'Rec Img Auth',
			'REC_IMG_ETAG' => 'Rec Img Etag',
			'RET_ID' => 'Ret',
			'REC_KCAL' => 'Rec Kcal',
			'REC_NAME_EN_GB' => 'Rec Name En Gb',
			'REC_NAME_DE_CH' => 'Rec Name De Ch',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}

	public function getSearchFields(){
		return array('REC_ID', 'REC_NAME_' . Yii::app()->session['lang']);
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('PRF_UID',$this->PRF_UID);
		$criteria->compare('REC_IMG',$this->REC_IMG,true);
		$criteria->compare('REC_IMG_AUTH',$this->REC_IMG_AUTH,true);
		$criteria->compare('REC_IMG_ETAG',$this->REC_IMG_ETAG,true);
		$criteria->compare('RET_ID',$this->RET_ID);
		$criteria->compare('REC_KCAL',$this->REC_KCAL);
		$criteria->compare('REC_NAME_EN_GB',$this->REC_NAME_EN_GB,true);
		$criteria->compare('REC_NAME_DE_CH',$this->REC_NAME_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON,true);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON,true);

		$criteria->with = array('steps' => array('with' => 'ingredient', 'with' => 'stepType'));
		$criteria->with = array('recipeTypes');
		
		$criteria->compare('ING_ID',$this->steps->ingredient,true);
		$criteria->compare('STT_ID',$this->steps->stepType,true);
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		/*
		$sort->attributes = array(
			'nutrientData' => array(
				'asc' => 'NUT_ID',
				'desc' => 'NUT_ID DESC',
			),
			'groupNames' => array(
				'asc' => 'GRP_ID',
				'desc' => 'GRP_ID DESC',
			),
			'subgroupNames' => array(
				'asc' => 'SGR_ID',
				'desc' => 'SGR_ID DESC',
			),
			'ingredientConveniences' => array(
				'asc' => 'ICO_ID',
				'desc' => 'ICO_ID DESC',
			),
			'storability' => array(
				'asc' => 'STB_ID',
				'desc' => 'STB_ID DESC',
			),
			'*',
		);
		*/
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
