<?php

class CookAsisstantInfo extends CModel
{
	public $meal = null;
	public $courseNr = 0;
	public $course =  null;
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
	public $ingredientIdToNutrient = array();
	
	public function attributeNames(){
		return array('meal', 'courseNr', 'course', 'recipeSteps', 'stepNumbers', 'steps', 'totalWeight', 'stepStartTime', 'recipeStartTime', 'cookWith', 'totalTime', 'prepareTime', 'cookTime', 'usedTime', 'recipeUsedTime', 'timeDiff', 'finishedIn', 'timeDiffMax', '$started', 'cookInState', 'ingredientWeight', 'ingredientIdToNutrient');
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