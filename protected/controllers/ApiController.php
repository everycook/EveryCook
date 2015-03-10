<?php

class ApiController extends CController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('searchRecipes','searchIngredients','recipeDetail','ingredientDetail'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('startAssistant','voteRecipe'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('updateRecipe','updateIngredient'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public $lang = 'EN_GB';
	protected static $trans=null;
	public function getTrans()
	{
		return self::$trans;
	}
	
	protected function beforeAction($action)
	{
		if (isset($_GET['lang'])){
			$this->lang = $_GET['lang'];
		} else if (isset($_POST['lang'])){
			$this->lang = $_POST['lang'];
		} else {
			$this->lang='EN_GB';
		}
		
		if (isset($_GET['token'])){
			$token = $_GET['token'];
		} else if (isset($_POST['token'])){
			$token = $_POST['token'];
		} else {
			//no token found, error
			$this->error($this->lang, 'API_GENERAL_NO_TOKEN');
			return false;
		}
		//TODO: do check of API token here!
		if ($token != 'everycook'){
			$this->error($this->lang, 'API_GENERAL_TOKEN_INVALID');
			return false;
		}
		return true;
	}

	protected function afterAction($action){
		Yii::app()->end();
	}
	
	private function error($lang, $errorKey, $additionalValues = null){
		self::$trans = Controller::loadTranslations($lang);
		self::$trans->showKeyIfAbsent = true; //TODO r
		$error = array(
				'success' => false,
				'lang' => $lang,
				'errorMessage' =>  $this->trans->__get($errorKey),
		);
		if ($additionalValues != null){
			$error = array_merge($error, $additionalValues);
		}
		$this->sendResponse($error);
	}
	
	private function sendResponse($dataArray){
		//JSONify $dataArray
		/*
			$data = array();
			foreach($dataArray as $key=>$value){
			$data[$key]=CJSON::encode($value);
			}
			$data = array('jsonResponse'=>CJSON::encode($data););
			*/
// 		$data = array('jsonResponse'=>CJSON::encode($dataArray));
// 		$this->renderPartial('response',$data,false);
		header('Content-type: application/json');
		echo CJSON::encode($dataArray);
	}
	


	/* ################# Recipe Detail ################# */
	// http://localhost/EveryCook/api/recipeDetail?token=everycook&rec_id=1&servings=1
	public function actionRecipeDetail(){
		if (isset($_GET['rec_id'])){
			$rec_id = $_GET['rec_id'];
		} else if (isset($_POST['rec_id'])){
			$rec_id = $_POST['rec_id'];
		} else {
			$this->error($this->lang, 'API_DETAIL_NO_ID');
			return;
		}
	
		$prepareParam = array();
		if (isset($_GET['servings'])){
			$prepareParam['servings'] = $_GET['servings'];
		} else if (isset($_POST['servings'])){
			$prepareParam['servings'] = $_POST['servings'];
		}
		if (isset($_GET['calories'])){
			$prepareParam['calories'] = $_GET['calories'];
		} else if (isset($_POST['calories '])){
			$prepareParam['calories'] = $_POST['calories'];
		}
		if (isset($_GET['co_in'])){
			$prepareParam['co_in'] = $_GET['co_in'];
		} else if (isset($_POST['co_in'])){
			$prepareParam['co_in'] = $_POST['co_in'];
		} else {
			$prepareParam['co_in'] = 'hand';
		}
		if (isset($prepareParam['co_in'])){
			if (!is_numeric($prepareParam['co_in'])){
				if ($prepareParam['co_in'] == 'everycook' || $prepareParam['co_in'] == 'ec'){
					$prepareParam['co_in'] = 1; //COI_ID for everycook
				} else {
					//unset($prepareParam['co_in']);
					$prepareParam['co_in'] = 3; //COI_ID for Cooking pot
				}
			}
		}
	
		//query Recipe informations
		$url = Yii::app()->createAbsoluteUrl("savedImage/recipes");
		$fieldMapping = array(
				'title' => 'REC_NAME_' . $this->lang,
				'img_url' => 'CASE WHEN REC_IMG_ETAG IS NOT NULL AND REC_IMG_ETAG <> \'\' THEN concat("' . $url . '/", REC_ID ,".png") ELSE "" END',
				'rec_id' => 'REC_ID',
		);

		$fieldsToSelect = array_merge($fieldMapping, array(
				'REC_KCAL',
				'REC_SERVING_COUNT',
				
		));
		$command = Yii::app()->db->createCommand();
		//set selected fields
		$selectStatement = '';
		foreach($fieldsToSelect as $alias=>$field){
			if (is_numeric($alias)){
				$selectStatement .= ', ' . $field;
			} else {
				$selectStatement .= ', ' . $field . ' as ' . $alias;
			}
		}
		$command->select = substr($selectStatement, 2);
		$command->from = 'recipes';
		$command->where('recipes.REC_ID = :id', array(':id'=>$rec_id));
		$recipe = $command->queryRow();
	
		if ($recipe === false){
			//recipe with given id not found
			$this->error($this->lang, 'API_DETAIL_NO_ID', array(
					'errorDetails' => array('rec_id'),
					'rec_id' => $rec_id,
			));
			return;
		}
		
		//calulate amount multiplier
		$rec_kcal = $recipe['REC_KCAL'];
		$rec_servings = $recipe['REC_SERVING_COUNT'];
		$rec_proz = 1;
		if ($rec_kcal != 0){
			if (isset($prepareParam['calories']) && is_numeric($prepareParam['calories'])){
				if ($rec_servings <= 0){
					$rec_proz = $prepareParam['calories'] / $rec_kcal;
				} else {
					$rec_proz = $prepareParam['calories'] / ($rec_kcal * $rec_servings); //new recipes have REC_KCAL per serving not per recipe
				}
			} else if (isset($prepareParam['servings']) && is_numeric($prepareParam['servings'])){
				if ($rec_servings <= 0){
					$rec_proz = $prepareParam['servings']; //servings not set, is old recipe.... calculate als if the recipe is for 1 serving
				} else {
					$rec_proz = $prepareParam['servings'] / $rec_servings;
				}
			} 
		}
		
		//query ingredient Informations
		$url = Yii::app()->createAbsoluteUrl("savedImage/ingredients");
		$fieldMappingIngredients = array(
				'ing_name' => 'ING_NAME_' . $this->lang,
				'img_url' => 'CASE WHEN ING_IMG_ETAG IS NOT NULL AND ING_IMG_ETAG <> \'\' THEN concat("' . $url . '/", ingredients.ING_ID ,".png") ELSE "" END',
				'ing_id' => 'ingredients.ING_ID',
				'ing_qty' => 'steps.STE_GRAMS',
				'qty_unit' => '"g"',
		);
		$ingredientsCommand = Yii::app()->db->createCommand();
		//set selected fields
		$selectStatement = '';
		foreach($fieldMappingIngredients as $alias=>$field){
			$selectStatement .= ', ' . $field . ' as ' . $alias;
		}
		$ingredientsCommand->select = substr($selectStatement, 2);
		$ingredientsCommand->from = 'steps';
		$ingredientsCommand->join('ingredients', 'steps.ING_ID=ingredients.ING_ID'); //because of "join" (not "leftJoin") a condition "AND steps.ING_ID is NOT NULL" is not needed
		$ingredientsCommand->where('steps.REC_ID = :id', array(':id'=>$rec_id));
		$ingredientsCommand->order('steps.STE_STEP_NO');
		$ingredients = $ingredientsCommand->queryAll();
	
		
		
		//query step Informations
		$fieldMappingSteps = array(
				'step_no' => 'STE_STEP_NO',
				'ing_id' => 'ING_ID',
				'action' => 'AIN_DESC_' . $this->lang,
// 				'action' => 'AOU_DESC_' . $this->lang,
		);
		$actionTextFields = array_flip(Steps::getFieldToCssClass());
		$actionTextFields['cookin'] = '("#cookin#")';
		//$actionTextFields['tools'] = 'action_out.TOO_ID';
		$fieldMappingStepsSelect = array_merge($fieldMappingSteps, $actionTextFields);
		
		$stepsCommand = Yii::app()->db->createCommand();
		//set selected fields
		$selectStatement = '';
		foreach($fieldMappingStepsSelect as $alias=>$field){
			$selectStatement .= ', ' . $field . ' as ' . $alias;
		}
		$stepsCommand->select = substr($selectStatement, 2);
		$stepsCommand->from = 'steps';
		// 		'ING_ID'=>'ingredient',
		// 		'TOO_ID'=>'tool',
		// 		'COI_ID'=>'cookin
		//$stepsCommand->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID');
		$stepsCommand->leftJoin('actions_in', 'steps.AIN_ID=actions_in.AIN_ID');
		$stepsCommand->where('steps.REC_ID = :id', array(':id'=>$rec_id));
		$stepsCommand->order('steps.STE_STEP_NO');
		
		//$stepsCommand->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID');
// 		$stepsCommand->leftJoin('ain_to_aou', 'actions_in.AIN_ID=ain_to_aou.AIN_ID');
// 		$stepsCommand->leftJoin('actions_out', 'ain_to_aou.AOU_ID=action_out.AOU_ID');
// 		$stepsCommand->leftJoin('step_types', 'action_out.STT_ID=step_types.STT_ID');
// 		$stepsCommand->where('steps.REC_ID = :id AND ain_to_aou.COI_ID = :coi_id', array(':id'=>$rec_id, ':coi_id'=>$prepareParam['co_in']));
// 		$stepsCommand->order('steps.STE_STEP_NO, ain_to_aou.ATA_NO');
		$steps = $stepsCommand->queryAll();
		
		
		
		
		//prepare result object
		//remove technical fields
		$recipeResult = array();
		foreach ($fieldMapping as $alias=>$field){
			$recipeResult[$alias] = $recipe[$alias];
		}
		
		//format ingredients
		$ingredientsResult = array();
		foreach($ingredients as $ingredient){
			$ingredientResult = array();
			foreach($fieldMappingIngredients as $alias=>$field){
				$ingredientResult[$alias] = $ingredient[$alias];
			}
			$ingredientsResult[$ingredient['ing_id']] = $ingredientResult;
		}
		$recipeResult['ingredients'] = $ingredientsResult;
		
		//format steps
		$stepsResult = array();
		foreach ($steps as $key=>$step){
			$stepResult = array();
			foreach($fieldMappingSteps as $alias=>$field){
				$stepResult[$alias] = $step[$alias];
			}
			$textParams = array();
			foreach($actionTextFields as $alias=>$field){
				$textParams[$alias] = $step[$alias];
			}
			//calculate changed amount of ingredient
			if(isset($textParams['weight']) && is_numeric($textParams['weight'])){
				$textParams['weight'] = $textParams['weight'] * $rec_proz;
			}
			$textParams['time_sec'] = $textParams['time'];
			$textParams['time'] = date('H:i:s', $textParams['time']-3600);
			
// 			$textParams['weight_unit'] = 'g';
			
			$stepResult['textParams'] = $textParams;
			$stepsResult[$key] = $stepResult; 
		}
		$recipeResult['steps'] = $stepsResult;
		
		
		$globalTextParams = array();
		$globalTextParams['time_unit'] = 'h';
		$globalTextParams['weight_unit'] = 'g';
		$globalTextParams['temp_unit'] = '°C';
		$globalTextParams['press_unit'] = 'kpa';
		$recipeResult['globalTextParams'] = $globalTextParams;
		
		$result = array(
				'success' => true,
				'data' => $recipeResult,
// 				$prepareParam['servings']
// 				$prepareParam['calories']
		);
		
		//output result
		$this->sendResponse($result);
	}

	/* ################# Recipe Search ################# */
	// http://localhost/EveryCook/api/searchrecipe?token=everycook&start=30&length=20
	// http://localhost/EveryCook/api/searchrecipe?token=everycook&query=risotto
	
	public $pageStart = 0;
	public $pageLength = 10;
	public $searchSort = 'score';
	protected function loadPaginationInformations(){
		if (isset($_GET['start'])){
			$this->pageStart = $_GET['start'];
		} else if (isset($_POST['start'])){
			$this->pageStart = $_POST['start'];
		}
		
		if (isset($_GET['length'])){
			$this->pageLength = $_GET['length'];
		} else if (isset($_POST['length'])){
			$this->pageLength = $_POST['length'];
		}
		
		if (isset($_GET['sort'])){
			$this->searchSort = $_GET['sort'];
		} else if (isset($_POST['sort'])){
			$this->searchSort = $_POST['sort'];
		}
	}
	
	public function actionSearchRecipes(){
		$this->loadPaginationInformations();
		
		$searchParam = array();
		//overall search
		if (isset($_GET['query'])){
			$searchParam['query'] = $_GET['query'];
		} else if (isset($_POST['query'])){
			$searchParam['query'] = $_POST['query'];
		}
		//search only in title
		if (isset($_GET['title'])){
			$searchParam['title'] = $_GET['title'];
		} else if (isset($_POST['title'])){
			$searchParam['title'] = $_POST['title'];
		}
		if (isset($_GET['w_ing'])){
			$searchParam['w_ing'] = $_GET['w_ing'];
		} else if (isset($_POST['w_ing'])){
			$searchParam['w_ing'] = $_POST['w_ing'];
		}
		if (isset($searchParam['w_ing']) && is_string($searchParam['w_ing'])){
			$searchParam['w_ing'] = split(',', $searchParam['w_ing']);
		}
		if (isset($_GET['wo_ing'])){
			$searchParam['wo_ing'] = $_GET['wo_ing'];
		} else if (isset($_POST['wo_ing'])){
			$searchParam['wo_ing'] = $_POST['wo_ing'];
		}
		if (isset($searchParam['wo_ing']) && is_string($searchParam['wo_ing'])){
			$searchParam['wo_ing'] = split(',', $searchParam['wo_ing']);
		}
		
		if (isset($_GET['rec_type'])){
			$searchParam['rec_type'] = $_GET['rec_type'];
		} else if (isset($_POST['rec_type'])){
			$searchParam['rec_type'] = $_POST['rec_type'];
		}
		
		if (isset($_GET['rec_cuisine'])){
			$searchParam['rec_cuisine'] = $_GET['rec_cuisine'];
		} else if (isset($_POST['wo_ing'])){
			$searchParam['rec_cuisine'] = $_POST['rec_cuisine'];
		}
		
		$resultField = array('lang','title','img_url','rec_id');
		$this->performSearch('recipes', $searchParam, $resultField);
	}
	
	private function performSearch($type, $searchParam, $resultField){
		$searchEngine = 'yii'; //'yii','solr'
		if (isset($_GET['searchEngine'])){
			$searchEngine = $_GET['searchEngine'];
		} else if (isset($_POST['searchEngine'])){
			$searchEngine = $_POST['searchEngine'];
		}
		if ($searchEngine == 'solr'){
			try {
				$this->performSolrSearch($type, $searchParam, $resultField);
			} catch (Exception $e){
				$this->error($this->lang, 'API_SEARCH_ERROR_PERFORMING_SEARCH');
			}
		} else if ($searchEngine == 'yii'){
			try {
				$this->performYiiSearch($type, $searchParam, $resultField);
			} catch (Exception $e){
				$this->error($this->lang, 'API_SEARCH_ERROR_PERFORMING_SEARCH');
			}
		} else {
			$this->error($this->lang, 'API_SEARCH_INVALID_SEARCH_ENGINE');
		}
	}
	
	/* ################################################# SOLR ################################################# */
	private function performSolrSearch($type, $searchParam, $resultField){
		$this->error($this->lang, 'API_SEARCH_SOLR_NOT_IMPLEMENTED');
		return;
		
		if ($type == 'recipes'){
			
		} else if ($type == 'ingredients'){
		} else {
			$this->error($this->lang, 'API_SEARCH_INVALID_TYPE');
		}
	}
	
	
	/* ################################################# Yii ################################################# */
	private function performYiiSearch($type, $searchParam, $resultField){
		$criteria=new CDbCriteria;
		$querys = array();
		if(isset($searchParam['query'])){
			$querys = array_merge($querys,explode(',', $searchParam['query']));
		}
		if(isset($searchParam['title'])){
			$querys = array_merge($querys,explode(',', $searchParam['title']));
		}
		if ($type == 'recipes'){
			$model = Recipes::model();
			$titleFields = array('REC_NAME_' . $this->lang,'REC_SYNONYM_' . $this->lang); //$model->getSearchFields()
			$criteriaMapping = array(
					'rec_type'=>'recipes.RET_ID',
					'rec_cuisine'=>array('recipes.CUT_ID','recipes.CST_ID','recipes.CSS_ID')
			);
			$url = Yii::app()->createAbsoluteUrl("savedImage/recipes");
			$fieldMapping = array(
					'title' => 'REC_NAME_' . $this->lang,
					'img_url' => 'CASE WHEN REC_IMG_ETAG IS NOT NULL AND REC_IMG_ETAG <> \'\' THEN concat("' . $url . '/", REC_ID ,".png") ELSE "" END',
					'rec_id' => 'REC_ID',
			);
		} else if ($type == 'ingredients'){
			$model = Ingredients::model();
			$titleFields = array('ING_NAME_' . $this->lang,'ING_SYNONYM_' . $this->lang); //$model->getSearchFields()
			$criteriaMapping = array();
			$url = Yii::app()->createAbsoluteUrl("savedImage/ingredients");
			$fieldMapping = array(
					'title' => 'ING_NAME_' . $this->lang,
					'img_url' => 'CASE WHEN ING_IMG_ETAG IS NOT NULL AND ING_IMG_ETAG <> \'\' THEN concat("' . $url . '/", ING_ID ,".png") ELSE "" END',
					'ing_id' => 'ING_ID',
			);
		} else {
			$this->error($this->lang, 'API_SEARCH_INVALID_TYPE');
			return;
		}
		$commandBuilder = $criteriaString = $model->commandBuilder;
		
		//set selected fields
		$selectStatement = '';
		foreach($resultField as $field){
			if(isset($fieldMapping[$field])){
				$selectStatement .= ', ' . $fieldMapping[$field] . ' as ' . $field;
			}
		}
		if ($selectStatement == ''){
			$this->error($this->lang, 'API_SEARCH_NO_FIELD_TO_RETURN');
			return;
		}
		if ($type == 'ingredients'){
			$criteria->select = substr($selectStatement, 2);
		} else if ($type == 'recipes'){
			//$criteria->select = '(CASE WHEN recipes.PRF_UID IS NOT NULL THEN 1 ELSE 0 END) AS pro' . $selectStatement;
			$criteria->select = substr($selectStatement, 2);
		}
		
		//add query/title criterias
		if (count($querys)>0){
			$searches = array();
			foreach($querys as $queryText){
				if (strlen(trim($queryText))>0) {
					$criteriaString = $commandBuilder->createSearchCondition($model->tableName(),$titleFields,$queryText, $type . '.');
					$searches[] = $criteriaString;
				}
			}
			if (count($searches)>0){
				$criteria->condition = $this->mergeConditionList($searches, 'AND');
			}
		}
		
		//check containing ingredient conditions 
		if ($type == 'recipes'){
			if(isset($searchParam['w_ing']) && count($searchParam['w_ing'])>0){
				$ids = $searchParam['w_ing'];
				//$criteria->addInCondition(Steps::model()->tableName().'.ING_ID',$ids);
				$idsCount = count($ids);
				$this->addIngredientIdCondition($criteria, $ids, 'ingCount', $ingCount);
			}
			if(isset($searchParam['wo_ing']) && count($searchParam['wo_ing'])>0){
				$ids = $searchParam['wo_ing'];
				$this->addIngredientIdCondition($criteria, $ids, 'ingNotCount', 0);
			}
		}
		
		//check all other conditions
		foreach($criteriaMapping as $param=>$queryField){
			if (isset($searchParam[$param])){
				$value = $searchParam[$param];
				if(is_array($queryField)){
					foreach ($queryField as $field){
						$criteria->addInCondition($field, $value);
					}
				} else {
					$criteria->addInCondition($queryField, $value);
				}
			}
		}
		
		//add sort/order
		if ($type == 'recipes'){
			$orderByKeyToField = array(
					'score'=>'REC_NAME_' . $this->lang,
					'az'=>'REC_NAME_' . $this->lang,
					'za'=>'REC_NAME_' . $this->lang . ' DESC',
					'new'=>'CANGED_ON DESC', 
					'old'=>'CANGED_ON', 
					'K'=>'REC_KCAL',
					'k'=>'REC_KCAL DESC',
					'C'=>'REC_COMPLEXITY',
					'c'=>'REC_COMPLEXITY DESC',
					/*
					'P'=>'PreparationTime',
					'R'=>'Rating',''=>'',
					*/
			);
			if (isset($orderByKeyToField[$this->searchSort])){
				$criteria->order = $orderByKeyToField[$this->searchSort];
				/*
				$criteria->order = 'pro DESC, ' . $orderByKeyToField[$this->searchSort];
			} else {
				$criteria->order = 'pro DESC';
				*/
			}
		} else if ($type == 'ingredients'){
			$orderByKeyToField = array(
					'score'=>'ING_NAME_' . $this->lang,
					'az'=>'ING_NAME_' . $this->lang,
					'za'=>'ING_NAME_' . $this->lang . ' DESC',
					'new'=>'CANGED_ON DESC', 
					'old'=>'CANGED_ON', 
			);
			if (isset($orderByKeyToField[$this->searchSort])){
				$criteria->order = $orderByKeyToField[$this->searchSort];
			}
		}
		
		
		//get total count
		$command = $this->criteriaToCommand($criteria);
		$searchSql = $command->getText();
		$totalCommand = Yii::app()->db->createCommand('SELECT count(*) FROM (' . $searchSql . ') AS innerQuery');
		$totalCount = $totalCommand->queryScalar($command->params);
		
		//prepare main result
		if ($totalCount == 0){
			//TODO: return no results
			$rows = array();
		} else if ($totalCount <= $this->pageStart){
			//TODO: return no more results
			$rows = array(); 
		} else {
			//TODO optimice / speedup
			$command = $this->criteriaToCommand($criteria); //command must recrate, because getText for totalCommand, make limit(next line) has no effect...
			$command->limit($this->pageLength, $this->pageStart);
			$rows = $command->queryAll();
		}
		
		//prepare result object
		$result = array(
				'success' => true,
				'start' => $this->pageStart,
				'limit' => $this->pageLength,
				'length' => count($rows),
				'total' => $totalCount,
				'data' => $rows,
		);
		
		//output result
		$this->sendResponse($result);
	}
	
	
	
	
	private function addIngredientIdCondition($criteria, $ids, $alias, $amount){
		$stepModel = new Steps;
		$ingCriteria=new CDbCriteria;
		$ingCriteria->select = 'count(DISTINCT i.ING_ID) as amount'; // $ingModel->getTableSchema()->primaryKey;
		$ingCriteria->join = 'LEFT JOIN ingredients i ON s.ING_ID=i.ING_ID';
		$ingCriteria->condition = 's.REC_ID = recipes.REC_ID';
		$ingCriteria->addInCondition('i.ING_ID',$ids);
		$subQuery=$stepModel->getCommandBuilder()->createFindCommand($stepModel->getTableSchema(),$ingCriteria, 's')->getText();
		
		//$criteria->addCondition('(' . $subQuery . ') = ' . $idsCount);
		//$additionalSelect = $additionalSelect . ',(' . $subQuery . ') as ' . $alias;
		$criteria->select = $criteria->select . ',(' . $subQuery . ') as ' . $alias;
		$criteria->having=$this->mergeConditions($criteria->having, $alias . ' = ' . $amount);
		$criteria->params = array_merge($criteria->params, $ingCriteria->params);
		//$additionalSelectParams = array_merge($additionalSelectParams, $ingCriteria->params);
	}

	private function mergeConditions($cond1, $cond2){
		if (!isset($cond1) || $cond1 == ''){
			return $cond2;
		}
		if (!isset($cond2) || $cond2 == ''){
			return $cond1;
		}
		return '(' . $cond1 . ') AND (' . $cond2 . ')';
	}
	
	private function mergeConditionList($conditions, $op = "OR"){
		$result = '';
		foreach($conditions as $condition){
			if ($result != ''){
				$result.=' ' . $op . ' ' . $condition;
			} else {
				$result.=$condition;
			}
		}
		return '(' . $result. ')';
	}

	private function criteriaToCommand($criteria){
		$command = Yii::app()->db->createCommand();
		$command->distinct = $criteria->distinct;
		if (isset($criteria->select)){
			//$command->select('recipes.*, recipe_types.*' . $criteria->select);
			$command->select($criteria->select);
		}
		$command->from('recipes');
		if (isset($criteria->join)){
			$command->join = $criteria->join;
		}
		if (strpos($criteria->condition, 'ingredients.') !== false){
			$command->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID');
			$command->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID');
			if (strpos($criteria->condition, 'step_types.') !== false){
				$command->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID');
			}
		} else if (strpos($criteria->condition, 'step_types.') !== false){
			$command->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID');
			$command->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID');
		} else if (strpos($criteria->condition, 'steps.') !== false){
			$command->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID');
		}
	
		if (isset($criteria->condition)){
			$command->where($criteria->condition);
		}
		if (isset($criteria->group)){
			$command->group($criteria->group);
		}
		if (isset($criteria->having)){
			$command->having($criteria->having);
		}
		if (isset($criteria->order)){
			$command->order($criteria->order);
		}
		//$command->bindValues($criteria->params);
		$command->params = $criteria->params;
		return $command;
	}
}
