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
 * This is the model class for table "steps".
 *
 * The followings are the available columns in table 'steps':
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
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class Steps extends ActiveRecordEC
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Steps the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'steps';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('REC_ID, STE_STEP_NO, AIN_ID, CREATED_BY, CREATED_ON', 'required'),
			array('REC_ID, AIN_ID, ING_ID, STE_STEP_NO, STE_GRAMS, STE_CELSIUS, STE_KPA, STE_RPM, STE_CLOCKWISE, STE_STIR_RUN, STE_STIR_PAUSE, STE_STEP_DURATION, TOO_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'numerical', 'integerOnly'=>true),
			array('STE_PREP', 'length', 'max'=>1),
			array('AIN_ID, ING_ID, STE_GRAMS, STE_CELSIUS, STE_KPA, STE_RPM, STE_CLOCKWISE, STE_STIR_RUN, STE_STIR_PAUSE, STE_STEP_DURATION, TOO_ID, recipe, ingredient, action, stepType', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('REC_ID, AIN_ID, ING_ID, STE_STEP_NO, STE_PREP, STE_GRAMS, STE_CELSIUS, STE_KPA, STE_RPM, STE_CLOCKWISE, STE_STIR_RUN, STE_STIR_PAUSE, STE_STEP_DURATION, TOO_ID, CREATED_BY, CREATED_ON, CHANGED_BY, CHANGED_ON', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			/*'recipe' => array(self::BELONGS_TO, 'Recipes', 'REC_ID'),
			'stepType' => array(self::BELONGS_TO, 'StepTypes', 'STT_ID'),*/
			'ingredient' => array(self::BELONGS_TO, 'Ingredients', 'ING_ID'),
			'actionIn' => array(self::BELONGS_TO, 'ActionsIn', 'AIN_ID'),
			'tool' => array(self::BELONGS_TO, 'Tools', 'TOO_ID'),
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
			//'STT_ID' => 'Stt',
			'CREATED_BY' => 'Created By',
			'CREATED_ON' => 'Created On',
			'CHANGED_BY' => 'Changed By',
			'CHANGED_ON' => 'Changed On',
		);
	}

	public function getSearchFields(){
		return array('REC_ID', 'REC_NAME_' . Yii::app()->session['lang']);
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
		
		return $criteria;
	}
	
	public function getCriteria(){
		$criteria=new CDbCriteria;
		
		$criteria->compare('REC_ID',$this->REC_ID);
		$criteria->compare('AIN_ID',$this->AIN_ID);
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
		$criteria->compare('TOO_ID',$this->TOO_ID);
		
		//$criteria->with = array('recipe' => array());
		$criteria->with = array('ingredient' => array('nutrientData'));
		$criteria->with = array('action' => array());
		$criteria->with = array('actionsIn' => array());
		$criteria->with = array('stepType' => array());
		
		$criteria->compare('REC_ID',$this->recipe,true);
		$criteria->compare('ING_ID',$this->ingredient,true);
		$criteria->compare('AIN_ID',$this->actionsIn,true);
		
		$criteria->order('STE_STEP_NO');
		
		return $criteria;
	}
	
	public function getSort(){
		$sort = new CSort;
		
		$sort->attributes = array(
			'REC_ID' => array(
				'asc' => 'REC_ID, STE_STEP_NO',
				'desc' => 'REC_ID DESC, STE_STEP_NO DESC',
			),
			'STE_STEP_NO' => array(
				'asc' => 'REC_ID, STE_STEP_NO',
				'desc' => 'REC_ID DESC, STE_STEP_NO DESC',
			),
			'ingredient' => array(
				'asc' => 'INC_ID',
				'desc' => 'INC_ID DESC',
			),
			'nutrientData' => array(
				'asc' => 'NUT_ID',
				'desc' => 'NUT_ID DESC',
			),
			/*
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getCriteria(),
			'sort'=>$this->getSort(),
		));
	}


	public function getAsHTMLString($cookin = '#cookin#'){
		if (isset($this->actionIn) && $this->actionIn != null){
			return self::getHTMLString($this, $this->actionIn->__get('AIN_DESC_' . Yii::app()->session['lang']), $cookin);
		} else {
			return '';
		}
	}
	
	public static function getFieldToCssClass(){
		return array(
				'ING_ID'=>'ingredient',
				'TOO_ID'=>'tool',
				'STE_GRAMS'=>'weight',
				'STE_STEP_DURATION'=>'time',
				'STE_CELSIUS'=>'temp',
				'STE_KPA'=>'pressure',
				'COI_ID'=>'cookin'
		);
	}
	
	public static function getHTMLString($step, $text, $cookin = '#cookin#'){
		if (isset($text) && $text != null){
			$replText = '<span class="ingredient">' . ((isset($step->ingredient) && $step->ingredient != null)?$step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']):'#ingredient#') . '</span> ';
			$text = str_replace('#ingredient', $replText, $text);
			
			$replText = '<span class="weight">' . (($step->STE_GRAMS)?$step->STE_GRAMS:'#weight#') . '</span><span class="weight_unit">g</span> ';
			$text = str_replace('#weight', $replText, $text);
			
			$replText = '<span class="tool">' . ((isset($step->tool) && $step->tool != null)?$step->tool->__get('TOO_DESC_' . Yii::app()->session['lang']):'') . '</span> ';
			$text = str_replace('#tool', $replText, $text);
			
			if ($step->STE_STEP_DURATION){
				$time = date('H:i:s', $step['STE_STEP_DURATION']-3600);
			} else {
				$time = '#time#';
			}
			$replText = '<span class="time">' . $time . '</span><span class="time_unit">h</span> ';
			$text = str_replace('#time', $replText, $text);
			
			if ($step->STE_CELSIUS){
				$replText = '<span class="temp">' . ($step->STE_CELSIUS?$step->STE_CELSIUS:'#temp#') . '</span><span class="temp_unit">°C</span> ';
				$text = str_replace('#temp', $replText, $text);
			}
			
			$replText = '<span class="pressure">' .(($step->STE_KPA)?$step->STE_KPA:'#press#') . '</span><span class="pressure_unit">kpa</span> ';
			$text = str_replace('#press', $replText, $text);
			
			$replText = '<span class="cookin">' . $cookin . '</span> ';
			$text = str_replace('#cookin', $replText, $text);
			return $text;
		}
		return "";
	}
}
