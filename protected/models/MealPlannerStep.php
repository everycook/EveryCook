<?php

class MealPlannerStep extends CModel
{
	public $recipeNr = 0;
	public $stepNr = 0;
	public $recipeName = null;
	public $stepDuration = 0;
	public $finishedIn = 0;
	public $inTime = true;
	public $nextStepIn = 0;
	public $mustWait = true;
	public $actionText = null;
	public $ingredientId = null;
	public $ingredientCopyright = null;
	public $ingredientGrammsTotal = 0;
	public $ingredientGrammsCurrent = 0;
	
	public function attributeNames(){
		return array('recipeNr', 'stepNr', 'recipeName', 'stepDuration', 'finishedIn', 'inTime', 'nextStepIn', 'mustWait', 'actionText', 'ingredientId', 'ingredientGrammsTotal', 'ingredientGrammsCurrent');
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