<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

/**
 * This is the model class for table "step_changes".
 *
 * The followings are the available columns in table 'step_changes':
 * @property integer $REC_ID
 * @property integer $AIN_ID
 * @property integer $ING_ID
 * @property integer $STE_STEP_NO
 * @property string $STE_PREP
 * @property integer $STE_GRAMS
 * @property integer $STE_CELSIUS
 * @property integer $STE_KPA
 * @property integer $STE_RPM
 * @property integer $STE_CLOCKWISE
 * @property integer $STE_STIR_RUN
 * @property integer $STE_STIR_PAUSE
 * @property integer $STE_STEP_DURATION
 * @property integer $TOO_ID
 * @property integer $AIN_ID_OLD
 * @property integer $ING_ID_OLD
 * @property string $STE_PREP_OLD
 * @property integer $STE_GRAMS_OLD
 * @property integer $STE_CELSIUS_OLD
 * @property integer $STE_KPA_OLD
 * @property integer $STE_RPM_OLD
 * @property integer $STE_CLOCKWISE_OLD
 * @property integer $STE_STIR_RUN_OLD
 * @property integer $STE_STIR_PAUSE_OLD
 * @property integer $STE_STEP_DURATION_OLD
 * @property integer $TOO_ID_OLD
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 * @property string $SCH_ACTION
 * @property string $SCH_SAVED
 */
class StepChanges extends ActiveRecordECChange
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StepChanges the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'step_changes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('REC_ID, STE_STEP_NO', 'required'),
			array('REC_ID, AIN_ID, ING_ID, STE_STEP_NO, STE_GRAMS, STE_CELSIUS, STE_KPA, STE_RPM, STE_CLOCKWISE, STE_STIR_RUN, STE_STIR_PAUSE, STE_STEP_DURATION, TOO_ID, AIN_ID_OLD, ING_ID_OLD, STE_GRAMS_OLD, STE_CELSIUS_OLD, STE_KPA_OLD, STE_RPM_OLD, STE_CLOCKWISE_OLD, STE_STIR_RUN_OLD, STE_STIR_PAUSE_OLD, STE_STEP_DURATION_OLD, TOO_ID_OLD, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('STE_PREP, STE_PREP_OLD, SCH_SAVED', 'length', 'max'=>1),
			array('SCH_ACTION', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, AIN_ID, ING_ID, STE_STEP_NO, STE_PREP, STE_GRAMS, STE_CELSIUS, STE_KPA, STE_RPM, STE_CLOCKWISE, STE_STIR_RUN, STE_STIR_PAUSE, STE_STEP_DURATION, TOO_ID, AIN_ID_OLD, ING_ID_OLD, STE_PREP_OLD, STE_GRAMS_OLD, STE_CELSIUS_OLD, STE_KPA_OLD, STE_RPM_OLD, STE_CLOCKWISE_OLD, STE_STIR_RUN_OLD, STE_STIR_PAUSE_OLD, STE_STEP_DURATION_OLD, TOO_ID_OLD, CHANGED_BY, CHANGED_ON, SCH_ACTION, SCH_SAVED', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'REC_ID' => 'Rec',
			'AIN_ID' => 'Ain',
			'ING_ID' => 'Ing',
			'STE_STEP_NO' => 'Ste Step No',
			'STE_PREP' => 'Ste Prep',
			'STE_GRAMS' => 'Ste Grams',
			'STE_CELSIUS' => 'Ste Celsius',
			'STE_KPA' => 'Ste Kpa',
			'STE_RPM' => 'Ste Rpm',
			'STE_CLOCKWISE' => 'Ste Clockwise',
			'STE_STIR_RUN' => 'Ste Stir Run',
			'STE_STIR_PAUSE' => 'Ste Stir Pause',
			'STE_STEP_DURATION' => 'Ste Step Duration',
			'TOO_ID' => 'Too',
			'AIN_ID_OLD' => 'Ain Id Old',
			'ING_ID_OLD' => 'Ing Id Old',
			'STE_PREP_OLD' => 'Ste Prep Old',
			'STE_GRAMS_OLD' => 'Ste Grams Old',
			'STE_CELSIUS_OLD' => 'Ste Celsius Old',
			'STE_KPA_OLD' => 'Ste Kpa Old',
			'STE_RPM_OLD' => 'Ste Rpm Old',
			'STE_CLOCKWISE_OLD' => 'Ste Clockwise Old',
			'STE_STIR_RUN_OLD' => 'Ste Stir Run Old',
			'STE_STIR_PAUSE_OLD' => 'Ste Stir Pause Old',
			'STE_STEP_DURATION_OLD' => 'Ste Step Duration Old',
			'TOO_ID_OLD' => 'Too Id Old',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
			'SCH_ACTION' => 'Sch Action',
			'SCH_SAVED' => 'Sch Saved',
		);
	}
	
	public function getSearchFields(){
		return array('REC_ID', 'REC_DESC_' . Yii::app()->session['lang']);
	}
	
	
	public function getCriteriaString(){
		$criteria=new CDbCriteria;
		
		$criteria->compare($this->tableName().'.REC_ID',$this->REC_ID);
		$criteria->compare($this->tableName().'.AIN_ID',$this->AIN_ID);
		$criteria->compare($this->tableName().'.ING_ID',$this->ING_ID);
		$criteria->compare($this->tableName().'.STE_STEP_NO',$this->STE_STEP_NO);
		$criteria->compare($this->tableName().'.STE_PREP',$this->STE_PREP,true);
		$criteria->compare($this->tableName().'.STE_GRAMS',$this->STE_GRAMS);
		$criteria->compare($this->tableName().'.STE_CELSIUS',$this->STE_CELSIUS);
		$criteria->compare($this->tableName().'.STE_KPA',$this->STE_KPA);
		$criteria->compare($this->tableName().'.STE_RPM',$this->STE_RPM);
		$criteria->compare($this->tableName().'.STE_CLOCKWISE',$this->STE_CLOCKWISE);
		$criteria->compare($this->tableName().'.STE_STIR_RUN',$this->STE_STIR_RUN);
		$criteria->compare($this->tableName().'.STE_STIR_PAUSE',$this->STE_STIR_PAUSE);
		$criteria->compare($this->tableName().'.STE_STEP_DURATION',$this->STE_STEP_DURATION);
		$criteria->compare($this->tableName().'.TOO_ID',$this->TOO_ID);
		$criteria->compare($this->tableName().'.AIN_ID_OLD',$this->AIN_ID_OLD);
		$criteria->compare($this->tableName().'.ING_ID_OLD',$this->ING_ID_OLD);
		$criteria->compare($this->tableName().'.STE_PREP_OLD',$this->STE_PREP_OLD,true);
		$criteria->compare($this->tableName().'.STE_GRAMS_OLD',$this->STE_GRAMS_OLD);
		$criteria->compare($this->tableName().'.STE_CELSIUS_OLD',$this->STE_CELSIUS_OLD);
		$criteria->compare($this->tableName().'.STE_KPA_OLD',$this->STE_KPA_OLD);
		$criteria->compare($this->tableName().'.STE_RPM_OLD',$this->STE_RPM_OLD);
		$criteria->compare($this->tableName().'.STE_CLOCKWISE_OLD',$this->STE_CLOCKWISE_OLD);
		$criteria->compare($this->tableName().'.STE_STIR_RUN_OLD',$this->STE_STIR_RUN_OLD);
		$criteria->compare($this->tableName().'.STE_STIR_PAUSE_OLD',$this->STE_STIR_PAUSE_OLD);
		$criteria->compare($this->tableName().'.STE_STEP_DURATION_OLD',$this->STE_STEP_DURATION_OLD);
		$criteria->compare($this->tableName().'.TOO_ID_OLD',$this->TOO_ID_OLD);
		$criteria->compare($this->tableName().'.CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare($this->tableName().'.CHANGED_ON',$this->CHANGED_ON);
		$criteria->compare($this->tableName().'.SCH_ACTION',$this->SCH_ACTION,true);
		$criteria->compare($this->tableName().'.SCH_SAVED',$this->SCH_SAVED,true);
		return $criteria;
	}
	
	public function getCriteria(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('AIN_ID',$this->AIN_ID);
		$criteria->compare('ING_ID',$this->ING_ID);
		$criteria->compare('STE_STEP_NO',$this->STE_STEP_NO);
		$criteria->compare('STE_PREP',$this->STE_PREP,true);
		$criteria->compare('STE_GRAMS',$this->STE_GRAMS);
		$criteria->compare('STE_CELSIUS',$this->STE_CELSIUS);
		$criteria->compare('STE_KPA',$this->STE_KPA);
		$criteria->compare('STE_RPM',$this->STE_RPM);
		$criteria->compare('STE_CLOCKWISE',$this->STE_CLOCKWISE);
		$criteria->compare('STE_STIR_RUN',$this->STE_STIR_RUN);
		$criteria->compare('STE_STIR_PAUSE',$this->STE_STIR_PAUSE);
		$criteria->compare('STE_STEP_DURATION',$this->STE_STEP_DURATION);
		$criteria->compare('TOO_ID',$this->TOO_ID);
		$criteria->compare('AIN_ID_OLD',$this->AIN_ID_OLD);
		$criteria->compare('ING_ID_OLD',$this->ING_ID_OLD);
		$criteria->compare('STE_PREP_OLD',$this->STE_PREP_OLD,true);
		$criteria->compare('STE_GRAMS_OLD',$this->STE_GRAMS_OLD);
		$criteria->compare('STE_CELSIUS_OLD',$this->STE_CELSIUS_OLD);
		$criteria->compare('STE_KPA_OLD',$this->STE_KPA_OLD);
		$criteria->compare('STE_RPM_OLD',$this->STE_RPM_OLD);
		$criteria->compare('STE_CLOCKWISE_OLD',$this->STE_CLOCKWISE_OLD);
		$criteria->compare('STE_STIR_RUN_OLD',$this->STE_STIR_RUN_OLD);
		$criteria->compare('STE_STIR_PAUSE_OLD',$this->STE_STIR_PAUSE_OLD);
		$criteria->compare('STE_STEP_DURATION_OLD',$this->STE_STEP_DURATION_OLD);
		$criteria->compare('TOO_ID_OLD',$this->TOO_ID_OLD);
		$criteria->compare('CHANGED_BY',$this->CHANGED_BY);
		$criteria->compare('CHANGED_ON',$this->CHANGED_ON);
		$criteria->compare('SCH_ACTION',$this->SCH_ACTION,true);
		$criteria->compare('SCH_SAVED',$this->SCH_SAVED,true);
		//Add with conditions for relations
		//$criteria->with = array('???relationName???' => array());
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		$sort->attributes = array(
		/*
			'sortId' => array(
				'asc' => 'REC_ID',
				'desc' => 'REC_ID DESC',
			),
		*/
			'*',
		);
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