<?php

class CookAssistantController extends Controller {
	public function actionStart(){
		$meal = $this->loadModel($_GET['id'], true);
		$course = $meal->meaToCous[0]->course;
		
		$info = new MealPlannerInfo();
		$info->meal = $meal;
		$info->courseNr = 0;
		$info->course = $course;
		$info->startTime = time();
		$stepNumbers = array();
		$stepStartTime = array();
		$cookWithEveryCook = array();
		$totalTimes = array();
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$stepNumbers[] = 0;
			$stepStartTime[] = time();
			$cookWithEveryCook[] = false;
			$totalTime = 0;
			foreach($course->couToRecs[$recipeNr]->recipe->steps as $step){
				$totalTime += $step->STE_STEP_DURATION;
			}
			$totalTimes[] = $totalTime;
		}
		$info->stepNumbers = $stepNumbers;
		$info->stepStartTime = $stepStartTime;
		$info->cookWithEveryCook = $cookWithEveryCook;
		$info->totalTime = $totalTimes;
		$this->loadSteps($info);
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$this->sendActionToFirmware($info, $recipeNr);
		}
		Yii::app()->session['cookingInfo'] = $info;
		
		$this->checkRenderAjax('index', array('info'=>$info));
		//$this->redirect('cookassistant/index');
	}
	
	private function loadSteps($info){
		$course = $info->course;
		$currentSteps = array();
		$currentTime = time();
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$mealStep = new MealPlannerStep();
			$stepNr = $info->stepNumbers[$recipeNr];
			$recipe = $course->couToRecs[$recipeNr]->recipe;
			$step = $recipe->steps[$stepNr];
			$stepStartTime = $info->stepStartTime[$recipeNr];
			
			$mealStep->recipeNr = $recipeNr;
			$mealStep->stepNr = $stepNr;
			$mealStep->recipeName = $recipe->__get('REC_NAME_'.Yii::app()->session['lang']);
			$mealStep->stepDuration = $step->STE_STEP_DURATION;
			$mealStep->finishedIn = $info->totalTime[$recipeNr];
			//$mealStep->inTime = $stepStartTime + $step->STE_STEP_DURATION < $currentTime;
			$mealStep->nextStepIn = $currentTime - $stepStartTime + $step->STE_STEP_DURATION;
			//$mealStep->mustWait = $step->STT_ID != 0; //TODO 10% befor time end, next is possible
			$mealStep->mustWait  = false;
			$mealStep->actionText = $step->action->__get('ACT_DESC_'.Yii::app()->session['lang']);
			
			if (isset($step->ingredient)){
				$mealStep->ingredientId = $step->ingredient->ING_ID;
				$mealStep->ingredientCopyright = $step->ingredient->ING_IMG_AUTH;
				$mealStep->actionText = str_replace('#objectofaction#', $step->ingredient->__get('ING_NAME_'.Yii::app()->session['lang']), $mealStep->actionText);
			} else {
				$mealStep->ingredientId = 0;
				$mealStep->ingredientCopyright = '';
			}
			$mealStep->ingredientGrammsTotal = 0; //TODO calculate
			$mealStep->ingredientGrammsCurrent = 0; //TODO read from firmware
			
			$currentSteps[] = $mealStep;
		}
		$info->steps = $currentSteps;
	}

	public function actionGotoCourse($number){
		$info = Yii::app()->session['cookingInfo'];
		$meal = $info->meal;
		if (isset($meal->meaToCous[$number])){
			$info = new MealPlannerInfo();
			$info->meal = $meal;
			$info->courseNr = $number;
			$info->course = $meal->meaToCous[$number]->course;
			$info->startTime = time();
			
			$stepNumbers = array();
			$stepStartTime = array();
			$cookWithEveryCook = array();
			for ($recipeNr=0; $recipeNr<count($info->course->couToRecs); ++$recipeNr){
				$stepNumbers[] = 0;
				$stepStartTime[] = time();
				$cookWithEveryCook[] = false;
			}
			$info->stepNumbers = $stepNumbers;
			$info->stepStartTime = $stepStartTime;
			$info->cookWithEveryCook = $cookWithEveryCook;
			$this->loadSteps($info);
			Yii::app()->session['cookingInfo'] = $info;
		} else {
			//TODO error course not exist for meal
		}
		
		$this->checkRenderAjax('index', array('info'=>$info));
	}
	
	public function actionNextCourse(){
		$info = Yii::app()->session['cookingInfo'];
		$this->actionGotoCourse($info->courseNr + 1);
	}
	
	public function actionAbort() {
		//TODO abort cooking
		$info = Yii::app()->session['cookingInfo'];
		sendStopToFirmware($info);
		$this->checkRenderAjax('abort');
	}

	public function actionIndex(){
		$info = Yii::app()->session['cookingInfo'];
		$this->checkRenderAjax('index', array('info'=>$info));
	}
	
	private function sendActionToFirmware($info, $recipeNr){
		if (isset($info->cookWithEveryCook[$recipeNr]) && $info->cookWithEveryCook[$recipeNr]){
			//TODO send to EveryCook firmware
		}
	}
	
	private function sendStopToFirmware($info){
		for ($recipeNr=0; $recipeNr<count($info->$course->couToRecs); ++$recipeNr){
			if (isset($info->cookWithEveryCook[$recipeNr]) && $info->cookWithEveryCook[$recipeNr]){
				//TODO send to EveryCook firmware
			}
		}
	}
	
	public function actionNext($recipeNr){
		$info = Yii::app()->session['cookingInfo'];
		
		if (isset($info->stepNumbers[$recipeNr])){
			if (isset($info->course->couToRecs[$recipeNr]->recipe->steps[$info->stepNumbers[$recipeNr]+1])){
				++$info->stepNumbers[$recipeNr];
				$this->loadSteps($info);
				$this->sendActionToFirmware($info, $recipeNr);
				Yii::app()->session['cookingInfo'] = $info;
			} else {
				//TODO recipe ended, no next step available.
			}
		} else {
			//TODO error recipeNr doesnt exist.
		}
		//$this->render('index', array('info'=>$info));
		$this->redirect('index');
	}

	public function actionOverview() {
		//TODO create overview
		$this->checkRenderAjax('overview');
	}

	public function actionPrev() {
		//TODO is a "back" action needed?
		$this->checkRenderAjax('prev');
	}
	
	public function actionEnd(){
		//TODO stop cooking
		$info = Yii::app()->session['cookingInfo'];
		sendStopToFirmware($info);
		$this->checkRenderAjax('end');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Meals::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}