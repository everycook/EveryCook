<?php

/**
 * This is the model class for table "subgroup_names".
 *
 * The followings are the available columns in table 'subgroup_names':
 * @property integer $SUBGRP_ID
 * @property integer $SUBGRP_OF
 * @property string $SUBGRP_DESC_EN
 * @property string $SUBGRP_DESC_DE
 */
class SubgroupNames extends CActiveRecord
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
			array('SUBGRP_OF, SUBGRP_DESC_EN', 'required'),
			array('SUBGRP_OF', 'numerical', 'integerOnly'=>true),
			array('SUBGRP_DESC_EN, SUBGRP_DESC_DE', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SUBGRP_ID, SUBGRP_OF, SUBGRP_DESC_EN, SUBGRP_DESC_DE', 'safe', 'on'=>'search'),
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
			'SUBGRP_ID' => 'Subgrp',
			'SUBGRP_OF' => 'Subgrp Of',
			'SUBGRP_DESC_EN' => 'Subgrp Desc En',
			'SUBGRP_DESC_DE' => 'Subgrp Desc De',
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

		$criteria->compare('SUBGRP_ID',$this->SUBGRP_ID);
		$criteria->compare('SUBGRP_OF',$this->SUBGRP_OF);
		$criteria->compare('SUBGRP_DESC_EN',$this->SUBGRP_DESC_EN,true);
		$criteria->compare('SUBGRP_DESC_DE',$this->SUBGRP_DESC_DE,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}