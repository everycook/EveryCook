<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $PRO_ID
 * @property string $PRO_BARCODE
 * @property integer $PRO_PACKAGE_GRAMMS
 * @property integer $ING_ID
 * @property integer $ECO_ID
 * @property integer $ETH_ID
 * @property string $PRO_IMG_FILENAME
 * @property string $PRO_IMG_AUTH
 * @property string $PRO_IMG_ETAG
 * @property string $PRO_NAME_EN_GB
 * @property string $PRO_NAME_DE_CH
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Products extends ActiveRecordEC
{
	public $filename;
	public $imagechanged;
	public $oldProducers = null;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Products the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ECO_ID, ETH_ID, PRO_IMG_ETAG, PRO_NAME_EN_GB, PRO_NAME_DE_CH, CREATED_BY, CREATED_ON', 'required'),
			array('PRO_IMG_AUTH', 'required', 'on'=>'withPic'),
			array('PRO_PACKAGE_GRAMMS, ING_ID, ECO_ID, ETH_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('PRO_BARCODE', 'length', 'max'=>20),
			array('PRO_IMG_FILENAME', 'length', 'max'=>250),
			array('PRO_IMG_AUTH', 'length', 'max'=>30),
			array('PRO_IMG_ETAG', 'length', 'max'=>40),
			array('PRO_NAME_EN_GB, PRO_NAME_DE_CH', 'length', 'max'=>100),
			array('CHANGED_ON', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRO_ID, PRO_BARCODE, PRO_PACKAGE_GRAMMS, ING_ID, ECO_ID, ETH_ID, PRO_IMG_FILENAME, PRO_IMG_AUTH, PRO_IMG_ETAG, PRO_NAME_EN_GB, PRO_NAME_DE_CH, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ingredient' => array(self::BELONGS_TO, 'Ingredients', 'ING_ID'),
			'ecology' => array(self::BELONGS_TO, 'Ecology', 'ECO_ID'),
			'ethicalCriteria' => array(self::BELONGS_TO, 'EthicalCriteria', 'ETH_ID'),
			//'proToPrds' => array(self::MANY_MANY, 'ProToPrd', 'PRO_ID'),
			//'producers' => array(self::HAS_MANY, 'Producers', 'PRD_ID'),
			'producers' => array(self::MANY_MANY, 'Producers', 'pro_to_prd(PRO_ID,PRD_ID)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'PRO_ID' => 'Pro',
			'PRO_BARCODE' => 'Pro Barcode',
			'PRO_PACKAGE_GRAMMS' => 'Pro Package Gramms',
			'ING_ID' => 'Ing',
			'ECO_ID' => 'Eco',
			'ETH_ID' => 'Eth',
			'PRO_IMG_FILENAME' => 'Pro Img Filename',
			'PRO_IMG_AUTH' => 'Pro Img Auth',
			'PRO_IMG_ETAG' => 'Pro Img Etag',
			'PRO_NAME_EN_GB' => 'Pro Name En Gb',
			'PRO_NAME_DE_CH' => 'Pro Name De Ch',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}

	public function getSearchFields(){
		return array('PRO_ID', 'PRO_NAME_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.PRO_ID',$this->PRO_ID);
		$criteria->compare($this->tableName().'.PRO_BARCODE',$this->PRO_BARCODE,true);
		$criteria->compare($this->tableName().'.PRO_PACKAGE_GRAMMS',$this->PRO_PACKAGE_GRAMMS);
		$criteria->compare($this->tableName().'.ING_ID',$this->ING_ID);
		$criteria->compare($this->tableName().'.ECO_ID',$this->ECO_ID);
		$criteria->compare($this->tableName().'.ETH_ID',$this->ETH_ID);
		$criteria->compare($this->tableName().'.PRO_IMG_FILENAME',$this->PRO_IMG_FILENAME,true);
		$criteria->compare($this->tableName().'.PRO_IMG_AUTH',$this->PRO_IMG_AUTH,true);
		$criteria->compare($this->tableName().'.PRO_IMG_ETAG',$this->PRO_IMG_ETAG,true);
		$criteria->compare($this->tableName().'.PRO_NAME_EN_GB',$this->PRO_NAME_EN_GB,true);
		$criteria->compare($this->tableName().'.PRO_NAME_DE_CH',$this->PRO_NAME_DE_CH,true);
		$criteria->compare($this->tableName().'.CREATED_BY',$this->CREATED_BY);
		$criteria->compare($this->tableName().'.CREATED_ON',$this->CREATED_ON);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
		
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('PRO_ID',$this->PRO_ID);
		$criteria->compare('PRO_BARCODE',$this->PRO_BARCODE,true);
		$criteria->compare('PRO_PACKAGE_GRAMMS',$this->PRO_PACKAGE_GRAMMS);
		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('ECO_ID',$this->ECO_ID);
		$criteria->compare('ETH_ID',$this->ETH_ID);
		$criteria->compare('PRO_IMG_FILENAME',$this->PRO_IMG_FILENAME,true);
		$criteria->compare('PRO_IMG_AUTH',$this->PRO_IMG_AUTH,true);
		$criteria->compare('PRO_IMG_ETAG',$this->PRO_IMG_ETAG,true);
		$criteria->compare('PRO_NAME_EN_GB',$this->PRO_NAME_EN_GB,true);
		$criteria->compare('PRO_NAME_DE_CH',$this->PRO_NAME_DE_CH,true);
		$criteria->compare('CREATED_BY',$this->CREATED_BY);
		$criteria->compare('CREATED_ON',$this->CREATED_ON);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		$criteria->with = array('ingredient');
		$criteria->with = array('producers' => array('with' => 'ingredient', 'with' => 'stepType'));
		
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
	public function search(){
		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getCriteria(),
			'sort'=>$this->getSort(),
		));
	}
}
