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
 * This is the model class for table "steps_history".
 *
 * The followings are the available columns in table 'steps_history':
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
class StepsHistory extends Steps
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StepsHistory the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'steps_history';
	}

	protected function beforeValidate() {
		$this->updateChangePointer = false;
		$this->updateChangeTime = false;
		return parent::beforeValidate();
	}

}