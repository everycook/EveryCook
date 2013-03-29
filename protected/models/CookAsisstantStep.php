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

class CookAsisstantStep extends CModel
{
	public $recipeNr = 0;
	public $stepNr = 0;
	public $recipeName = null;
	public $stepDuration = 0;
	public $finishedIn = 0;
	public $finishedAt = "";
	public $inTime = true;
	public $nextStepIn = 0;
	public $nextStepTotal = 0;
	public $lowestFinishedIn = 0;
	public $mustWait = true;
	public $autoClick = false;
	public $mainActionText = null;
	public $actionText = null;
	public $ingredientId = null;
	public $ingredientCopyright = null;
	public $percent = 0;
	public $endReached = false;
	public $weightReachedTime = 0;
	public $stepType = 0;
	public $currentTemp = null;
	public $currentPress = 0;
	public $HWValues = array();
	
	public function attributeNames(){
		return array('recipeNr', 'stepNr', 'recipeName', 'stepDuration', 'finishedIn', 'finishedAt', 'inTime', 'nextStepIn', 'nextStepTotal', 'lowestFinishedIn', 'mustWait', 'autoClick', 'mainActionText', 'actionText', 'ingredientId', 'percent', 'endReached', 'weightReachedTime', 'stepType', 'currentTemp', 'currentPress', 'HWValues');
	}	
	
	public function __get($name) {
		if(isset($this->$name)) {
			return $this->$name;
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return MeaToCou the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('', 'required'),
			//array('', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
	}

}