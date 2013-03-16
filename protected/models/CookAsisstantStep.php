<?php

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