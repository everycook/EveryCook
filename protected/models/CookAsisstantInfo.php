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

class CookAsisstantInfo extends CModel
{
	public $meal = null;
	public $courseNr = 0;
	public $recipeSteps = array();
	public $stepNumbers = array();
	public $steps = array();
	public $totalWeight = array();
	public $stepStartTime = array();
	public $recipeStartTime = array();
	public $cookWith = array();
	public $totalTime = array();
	public $prepareTime = array();
	public $cookTime = array();
	public $usedTime = array();
	public $recipeUsedTime = array();
	public $timeDiff = array();
	public $finishedIn = 0;
	public $timeDiffMax = 0;
	public $started = false;
	public $cookInState = array();
	public $ingredientWeight = array();
	public $ingredientWeightInPan = array();
	public $ingredientIdToNutrient = array();
	public $courseFinished = array();
	public $voted = array();
	public $physics = array();
	public $recipeCookedInfos = array();
	
	public function attributeNames(){
		return array('meal', 'courseNr', 'course', 'recipeSteps', 'stepNumbers', 'steps', 'totalWeight', 'stepStartTime', 'recipeStartTime', 'cookWith', 'totalTime', 'prepareTime', 'cookTime', 'usedTime', 'recipeUsedTime', 'timeDiff', 'finishedIn', 'timeDiffMax', '$started', 'cookInState', 'ingredientWeight', 'ingredientWeightInPan', 'ingredientIdToNutrient', 'courseFinished', 'voted', 'physics', 'recipeCookedInfos');
	}	
	
	public function __get($name) {
		if(isset($this->$name)) {
			return $this->$name;
		} else {
			return null;
		}
	}
	
	/*
	public function __set($name, $value) {
		if (in_array($name, $this->attributeNames())){
			$this->$name = $value;
		} else {
			parent::__set($name,$value);
		}
	}
	*/
	
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