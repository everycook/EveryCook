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
				'actions'=>array('searchRecipes','searchIngredients','recipeDetail','ingredientDetail','searchCategories'),
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
	public $version = 1;
	protected static $trans=null;
	public function getTrans()
	{
		return self::$trans;
	}

	protected function setLangIfValid($lang){
		foreach (Controller::$allLanguages as $key=>$desc){
			if (strcasecmp($lang, $key) == 0) {
				$this->lang = $key;
				//Yii::app()->session['lang'] = $key; 
				return;
			}
		}
	}
	
	protected $searchCategories = array(); 
	
	protected $searchCriteriasRecipes = array();
	protected $searchCriteriasIngredients = array();
	
	protected $criteriaMappingRecipes = array();
	protected $fieldMappingRecipes = array();
	protected $resultFieldsRecipes = array();
	
	protected $fieldMappingRecipeDetail = array();
	protected $fieldMappingRecipeDetailIngredients = array();
	protected $fieldMappingRecipeDetailSteps = array();
	
	protected $criteriaMappingIngredients = array();
	protected $fieldMappingIngredients = array();
	protected $resultFieldsIngredients = array();
	
	protected function prepareMappings($lang){
		$cusineImgUrl = Yii::app()->createAbsoluteUrl("savedImage/cusineTypes");
		$cusineSubImgUrl = Yii::app()->createAbsoluteUrl("savedImage/cusineSubTypes");
		$cusineSubSubImgUrl = Yii::app()->createAbsoluteUrl("savedImage/cusineSubSubTypes");
		
		$this->searchCategories = array(
			'rec_type'=>array(
					'table'=>'recipe_types', 
					'id'=>'RET_ID', 
					'desc'=>'RET_DESC_' .$lang, 
					'otherFields'=>null,
					'subTypes'=>null,
					'parent'=>null,
			),
			'rec_cuisine'=>array(
					'table'=>'cusine_types', 
					'id'=>'CUT_ID', 
					'desc'=>'CUT_DESC_' .$lang, 
					'otherFields'=>array(
						'img_url' => 'CASE WHEN CUT_IMG_ETAG IS NOT NULL AND CUT_IMG_ETAG <> "" THEN concat("' . $cusineImgUrl . '/", CUT_ID ,".png") ELSE null END',
					), 
					'subTypes'=>array('rec_sub_cuisine'), 
					'parent'=>null,
					'conditions'=>null,
			),
			'rec_sub_cuisine'=>array(
					'table'=>'cusine_sub_types', 
					'id'=>'CST_ID', 
					'desc'=>'CST_DESC_' .$lang, 
					'otherFields'=>array(
						'img_url' => 'CASE WHEN CST_IMG_ETAG IS NOT NULL AND CST_IMG_ETAG <> "" THEN concat("' . $cusineSubImgUrl . '/", CST_ID ,".png") ELSE null END',
					), 
					'subTypes'=>array('rec_sub_sub_cuisine'), 
					'parent'=>'rec_cuisine',
					'conditions'=>null,
			),
			'rec_sub_sub_cuisine'=>array(
					'table'=>'cusine_sub_sub_types', 
					'id'=>'CSS_ID', 
					'desc'=>'CSS_DESC_' .$lang, 
					'otherFields'=>array(
						'img_url' => 'CASE WHEN CSS_IMG_ETAG IS NOT NULL AND CSS_IMG_ETAG <> "" THEN concat("' . $cusineSubSubImgUrl . '/", CSS_ID ,".png") ELSE null END',
					), 
					'subTypes'=>null, 
					'parent'=>'rec_sub_cuisine',
					'conditions'=>null,
			),
			'tag'=>array(
					'table'=>'tags', 
					'assignTable'=>'rec_to_tag',
					'id'=>'TAG_ID', 
					//'desc'=>'TAG_DESC_' .$lang, 
					'desc'=>($lang=='EN_GB')?'TAG_DESC_EN_GB':'CASE WHEN (TAG_DESC_' .$lang . ' IS NOT NULL) THEN TAG_DESC_' .$lang . ' ELSE TAG_DESC_EN_GB END',
					'otherFields'=>null,
					'subTypes'=>null,
					'parent'=>null,
					'conditions'=>array('where'=>'TAG_IGNORE <> :val','params'=>array(':val'=>'Y')),
			),
			'difficulty'=>array(
					'table'=>'difficulty', 
					'id'=>'DIF_ID', 
					'desc'=>'DIF_DESC_' .$lang, 
					'otherFields'=>null,
					'subTypes'=>null,
					'parent'=>null,
			),
		);
		
		//merge on array type has currently no effect, different logic tru fixed logic for ing fields, all others are or 
		$this->searchCriteriasRecipes = array(
				'query' => array('type'=>'single', 'addMapping'=>false),
				'title' => array('type'=>'single', 'addMapping'=>false),
				'w_ing' => array('type'=>'array', 'merge'=>'and', 'addMapping'=>false),
				'wo_ing' => array('type'=>'array', 'merge'=>'and', 'addMapping'=>false),
				'rating' => array('type'=>'single', 'select'=>'recipes.REC_RATING', 'op'=>'='),
				'rating_above' => array('type'=>'single', 'select'=>'recipes.REC_RATING', 'op'=>'>='),
				'rating_below' => array('type'=>'single', 'select'=>'recipes.REC_RATING', 'op'=>'<'),
				'rec_id' => array('type'=>'array', 'merge'=>'or', 'select'=>'recipes.REC_ID'),
		);
		$this->criteriaMappingRecipes = array();
		foreach ($this->searchCriteriasRecipes as $name=>$options){
			if(!isset($options['addMapping']) || $options['addMapping']){
				if(isset($options['select'])){
					$this->criteriaMappingRecipes[$name] = $options['select'];
				} else {
					$this->criteriaMappingRecipes[$name] = $name;
				}
			}
		}
		foreach ($this->searchCategories as $name=>$options){
			if (isset($options['assignTable'])){
				$select = $options['assignTable'] . '.' . $options['id'];
			} else {
				$select = 'recipes.' . $options['id'];
			}
			$this->searchCriteriasRecipes[$name] = array('type'=>'array', 'merge'=>'or', 'select' => $select);
			$this->criteriaMappingRecipes[$name] = $select;
		}
// 		$this->criteriaMappingRecipes = array(
// 				'rec_type'=>'recipes.RET_ID',
// 				'rec_cuisine'=>'recipes.CUT_ID',
// 				'rec_sub_cuisine'=>'recipes.CST_ID',
// 				'rec_sub_sub_cuisine'=>'recipes.CSS_ID',
// 		);
		
		$recipesImgUrl = Yii::app()->createAbsoluteUrl("savedImage/recipes");
		$ingredientsImgUrl = Yii::app()->createAbsoluteUrl("savedImage/ingredients");
		
		$this->fieldMappingRecipes = array(
				'title' => 'REC_NAME_' . $lang,
				'img_url' => 'CASE WHEN REC_IMG_ETAG IS NOT NULL AND REC_IMG_ETAG <> "" THEN concat("' . $recipesImgUrl . '/", recipes.REC_ID ,".png") ELSE null END',
				'rec_id' => array('select'=>'recipes.REC_ID', 'type'=>'int'),
				'rec_type' => array('select'=>'recipes.RET_ID', 'type'=>'int'),
				'rec_cuisine' => array('select'=>'recipes.CUT_ID', 'type'=>'int'),
				'rec_sub_cuisine' => array('select'=>'recipes.CST_ID', 'type'=>'int'),
				'rec_sub_sub_cuisine' => array('select'=>'recipes.CSS_ID', 'type'=>'int'),
				'prep_time' => array('select'=>'recipes.REC_TIME_PREP', 'type'=>'int'),
				'cook_time' => array('select'=>'recipes.REC_TIME_COOK', 'type'=>'int'),
				'total_time' => array('select'=>'recipes.REC_TIME_TOTAL', 'type'=>'int'),
				'difficulty' => 'difficulty.DIF_DESC_' .$lang,
				'rating' => array('select'=>'recipes.REC_RATING', 'type'=>'int'),
 				'autor' =>  'CASE WHEN recipes.PRF_UID IS NOT NULL THEN concat(professional_profiles.PRF_FIRSTNAME, " " ,professional_profiles.PRF_LASTNAME) ELSE NULL END',
				'tags' => array('select'=>'tags.tags', 'type'=>'commalist', 'listType'=>'int'),
				'lastchange' => array('select'=>'recipes.CHANGED_ON', 'type'=>'int'),
		);
//		$this->resultFieldsRecipes =  array('title','img_url','rec_id');
		$this->resultFieldsRecipes = array_keys($this->fieldMappingRecipes);
		
		$this->fieldMappingRecipeDetail = array(
				'title' => 'REC_NAME_' . $lang,
				'img_url' => 'CASE WHEN REC_IMG_ETAG IS NOT NULL AND REC_IMG_ETAG <> "" THEN concat("' . $recipesImgUrl . '/", recipes.REC_ID ,".png") ELSE null END',
				'rec_id' => array('select'=>'recipes.REC_ID', 'type'=>'int'),'rec_type' => array('select'=>'recipes.RET_ID', 'type'=>'int'),
				'rec_type' => array('select'=>'recipes.RET_ID', 'type'=>'int'),
				'rec_cuisine' => array('select'=>'recipes.CUT_ID', 'type'=>'int'),
				'rec_sub_cuisine' => array('select'=>'recipes.CST_ID', 'type'=>'int'),
				'rec_sub_sub_cuisine' => array('select'=>'recipes.CSS_ID', 'type'=>'int'),
				'prep_time' => array('select'=>'recipes.REC_TIME_PREP', 'type'=>'int'),
				'cook_time' => array('select'=>'recipes.REC_TIME_COOK', 'type'=>'int'),
				'total_time' => array('select'=>'recipes.REC_TIME_TOTAL', 'type'=>'int'),
				'difficulty' => 'difficulty.DIF_DESC_' .$lang,
				'rating' => array('select'=>'recipes.REC_RATING', 'type'=>'int'),
				'tags' => array('select'=>'tags.tags', 'type'=>'commalist', 'listType'=>'int'),
				'lastchange' => array('select'=>'recipes.CHANGED_ON', 'type'=>'int'),
		);
		$this->fieldMappingRecipeDetailIngredients = array(
				'ing_name' => 'ING_NAME_' . $lang,
				'img_url' => 'CASE WHEN ING_IMG_ETAG IS NOT NULL AND ING_IMG_ETAG <> "" THEN concat("' . $ingredientsImgUrl . '/", ingredients.ING_ID ,".png") ELSE null END',
				'ing_id' => array('select'=>'ingredients.ING_ID', 'type'=>'int'),
				'ing_qty' => array('select'=>'sum(steps.STE_GRAMS)', 'type'=>'int'),
				'qty_unit' => '"g"',
		);
		$this->fieldMappingRecipeDetailSteps = array(
				'isPrepare' => array('select'=>'CASE WHEN (steps.STE_PREP = "Y" OR actions_in.AIN_PREP = "Y") THEN 1 ELSE 0 END', 'type'=>'bool'),
				'step_no' => array('select'=>'STE_STEP_NO', 'type'=>'int'),
				'ing_id' => array('select'=>'ING_ID', 'type'=>'int'),
				'action' => 'AIN_DESC_' . $lang,
// 				'action' => 'AOU_DESC_' . $lang,
		);
		

		$this->searchCriteriasIngredients = array(
				'query' => array('type'=>'single', 'addMapping'=>false),
				'title' => array('type'=>'single', 'addMapping'=>false),
				'start_with' => array('type'=>'single', 'addMapping'=>false),
				'ing_id' => array('type'=>'array', 'merge'=>'or', 'select'=>'ingredients.ING_ID'),
		);
		$this->criteriaMappingIngredients = array();
		foreach ($this->searchCriteriasIngredients as $name=>$options){
			if(!isset($options['addMapping']) || $options['addMapping']){
				if(isset($options['select'])){
					$this->criteriaMappingIngredients[$name] = $options['select'];
				} else {
					$this->criteriaMappingIngredients[$name] = $name;
				}
			}
		}
		$this->fieldMappingIngredients = array(
				'title' => 'ING_NAME_' . $lang,
				'img_url' => 'CASE WHEN ING_IMG_ETAG IS NOT NULL AND ING_IMG_ETAG <> "" THEN concat("' . $ingredientsImgUrl . '/", ING_ID ,".png") ELSE null END',
				'ing_id' => array('select'=>'ING_ID', 'type'=>'int'),
		);

		$this->resultFieldsIngredients = array_keys($this->fieldMappingIngredients);
	}
		
	
	protected function beforeAction($action)
	{
		$this->setLangIfValid($this->getParam('lang'));
		$token = $this->getParam('token');
		if(!isset($token)){
			//no token found, error
			$this->error($this->lang, 'API_GENERAL_NO_TOKEN');
			return false;
		}
		$this->version = floatval($this->getParam('ver', $this->version));
		if ($this->version != 1){
			$this->error($this->lang, 'API_GENERAL_INVALID_VERSION');
			return false;
		}
		//TODO: do check of API token here!
		if ($token != 'everycook'){
			$this->error($this->lang, 'API_GENERAL_TOKEN_INVALID');
			return false;
		}
		$this->prepareMappings($this->lang);
		return true;
	}

	protected function afterAction($action){
		Yii::app()->end();
	}
	
	private function error($lang, $errorKey, $additionalValues = null){
		self::$trans = Controller::loadTranslations($lang);
		self::$trans->showKeyIfAbsent = true; //TODO remove
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
		$dataArray = array_merge(array('success'=>true,'ver'=>$this->version, 'lang'=>$this->lang), $dataArray);
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
	
	private function getParam($param, $default = null){
		if (isset($_GET[$param])){
			return $_GET[$param];
		} else if (isset($_POST[$param])){
			return $_POST[$param];
		} else {
			return $default;
		}
	}
	
	private function loadTreeData($paramOptions, $parentIdField){
		if (strpos($paramOptions['desc'],' ') !== false){
			$selectFields = array($paramOptions['id'] . ' as id', $paramOptions['desc'] . ' as `desc`');
		} else {
			$selectFields = array($paramOptions['id'] . ' as id', $paramOptions['desc'] . ' as desc');
		}
		if (isset($parentIdField)){
			$selectFields[] = $parentIdField . ' as parentId';
		}
		if (isset($paramOptions['otherFields'])){
			foreach ($paramOptions['otherFields'] as $alias=>$field){
				$selectFields[] = $field . ' as ' . $alias;
			}
		}
		$command = Yii::app()->db->createCommand()->select($selectFields)
			->from($paramOptions['table'])
			->order($paramOptions['desc']);
		if (isset($paramOptions['conditions'])){
			$command->where($paramOptions['conditions']['where'],$paramOptions['conditions']['params']);
		}
		
		$values = $command->queryAll();
		
		$valuesPrepared = array();
		foreach($values as $value){
			$valuePrepared = $value;
			$valuePrepared['id'] = intval($valuePrepared['id']);
			if(isset($valuePrepared['parentId'])){
				$parentId = $valuePrepared['parentId'];
				unset($valuePrepared['parentId']);
				if (!isset($valuesPrepared[$parentId])){
					$valuesPrepared[$parentId] = array();
				}
				$valuesPrepared[$parentId][] = $valuePrepared;
			} else {
				$valuesPrepared[] = $valuePrepared;
			}
		}
		return $valuesPrepared;
	}
	
	private function loadTreeSubData(&$treeValues, $typeName, $parentIdField){
		if (!array_key_exists($typeName, $treeValues)){
			if (array_key_exists($typeName, $this->searchCategories)){
				$typeOptions = $this->searchCategories[$typeName];
				$treeValues[$typeName] = $this->loadTreeData($typeOptions, $parentIdField);
				
				if (isset($typeOptions['subTypes'])){
					$subTypes = $typeOptions['subTypes'];
					foreach ($subTypes as $subType){
						$this->loadTreeSubData($treeValues, $subType, $typeOptions['id']);
					}
				}
			}
		}
	}
	
	private function prepareTreeData($treeValues, $typeOptions, $values){
		if (isset($typeOptions['subTypes'])){
			$subTypes = $typeOptions['subTypes'];
			$subTypesOptions = array();
			foreach ($subTypes as $subType){
				if (array_key_exists($subType, $this->searchCategories)){
					$subTypesOptions[$subType] = $this->searchCategories[$subType];
				}
			}
			if (count($subTypesOptions) == 0){
				return $values;
			}
			$valuesPrepared = array();
			foreach ($values as $value){
				$valuePrepared = $value;
				$subTypeValues = array();
				foreach ($subTypesOptions as $subType=>$subTypeOptions){
					$subValues = $treeValues[$subType];
					$parentId = $value['id'];
					if(isset($subValues[$parentId])){
						$subTypeValues[$subType] = $this->prepareTreeData($treeValues, $subTypeOptions, $subValues[$parentId]);
					}
				}
				$valuePrepared['subTypes'] = $subTypeValues;
				$valuesPrepared[] = $valuePrepared;
			}
			return $valuesPrepared;
		} else {
			return $values;
		}
		
	}
	
	/* ################# searchCategories ################# */
	// http://localhost/EveryCook/api/recipeDetail?token=everycook&rec_id=1&servings=1
	public function actionSearchCategories(){
		$params = array();
		foreach ($this->searchCategories as $name=>$options){
			$paramValue = $this->getParam($name);
			if (isset($paramValue)){
				$params[$name] = $paramValue;
			} 
		}
		$data = array();
		$paramType = $this->getParam('type');
		$fullTree = $this->getParam('tree');
		if(isset($paramType)){
			if (!array_key_exists($paramType, $this->searchCategories)){
				$this->error($this->lang, 'API_SEARCH_CATEGORIES_INVALID_TYPE');
				return;
			}
			$paramOptions = $this->searchCategories[$paramType];
			if ($fullTree){
				$treeValues = array();
				$this->loadTreeSubData($treeValues, $paramType, null);
				$parent = $treeValues[$paramType];
				$valuesPrepared = $this->prepareTreeData($treeValues, $paramOptions, $parent);
			} else {
				if (strpos($paramOptions['desc'],' ') !== false){
					$selectFields = array($paramOptions['id'] . ' as id', $paramOptions['desc'] . ' as `desc`');
				} else {
					$selectFields = array($paramOptions['id'] . ' as id', $paramOptions['desc'] . ' as desc');
				}
				if (isset($paramOptions['otherFields'])){
					foreach ($paramOptions['otherFields'] as $alias=>$field){
						$selectFields[] = $field . ' as ' . $alias; 
					}
				}
				$command = Yii::app()->db->createCommand()->select($selectFields)
					->from($paramOptions['table'])
					//->order($paramOptions['desc']);
					->order('desc');
				if (isset($paramOptions['conditions'])){
					$command->where($paramOptions['conditions']['where'],$paramOptions['conditions']['params']);
				}
				if (count($params) > 0) {
					$parent = $paramOptions['parent'];
	// 				foreach($paramValues as $key => $value){ 
	// 					if($parent == $key){
					if (isset($params[$parent])){
						$parentParamOptions = $this->searchCategories[$parent];
						//$command->join($parentParamOptions['table'], $paramOptions['table'] . '.' . $parentParamOptions['id'] .'=' . $parentParamOptions['table'] . '.' . $parentParamOptions['id']);
						//$command->where($parentParamOptions['table'] . '.' . $parentParamOptions['id'] . ' = :parentId', array(':parentId' => $params[$parent]));
						if (isset($paramOptions['conditions'])){
							$command->where($paramOptions['conditions']['where'] . ' AND ' . $parentParamOptions['id'] . ' = :parentId',array_merge($paramOptions['conditions']['params'], array(':parentId' => $params[$parent])));
						} else {
							$command->where($parentParamOptions['id'] . ' = :parentId', array(':parentId' => $params[$parent]));
						}
					}
				}
				$values = $command->queryAll();
				
				$valuesPrepared = array();
				foreach($values as $value){
					$valuePrepared = $value;
					$valuePrepared['id'] = intval($valuePrepared['id']);
					$valuesPrepared[] = $valuePrepared;
				}
			}
			$data['values'] = $valuesPrepared;

			self::$trans = Controller::loadTranslations($this->lang);
			$data['desc'] = self::$trans->__get('FIELD_' . $paramOptions['id']);
			$data['subTypes'] = $paramOptions['subTypes'];
			$data['parent'] = $paramOptions['parent'];
			
			
			$params['type'] = $paramType;
		} else {
			//show possible types
			self::$trans = Controller::loadTranslations($this->lang);
			$typeValues = array();
			foreach ($this->searchCategories as $paramType=>$paramOptions){
				$otherFields = null;
				if(is_array($paramOptions['otherFields'])){
					$otherFields = array_keys($paramOptions['otherFields']);
				}
				$typeValues[] = array('type'=>$paramType, 'desc' => self::$trans->__get('FIELD_' . $paramOptions['id']), 'otherFields'=>$otherFields, 'subTypes' => $paramOptions['subTypes'], 'parent' => $paramOptions['parent']);
			}
			$data['types'] = $typeValues;
		}
		$result = array(
				'success' => true,
				'data' => $data,
				'params' => $params,
		);
		
		//output result
		$this->sendResponse($result);
	}

	/* ################# Recipe Detail ################# */
	// http://localhost/EveryCook/api/recipeDetail?token=everycook&rec_id=1&servings=1
	public function actionRecipeDetail(){
		$rec_id = $this->getParam('rec_id');
		if (!isset($rec_id)){
			$this->error($this->lang, 'API_DETAIL_NO_ID');
			return;
		}
		
		$prepareParam = array();
		$prepareParam['rec_id'] = $rec_id;
		$prepareParam['servings'] = $this->getParam('servings', null);
		$prepareParam['calories'] = $this->getParam('calories', null);
		$prepareParam['co_in'] = $this->getParam('co_in', null);
		
		if (isset($prepareParam['co_in'])){
			if (!is_numeric($prepareParam['co_in'])){
				if ($prepareParam['co_in'] == 'everycook' || $prepareParam['co_in'] == 'ec'){
					$prepareParam['co_in'] = 1; //COI_ID for everycook
				} else {
					//unset($prepareParam['co_in']);
// 					$prepareParam['co_in'] = 3; //COI_ID for Cooking pot
					$prepareParam['co_in'] = null;
				}
			}
		}
		
		//query Recipe informations
		$fieldMapping = $this->fieldMappingRecipeDetail;

		$fieldsToSelect = array_merge($fieldMapping, array(
				'REC_KCAL',
				'REC_SERVING_COUNT',
				
		));
		$command = Yii::app()->db->createCommand();
		//set selected fields
		$command->select = $this->fieldMappingToSelect($fieldsToSelect);
		$command->from = 'recipes';
		if (strpos($command->select, 'tags.tags') !== false){
			$command->leftJoin('(SELECT REC_ID, GROUP_CONCAT(`TAG_ID` SEPARATOR \',\') as tags FROM `rec_to_tag` GROUP BY REC_ID) tags', 'recipes.REC_ID = tags.REC_ID');
		}
		if (strpos($command->select, 'difficulty.') !== false){
			$command->leftJoin('difficulty', 'recipes.DIF_ID=difficulty.DIF_ID');
		}
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
		$fieldMappingIngredients = $this->fieldMappingRecipeDetailIngredients;
		$ingredientsCommand = Yii::app()->db->createCommand();
		//set selected fields
		$ingredientsCommand->select = $this->fieldMappingToSelect($fieldMappingIngredients);
		$ingredientsCommand->from = 'steps';
		$ingredientsCommand->join('ingredients', 'steps.ING_ID=ingredients.ING_ID'); //because of "join" (not "leftJoin") a condition "AND steps.ING_ID is NOT NULL" is not needed
		$ingredientsCommand->where('steps.REC_ID = :id', array(':id'=>$rec_id));
		$ingredientsCommand->order('steps.STE_STEP_NO');
		$ingredientsCommand->group('ingredients.ING_ID');
		$ingredients = $ingredientsCommand->queryAll();
	
		
		
		//query step Informations
		$fieldMappingSteps = $this->fieldMappingRecipeDetailSteps;
		$actionTextFields = array_flip(Steps::getFieldToCssClass());
		
		$command = Yii::app()->db->createCommand()
			->select('cook_in.COI_ID, cook_in.COI_DESC_' . $this->lang)
			->from('recipes')
			->leftJoin('rec_to_coi', 'recipes.REC_ID=rec_to_coi.REC_ID')
			->leftJoin('cook_in', 'rec_to_coi.COI_ID=cook_in.COI_ID')
			->where('recipes.REC_ID = :id', array(':id'=>$rec_id));
		$cookIns = $command->queryAll();
		$cookIn = $cookIns[0]['COI_DESC_' . $this->lang];
		foreach($cookIns as $line){
			if ($line['COI_ID'] != 1){
				$cookIn = $line['COI_DESC_' . $this->lang];
				break;
			}
		}
		
		$actionTextFields['cookin'] = '("'.$cookIn.'")';
		//$actionTextFields['tools'] = 'action_out.TOO_ID';
		$fieldMappingStepsSelect = array_merge($fieldMappingSteps, $actionTextFields);
		
		$stepsCommand = Yii::app()->db->createCommand();
		//set selected fields
		$stepsCommand->select = $this->fieldMappingToSelect($fieldMappingStepsSelect);
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
		$recipeResult = $this->copyMappedfields($fieldMapping, $recipe);
		
		//format ingredients
		$ingredientsResult = array();
		foreach($ingredients as $ingredient){
			$ingredientResult = $this->copyMappedfields($fieldMappingIngredients, $ingredient);
			//calculate changed amount of ingredient
			if(isset($ingredientResult['ing_qty'])){
				$ingredientResult['ing_qty'] = round($ingredientResult['ing_qty'] * $rec_proz);
			}
			$ingredientsResult[$ingredientResult['ing_id']] = $ingredientResult;
		}
		$recipeResult['ingredients'] = $ingredientsResult;
		
		//format steps
		$stepsResult = array();
		foreach ($steps as $key=>$step){
			$stepResult = $this->copyMappedfields($fieldMappingSteps, $step);
			
			$textParams = array();
			foreach($actionTextFields as $alias=>$field){
				$textParams[$alias] = $step[$alias];
			}
			if(isset($textParams['ingredient'])){
				$textParams['ingredient'] = intval($textParams['ingredient']);
			}
			
			//calculate changed amount of ingredient
			if(isset($textParams['weight']) && is_numeric($textParams['weight'])){
				$textParams['weight'] = round($textParams['weight'] * $rec_proz);
			}
			$textParams['time_sec'] = intval($textParams['time']);
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
		
		foreach ($prepareParam as $key=>$value){
			if ($value == null){
				unset($prepareParam[$key]);
			}
		}
		
		$result = array(
				'success' => true,
				'data' => $recipeResult,
				'params' => $prepareParam,
		);
		
		//output result
		$this->sendResponse($result);
	}

	/* ################# Recipe Search ################# */
	// http://localhost/EveryCook/api/searchrecipe?token=everycook&start=30&length=20
	// http://localhost/EveryCook/api/searchrecipe?token=everycook&query=risotto
	
	public $pageOffset = 0;
	public $pageLimit = 10;
	public $searchSort = 'score';
	protected function loadPaginationInformations(){
		$this->pageOffset = intval($this->getParam('offset', $this->pageOffset));
		$this->pageLimit = intval($this->getParam('limit', $this->pageLimit));
		$this->searchSort = $this->getParam('sort', $this->searchSort);
	}
	
	public function actionSearchRecipes(){
		$this->loadPaginationInformations();
		
		$searchParam = array();
		foreach($this->searchCriteriasRecipes as $param => $options){
			$value = $this->getParam($param);
			if(isset($value)){
				if($options['type'] == 'array'){
					if (!is_array($value)){
						$value = split(',', $value);
					}
				}
				$searchParam[$param] = $value;
			}
		}
		$resultField = $this->resultFieldsRecipes;
		$this->performSearch('recipes', $searchParam, $resultField);
	}
	
	public function actionSearchIngredients(){
		$this->loadPaginationInformations();
		
		$searchParam = array();
		foreach($this->searchCriteriasIngredients as $param => $options){
			$value = $this->getParam($param);
			if(isset($value)){
				if($options['type'] == 'array'){
					if (!is_array($value)){
						$value = split(',', $value);
					}
				}
				$searchParam[$param] = $value;
			}
		}
		$resultField = $this->resultFieldsIngredients;
		$this->performSearch('ingredients', $searchParam, $resultField);
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
				$this->error($this->lang, 'API_SEARCH_ERROR_PERFORMING_SEARCH', array('errorTrace'=>$e));
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
			$criteriaMapping = $this->criteriaMappingRecipes;
			$fieldMapping = $this->fieldMappingRecipes;
			$searchCriteria = $this->searchCriteriasRecipes;
		} else if ($type == 'ingredients'){
			$model = Ingredients::model();
			$titleFields = array('ING_NAME_' . $this->lang,'ING_SYNONYM_' . $this->lang); //$model->getSearchFields()
			$criteriaMapping = $this->criteriaMappingIngredients;
			$fieldMapping = $this->fieldMappingIngredients;
			$searchCriteria = $this->searchCriteriasIngredients;
		} else {
			$this->error($this->lang, 'API_SEARCH_INVALID_TYPE');
			return;
		}
		$commandBuilder = $criteriaString = $model->commandBuilder;
		
		//set selected fields
		$selectStatement = $this->fieldMappingToSelectFiltered($resultField, $fieldMapping);
		if ($selectStatement == ''){
			$this->error($this->lang, 'API_SEARCH_NO_FIELD_TO_RETURN');
			return;
		}
		if ($type == 'ingredients'){
			$criteria->select = $selectStatement;
		} else if ($type == 'recipes'){
			//$criteria->select = '(CASE WHEN recipes.PRF_UID IS NOT NULL THEN 1 ELSE 0 END) AS pro, ' . $selectStatement;
			$criteria->select = $selectStatement;
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
		if(isset($searchParam['start_with'])){
			$keyword = $searchParam['start_with'].'%';
			$searches = array();
			foreach($titleFields as $column){
				$searches[]='lower('.$column.') LIKE lower('.CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount.')';
				$criteria->params[CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount++]=$keyword;
			}
			if (count($searches)>0){
				$criteria->addCondition($this->mergeConditionList($searches, 'OR'));
			}
		}
		
		
		if ($type == 'recipes'){
			//check containing ingredient conditions 
			if(isset($searchParam['w_ing']) && count($searchParam['w_ing'])>0){
				$ids = $searchParam['w_ing'];
				//$criteria->addInCondition(Steps::model()->tableName().'.ING_ID',$ids);
				$idsCount = count($ids);
				$this->addIngredientIdCondition($criteria, $ids, 'ingCount', $idsCount);
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
					if (is_array($value)){
						//error not allowed combination, only use first value!
						$value = array_shift($value);
					}
					$columns=array();
					foreach ($queryField as $field){
						$columns[$field]=$value;
					}
					$criteria->addColumnCondition($columns,'OR');
					/*
					foreach ($queryField as $field){
						$criteria->addInCondition($field, $value);
					}
					*/
				} else {
					if(isset($searchCriterias[$param]['op'])){
						$value = $searchCriterias[$param]['op'] . $value;
					}
					$criteria->compare($queryField, $value);
				}
			}
		}
		
		//add sort/order
		if ($type == 'recipes'){
			$orderByKeyToField = array(
					'score'=>'REC_NAME_' . $this->lang,
					'az'=>'REC_NAME_' . $this->lang,
					'za'=>'REC_NAME_' . $this->lang . ' DESC',
					'new'=>'lastchange DESC', 
					'old'=>'lastchange', 
					'K'=>'REC_KCAL',
					'k'=>'REC_KCAL DESC',
					'D'=>'difficulty.DIF_ORDER',
					'd'=>'difficulty.DIF_ORDER DESC',
					'P'=>'REC_TIME_PREP',
					'p'=>'REC_TIME_PREP DESC',
					'C'=>'REC_TIME_COOK',
					'c'=>'REC_TIME_COOK DESC',
					'T'=>'REC_TIME_TOTAL',
					't'=>'REC_TIME_TOTAL DESC',
					'R'=>'REC_RATING',
					'r'=>'REC_RATING DESC',
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
					'new'=>'lastchange DESC', 
					'old'=>'lastchange', 
			);
			if (isset($orderByKeyToField[$this->searchSort])){
				$criteria->order = $orderByKeyToField[$this->searchSort];
			}
		}
		
		$criteria->distinct = true;
		//get total count
		$command = $this->criteriaToCommand($criteria, $type);
		$searchSql = $command->getText();
		$totalCommand = Yii::app()->db->createCommand('SELECT count(*) FROM (' . $searchSql . ') AS innerQuery');
		$totalCount = intval($totalCommand->queryScalar($command->params));
		
		//prepare main result
		if ($totalCount == 0){
			//TODO: return no results
			$rows = array();
		} else if ($totalCount <= $this->pageOffset){
			//TODO: return no more results
			$rows = array(); 
		} else {
			//TODO optimice / speedup
			$command = $this->criteriaToCommand($criteria, $type); //command must recrate, because getText for totalCommand, make limit(next line) has no effect...
			$command->limit($this->pageLimit, $this->pageOffset);
			$rows = $command->queryAll();
		}
		
		//optimize output
		$data = array();
		foreach($rows as $key=>$row){
			$entry = $this->copyMappedfields($fieldMapping, $row);
			$data[$key] = $entry;
		}
		
		//prepare result object
		$result = array(
				'success' => true,
				'offset' => $this->pageOffset,
				'limit' => $this->pageLimit,
				'length' => count($rows),
				'total' => $totalCount,
				'data' => $data,
				'params' => $searchParam,
		);
		
		//output result
		$this->sendResponse($result);
	}
	
	

	/* ################################################# helpers ################################################# */
	
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

	

	private function fieldMappingToSelectFiltered($requestedFields, $fieldMapping){
		$selectStatement = '';
		foreach($requestedFields as $alias){
			if(isset($fieldMapping[$alias])){
				$field = $fieldMapping[$alias];
				if(is_array($field)){
					$selectStatement .= ', ' . $field['select'] . ' as ' . $alias;
				} else {
					$selectStatement .= ', ' . $field . ' as ' . $alias;
				}
			}
		}
		if ($selectStatement == ''){
			return $selectStatement;
		} else {
			return substr($selectStatement, 2);
		}
	}
	
	private function fieldMappingToSelect($fieldMapping){
		$selectStatement = '';
		foreach($fieldMapping as $alias=>$field){
			if (is_numeric($alias)){
				$selectStatement .= ', ' . $field;
			} else {
				if(is_array($field)){
					$selectStatement .= ', ' . $field['select'] . ' as ' . $alias;
				} else {
					$selectStatement .= ', ' . $field . ' as ' . $alias;
				}
			}
		}
		return substr($selectStatement, 2);
	}
	
	private function copyMappedfields($fieldMapping, $data){
		$result = array();
	
		foreach($fieldMapping as $alias=>$field){
			$result[$alias] = $data[$alias];
			if(isset($result[$alias])){
				if (is_array($field)){
					if (isset($field['type'])){
						if ($field['type'] == 'int'){
							$result[$alias] = intval($result[$alias]);
						} else if ($field['type'] == 'bool'){
							if (is_numeric($result[$alias])){
								$result[$alias] = $result[$alias] == 1;
							} else if (is_string($result[$alias])){
								$result[$alias] = $result[$alias] == 'Y' || strtolower($result[$alias]) == 'true';
// 								} else {
// 									$result[$alias] = !!$result[$alias];
							}
						} else if ($field['type'] == 'commalist'){
							$result[$alias] = explode (',', $result[$alias]);
							if (isset($field['listType']) && $field['listType'] == 'int'){
								$listResult = $result[$alias];
								foreach($listResult as $index=>$value){
									$listResult[$index] = intval($value);
								}
								$result[$alias] = $listResult;
							}
						}
					}
				}
			}
		}
		return $result;
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

	private function criteriaToCommand($criteria,$from){
		$command = Yii::app()->db->createCommand();
		$command->distinct = $criteria->distinct;
		if (isset($criteria->select)){
			//$command->select('recipes.*, recipe_types.*' . $criteria->select);
			$command->select($criteria->select);
		}
		$command->from($from);
		if (isset($criteria->join)){
			$command->join = $criteria->join;
		}
		if ($from == 'recipes'){
			if (strpos($criteria->select, 'professional_profiles.') !== false){
				$command->leftJoin('professional_profiles', 'recipes.PRF_UID=professional_profiles.PRF_UID');
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
			if (strpos($criteria->condition, 'rec_to_tag.') !== false){
				$command->leftJoin('rec_to_tag', 'recipes.REC_ID=rec_to_tag.REC_ID');
			}
			if (strpos($criteria->select, 'tags.tags') !== false){
				$command->leftJoin('(SELECT REC_ID, GROUP_CONCAT(`TAG_ID` SEPARATOR \',\') as tags FROM `rec_to_tag` GROUP BY REC_ID) tags', 'recipes.REC_ID = tags.REC_ID');
			}
			//LEFT OUTER JOIN (SELECT REC_ID, GROUP_CONCAT(`TAG_ID`SEPARATOR ',') as tags FROM `rec_to_tag` GROUP BY REC_ID) tags ON recipes.REC_ID = tags.REC_ID
	
			if (strpos($criteria->select, 'difficulty.') !== false || strpos($criteria->order, 'difficulty.') !== false){
				$command->leftJoin('difficulty', 'recipes.DIF_ID=difficulty.DIF_ID');
			}
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
