<?php
class RecipesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
// 			array( //vor API 2.0 version see behaviors()
// 					'CHttpCacheFilter + actionSuggestion',
// 					'cacheControl' => "public, max-age=3600",
// // 					'etagSeedExpression' => function() {
// // 						return $this->getCategoryLastUpdate();
// // 					},
// // 					'lastModifiedExpression' => function() {
// // 						return $this->getCategoryLastUpdate();
// // 					}
// 			),
		);
	}
	
	protected $createBackup = 'Recipes_Backup';
	protected $searchBackup = 'Recipes';
	protected $searchBackupSolr = 'RecipesSolr';
	public $isTemplateChoose = false;
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','searchFridge','displaySavedImage','chooseRecipe','chooseTemplateRecipe','updateSessionValues','updateSessionValue','history','historyCompare', 'viewHistory', 'autocomplete','autocompleteId','actionSuggestion','getCusineSubTypes','getCusineSubSubTypes','cusinesAutocomplete','cusinesAutocompleteId','viewShoppingList', 'autocompleteSolr','tagAutocomplete','tagAutocompleteId'),
				//'advanceSearch','advanceChooseRecipe','advanceChooseTemplateRecipe',
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage','delicious','disgusting','hide','cancel','showLike', 'showNotLike', 'getRecipeInfos', 'setHistoryVersion','createShoppingList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/*
	//API 2.0
	public function behaviors() {
		// http://www.yiiframework.com/doc-2.0/guide-caching-http.html
		return [ 
			[ 
				//'class' => 'yii\filters\HttpCache',
				'class' => 'framework/web/filters/CHttpCacheFilter',
				'only' => ['actionSuggestion'],
// 				'lastModified' => function ($action, $params) {
// 					$q = new \yii\db\Query();
// 					return $q->from('post')->max('updated_at');
// 				},
// 				'etagSeed' => function ($action, $params) {
// 					$post = $this->findModel ( \Yii::$app->request->get ( 'id' ) );
// 					return serialize ( [ 
// 							$post->title,
// 							$post->content 
// 					] );
// 				},
				//'cacheControlHeader' => 'public, max-age=3600',
				'sessionCacheLimiter' => 'public',
			] 
		];
	}
	*/
	
	public function actionCancel(){
		$this->saveLastAction = false;
		$Session_Backup = Yii::app()->session[$this->createBackup];
		unset(Yii::app()->session[$this->createBackup.'_Time']);
		if (isset($Session_Backup) && isset($Session_Backup->REC_ID)){
			if (isset($id)){
				$REC_CHANGED_ON = $Session_Backup->CHANGED_ON;
				Yii::app()->db->createCommand()->delete(RecipeChanges::model()->tableName(), 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by', array(':id'=>$Session_Backup->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
				Yii::app()->db->createCommand()->delete(StepChanges::model()->tableName(), $updateFields, 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by', array(':id'=>$Session_Backup->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
				//TODO: remove all RecipeChanges & StepChanges without ID from this user
			}
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->REC_ID));
		} else {
			unset(Yii::app()->session[$this->createBackup]);
			$this->showLastNotCreateAction();
			//$this->forwardAfterSave(array('search'));
		}
	}
	
	public function calculateNutrientData($id){
		$command = Yii::app()->db->createCommand()
			->select('steps.STE_STEP_NO, steps.STE_GRAMS, ingredients.ING_NAME_'.Yii::app()->session['lang'] .', ingredients.ING_ID, nutrient_data.*')
			->from('steps')
			->leftJoin('ingredients', 'ingredients.ING_ID = steps.ING_ID')
			->leftJoin('nutrient_data', 'nutrient_data.NUT_ID = ingredients.NUT_ID')
			//->where('steps.REC_ID = :id AND nutrient_data.NUT_ID IS NOT NULL', array(':id'=>$id));
			->where('steps.REC_ID = :id AND ingredients.ING_ID IS NOT NULL', array(':id'=>$id));
			
		$rows = $command->queryAll();
		$modelNutrientData = new NutrientData();
		$fullWeight = 0;
		if ($this->debug) echo '<strong>NutrientData calculating:</strong><br>';
		foreach($rows as $row){
			$stepIngredientWeight = $row['STE_GRAMS'];
			if ($row['NUT_ID'] == null){
				if ($this->debug) echo 'No NutrientData,......... step: ' . $row['STE_STEP_NO'] . ': ' . $row['STE_GRAMS'] . 'g, ' . $row['ING_ID'] . ': ' . $row['ING_NAME_'.Yii::app()->session['lang']] . ' nutid:' . $row['NUT_ID'] . '<br>';
			} else if ($stepIngredientWeight <= 0){
				if ($this->debug) echo 'Weight is 0,......... step: ' . $row['STE_STEP_NO'] . ': ' . $row['STE_GRAMS'] . 'g, ' . $row['ING_ID'] . ': ' . $row['ING_NAME_'.Yii::app()->session['lang']] . ' nutid:' . $row['NUT_ID'] . '<br>';
			} else {
				$fullWeight += $stepIngredientWeight;
				
				if ($this->debug) echo 'OK use it,......... step: ' . $row['STE_STEP_NO'] . ': ' . $row['STE_GRAMS'] . 'g, ' . $row['ING_ID'] . ': ' . $row['ING_NAME_'.Yii::app()->session['lang']] . ' nutid:' . $row['NUT_ID'] . '<br>';
				foreach($modelNutrientData->attributeNames() as $field){
					$modelNutrientData->$field += ($row[$field] / 100) * $stepIngredientWeight;
				}
			}
		}
		if ($this->debug) echo 'total weight: ' . $fullWeight . '<br>';
		if ($fullWeight>0){
			/*
			//calc values for 100g
			foreach($modelNutrientData->attributeNames() as $field){
				$modelNutrientData->$field = round(($modelNutrientData->$field / $fullWeight) * 100, 2);
			}
			*/
			return $modelNutrientData;
		} else {
			return null;
		}
	}

	private function updateKCal($id, $servings, $old_kcal, $CHANGED_ON){
		$nutrientData = $this->calculateNutrientData($id);
		if ($nutrientData != null){
			$kcal = round($nutrientData->NUT_ENERG);
			if ($servings <= 0){
				$servings = 1;
			}
			if ($this->debug) {echo 'recipe kcal:' . $old_kcal . ', calculate kcal:' . $kcal . ' / ' . $servings . ' Servings = ' . ( $kcal / $servings). '<br>'."\n";}
			$kcal /= $servings;
		} else {
			$kcal = 0;
		}
		if ($old_kcal == null || $old_kcal != $kcal){
			if ($CHANGED_ON == null){
				Yii::app()->db->createCommand()->update(Recipes::model()->tableName(), array('REC_KCAL'=>$kcal), 'REC_ID = :id', array(':id'=>$id));
			} else {
				Yii::app()->db->createCommand()->update(Recipes::model()->tableName(), array('REC_KCAL'=>$kcal), 'REC_ID = :id AND CHANGED_ON = :change', array(':id'=>$id, ':change'=>$CHANGED_ON));
			}
		}
		return $nutrientData;
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
			unset(Yii::app()->session[$this->searchBackupSolr]);
		}
		$model = $this->loadModel($id);
		$cookin = "#cookin";
		if (isset($model->recToCois) && count($model->recToCois)>0){
			$coi_id = $model->recToCois[0]->COI_ID;
			$cookin = Yii::app()->db->createCommand()->select('COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->where('COI_ID = :id',array(':id'=>$coi_id))->queryScalar();
		}
		$nutrientData = $this->updateKCal($id, $model->REC_SERVING_COUNT, $model->REC_KCAL, null);
		$this->checkRenderAjax('view',array(
			'model'=>$model,
			'nutrientData'=>$nutrientData,
			'cookin'=>$cookin,
			'history'=>false
		));
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewHistory($id, $CHANGED_ON){
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
			unset(Yii::app()->session[$this->searchBackupSolr]);
		}
		$model=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$CHANGED_ON));
		$cookin = "#cookin";
		if (isset($model->recToCois) && count($model->recToCois)>0){
			$coi_id = $model->recToCois[0]->COI_ID;
			$cookin = Yii::app()->db->createCommand()->select('COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->where('COI_ID = :id',array(':id'=>$coi_id))->queryScalar();
		}
		
		$nutrientData = $this->updateKCal($id, $model->REC_SERVING_COUNT, $model->REC_KCAL, $CHANGED_ON);
		$this->checkRenderAjax('view',array(
			'model'=>$model,
			'nutrientData'=>$nutrientData,
			'cookin'=>$cookin,
			'history'=>true
		));
	}

	public function actionUploadImage(){
		$this->saveLastAction = false;
		if (isset($_GET['id'])){
			$id = $_GET['id'];
		}
		
		$Session_Recipes_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Recipes_Backup)){
			$oldmodel = $Session_Recipes_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->REC_ID != $id){
				$oldmodel = $this->loadModel($id);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
		} else {
			$model=new Recipes;
		}
		
		Functions::uploadImage('Recipes', $model, $this->createBackup, 'REC_IMG');
	}
	
	public function actionViewShoppingList($ids){
		$this->saveLastAction = false;
		$servings = -1;
		if (isset($_GET['servings'])){
			$servings = $_GET['servings'];
		}
		$old_id = null;
		if (isset($_GET['old_id'])){
			$old_id = $_GET['old_id'];
		}
		$shoppingList = $this->prepareShoppingList($ids, $servings, $old_id);
		$shoppingList->CHANGED_ON = time();
		Yii::app()->session['Shoppinglists_Backup'] = $shoppingList;
		Yii::app()->session['Shoppinglists_Backup'.'_Time'] = time();
		//$this->forwardTo(array('shoppinglists/view', 'id'=>'backup'));
		$this->forwardTo(array('shoppinglists/preview'));
	}
	
	public function actionCreateShoppingList($id){
		$this->saveLastAction = false;
		$servings = -1;
		if (isset($_GET['servings'])){
			$servings = $_GET['servings'];
		}
		
		$shoppingList = $this->prepareShoppingList($id, $servings);

		if(Yii::app()->user->demo){
			$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
		} else {
			if ($shoppingList->save()){
				Yii::app()->user->addShoppingListId($shoppingList->SHO_ID);
				
				$this->forwardAfterSave(array('shoppinglists/view', 'id'=>$shoppingList->SHO_ID));
			} else {
				echo '<pre>';
				print_r($shoppingList->getErrors());
				echo '</pre>';
			}
		}
	}
	
	public static function prepareShoppingList($id_string, $servings, $old_id){
		$ids = explode(';', $id_string);
		if (count($ids)==1){
			$parts = explode(':', $ids[0]);
			if (count($parts)==1){
				$id_portions = array($parts[0] => $servings);
			} else {
				$id_portions = array($parts[0] => $parts[1]);
			}
			$rec_ids = array($parts[0]);
		} else {
			$id_portions = array();
			foreach($ids as $id){
				$parts = explode(':', $id);
				if (count($parts)==1){
					$id_portions[$parts[0]] = $servings;
				} else {
					$id_portions[$parts[0]] = $parts[1];
				}
			}
			$rec_ids = array_keys($id_portions);
		}
		
		$criteria=new CDbCriteria;
		$criteria->addInCondition('recipes.REC_ID',$rec_ids)
			->addCondition('ingredients.ING_ID IS NOT NULL');
		
		$command = Yii::app()->db->createCommand()
			->select('recipes.*, steps.STE_STEP_NO, steps.STE_GRAMS, ingredients.ING_IMG_AUTH, ingredients.ING_ID, ING_NAME_'.Yii::app()->session['lang'])
			->from('recipes')
			->leftJoin('steps', 'steps.REC_ID=recipes.REC_ID')
			->leftJoin('ingredients', 'ingredients.ING_ID=steps.ING_ID')
			->where($criteria->condition, $criteria->params)
			->order('recipes.REC_ID, steps.STE_STEP_NO');
		$rows = $command->queryAll();
		
		$ing_to_weights = array();
	
		$rec_id = -1;
		for($i=0; $i<count($rows); ++$i){
			$row = $rows[$i];
			if ($rec_id != $row['REC_ID']){
// 				$meaToCou = $meaToCous[$row['COU_ID']];
// 				$meal_gda = $meaToCou['MTC_KCAL_DAY_TOTAL'] * $meaToCou['MEA_PERC_GDA'] / 100;
// 				$cou_gda = $meal_gda * $meaToCou['MTC_PERC_MEAL'] / 100;
// 				$rec_gda = $cou_gda * $row['CTR_REC_PROC'] / 100;
// 				$rec_kcal = $row['REC_KCAL'];
				$rec_servings = $row['REC_SERVING_COUNT'];
				$servings = $id_portions[$row['REC_ID']];
				if ($servings == -1){
					$rec_proz = 1;
					if ($rec_servings > 0){
						$servings = $rec_servings;
					} else {
						$servings = 1;
					}
				} else {
					if ($rec_servings > 0){
						$rec_proz = $servings / $rec_servings;
					} else {
						$rec_proz = $servings;
					}
				}
				$rec_id = $row['REC_ID'];
			}
			$ing_amount = $row['STE_GRAMS'] * $rec_proz;
				
			if(isset($ing_to_weights[$row['ING_ID']])){
				$ing_to_weights[$row['ING_ID']] += $ing_amount;
			} else {
				$ing_to_weights[$row['ING_ID']] = $ing_amount;
			}
		}
		
		if (isset($old_id)){
			$shoppingList = Shoppinglists::model()->findByPk($old_id);
		}
		if (!isset($shoppingList)){
			$shoppingList = new Shoppinglists;
			$shoppingList->SHO_DATE = time(); //TODO
		} else {
			$shoppingList->setIsNewRecord(false);
		}
		
// 		$ing_ids_old = explode(';',$shoppingList->SHO_INGREDIENTS);
// 		$ing_weights_old = explode(';',$shoppingList->SHO_WEIGHTS);
// 		$pro_ids_old = explode(';',$shoppingList->SHO_PRODUCTS);
// 		$amounts_old = explode(';',$shoppingList->SHO_QUANTITIES);
	
// 		$ingToIndex = array();
// 		//$ing_to_weights_old = array();
// 		for($i=0;$i<count($ing_ids_old);++$i){
// 			$ing_id = $ing_ids_old[$i];
// 			if ($ing_id != ''){
// 				$ingToIndex[$ing_id]=$i;
// 				//$ing_to_weights_old[$ing_id] = $ing_weights_old[$i];
// 				if (!isset($ing_to_weights[$ing_id])){
// 					$ing_to_weights[$ing_id] = $ing_weights_old[$i];
// 				}
// 			}
// 		}
	
		$ing_id_text = '';
		$ing_weight_text = '';
		$pro_id_text = '';
		$amount_text = '';
		$haveIt_text = '';
		foreach($ing_to_weights as $ing_id=>$ing_weight){
			if ($ing_id_text != ''){
				$ing_id_text .= ';';
				$ing_weight_text .= ';';
				$pro_id_text .= ';';
				$amount_text .= ';';
				$haveIt_text .= ';';
			}
			$ing_id_text .= $ing_id;
			$ing_weight_text .= round($ing_weight, 2);
// 			if(isset($ingToIndex[$ing_id])){
// 				$pro_id_text .= $pro_ids_old[$ingToIndex[$ing_id]];
// 				$amount_text .= $amounts_old[$ingToIndex[$ing_id]];
// 			}
		}
		if (count($ids)==1){
			$shoppingList->SHO_RECIPES = $rec_id .':' . $servings;
		} else  {
			$shoppingList->SHO_RECIPES = $id_string;
		}
		
		$shoppingList->SHO_INGREDIENTS = $ing_id_text;
		$shoppingList->SHO_WEIGHTS = $ing_weight_text;
		$shoppingList->SHO_PRODUCTS = $pro_id_text;
		$shoppingList->SHO_QUANTITIES = $amount_text;
		$shoppingList->SHO_HAVE_IT = $haveIt_text;
		
		return $shoppingList;
	}
	
	
	private function readActionsInDetails($coi_ids, $tools, $stepTypeConfig){
		if (count($coi_ids) == 0){
			return array(array(),array());
		}
		//evaluate valid actionsIn id's
		$actionsInAllowedCommand = Yii::app()->db->createCommand();
		$firstCOI = true;
		$lastcoi_id = -1;
		foreach($coi_ids as $coi_id){
			if ($firstCOI){
				$actionsInAllowedCommand->select('coi'.$coi_id.'.AIN_ID')->from('ain_to_coi coi'.$coi_id)->where('coi'.$coi_id.'.coi_id = '.$coi_id);
				$firstCOI = false;
				$lastcoi_id = $coi_id;
			} else {
				$actionsInAllowedCommand->join('ain_to_coi coi'.$coi_id, 'coi'.$coi_id.'.ain_id = coi'.$lastcoi_id.'.ain_id and coi'.$coi_id.'.coi_id = '.$coi_id);
				$lastcoi_id = $coi_id;
			}
		}
		//Example: SELECT coi1.ain_id FROM `ain_to_coi` coi1 join `ain_to_coi` coi2 ON coi1.ain_id = coi2.ain_id and coi2.coi_id = 2 where coi1.coi_id = 1;
		$ain_ids = $actionsInAllowedCommand->queryColumn();
		
		//read actionsIn data
		$actionsCriteria=new CDbCriteria;
		$actionsCriteria->compare('AIN_ID', $ain_ids);
		$actionsInCommand = Yii::app()->db->createCommand()->from('actions_in');
		$actionsInCommand->where($actionsCriteria->condition, $actionsCriteria->params);
		$actionsInsList = $actionsInCommand->queryAll();
		$actionsIn = CHtml::listData($actionsInsList,'AIN_ID','AIN_DESC_'.Yii::app()->session['lang']);
		$actionsIns = array();
		$actionsInsIndexed = array();
		$actionsInsPrep = array();
		$actionsInsNormal = array();
		$ainTextField = 'AIN_DESC_'.Yii::app()->session['lang'];
		foreach($actionsInsList as $actionsIn) {
			$actionsIns[$actionsIn['AIN_ID']] = $actionsIn[$ainTextField];
			$actionsInsIndexed[$actionsIn['AIN_ID']] = $actionsIn;
			if ($actionsIn['AIN_PREP'] == 'Y'){
				$actionsInsPrep[$actionsIn['AIN_ID']] = $actionsIn[$ainTextField];
			} else {
				$actionsInsNormal[$actionsIn['AIN_ID']] = $actionsIn[$ainTextField];
			}
		}
		$actionsIn = $actionsIns;
		
		//read all actionsOuts
		$actionsOuts = Yii::app()->db->createCommand()->from('actions_out')->order('AOU_ID')->queryAll();
		$actionsOutsIndexedArray = array();
		foreach($actionsOuts as $actionsOut){
			$actionsOutsIndexedArray[$actionsOut['AOU_ID']] = $actionsOut;
		}
		
		//read all matching ainToAou entrys, orderd by keys.
		$actionsCriteria->compare('COI_ID', $coi_ids);
		$ainToAous = Yii::app()->db->createCommand()->from('ain_to_aou')->order('AIN_ID, COI_ID, ATA_NO')->where($actionsCriteria->condition, $actionsCriteria->params)->queryAll();
		
		$textKeys=array('ING_ID'=>'#ingredient',
			'TOO_ID'=>'#tool',
			'STE_GRAMS'=>'#weight',
			'STE_STEP_DURATION'=>'#time',
			'STE_CELSIUS'=>'#temp',
			'STE_KPA'=>'#press',
			);
		
		$emptyStep = new Steps();
		$emptyStep->unsetAttributes();
		
		//loop thru ainToAou, collect all data for actionsIn and prepare details text to show.
		$actionsInDetails = array();
		$last_ain_id = -1;
		$last_coi_id = -1;
		foreach($ainToAous as $ainToAou){
			$ain_id = $ainToAou['AIN_ID'];
			$coi_id = $ainToAou['COI_ID'];
			if ($last_ain_id != $ain_id){
				if ($last_ain_id != -1){
					if ($last_coi_id != -1){
						$actionText = $actionsIn[$last_ain_id];
						foreach($textKeys as $field=>$textKey){
							if (strpos($actionText, $textKey) !== false){
								$required[$field]=true;
								$requiredAction[$field]=true;
							}
						}
						$requiredNew = array();
						foreach($required as $key=>$val){
							$requiredNew[] = $key;
						}
						$defaultNew = array();
						foreach($default as $key=>$val){
							$defaultNew[$key] =  $val;
						}
						$coiInfos['aou'] = $aous;
						$coiInfos['required'] = $requiredNew;
						$coiInfos['default'] = $defaultNew;
						$coiInfos['desc'] = '<span class="ain_coi_desc" id="ain_coi_desc_'.$last_ain_id.'_'.$last_coi_id.'">'.$desc.'</span>';
						$cois[$last_coi_id] = $coiInfos;
						$ain_desc .= $coiInfos['desc'];
					}
					
					if (isset($actionsInsIndexed[$last_ain_id])){
						$stepType = $actionsInsIndexed[$last_ain_id];
						
						$defaultValues = explode(';', $actionsInsIndexed[$last_ain_id]['AIN_DEFAULT']);
						foreach($defaultValues as $keyvalue){
							$keyvalue = trim($keyvalue);
							if($keyvalue != ''){
								list($field,$value) = explode('=', $keyvalue, 2);
								$field = trim($field);
								$value = trim($value);
								$defaultAction[$field] = $value;
							}
						}
					}
					
					$requiredNew = array();
					foreach($requiredAction as $key=>$val){
						$requiredNew[] = $key;
					}
					$defaultNew = array();
					foreach($defaultAction as $key=>$val){
						$defaultNew[$key] =  $val;
					}
					$details['cookIns'] = $cois;
					$details['required'] = $requiredNew;
					$details['default'] = $defaultNew;
					$actionHtml = Steps::getHTMLString($emptyStep, $actionsIn[$last_ain_id]);
					$details['desc'] = '<span class="ain_desc" id="ain_desc_'.$last_ain_id.'"><input type="hidden" class="actionRequireds" value="'.CHtml::encode(CJSON::encode($requiredNew)).'"/><input type="hidden" class="actionDefaults" value="'.CHtml::encode(CJSON::encode($defaultNew)).'"/><input type="hidden" class="actionHtml" value="'.CHtml::encode($actionHtml).'"/>'.$ain_desc.'</span>';
					$actionsInDetails[$last_ain_id] = $details;
				}
				$last_coi_id = -1;
				$cois=array();
				$ain_desc = '';
				$requiredAction = array();
				$defaultAction = array();
			}
			if ($last_coi_id != $coi_id){
				if ($last_coi_id != -1){
					$actionText = $actionsIn[$ain_id];
					foreach($textKeys as $field=>$textKey){
						if (strpos($actionText, $textKey) !== false){
							$required[$field]=true;
							$requiredAction[$field]=true;
						}
					}
					$requiredNew = array();
					foreach($required as $key=>$val){
						$requiredNew[] = $key;
					}
					$defaultNew = array();
					foreach($default as $key=>$val){
						$defaultNew[$key] =  $val;
					}
					$coiInfos['aou'] = $aous;
					$coiInfos['required'] = $requiredNew;
					$coiInfos['default'] = $defaultNew;
					$coiInfos['desc'] = '<span class="ain_coi_desc" id="ain_coi_desc_'.$ain_id.'_'.$last_coi_id.'">'.$desc.'</span>';
					$cois[$last_coi_id] = $coiInfos;
					$ain_desc .= $coiInfos['desc'];
				}
				$aous = array();
				$required = array();
				$default = array();
				$desc = '';
			}
			
			$actionsOut = $actionsOutsIndexedArray[$ainToAou['AOU_ID']];
			$actionsOut['ATA_COI_PREP'] = $ainToAou['ATA_COI_PREP'];
			$desc .= '<span class="aouLine"><span style="font-weight:bold;">'.$this->trans->RECIPES_DETAILSTEP.' '.$ainToAou['ATA_NO'].':</span> ';
			
			$actionText = $actionsOut['AOU_DESC_'.Yii::app()->session['lang']];
			$toolDescPart = '';
			if (isset($tools[$actionsOut['TOO_ID']])){
				$tool = $tools[$actionsOut['TOO_ID']];
				$actionsOut['tool'] = $tool;
				$toolDescPart = 'Tool: ' . $tool . ', ';
				$actionText = str_replace('#tool',$tool, $actionText);
			} else if ($actionsOut['TOO_ID'] > 0){
				$toolDescPart = 'Tool: id-' . $actionsOut['TOO_ID'] . ', ';
				$actionText = str_replace('#tool','ToolId-' . $actionsOut['TOO_ID'], $actionText);
			}
			foreach($textKeys as $field=>$textKey){
				if (strpos($actionText, $textKey) !== false){
					if (!isset($actionsOut[$textKey]) || $actionsOut[$textKey] == -1){
						if (!($field == 'STE_STEP_DURATION' && $actionsOut['AOU_DURATION']<-1)){
							$required[$field]=true;
							$requiredAction[$field]=true;
						}
					}
				}
			}
			
			$desc .= $actionText . ' <span class="smallDetails">(';
			if (isset($stepTypeConfig[$actionsOut['STT_ID']])){
				$stepType = $stepTypeConfig[$actionsOut['STT_ID']];
				$actionsOut['stepType'] = $stepType;
				$desc .= $this->trans->FIELD_STT_ID.': ' . $stepType['STT_DESC_'.Yii::app()->session['lang']] . ', ';
				
				$requiredFields = explode(';', $stepType['STT_REQUIRED']);
				foreach($requiredFields as $requiredField){
					if($requiredField != ''){
						$required[$requiredField] = true;
						$requiredAction[$requiredField] = true;
					}
				}
				$defaultValues = explode(';', $stepType['STT_DEFAULT']);
				foreach($defaultValues as $keyvalue){
					$keyvalue = trim($keyvalue);
					if($keyvalue != ''){
						list($field,$value) = explode('=', $keyvalue, 2);
						$field = trim($field);
						$value = trim($value);
						$default[$field] = $value;
						$defaultAction[$field] = $value;
					}
				}
			} else if ($actionsOut['STT_ID'] > 0){
				$desc .= $this->trans->FIELD_STT_ID . ': id-' . $actionsOut['STT_ID'] . ', ';
			}
			$desc .= $toolDescPart . $this->trans->FIELD_AOU_DURATION.': ' . $actionsOut['AOU_DURATION'] . ', '.$this->trans->FIELD_AOU_DUR_PRO.': ' . $actionsOut['AOU_DUR_PRO'] . ', '.$this->trans->FIELD_AOU_PREP.': ' . $actionsOut['AOU_PREP'] . ', '.$this->trans->FIELD_ATA_COI_PREP.': ' . $actionsOut['ATA_COI_PREP'] . ', '.$this->trans->FIELD_AOU_CIS_CHANGE.': ' . $actionsOut['AOU_CIS_CHANGE'] . ')</span></span>'."\r\n";
			
			$aous[$ainToAou['ATA_NO']] = $actionsOut;
			
			$last_ain_id = $ain_id;
			$last_coi_id = $coi_id;
		}
		if ($last_ain_id != -1){
			if ($last_coi_id != -1){
				$actionText = $actionsIn[$last_ain_id];
				foreach($textKeys as $field=>$textKey){
					if (strpos($actionText, $textKey !== false)){
						$required[$field]=true;
						$requiredAction[$field]=true;
					}
				}
				$requiredNew = array();
				foreach($required as $key=>$val){
					$requiredNew[] = $key;
				}
				$defaultNew = array();
				foreach($default as $key=>$val){
					$defaultNew[] = $key . '='.$val;
				}
				$coiInfos['aou'] = $aous;
				$coiInfos['required'] = $requiredNew;
				$coiInfos['default'] = $defaultNew;
				$coiInfos['desc'] = '<span class="ain_coi_desc" id="ain_coi_desc_'.$last_ain_id.'_'.$last_coi_id.'">'.$desc.'</span>';
				$cois[$last_coi_id] = $coiInfos;
				$ain_desc .= $coiInfos['desc'];
			}
			$requiredNew = array();
			foreach($requiredAction as $key=>$val){
				$requiredNew[] = $key;
			}
			$defaultNew = array();
			foreach($defaultAction as $key=>$val){
				$defaultNew[] = $key . '='.$val;
			}
			$details['cookIns'] = $cois;
			$details['required'] = $requiredNew;
			$details['default'] = $defaultNew;
			$actionHtml = Steps::getHTMLString($emptyStep, $actionsIn[$last_ain_id]);
			$details['desc'] = '<span class="ain_desc" id="ain_desc_'.$last_ain_id.'"><input type="hidden" class="actionRequireds" value="'.CHtml::encode(CJSON::encode($requiredNew)).'"/><input type="hidden" class="actionDefaults" value="'.CHtml::encode(CJSON::encode($defaultNew)).'"/><input type="hidden" class="actionHtml" value="'.CHtml::encode($actionHtml).'"/>'.$ain_desc.'</span>';
			$actionsInDetails[$last_ain_id] = $details;
		}
		return array($actionsInDetails, $actionsIn);
	}
	
	private function prepareRecipeChanges($model){
		$changes = array();
		/*
		if (isset($_POST['fields'])){
			$fields = $_POST['fields'];
			$fields = explode(',', $fields);
			$changes = array();
			foreach($fields as $field){
				if ($field != ''){
					if ($field == 'COI_ID[]'){
						//TODO: should this be handled there also? (because  problems with ActionIn to ActionOut could occure).
					} else {
						$field = substr($field, 8, -1); // strlen('Recipes[')
						$changeModel = new RecipeChanges;
						$changeModel->REC_ID = $model->REC_ID;
						$changeModel->RCH_FIELD = $field;
						$changeModel->RCH_OLD_VALUE = $model->attributes[$field];
						
						$changes[$field] = $changeModel;
					}
				}
			}
		}
		*/
		foreach($model->attributes as $field=>$val){
			if ($field != ''){
				$changeModel = new RecipeChanges;
				$changeModel->REC_ID = $model->REC_ID;
				$changeModel->RCH_FIELD = $field;
				$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
				//$changeModel->RCH_OLD_VALUE = $model->attributes[$field];
				$changeModel->RCH_OLD_VALUE = $val;
				
				$changes[$field] = $changeModel;
			}
		}
		return $changes;
	}
	
	private function logRecipeChanges($model, $changes, $timestamp){
		$oldValues = array();
		foreach($changes as $changeModel){
			$changeModel->RCH_NEW_VALUE = $model->attributes[$changeModel->RCH_FIELD];
			if ($changeModel->RCH_OLD_VALUE != $changeModel->RCH_NEW_VALUE){
				$changeModel->CHANGED_ON = $timestamp;
				$changeModel->save();
				if ($this->debug) {echo $changeModel->RCH_FIELD . "\n";}
				$oldValues[$changeModel->RCH_FIELD] = $changeModel->RCH_OLD_VALUE;
			}
		}
		return $oldValues;
	}
	
	private function getRecipeUndoKey($changeModel){
		return $changeModel->REC_ID . "_" . $changeModel->CHANGED_BY . "_" . $changeModel->CHANGED_ON;
	}
	private function getStepUndoKey($changeModel){
		return $changeModel->REC_ID . "_" . $changeModel->STE_STEP_NO . "_" . $changeModel->CHANGED_BY . "_" . $changeModel->CHANGED_ON;
	}
	
	public function actionUpdateSessionValues(){
		if(isset($_POST['Recipes'])){
			$model = Yii::app()->session[$this->createBackup];
			if (isset($model)){
				$recToCois = $model->recToCois;
				$dateTime = new DateTime();
				$timestamp = $dateTime->getTimestamp();
				$changes = $this->prepareRecipeChanges($model);
				$model->attributes=$_POST['Recipes'];
				$oldValues = $this->logRecipeChanges($model, $changes, $timestamp);
				
				if (isset($_POST['Steps'])){
					$action = '';
					$actionIndex = -1;
					if (isset($_POST['remove'])){
						$action = 'DELETE';
						$actionIndex=$_POST['remove'];
						$prefIndex = $actionIndex;
					} else if (isset($_POST['add'])){
						$action = 'ADD';
						$actionIndex=$_POST['add'];
						$prefIndex = $actionIndex;
					} else if (isset($_POST['move'])){
						$action = 'MOVE';
						$prefIndex=$_POST['move'];
						if (isset($_POST['to'])){
							$actionIndex=$_POST['to'];
						}
					}
					//if ($this->debug) {echo "action:$action,actionIndex:$actionIndex\n";}
					$dataArray = $_POST['Steps'];
					if ($actionIndex != -1){
						$changeModel = new StepChanges;
						$result;
						if ($action == 'ADD' || $action == 'MOVE'){
							$entry = $dataArray[$actionIndex];
							if (is_array($entry) && count($entry) == 1 && isset($entry['json'])){
								try {
									$entry = CJSON::decode($entry['json']);
								} catch (exception $e){}
							}
							if ($this->debug) {var_dump($entry);}
							$changeModel->attributes = $entry;
							$changeModel = Functions::arrayToRelatedObjects($changeModel, $entry);
							if ($action == 'ADD'){
								$result = $entry;
							}
						} else {
							$oldArray = $model['steps'];
							if (isset($oldArray[$actionIndex-1])){
								$oldEntry = $oldArray[$actionIndex-1];
								$this->setStepChangesOldValues($changeModel, $oldEntry->attributes);
								$result = $oldEntry->attributes;
							}
						}
						if($changeModel->REC_ID == ''){
							$changeModel->REC_ID = $model->REC_ID;
						}
						if($changeModel->REC_ID == ''){
							//recipe not saved
							$changeModel->REC_ID = -1;
						}
						if($action == 'MOVE'){
							$changeModel->STE_STEP_NO = $prefIndex;
						} else {
							$changeModel->STE_STEP_NO = $actionIndex-1;
						}
						$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
						
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN','MOVE'
						$changeModel->CHANGED_ON = $timestamp;

						if($changeModel->save()){
							if (isset($result)){
								$result["REC_ID"] = $changeModel->REC_ID;
								$result["STE_STEP_NO"] = $changeModel->STE_STEP_NO;
								echo '{"action":"'.$action.'","stepNr":"'.$actionIndex.'","prefIndex":"'.($prefIndex).'","undoKey":"'.$this->getStepUndoKey($changeModel).'","prevValues":'.CJSON::encode($result).'}';
							} else {

								echo '{"action":"'.$action.'","stepNr":"'.$actionIndex.'","prefIndex":"'.($prefIndex).'","undoKey":"'.$this->getStepUndoKey($changeModel).'"}';
							}
						} else {
							if ($this->debug) {echo 'error on save: ';  var_dump($changeModel->getErrors());}
						}
					} else {
						if (count($changes)>0){
							$changeModel = reset($changes);
							if (!isset($changeModel->CHANGED_BY)){
								$changeModel->CHANGED_BY = Yii::app()->user->id;
							}
						} else {
							$changeModel = new RecipeChanges;
							$changeModel->REC_ID = $model->REC_ID;
							$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
							$changeModel->CHANGED_ON = $timestamp;
							$changeModel->CHANGED_BY = Yii::app()->user->id;
						}
						
						echo '{"action":"RECIPE","undoKey":"'.$this->getRecipeUndoKey($changeModel).'","prevValues":'.CJSON::encode($oldValues).'}';
					}
				
					$model = Functions::arrayToRelatedObjects($model, array('steps'=>$dataArray));
					$model->recToCois = $recToCois;
				}
				
				Yii::app()->session[$this->createBackup] = $model;
				Yii::app()->session[$this->createBackup.'_Time'] = time();
			} else {
				if ($this->debug) {echo 'error on update session values';}
			}
		}
	}
	
	private function setStepChangesOldValues($changeModel, $values){
		foreach($values as $field=>$val){
			$changeModel->setAttribute($field.'_OLD', $val);
		}
		return $changeModel;
	}
	
	public function actionUpdateSessionValue($StepNr){
		if (isset($_POST['Steps'])){
			$model = Yii::app()->session[$this->createBackup];
			if (isset($model)){
				$newArray = $model['steps'];
				if (isset($newArray[$StepNr-1])){
					$newModel = $newArray[$StepNr-1];
				} else {
					$newModel = new Steps;
				}
				$dataArray = $_POST['Steps'];
				$entry = $dataArray[$StepNr];
				if (is_array($entry) && count($entry) == 1 && isset($entry['json'])){
					try {
						$entry = CJSON::decode($entry['json']);
					} catch (exception $e){}
				}
				$action = (isset($_POST['action']))?$_POST['action']:$_GET['action'];
				if ($action != 'IGNORE'){
					$prefIndex=(isset($_POST['prefIndex']))?$_POST['prefIndex']:$_GET['prefIndex'];
					$prefIndex--;
					$changeModel = new StepChanges;
					$changeModel->attributes = $entry;
					$changeModel = Functions::arrayToRelatedObjects($changeModel, $entry);
					if($changeModel->REC_ID == ''){
						$changeModel->REC_ID = $model->REC_ID;
					}
					if($changeModel->REC_ID == ''){
						//recipe not saved
						$changeModel->REC_ID = -1;
					}
					$changeModel->STE_STEP_NO = $prefIndex;
					if ($action == 'CHANGE'){
						if (isset($newArray[$prefIndex])){
							$oldEntry = $newArray[$prefIndex];
							$this->setStepChangesOldValues($changeModel, $oldEntry->attributes);
							$result = $oldEntry->attributes;
						}
					}
					$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
					
					if (isset($_POST['undoKey'])){
						$changeModel->SCH_UNDO_KEY = $_POST['undoKey'];
						$action = 'UNDO_' . $action;
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN','MOVE','UNDO_CHANGE','UNDO_ADD','UNDO_DELETE','UNDO_UP','UNDO_DOWN'
					} else {
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN','MOVE','UNDO_CHANGE','UNDO_ADD','UNDO_DELETE','UNDO_UP','UNDO_DOWN'
					}
					//CHANGED_ON set in save
					if($changeModel->save()){
						if (isset($result)){
							$result["REC_ID"] = $changeModel->REC_ID;
							$result["STE_STEP_NO"] = $changeModel->STE_STEP_NO;
							echo '{"action":"'.$action.'","stepNr":"'.$StepNr.'","prefIndex":"'.($prefIndex+1).'","undoKey":"'.$this->getStepUndoKey($changeModel).'","prevValues":'.CJSON::encode($result).'}';
						} else {
							echo '{"action":"'.$action.'","stepNr":"'.$StepNr.'","prefIndex":"'.($prefIndex+1).'","undoKey":"'.$this->getStepUndoKey($changeModel).'"}';
						}
					} else {
						if ($this->debug) {echo 'error on save: ';  var_dump($changeModel->getErrors());}
					}
				}
				
				$newModel->unsetAttributes();
				$newModel->attributes = $entry;
				$newArray[$StepNr-1] = Functions::arrayToRelatedObjects($newModel, $entry);
				
				$model['steps'] = $newArray;
				Yii::app()->session[$this->createBackup] = $model;
				Yii::app()->session[$this->createBackup.'_Time'] = time();
			} else {
				if ($this->debug) {echo 'error on update session values';}
			}
		}
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Recipes_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Recipes_Backup)){
			$oldmodel = $Session_Recipes_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->REC_ID != $id){
				$oldmodel = $this->loadModel($id);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPictureFilename = $oldmodel->REC_IMG_FILENAME;
			$oldAmount = count($oldmodel->steps);
		} else {
			$model=new Recipes;
			$oldPictureFilename = null;
			$oldAmount = 0;
		}
		if (isset($model->REC_IMG_FILENAME) && $model->REC_IMG_FILENAME != ''){
			$model->setScenario('withPic');
		}
		
		//Rec to cois
		$recToCois = array();
		$recToCoiList_old = '';
		if(isset($_POST['COI_ID'])){
			foreach($_POST['COI_ID'] as $coi_id){
				if (isset($coi_id) && $coi_id>0){
					$recToCoi = new RecToCoi;
					$recToCoi->REC_ID = $model->REC_ID;
					$recToCoi->COI_ID = $coi_id;
					$recToCois[] = $recToCoi;
				}
			}
			foreach($model->recToCois as $recToCoi){
				$recToCoiList_old .= $recToCoi->COI_ID . ',';
			}
		} else {
			$recToCois = $model->recToCois;
		}
		$coi_ids = array();
		$recToCoiList_new = '';
		foreach($recToCois as $recToCoi){
			$coi_ids[] = $recToCoi->COI_ID;
			$recToCoiList_new .= $recToCoi->COI_ID . ',';
		}
		if(isset($_POST['COI_ID'])){
			if ($recToCoiList_old != $recToCoiList_new){
				$changeModel = new RecipeChanges;
				$changeModel->REC_ID = $id;
				$changeModel->RCH_FIELD = 'rec_to_coi.COI_ID';
				$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
				$changeModel->RCH_OLD_VALUE = $recToCoiList_old;
				$changeModel->RCH_NEW_VALUE = $recToCoiList_new;
				$changeModel->save();
			}
		}
		

		//Rec to tags
		$recToTags = array();
		$recToTagList_old = '';
		if(isset($_POST['tags'])){
			$tagArray = split(',',$_POST['tags']); 
			foreach($tagArray as $tag_id){
				if (isset($tag_id)){
					if(is_numeric($tag_id)){
						if ($tag_id>0){
							$recToTag = new RecToTag;
							$recToTag->REC_ID = $model->REC_ID;
							$recToTag->TAG_ID = $tag_id;
							$recToTags[] = $recToTag;
						}
					} else {
						if(Yii::app()->user->demo){
							$this->errorText .= sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
						} else {
							//insert new Tag(if needed), and get id
							$tag_id = trim($tag_id);
							if (strlen($tag_id)>1){ //more than 1 char needed
								$tag_id = Yii::app()->db->createCommand("SELECT getTagId(:tag,:lang,:prf_id);")->queryScalar(array(':tag'=>$tag_id,':lang'=>Yii::app()->session['lang'],':prf_id'=>Yii::app()->user->id));
								if ($tag_id != null){
									$recToTag = new RecToTag;
									$recToTag->REC_ID = $model->REC_ID;
									$recToTag->TAG_ID = $tag_id;
									$recToTags[] = $recToTag;
								}
							}
						}
					}
				}
			}
			foreach($model->recToTags as $recToTag){
				$recToTagList_old .= $recToTag->TAG_ID . ',';
			}
		} else {
			$recToTags = $model->recToTags;
		}
		$tag_ids = array();
		$recToTagList_new = '';
// 		$tags = array();
		foreach($recToTags as $recToTag){
			$tag_ids[] = $recToTag->TAG_ID;
			$recToTagList_new .= $recToTag->TAG_ID . ',';
// 			if (isset($recToTag->tag)){
// 				$tags[$recToTag->tag->TAG_ID] = $recToTag->tags->__get('TAG_DESC_'.Yii::app()->session['lang']);
// 			}
		}
// 		$recToTagList_new = implode(',', array_keys($tags));
		
		if(isset($_POST['tags'])){
			if ($recToTagList_old != $recToTagList_new){
				$changeModel = new RecipeChanges;
				$changeModel->REC_ID = $id;
				$changeModel->RCH_FIELD = 'rec_to_tag.TAG_ID';
				$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
				$changeModel->RCH_OLD_VALUE = $recToTagList_old;
				$changeModel->RCH_NEW_VALUE = $recToTagList_new;
				$changeModel->save();
			}
		}
		if(strlen($recToTagList_new)>0){
			$recToTagList_new = substr($recToTagList_new, 0, -1);
		}
		
		//read StepType config and create indexed details List
		$stepTypeConfig = Yii::app()->db->createCommand()->select('STT_ID,STT_DEFAULT,STT_REQUIRED,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->order('STT_ID')->queryAll();
		$stepTypeConfigIndexed = array();
		foreach($stepTypeConfig as $stepType){
			$stepTypeConfigIndexed[$stepType['STT_ID']] = $stepType;
		}
		
		//read all Tools and create indexed nameList
		$tools = Yii::app()->db->createCommand()->select('TOO_ID,TOO_DESC_'.Yii::app()->session['lang'])->from('tools')->order('TOO_DESC_'.Yii::app()->session['lang'])->queryAll();
		$tools = CHtml::listData($tools,'TOO_ID','TOO_DESC_'.Yii::app()->session['lang']);
		
		list($actionsInDetails, $actionsIn) = $this->readActionsInDetails($coi_ids, $tools, $stepTypeConfigIndexed);
		
		$updateCookInOK = false;
		if(isset($_POST['updateCookIn'])){
			//Check if all actionsIn are still possible
			if (isset($_POST['Steps'])){
				$index=1;
				foreach($_POST['Steps'] as $step){
					if (is_array($step) && count($step) == 1 && isset($step['json'])){
						try {
							$step = CJSON::decode($step['json']);
						} catch (exception $e){}
					}
					$ain_id = $step['AIN_ID'];
					if ($ain_id >0 && !isset($actionsIn[$ain_id])){
						array_push($this->errorFields, 'Steps_'.$index.'_AIN_ID');
					}
					++$index;
				}
			} else {
				$index=1;
				foreach($model->steps as $step){
					$ain_id = $step['AIN_ID'];
					if ($ain_id >0 && !isset($actionsIn[$ain_id])){
						array_push($this->errorFields, 'Steps_'.$index.'_AIN_ID');
					}
					++$index;
				}
			}
			
			if(count($this->errorFields)==0){
				$model->recToCois = $recToCois;
				$updateCookInOK = true;
			} else {
				$this->errorText = $this->trans->RECIPES_COOKIN_CHANGE_ERROR;
				$recToCois = $model->recToCois;
				$coi_ids = array();
				foreach($model->recToCois as $recToCoi){
					$coi_ids[] = $recToCoi->COI_ID;
				}
				
				list($actionsInDetails, $actionsIn) = $this->readActionsInDetails($coi_ids, $tools, $stepTypeConfigIndexed);
			}
		} else {
			$updateCookInOK = true;
		}
		
		if(isset($_POST['Recipes'])){
			$changes = $this->prepareRecipeChanges($model);
			$model->attributes=$_POST['Recipes'];
			
			$dateTime = new DateTime();
			$timestamp = $dateTime->getTimestamp();
			$this->logRecipeChanges($model, $changes, $timestamp);
			
			$steps = array();
			$stepsOK = true;
			$ingredientPrepareSteps = array();
			$ingredientUseSteps = array();
			if (isset($_POST['Steps'])){
				$model = Functions::arrayToRelatedObjects($model, array('steps'=> $_POST['Steps']));
				$model->recToCois = $recToCois;
				$model->recToTags = $recToTags;
				
				$index = 1;
				foreach($model->steps as $step){
					$required = array();
					if (isset($actionsInDetails[$step['AIN_ID']])){
						$ainDetails = $actionsInDetails[$step['AIN_ID']];
						$required = $ainDetails['required'];
					}
					if (isset($required) && count($required)>0){
						foreach($required as $requiredField){
							if (!isset($step[$requiredField]) || $step[$requiredField] === null || $step[$requiredField] === ''){
								$this->errorText .= sprintf($this->trans->RECIPES_REQUIRED_FIELD_EMPTY, $requiredField, $index);
								array_push($this->errorFields, 'Steps_'.$index.'_'.$requiredField);
								$stepsOK = false;
							}
						}
					}
					if (isset($step['ING_ID']) && $step['ING_ID']>0){
						if (isset($step['STE_GRAMS']) /*&& $step['STE_GRAMS'] > 0*/){
							$ingredientPrepareSteps[$step['ING_ID']] = $index;
						} else {
							$ingredientUseSteps[$index] = $step['ING_ID'];
						}
					}
					
					/*
					foreach($step->getAttributes(false) as $key=>$value){
						if ($value === '' && $key != 'REC_ID' && $key != 'STE_STEP_NO'){
							$this->errorText .= '<li>Value ' . $key . ' of Step' . $index . ' is empty.</li>';
							array_push($this->errorFields, 'Steps_'.$index.'_'.$key);
							$stepsOK = false;
						}
					}
					*/
					++$index;
				}
				foreach($ingredientUseSteps as $stepNr=>$ing_id){
					if(!isset($ingredientPrepareSteps[$ing_id])){
						//ingredient not prepared
						$this->errorText .= sprintf($this->trans->RECIPES_INGREDIENT_NOT_PREPARED, $stepNr);
						array_push($this->errorFields, 'Steps_'.$stepNr.'_ING_ID');
						$stepsOK = false;
					} else if ($ingredientPrepareSteps[$ing_id]>$stepNr){
						//ingredient prepared after use
						$this->errorText .= sprintf($this->trans->RECIPES_INGREDIENT_PREPARED_TO_LATE, $stepNr, $ingredientPrepareSteps[$ing_id]);
						array_push($this->errorFields, 'Steps_'.$stepNr.'_ING_ID');
						$stepsOK = false;
					}
				}
			} else {
				if(!isset($_POST['updateCookIn'])){
					$this->errorText .= '<li>' . $this->trans->RECIPES_ERROR_NO_STEPS . '</li>';
				}
				$stepsOK = false;
			}
			if (isset($oldPictureFilename)){
				Functions::updatePicture($model,'REC_IMG', $oldPictureFilename);
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			
			if(isset($_POST['updateCookIn'])){
				$model->validate();
			} else {
				if(Yii::app()->user->demo){
					$this->errorText .= sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
					$model->validate();
				} else {
					if ($stepsOK){
						$REC_CHANGED_ON = $model->CHANGED_ON;
						$transaction=$model->dbConnection->beginTransaction();
						try {
							$model->CHANGED_ON = $timestamp;
							//TODO: add flag for professional users, so they can remove assign to there user
							if (in_array('professional', Yii::app()->user->roles) && !isset($model->REC_ID)){
								$model->PRF_UID = Yii::app()->user->id;
							}
							$model->updateChangeTime = false;
							if($model->save()){
								$saveOK = true;
								//Rec To Coi
								Yii::app()->db->createCommand()->delete(RecToCoi::model()->tableName(), 'REC_ID = :id', array(':id'=>$model->REC_ID));
								foreach($model->recToCois as $recToCoi){
									$recToCoi->REC_ID = $model->REC_ID;
									$recToCoi->CHANGED_ON = $timestamp;
									$recToCoi->updateChangeTime = false;
									$recToCoi->setIsNewRecord(true);
									if(!$recToCoi->save()){
										$saveOK = false;
										if ($this->debug) {echo 'error on save recToCoi: errors:'; var_dump($recToCoi->getErrors());}
									} else {
										$recToCoi->updateChangeTime = true;
									}
								}
								
								//Rec To Tag
								Yii::app()->db->createCommand()->delete(RecToTag::model()->tableName(), 'REC_ID = :id', array(':id'=>$model->REC_ID));
								foreach($model->recToTags as $recToTag){
									$recToTag->REC_ID = $model->REC_ID;
									$recToTag->CHANGED_ON = $timestamp;
									$recToTag->updateChangeTime = false;
									$recToTag->setIsNewRecord(true);
									if(!$recToTag->save()){
										$saveOK = false;
										if ($this->debug) {echo 'error on save recToTag: errors:'; var_dump($recToTag->getErrors());}
									} else {
										$recToTag->updateChangeTime = true;
									}
								}
								
								//Steps
								if ($saveOK){
									Yii::app()->db->createCommand()->delete(Steps::model()->tableName(), 'REC_ID = :id', array(':id'=>$model->REC_ID));
									$stepNo = 0;
									$stepAmount = count($model->steps) -1;
									$hasFinished = false;
									foreach($model->steps as $step){
										$step->REC_ID = $model->REC_ID;
										if ($step->AIN_ID == Yii::app()->params['FinishedActionId']){
											if ($stepNo < $stepAmount){
												continue;
											} else {
												$step->STE_STEP_DURATION = 0;
												$hasFinished = true;
											}
										}
										$step->STE_STEP_NO = $stepNo;
										$step->CHANGED_ON = $timestamp;
										$step->updateChangeTime = false;
										$step->setIsNewRecord(true);
										if(!$step->save()){
											$saveOK = false;
											if ($this->debug) {echo 'error on save Step: errors:'; var_dump($step->getErrors());}
										} else {
											$step->updateChangeTime = true;
										}
										++$stepNo;
									}
									if (!$hasFinished){
										$step = new Steps();
										$step->REC_ID = $model->REC_ID;
										$step->STE_STEP_NO = $stepNo;
										$step->CHANGED_ON = $timestamp;
										$step->updateChangeTime = false;
										$step->AIN_ID = Yii::app()->params['FinishedActionId'];
										$step->STE_STEP_DURATION = 0;
										if(!$step->save()){
											$saveOK = false;
											if ($this->debug) {echo 'error on save Step: errors:'; var_dump($step->getErrors());}
										} else {
											$step->updateChangeTime = true;
										}
									}
								}
								
								//finish save
								if ($saveOK){
									$this->updateKCal($model->REC_ID, $model->REC_SERVING_COUNT, null, null);
									
									$saveOK = true;
									$changed = Functions::fixPicturePathAfterSave($model,'REC_IMG', $model->REC_IMG_FILENAME);
									if ($changed){
										$model->updateChangeTime = false;
										if(!$model->save()){
											if ($this->debug) {echo 'error on save after img file: ';  var_dump($model->getErrors());}
											$transaction->rollBack();
											$saveOK = false;
										}
									}
									
									if (isset($id)){
										$updateFields = array('RCH_SAVED'=>$timestamp);
										Yii::app()->db->createCommand()->update(RecipeChanges::model()->tableName(), $updateFields, 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by AND RCH_SAVED IS NULL', array(':id'=>$model->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
										$updateFields = array('SCH_SAVED'=>$timestamp);
										Yii::app()->db->createCommand()->update(StepChanges::model()->tableName(), $updateFields, 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by AND SCH_SAVED IS NULL', array(':id'=>$model->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
										//TODO: update all RecipeChanges & StepChanges without REC_ID from this user to the new generated id: $model->REC_ID
									}
									
									if ($saveOK){
										//copy Entrys to History
										$condition = array(':id'=>$model->REC_ID);
										Yii::app()->db->createCommand()->setText('INSERT INTO recipes_history (SELECT * FROM `' . Recipes::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
										Yii::app()->db->createCommand()->setText('INSERT INTO steps_history (SELECT * FROM `' . Steps::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
										Yii::app()->db->createCommand()->setText('INSERT INTO rec_to_coi_history (SELECT * FROM `' . RecToCoi::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
										Yii::app()->db->createCommand()->setText('INSERT INTO rec_to_tag_history (SELECT * FROM `' . RecToTag::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
									}
									
									if ($saveOK){
										$transaction->commit();
										unset(Yii::app()->session[$this->createBackup]);
										unset(Yii::app()->session[$this->createBackup.'_Time']);
										$this->forwardAfterSave(array('view', 'id'=>$model->REC_ID));
										return;
									}
								} else {
									if ($this->debug) echo 'any errors occured, rollback';
									$transaction->rollBack();
									$saveOK = false;
								}
							} else {
								if ($this->debug) {echo 'error on save: ';  var_dump($model->getErrors());}
								$transaction->rollBack();
								$saveOK = false;
							}
						} catch(Exception $e) {
							if ($this->debug) echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
							$transaction->rollBack();
							$saveOK = false;
						}
						$model->updateChangeTime = true;
						if (!$saveOK){
							$model->CHANGED_ON = $REC_CHANGED_ON;
						}
					} else {
						//To show Recipe errors also
						$model->validate();
					}
				}
			}
		} else {
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
		}
		
		$recipeTypes = Yii::app()->db->createCommand()->select('RET_ID,RET_DESC_'.Yii::app()->session['lang'])->from('recipe_types')->queryAll();
		$recipeTypes = CHtml::listData($recipeTypes,'RET_ID','RET_DESC_'.Yii::app()->session['lang']);
		
		$cusineTypes = Yii::app()->db->createCommand()->select('CUT_ID,CUT_DESC_'.Yii::app()->session['lang'])->from('cusine_types')->order('CUT_DESC_'.Yii::app()->session['lang'])->queryAll();
		$cusineTypes = CHtml::listData($cusineTypes,'CUT_ID','CUT_DESC_'.Yii::app()->session['lang']);
		$cut_id = $model->CUT_ID;
		if ($cut_id != ''){
			$cusineSubTypes = Yii::app()->db->createCommand()->select('CST_ID,CST_DESC_'.Yii::app()->session['lang'])->from('cusine_sub_types')->where('CUT_ID = :cut_id', array(':cut_id'=>$cut_id))->order('CST_DESC_'.Yii::app()->session['lang'])->queryAll();
			$cusineSubTypes = CHtml::listData($cusineSubTypes,'CST_ID','CST_DESC_'.Yii::app()->session['lang']);
			
			$cst_id = $model->CST_ID;
			if ($cst_id != ''){
				$cusineSubSubTypes = Yii::app()->db->createCommand()->select('CSS_ID,CSS_DESC_'.Yii::app()->session['lang'])->from('cusine_sub_sub_types')->where('CST_ID = :cst_id', array(':cst_id'=>$cst_id))->order('CSS_DESC_'.Yii::app()->session['lang'])->queryAll();
				$cusineSubSubTypes = CHtml::listData($cusineSubSubTypes,'CSS_ID','CSS_DESC_'.Yii::app()->session['lang']);
			} else {
				$cusineSubSubTypes = array();
			}
		} else {
			$cusineSubTypes = array();
			$cusineSubSubTypes = array();
		}
		
		
		$stepTypes = CHtml::listData($stepTypeConfig,'STT_ID','STT_DESC_'.Yii::app()->session['lang']);
		//$ingredients = Yii::app()->db->createCommand()->select('ING_ID,ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->queryAll();
		//$ingredients = CHtml::listData($ingredients,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
		
		if (isset($model->steps) && isset($model->steps[0])/* && !isset($model->steps[0]->ingredient)*/){
			/*
			$ingredients = Yii::app()->db->createCommand()->select('ING_ID,ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->queryAll();
			$ingredients = CHtml::listData($ingredients,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
			$usedIngredients = array();
			foreach($model->steps as $step){
				foreach($ingredients as $row_key=>$row_val){
					if($row_key == $val){
						$usedIngredients = array_merge($usedIngredients,array($row_key=>$row_val));
						break;
					}
				}
			}
			*/
			$neededIngredients = array();
			$neededIngredientAmount = array();
			foreach($model->steps as $step){
				array_push($neededIngredients,$step->ING_ID);
				if (isset($neededIngredientAmount[$step->ING_ID])){
					$neededIngredientAmount[$step->ING_ID] += $step->STE_GRAMS;
				} else {
					$neededIngredientAmount[$step->ING_ID] = $step->STE_GRAMS;
				}
			}
			if (count($neededIngredients)>0){
				$criteria=new CDbCriteria;
				// use * //$criteria->select = 'ING_ID,ING_NAME_'.Yii::app()->session['lang'];
				$criteria->compare('ING_ID',$neededIngredients);
				$usedIngredientDetails = Yii::app()->db->commandBuilder->createFindCommand('ingredients', $criteria, '')->queryAll();
				$usedIngredients = CHtml::listData($usedIngredientDetails,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
			} else {
				$usedIngredients=array();
				$usedIngredientDetails=array();
			}
		} else {
			$usedIngredients=array();
			$usedIngredientDetails=array();
			$neededIngredientAmount=array();
		}
		
		$cookIns = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->queryAll();
		$cookIns = CHtml::listData($cookIns,'COI_ID','COI_DESC_'.Yii::app()->session['lang']);
		
		$cookInsSelected = array();
		foreach($coi_ids as $coiId){
			$cookInsSelected[$coiId]=$cookIns[$coiId];
		}

		$difficulty = Yii::app()->db->createCommand()->select('DIF_ID,DIF_DESC_'.Yii::app()->session['lang'])->from('difficulty')->order('DIF_ORDER')->queryAll();
		$difficulty = CHtml::listData($difficulty,'DIF_ID','DIF_DESC_'.Yii::app()->session['lang']);
		
		$stepsJSON = CJSON::encode($model->steps);
		$stepTypeConfig = CJSON::encode($stepTypeConfig);
		
		if (!isset($_POST['CookVariant']) || $_POST['CookVariant'] == ''){
			$_POST['CookVariant'] = 0;
		}
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'recipeTypes'=>$recipeTypes,
			'cusineTypes'=>$cusineTypes,
			'cusineSubTypes'=>$cusineSubTypes,
			'cusineSubSubTypes'=>$cusineSubSubTypes,
			'actionsIn'=>$actionsIn,
			'cookIns'=>$cookIns,
			'cookInsSelected'=>$cookInsSelected,
			'tools'=>$tools,
			'ingredients'=>$usedIngredients,
			'ingredientDetails'=>$usedIngredientDetails,
			'ingredientAmount'=>$neededIngredientAmount,
			'stepTypeConfig'=>$stepTypeConfig,
			'stepsJSON'=>$stepsJSON,
			'actionsInDetails'=>$actionsInDetails,
			'tags'=>$recToTagList_new,
			'difficulty'=>$difficulty,
		));
	}
	
	public function actionGetCusineSubTypes($cut_id){
		header('Content-type: application/json');
		
		foreach (Yii::app()->log->routes as $route) {
			if($route instanceof CWebLogRoute) {
				$route->enabled = false; // disable any weblogroutes
			}
		}
		$cusineSubTypes = Yii::app()->db->createCommand()->select('CST_ID,CST_DESC_'.Yii::app()->session['lang'])->from('cusine_sub_types')->where('CUT_ID = :cut_id', array(':cut_id'=>$cut_id))->order('CST_DESC_'.Yii::app()->session['lang'])->queryAll();
		$cusineSubTypes = CHtml::listData($cusineSubTypes,'CST_ID','CST_DESC_'.Yii::app()->session['lang']);
		
		echo CJSON::encode($cusineSubTypes);
		
		Yii::app()->end();
	}

	public function actionGetCusineSubSubTypes($cst_id){
		header('Content-type: application/json');
	
		foreach (Yii::app()->log->routes as $route) {
			if($route instanceof CWebLogRoute) {
				$route->enabled = false; // disable any weblogroutes
			}
		}
		$cusineSubSubTypes = Yii::app()->db->createCommand()->select('CSS_ID,CSS_DESC_'.Yii::app()->session['lang'])->from('cusine_sub_sub_types')->where('CST_ID = :cst_id', array(':cst_id'=>$cst_id))->order('CSS_DESC_'.Yii::app()->session['lang'])->queryAll();
		$cusineSubSubTypes = CHtml::listData($cusineSubSubTypes,'CSS_ID','CSS_DESC_'.Yii::app()->session['lang']);
	
		echo CJSON::encode($cusineSubSubTypes);
	
		Yii::app()->end();
	}
	
	public function actionGetRecipeInfos($id){
		header('Content-type: application/json');
		$this->saveLastAction = false;
		$model = $this->loadModel($id);
		if (isset($model->steps) && isset($model->steps[0])/* && !isset($model->steps[0]->ingredient)*/){
			/*
			$ingredients = Yii::app()->db->createCommand()->select('ING_ID,ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->queryAll();
			$ingredients = CHtml::listData($ingredients,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
			$usedIngredients = array();
			foreach($model->steps as $step){
				foreach($ingredients as $row_key=>$row_val){
					if($row_key == $val){
						$usedIngredients = array_merge($usedIngredients,array($row_key=>$row_val));
						break;
					}
				}
			}
			*/
			$neededIngredients = array();
			foreach($model->steps as $step){
				array_push($neededIngredients,$step->ING_ID);
			}
			if (count($neededIngredients)>0){
				$criteria=new CDbCriteria;
				$criteria->select = 'ING_ID,ING_NAME_'.Yii::app()->session['lang'];
				$criteria->compare('ING_ID',$neededIngredients);
				$usedIngredients = Yii::app()->db->commandBuilder->createFindCommand('ingredients', $criteria, '')->queryAll();
				$usedIngredients = CHtml::listData($usedIngredients,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
			} else {
				$usedIngredients=array();
			}
		} else {
			$usedIngredients=array();
		}
		
		if (isset($model->REC_IMG_FILENAME) && strlen($model->REC_IMG_FILENAME)>0){
			$currentModel = Yii::app()->session[$this->createBackup];
			if (!isset($currentModel) || $currentModel == null){
				$currentModel = new Recipes();
			}
			$currentModel->REC_IMG_FILENAME = $model->REC_IMG_FILENAME;
			$currentModel->REC_IMG_ETAG = $model->REC_IMG_ETAG;
			$currentModel->REC_IMG_AUTH = $model->REC_IMG_AUTH;
			
			Yii::app()->session[$this->createBackup] = $currentModel;
		}
		
		$stepsJSON = CJSON::encode($model->steps);
		$recToCoisJSON = CJSON::encode($model->recToCois);
		$ingredientsJSON = CJSON::encode($usedIngredients);
		$modelJSON = CJSON::encode($model);
		echo '{steps:'.$stepsJSON.', ingredients:'.$ingredientsJSON.', model:'.$modelJSON.', recToCois:'.$recToCoisJSON.'}';
		Yii::app()->end();
	}
	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if (isset($_GET['newModel']) && isset(Yii::app()->session[$this->createBackup.'_Time']) && $_GET['newModel']>Yii::app()->session[$this->createBackup.'_Time']){
				unset(Yii::app()->session[$this->createBackup]);
				unset(Yii::app()->session[$this->createBackup.'_Time']);
				unset($_GET['newModel']);
		}
		$this->prepareCreateOrUpdate(null, 'create');
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->prepareCreateOrUpdate($id, 'update');
	}
	
	public function actionActionSuggestion($ing_id){
		header('Content-type: application/json');
		
		foreach (Yii::app()->log->routes as $route) {
			if($route instanceof CWebLogRoute) {
				$route->enabled = false; // disable any weblogroutes
			}
		}
		$result = $this->getActionSuggestion($ing_id);
		$ingredients = $result['ingredients'];
		if (count($ingredients)>0){
			$criteria=new CDbCriteria;
			$criteria->select = 'ING_ID,ING_NAME_'.Yii::app()->session['lang'].',ING_IMG_AUTH';
			$criteria->compare('ING_ID',$ingredients);
			$usedIngredientDetails = Yii::app()->db->commandBuilder->createFindCommand('ingredients', $criteria, '')->queryAll();
			//$usedIngredients = CHtml::listData($usedIngredientDetails,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
			$usedIngredients = array();
			foreach ($usedIngredientDetails as $detail){
				$detail['ING_NAME'] = $detail['ING_NAME_'.Yii::app()->session['lang']];
				unset($detail['ING_NAME_'.Yii::app()->session['lang']]);
				$usedIngredients[$detail['ING_ID']] = $detail;
			}
			
			$result['ingredients'] = $usedIngredients;
		}
		echo CJSON::encode($result);

		Yii::app()->end();
	}
	
	public function getActionSuggestion($ing_id){
		$ingredients = array();
		$suggestions = array();
		if ($ing_id == 1 || $ing_id == 36){
			//onion
			$ing_id_oil = 10;
			$ingredients[] = $ing_id_oil;
			$suggestions[] = array('AIN_ID'=>'11', 'ING_ID'=>$ing_id, 'STE_GRAMS'=>50); //prepare onion
			
			//$suggestions[] = array('AIN_ID'=>'3', 'ING_ID'=>$ing_id_oil, 'STE_GRAMS'=>10); //weight/add olive oil
			$suggestions[] = array('AIN_ID'=>'11', 'ING_ID'=>$ing_id_oil, 'STE_GRAMS'=>10); //prepare olive oil
			$suggestions[] = array('AIN_ID'=>'12', 'ING_ID'=>$ing_id_oil); //add olive oil
			$suggestions[] = array('AIN_ID'=>'1', 'ING_ID'=>$ing_id); //cut onion (stripes)
			$suggestions[] = array('AIN_ID'=>'2', 'ING_ID'=>$ing_id); //cut onion (slices)
			
			//$suggestions[] = array('AIN_ID'=>'6', 'STE_CELSIUS'=>120, 'STE_RPM'=>100, 'STE_STIR_RUN'=>5, 'STE_STIR_PAUSE'=>20); //headup
			$suggestions[] = array('AIN_ID'=>'6', 'STE_CELSIUS'=>120, 'STE_RPM'=>0, 'STE_STIR_RUN'=>0, 'STE_STIR_PAUSE'=>0); //headup
			//$suggestions[] = array('AIN_ID'=>'5', 'STE_CELSIUS'=>120, 'STE_STEP_DURATION'=>15, 'STE_RPM'=>100, 'STE_STIR_RUN'=>5, 'STE_STIR_PAUSE'=>10); //cook / roast
			$suggestions[] = array('AIN_ID'=>'5', 'STE_CELSIUS'=>120, 'STE_STEP_DURATION'=>15, 'STE_RPM'=>0, 'STE_STIR_RUN'=>0, 'STE_STIR_PAUSE'=>0); //cook / roast
		} else {
			$suggestions[] = array('AIN_ID'=>'11', 'ING_ID'=>$ing_id, 'STE_GRAMS'=>0); //prepare
			$suggestions[] = array('AIN_ID'=>'12', 'ING_ID'=>$ing_id); //add prepared
			$suggestions[] = array('AIN_ID'=>'3', 'ING_ID'=>$ing_id, 'STE_GRAMS'=>0); //weight/add
			$suggestions[] = array('AIN_ID'=>'1', 'ING_ID'=>$ing_id); //cut (stripes)
			$suggestions[] = array('AIN_ID'=>'2', 'ING_ID'=>$ing_id); //cut (slices)
		}
		//TODO: define logic for actionSuggestion
		return array('suggestions'=>$suggestions, 'ingredients'=>$ingredients);
	}

	public function getMostCommon(){
		$mostCommons = array();
		//$mostCommons[] = array('AIN_ID'=>'6', 'STE_CELSIUS'=>100, 'STE_RPM'=>100, 'STE_STIR_RUN'=>5, 'STE_STIR_PAUSE'=>30); //headup
		$mostCommons[] = array('AIN_ID'=>'6', 'STE_CELSIUS'=>100, 'STE_RPM'=>0, 'STE_STIR_RUN'=>0, 'STE_STIR_PAUSE'=>0); //headup
		//$mostCommons[] = array('AIN_ID'=>'5', 'STE_CELSIUS'=>100, 'STE_STEP_DURATION'=>600, 'STE_RPM'=>100, 'STE_STIR_RUN'=>5, 'STE_STIR_PAUSE'=>30); //cook
		$mostCommons[] = array('AIN_ID'=>'5', 'STE_CELSIUS'=>100, 'STE_STEP_DURATION'=>600, 'STE_RPM'=>0, 'STE_STIR_RUN'=>0, 'STE_STIR_PAUSE'=>0); //cook
		//$mostCommons[] = array('AIN_ID'=>'7', 'STE_KPA'=>80, 'STE_STEP_DURATION'=>600, 'STE_RPM'=>100, 'STE_STIR_RUN'=>5, 'STE_STIR_PAUSE'=>30); //press cook
		$mostCommons[] = array('AIN_ID'=>'7', 'STE_KPA'=>80, 'STE_STEP_DURATION'=>600, 'STE_RPM'=>0, 'STE_STIR_RUN'=>0, 'STE_STIR_PAUSE'=>0); //press cook
		$mostCommons[] = array('AIN_ID'=>'10'); //release press
		return $mostCommons;
	}
	

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				$this->loadModel($id)->delete();
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->prepareSearch('search', null, null);
		/*
		$dataProvider=new CActiveDataProvider('Recipes');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Recipes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Recipes']))
			$model->attributes=$_GET['Recipes'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
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
		$command->bindValues($criteria->params);
		return $command;
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
	
	public function actionAutocompleteSolr($query){
		$this->isFancyAjaxRequest = true;
		$oldDebug = $this->debug;
		$this->debug = false;
		
		$criteria = new ASolrCriteria();
		$criteria->query = $query;
		//$criteria->setParam('qt','suggest' . strtolower(Yii::app()->language));
		$criteria->setParam('qt','suggest');
		
		$recipesSolrModel = new RecipesSolr();
		/*$data =*/ $recipesSolrModel->findAll($criteria);
		$response = $recipesSolrModel->getSolrConnection()->getLastQueryResponse();
		$solrObject = $response->getSolrObject();
		
		list($query_suggestions, $query_collations, $correctlySpelled) = $this->parseSpellCheck($solrObject);
		list($suggest_types, $suggest_merge) = $this->parseSuggest($solrObject);
		
		//format and merge lists
		$items = array();
		unset($query_collations['_max']);
// 		foreach ($query_collations as $value=>$freq){
// 			$items[$value] = array('name'=>$value, 'rank'=>$freq, 'src'=>'spell');
// 		}
		$suggest_types['spellCollation'] = $query_collations;
		
		$firstWord = reset($query_suggestions); //get spell suggestions for first word only
		if ($firstWord !== false){
			unset($firstWord['_max']);
			$suggest_types['spellSuggestion'] = $firstWord;
		}
		
		foreach ($suggest_types as $type=>$values){
			if (isset($values)){
				foreach ($values as $value=>$rank){
					$value = preg_replace('/<[^>]*>/', '', $value);
					if(is_null($rank)){
						$rank = 0;
					}
					if (isset($items[$value])){
						$items[$value] = array('name'=>$value, 'rank'=>max($rank, $items[$value]['rank']), 'src'=>$items[$value]['src'] .",".$type);
					} else {
						$items[$value] = array('name'=>$value, 'rank'=>$rank, 'src'=>$type);
					}
				}
			}
		}
		function strrnatcmp($a, $b){
			$comp = strnatcmp($b['rank'], $a['rank']);
			if ($comp == 0){
				$comp = strnatcmp(strtolower($a['name']), strtolower($b['name']));
			}
			return $comp;
		};
		usort ($items, "strrnatcmp");
		
// 		$result = array(
// 			'items'=>$items,
// 		);
		$result = $items;
// 		if ($oldDebug){
// 			$result = array();
// 			foreach($items as $value){
// 				$value['name'] = $value['name'] . '('.  $value['src']  .')'; 
// 				$result[] = $value;			
// 			}
// 		}

		header('Content-type: application/json');
		echo $this->processOutput(CJSON::encode($result));
		Yii::app()->end();
	}
	
	public function actionAutocomplete($query, $page){
		$this->isFancyAjaxRequest = true;
		
		$commandQuery = Yii::app()->db->createCommand()
			->select('("query") as type, RID_TEXT as id, RID_TEXT as name')
			->from('recipe_index_data')
			->where('RID_LANG = :lang AND RID_TEXT LIKE :query',array(':lang'=>Yii::app()->session['lang'], ':query'=>'%'.$query.'%'))
			->order('RID_TEXT, RID_COUNT desc')
			->limit(20, ($page-1)*20);
		$dataQuery = $commandQuery->queryAll();
		
		$command = Yii::app()->db->createCommand()
			->select('("recipe") as type, concat("id:", REC_ID) as id, REC_NAME_' . Yii::app()->session['lang'] . ' as name, REC_SYNONYM_' . Yii::app()->session['lang'] . ' as synonym')
			->from('recipes')
			->where('REC_NAME_' . Yii::app()->session['lang'] . ' LIKE :query OR REC_SYNONYM_' . Yii::app()->session['lang'] . ' LIKE :query2',array(':query'=>'%'.$query.'%', ':query2'=>'%'.$query.'%'))
			//->where('MATCH (REC_NAME_' . Yii::app()->session['lang'] . ', REC_SYNONYM_' . Yii::app()->session['lang'] .') AGAINST (:query IN BOOLEAN MODE)', array(':query'=>$query.'*'))
			->order('REC_NAME_' . Yii::app()->session['lang'] . ', REC_SYNONYM_' . Yii::app()->session['lang'])
			->limit(10, ($page-1)*10);
		$data = $command->queryAll();
		//if ($this->debug){echo $command->text;}
		
		if(count($dataQuery) < 20){
			$total_countQuery = ($page-1)*20 + count($dataQuery);
		} else {
			$commandQuery = Yii::app()->db->createCommand()
				->select('count(*)')
				->from('recipe_index_data')
				->where('RID_LANG = :lang AND RID_TEXT LIKE :query',array(':lang'=>Yii::app()->session['lang'], ':query'=>'%'.$query.'%'));
			$total_countQuery = $commandQuery->queryScalar();
		}
		
		$data = array_merge($dataQuery, $data);
		
		if (count($data) < 10){
			$total_count = ($page-1)*10 + count($data);
		} else {
			$command = Yii::app()->db->createCommand()
				->select('count(*)')
				->from('recipes')
				->where('REC_NAME_' . Yii::app()->session['lang'] . ' LIKE :query OR REC_SYNONYM_' . Yii::app()->session['lang'] . ' LIKE :query2',array(':query'=>'%'.$query.'%', ':query2'=>'%'.$query.'%'));
			$total_count = $command->queryScalar();
		}
		$result = array(
			'total_count'=>$total_countQuery + $total_count,
			'items'=>$data
		);

		header('Content-type: application/json');
		echo $this->processOutput(CJSON::encode($result));
		Yii::app()->end();
	}
	
	public function actionAutocompleteId($ids){
		header('Content-type: application/json');
		$this->isFancyAjaxRequest = true;
		
		$criteria=new CDbCriteria;
		if (strlen($ids)>0){
			$querys = explode(',', $ids);
			$rec_ids = array();
			$result = array();
			foreach($querys as $queryText){
				if (strlen($queryText)>0){
					if (substr($queryText,0,3) == 'id:'){
						$rec_ids[] = substr($queryText,3);
					} else if (substr($queryText,0,2) == 'q:'){
						$result[] = array('id' => substr($queryText,0,2), 'name' => substr($queryText,0,2), 'type'=>'query');
					} else {
						$result[] = array('id' => $queryText, 'name' => $queryText, 'type'=>'query');
					}
				}
			}
			if (count($rec_ids)>0){
				$criteria=new CDbCriteria;
				$criteria->addInCondition('REC_ID',$rec_ids);
				$command = Yii::app()->db->createCommand()
					->select('("recipe") as type, concat("id:", REC_ID) as id, REC_NAME_' . Yii::app()->session['lang'] . ' as name, REC_SYNONYM_' . Yii::app()->session['lang'] . ' as synonym')
					->from('recipes');
				$command->where($criteria->condition, $criteria->params);
				$data = $command->queryAll();
				$result = array_merge($result, $data);
			}
			echo $this->processOutput(CJSON::encode($result));
		} else {
			echo '[]';
		}
		Yii::app()->end();
	}
	
	private function prepareSolrSearch($view, $ajaxLayout, $criteria){
		if(isset($_GET['query'])){
			$query = $_GET['query'];
		} else if(isset($_POST['query'])){
			$query = $_POST['query'];
		} else {
			$query = '';
		}

		$selectedOrderBy = 'score';
		if(isset($_GET['orderby'])){
			$selectedOrderBy = $_GET['orderby'];
		} else if(isset($_POST['orderby'])){
			$selectedOrderBy = $_POST['orderby'];
		}
		
		$sort=new ASolrSort;
		$sort->attributes = array(
			'name' => array(
				'asc' =>'autor asc, REC_NAME_' . Yii::app()->session['lang'] . ' asc',
				'desc'=>'autor asc, REC_NAME_' . Yii::app()->session['lang'] . ' desc',
			),
			'kcal' => array(
				'asc' =>'autor asc, REC_KCAL asc',
				'desc'=>'autor asc, REC_KCAL desc',
			),
			'difficulty' => array(
				'asc' =>'autor asc, DIF_ORDER asc',
				'desc'=>'autor asc, DIF_ORDER desc',
			),
			'preparationTime' => array(
				'asc' =>'autor asc, preparationTime asc',
				'desc'=>'autor asc, preparationTime desc',
			),
			'rating' => array(
				'asc' =>'autor asc, rating asc',
				'desc'=>'autor asc, rating desc',
			),
			'score' => array(
				'asc' =>'autor asc, score asc',
				'desc'=>'autor asc, score desc',
			),
			/*
			// String and array attributes work as usual:
			'title',
			'latest'=>array(
				'asc'=>'created_at asc',
				'desc'=>'created_at desc',
			),
			// Solr parameters can be defined like this:
			'keyword'=>array(
				'asc'=>array(
					// Each of these parameters is set through setParam() in Solr.
					// Make sure you also have a 'sort' parameter here.
					'sortingQ'	=>'{!edismax qf="title^8.0"} '.$keyword,
					'sort'		=>'product(query($sortingQ),scale(stars,1,5)) asc',
				),
				'desc'=>array(
					'sort'		=>'product(query($sortingQ),scale(stars,1,5)) desc',
					'sortingQ'	=>'{!edismax qf="title^8.0"} '.$keyword,
				),
			),
			*/
		);
		//$sort->defaultOrder = 'score';
		$sort->defaultOrder = array(
			 'score'=>CSort::SORT_DESC,
		);
		$activeFacets = array(
			'autor'=>									array('title'=>$this->trans->RECIPES_CHEF, 'type'=>'field', 'multiSelect'=>true),
			'RET_DESC_'.Yii::app()->language=>			array('title'=>$this->trans->RECIPES_TYPE, 'type'=>'field', 'multiSelect'=>true),
			Yii::app()->language.'_ingredients_facet'=>	array('title'=>$this->trans->RECIPES_INGREDIENTS, 'type'=>'field', 'multiSelect'=>true),
			Yii::app()->language.'_cusines_facet'=>		array('title'=>$this->trans->FIELD_CUT_ID, 'type'=>'field', 'multiSelect'=>false),
			'REC_KCAL'=>								array('title'=>'kcal', 'type'=>'range', 'multiSelect'=>true),
		);
		
		$selectedFacets = array();
		$hasAnySelectedFacetValue = false;
		foreach($activeFacets as $name=>$fieldName){
			$values = array();
			if(isset($_GET[$name])){
				$values = $_GET[$name];
			} else if(isset($_POST[$name])){
				$values = $_POST[$name];
			}
			if(count($values)>0){
				$hasAnySelectedFacetValue = true;
			}
			$selectedFacets[$name] = $values;
		}
		
		$Session_RecipeSearch = Yii::app()->session[$this->searchBackupSolr];
		$searchFromSession = false;
		if (isset($Session_RecipeSearch)){
			//if($this->debug){echo "Session_RecipeSearchSolr isset";}
			if ($query == '' && !$hasAnySelectedFacetValue && (!isset($_GET['newSearch']) || $_GET['newSearch'] < $Session_RecipeSearch['time'])){
				$searchFromSession = true;
				if (isset($Session_RecipeSearch['query'])){
					$query = $Session_RecipeSearch['query'];
				}
				if (isset($Session_RecipeSearch['selectedOrderBy'])){
					$selectedOrderBy = $Session_RecipeSearch['selectedOrderBy'];
				}
				if (isset($Session_RecipeSearch['facets'])){
					$selectedFacets = $Session_RecipeSearch['facets'];
				}
			}
		}
		if (!$searchFromSession){
			$Session_RecipeSearch = array();
			$Session_RecipeSearch['query'] = $query;
			$Session_RecipeSearch['selectedOrderBy'] = $selectedOrderBy;
			$Session_RecipeSearch['facets'] = $selectedFacets;
			$Session_RecipeSearch['time'] = time();
			Yii::app()->session[$this->searchBackupSolr] = $Session_RecipeSearch;
		}
		
		$dataProvider = new ASolrDataProvider(RecipesSolr::model(),
				array(
						'pagination'=>array(
								'pageSize'=>10,
						),
						'sort'=> $sort,
				)
		);
		//initialice default Scope:
		$dataProvider->model->getSolrCriteria();
		$criteria = $dataProvider->getCriteria();
		$criteria->query = $query;
		
		//create filter query
		if ($hasAnySelectedFacetValue){
			foreach($selectedFacets as $facet=>$values){
				if (count($values)>0){
					$facetsOptions = $activeFacets[$facet];
					if ($facetsOptions['multiSelect']){
						if ($facetsOptions['type'] == 'field'){
							$values = implode(',', $values);
							//use of localParam tag and ex are to return "other values" on facet and not nothing
							$criteria->addFilterQuery('{!tag='.$facet.'}'.$facet.':'.$values);
							$criteria->addFacetField('{!ex='.$facet.'}'.$facet);
						} else if ($facetsOptions['type'] == 'range' || $facetsOptions['type'] == 'date'){
							$valuesNew = array();
							foreach($values as $value){
								$pos = strpos($value, '-');
								if ($pos === false){
									$valuesNew[] = $facet.':['.$value.' TO *]';
								} else {
									$valuesNew[] = $facet.':['.substr($value, 0, $pos).' TO '.substr($value, $pos+1).']';
								}
							}
							$values = implode(' OR ', $valuesNew);
							//use of localParam tag and ex are to return "other values" on facet and not nothing
							$criteria->addFilterQuery('{!tag='.$facet.'}'.$values);
// 							$criteria->addFacetRangeField('{!ex='.$facet.'}'.$facet); //function addFacetRangeField does not exist..
							$criteria->addParam('facet.'.$facetsOptions['type'], '{!ex='.$facet.'}'.$facet);
						}
					} else {
						if(is_array($values)){
							$values = reset($values);
							$selectedFacets[$facet]=$values;
						}
						$criteria->addFilterQuery($facet.':'.$values);
						$criteria->addParam('facet.'.$facetsOptions['type'], $facet);
					}
				} else {
					$criteria->addFacetField($facet);
				}
			}
		}
		/*
		$criteria->addParam('spellcheck.extendedResults', 'true');
		$criteria->addParam('spellcheck.collateExtendedResults', 'true');
		$criteria->addParam('spellcheck.onlyMorePopular', 'false');
		$criteria->addParam('spellcheck.maxCollations', 10); 
		*/
		
		/*
		echo 'query<br>';
		var_dump($query);
		echo 'dataProvider->getCriteria()->getPreparedParams()<br>';
		var_dump($dataProvider->getCriteria()->getPreparedParams());
		echo 'dataProvider->getCriteria()->getParams())<br>';
		var_dump($dataProvider->getCriteria()->getParams());

		echo 'dataProvider->getData()<br>';
		var_dump($dataProvider->getData(true));
		echo 'dataProvider<br>';
		var_dump($dataProvider);
		die();
		*/
		/*
		echo 'dataProvider->getData()<br>';
		//var_dump($dataProvider->getData(true));
		foreach ($dataProvider->getData(true) as $i=>$recipe){
			echo $i .'<br>';
			var_dump($recipe, );
		}
		die();
		*/
// 		echo 'activeFacets<br>';
// 		var_dump($activeFacets);
// 		echo 'this->getFacets($dataProvider)<br>';
// 		//var_dump($this->getFacets($dataProvider)->toArray());
// 		foreach ($this->getFacets($dataProvider)->toArray() as $name=>$facet){
// 			echo $name .'<br>';
// 			var_dump($facet->toArray());
// 		}
// 		die();
		
		
		$dataProvider->getData(); //fetch data 
		$response = $dataProvider->getSolrQueryResponse();
		$solrObject = $response->getSolrObject();

		if ($this->debug){
			echo "2{}<br>\r\n\r\n";
			echo 'http://localhost:8983/solr/recipes/select?' . $response->criteria->toString();
			echo "<br>\r\n\r\n";
		}
		
		list($query_suggestions, $query_collations, $correctlySpelled) = $this->parseSpellCheck($solrObject);
		
		$this->checkRenderAjax($view,array(
			'query'=>$query,
			'dataProvider'=>$dataProvider,
			'activeFacets'=>$activeFacets,
			'selectedFacets'=>$selectedFacets,
			'facets'=>$this->getFacets($dataProvider),
			'suggestions'=>$query_suggestions,
			'collations'=>$query_collations,
			'sort'=>$sort,
		), $ajaxLayout);
	}
	
	private function getFacets(ASolrDataProvider $dataProvider){
		$allFacets = new CAttributeCollection;
		$allFacets->mergeWith($dataProvider->getDateFacets());
		$allFacets->mergeWith($dataProvider->getFieldFacets());
		$allFacets->mergeWith($dataProvider->getQueryFacets());
		$allFacets->mergeWith($dataProvider->getRangeFacets());
		return $allFacets;
	}
	
	private function parseSpellCheck($solrObject){
		$query_suggestions = array();
		$query_collations = array();
		$correctlySpelled = null;
		if (isset($solrObject->spellcheck)){
			$showDYM=true;
			if (isset($solrObject->spellcheck->correctlySpelled)){
				$correctlySpelled = $solrObject->spellcheck->correctlySpelled; 
				$showDYM = !$correctlySpelled;
			}
			if ($showDYM){
				$spellcheck = $solrObject->spellcheck;
				// 				if ($this->debug){
				// 					var_dump($spellcheck->suggestions);
				// 					var_dump($spellcheck->collations);
				// 				}
				if (isset($spellcheck->suggestions)){
					// 					if(is_array($spellcheck->suggestions)){
					// 						$names = array_keys($spellcheck->suggestions);
					// 					} else {
					// 						$names = $spellcheck->suggestions->getPropertyNames();
					// 					}
					// 					foreach ($names as $name){
					// 						$name = trim($name);
					// 						$obj = $spellcheck->suggestions->$name;
					foreach ($spellcheck->suggestions as $name=>$obj){
						/*
						 $numFound = $obj->numFound; //occurenc in query string
						 $startOffset = $obj->startOffset; //in orig search string
						 $endOffset = $obj->endOffset;//in orig search string
						 */
						if (isset($obj->origFreq)){ //only avail if spellcheck.extendedResults=true
							$origFreq = $obj->origFreq;
						}
						$suggestions = $obj->suggestion;
						$values = array();
						$maxVal=0;
						$maxSuggestion=null;
						foreach($suggestions as $suggestion){
							if (is_object($suggestion)){ //only if spellcheck.extendedResults=true is set
								$freq = $suggestion->freq;
								$suggestion = $suggestion->word;
								if ($freq>$maxVal){
									$maxVal=$freq;
									$maxSuggestion=$suggestion;
								}
							} else {
								$freq = null;
							}
							if ($this->debug){
								echo 'Suggestion for "'.$name.'": '. $suggestion .'('.$freq.")<br>\n";
							}
							$values[$suggestion]=$freq;
						}
						$values['_max']=$maxSuggestion;
						$query_suggestions[$name]=$values;
					}
				}
				if (isset($spellcheck->collations)){
					$maxVal=0;
					$maxCollation=null;
					// 					foreach($spellcheck->collations->getPropertyNames() as $name){
					// 						$name = trim($name);
					// 						$obj = $spellcheck->collations->$name;
					foreach($spellcheck->collations as $name=>$obj){
						if (is_object($obj)){ //only if spellcheck.collateExtendedResults=true is set
							$hits = $obj->hits;
							$collationQuery = $obj->collationQuery;
							//$misspellingsAndCorrections = $obj->misspellingsAndCorrections; //array with changes words: word_in_search=>word_in_collation
							if($hits>$maxVal){
								$maxVal=$hits;
								$maxCollation=$collationQuery;
							}
						} else {
							$hits = null;
							$collationQuery = $obj;
						}
						if ($this->debug){
							echo 'collationQuery: '. $collationQuery .'('.$hits.")<br>\n";
						}
						$query_collations[$collationQuery]=$hits;
					}
					$query_collations['_max']=$maxCollation;
				}
			}
		}
		return array($query_suggestions, $query_collations, $correctlySpelled);
	}
	
	private function parseSuggest($solrObject){
		$suggest_types = array();
		$suggest_merge = array();
		if (isset($solrObject->suggest)){
			$suggest = $solrObject->suggest;
			foreach($suggest as $suggester=>$values){
				$suggestions = array();
				foreach($values as $searchTerm=>$infos){
					if ($infos->numFound>0){
						foreach($infos->suggestions as $index=>$suggestion){
							$suggestions[$suggestion->term] = $suggestion->weight;
						}
					}
				} 
				$suggest_types[$suggester] = $suggestions;
				$suggest_merge = array_merge($suggest_merge, $suggestions);
			}
		}
		return array($suggest_types, $suggest_merge);
	}
	

	private function prepareSearch($view, $ajaxLayout, $criteria)
	{
		$model=new Recipes('search');
		$model->unsetAttributes();  // clear any default values
		
		$modelAvailable = false;
		if(isset($_POST['Recipes'])){
			$model->attributes=$_POST['Recipes'];
			$modelAvailable = true;
		}
		
		$model2 = new SimpleSearchForm();
		if(isset($_POST['SimpleSearchForm']))
			$model2->attributes=$_POST['SimpleSearchForm'];
		
		if(isset($_GET['query'])){
			$query = $_GET['query'];
			$model2->query = $query;
		} else if(isset($_POST['query'])){
			$query = $_POST['query'];
			$model2->query = $query;
		} else {
			$query = $model2->query;
		}
		CDbCriteria::$paramCount = 0;
		$filters = array();
		$filterValues = array();
		$additionalSelect = '';
		$additionalSelectParams = array();
		if ($criteria == null){
			$criteria=new CDbCriteria;
			if (strlen($query)>0){
				$querys = explode(',', $query);
				$rec_ids = array();
				$searches = array();
				foreach($querys as $queryText){
					if (strlen($queryText)>0){
						if (substr($queryText,0,3) == 'id:'){
							$rec_ids[] = substr($queryText,3);
						} else if (substr($queryText,0,2) == 'q:'){
							$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),array('REC_NAME_' . Yii::app()->session['lang']),substr($queryText,2), 'recipes.'); //$model->getSearchFields()
							$searches[] = $criteriaString;
						} else {
							$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),array('REC_NAME_' . Yii::app()->session['lang']),$queryText, 'recipes.'); //$model->getSearchFields()
							$searches[] = $criteriaString;
						}
					}
				}
				$searches2 = array();
				if (count($searches)>0){
					$searches2[] = $this->mergeConditionList($searches, 'AND');
				}
				if (count($rec_ids)>0){
					$criteriaString = $model->commandBuilder->createInCondition($model->tableName(), 'REC_ID', $rec_ids, 'recipes.');
					$searches["id"] = $criteriaString;
					$searches2[] = $criteriaString;
				}
				if (count($searches2)>0){
					$filterValues['recipe']=$searches;
					$criteria->condition = $this->mergeConditionList($searches2);
				}
			}
		}
		
		if(isset($_GET['ing_id'])){
			$ing_id = $_GET['ing_id'];
		} else if(isset($_POST['ing_id'])){
			$ing_id = $_POST['ing_id'];
		}
		if(isset($ing_id) && strlen($ing_id) > 0){
			$filters['ing_id'] = $ing_id;
			$ids = explode(',', $ing_id);
			//$criteria->addInCondition(Steps::model()->tableName().'.ING_ID',$ids);
			
			$idsCount = count($ids);
			$stepModel = new Steps;
			$ingCriteria=new CDbCriteria;
			$ingCriteria->select = 'count(DISTINCT i.ING_ID) as amount'; // $ingModel->getTableSchema()->primaryKey;
			$ingCriteria->join = 'LEFT JOIN ingredients i ON s.ING_ID=i.ING_ID';
			$ingCriteria->condition = 's.REC_ID = recipes.REC_ID';
			$ingCriteria->addInCondition('i.ING_ID',$ids);
			$subQuery=$stepModel->getCommandBuilder()->createFindCommand($stepModel->getTableSchema(),$ingCriteria, 's')->getText();
			
			//$criteria->addCondition('(' . $subQuery . ') = ' . $idsCount);
			$additionalSelect = $additionalSelect . ',(' . $subQuery . ') as ingCount';
			$criteria->select = $criteria->select . ',(' . $subQuery . ') as ingCount';
			$criteria->having=$this->mergeConditions($criteria->having, 'ingCount = ' . $idsCount);
			$criteria->params = array_merge($criteria->params, $ingCriteria->params);
			$additionalSelectParams = array_merge($additionalSelectParams, $ingCriteria->params);
		}else {
			$filters['ing_id'] = '';
		}
		
		if(isset($_GET['ing_id_not'])){
			$ing_id_not = $_GET['ing_id_not'];
		} else if(isset($_POST['ing_id_not'])){
			$ing_id_not = $_POST['ing_id_not'];
		}
		if(isset($ing_id_not) && strlen($ing_id_not) > 0){
			$filters['ing_id_not'] = $ing_id_not;
			$ids = explode(',', $ing_id_not);
			//$criteria->addNotInCondition(Steps::model()->tableName().'.ING_ID',$ids);
			
			$stepModel = new Steps;
			$ingCriteria=new CDbCriteria;
			$ingCriteria->select = 'count(DISTINCT i.ING_ID) as amount'; // $ingModel->getTableSchema()->primaryKey;
			$ingCriteria->join = 'LEFT JOIN ingredients i ON s.ING_ID=i.ING_ID';
			$ingCriteria->condition = 's.REC_ID = recipes.REC_ID';
			$ingCriteria->addInCondition('i.ING_ID',$ids);
			$subQuery=$stepModel->getCommandBuilder()->createFindCommand($stepModel->getTableSchema(),$ingCriteria, 's')->getText();
			
			//$criteria->addCondition('(' . $subQuery . ') = 0');
			$additionalSelect = $additionalSelect . ',(' . $subQuery . ') as ingNotCount';
			$criteria->select = $criteria->select . ',(' . $subQuery . ') as ingNotCount';
			$criteria->having=$this->mergeConditions($criteria->having, 'ingNotCount = 0');
			$criteria->params = array_merge($criteria->params, $ingCriteria->params);
			$additionalSelectParams = array_merge($additionalSelectParams, $ingCriteria->params);
		} else {
			$filters['ing_id_not'] = '';
		}
		$filters['additionalSelect'] = $additionalSelect;
		$filters['additionalSelectParams'] = $additionalSelectParams;
		
		$filters['selectedAutor'] = array();
		$filters['selectedBatches'] = array();
		$filters['selectedTypeOfCusine'] = array();
		$filters['selectedTypes'] = array();
		$selectedOrderBy = 'N';
		
		/*
		if(isset($_GET['autor'])){
			$autor = $_GET['autor'];
		} else if(isset($_POST['autor'])){
			$autor = $_POST['autor'];
		}
		if(isset($autor)){
			//$autor = explode(',', $autor); // as array already
			$filters['selectedAutor'] = $autor;
			//TODO: change to user relativ groups
			$criteria->addInCondition('recipes.CREATED_BY ',$autor);
		}
		if(isset($_GET['batches'])){
			$batches = $_GET['batches'];
		} else if(isset($_POST['batches'])){
			$batches = $_POST['batches'];
		}
		if(isset($batches)){
			//$batches = explode(',', $batches); // as array already
			$filters['selectedBatches'] = $batches;
			//TODO: add Batches Logic
			//$criteria->addInCondition('recipes.REC_BATCHES ',$batches);
		}
		*/
		
		if(isset($_GET['typeOfCusine'])){
			$typeOfCusine = $_GET['typeOfCusine'];
		} else if(isset($_POST['typeOfCusine'])){
			$typeOfCusine = $_POST['typeOfCusine'];
		}
		if(isset($typeOfCusine)){
			//$typeOfCusine = explode(',', $typeOfCusine); // as array already
			$filters['selectedTypeOfCusine'] = $typeOfCusine;
			foreach($typeOfCusine as $key=>$value){
				if ($value === ''){
					$typeOfCusine[$key] = null;
					break;
				}
			}
			$criteria->addInCondition('recipes.CUT_ID ',$typeOfCusine);
			//TODO concatinate with OR, not the complete existing condition but the above and the next one
			//$criteria->addInCondition('recipes.CST_ID ',$typeOfCusine, 'OR');
		}
		
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		} else if(isset($_POST['type'])){
			$type = $_POST['type'];
		}
		if(isset($type)){
			//$type = explode(',', $type); // as array already
			$filters['selectedTypes'] = $type;
			$criteria->addInCondition('recipes.RET_ID ',$type);
		}
		
		if(isset($_GET['orderby'])){
			$selectedOrderBy = $_GET['orderby'];
		} else if(isset($_POST['orderby'])){
			$selectedOrderBy = $_POST['orderby'];
		}
		
// 		if ($this->debug) {
// 			echo "before session check \r\n";
// 			var_dump($criteria->params);
// 		}
		
		$Session_RecipeSearch = Yii::app()->session[$this->searchBackup];
		$searchFromSession = false;
		if (isset($Session_RecipeSearch)){
			if($this->debug){echo "Session_RecipeSearch isset";}
			if ($criteria->condition == '' && $criteria->having == '' && (!isset($_GET['newSearch']) || $_GET['newSearch'] < $Session_RecipeSearch['time'])){
				if($this->debug){echo "CDbCriteria::paramCount is: " .CDbCriteria::$paramCount;}
				//CDbCriteria::$paramCount = 0;
				$searchFromSession = true;
				if (isset($Session_RecipeSearch['query'])){
					$query = $Session_RecipeSearch['query'];
					$model2->query = $query;
				}
				$criteriaLoaded = false;
				if (isset($Session_RecipeSearch['criteria'])){
					$criteria = $Session_RecipeSearch['criteria'];
					$criteriaLoaded = true;
				}
				
				if (isset($Session_RecipeSearch['selectedOrderBy'])){
					$selectedOrderBy = $Session_RecipeSearch['selectedOrderBy'];
				}
				if (isset($Session_RecipeSearch['filters'])){
					$filters = $Session_RecipeSearch['filters'];
					$additionalSelect = $filters['additionalSelect'];
					$additionalSelectParams = $filters['additionalSelectParams'];
					if (!$criteriaLoaded){
						$criteria->params = array_merge($criteria->params, $additionalSelectParams);
// 						if ($this->debug) {
// 							echo 'change CDbCriteria::paramCount, adding ' .count($additionalSelectParams) . '<br />';
// 						}
						CDbCriteria::$paramCount+=count($additionalSelectParams);
					}
				}
			}
		}
		if (!$searchFromSession){
			$Session_RecipeSearch = array();
			$Session_RecipeSearch['query'] = $query;
			$Session_RecipeSearch['criteria'] = $criteria;
			$Session_RecipeSearch['selectedOrderBy'] = $selectedOrderBy;
			$Session_RecipeSearch['filters'] = $filters;
			$Session_RecipeSearch['time'] = time();
			Yii::app()->session[$this->searchBackup] = $Session_RecipeSearch;
		}
		
		//Additional display criterias
		$criteriaDisplay=new CDbCriteria;
		$criteriaDisplay->join = '';
		$criteriaDisplay->mergeWith($criteria);
		$criteriaDisplay->mergeWith(array(
			'join'=>'LEFT JOIN cusine_types cut ON cut.CUT_ID = recipes.CUT_ID'.
			' LEFT JOIN cusine_sub_types cst ON cst.CST_ID = recipes.CST_ID'.
			' LEFT JOIN cusine_sub_sub_types css ON css.CSS_ID = recipes.CSS_ID'.
			' LEFT JOIN recipe_types ret ON recipes.RET_ID=ret.RET_ID'.
			' LEFT JOIN professional_profiles prf ON recipes.PRF_UID=prf.PRF_UID'
		));
		$criteriaDisplay->select = $criteriaDisplay->select . ', cut.CUT_ID, cst.CST_ID, css.CSS_ID, cut.CUT_IMG_ETAG, cst.CST_IMG_ETAG, css.CSS_IMG_ETAG, cut.CUT_DESC_' . Yii::app()->session['lang'] . ' as CUT_DESC, cst.CST_DESC_' . Yii::app()->session['lang'] . ' as CST_DESC, css.CSS_DESC_' . Yii::app()->session['lang'] . ' as CSS_DESC, prf.*, (CASE WHEN recipes.PRF_UID IS NOT NULL THEN 1 ELSE 0 END) AS pro';
		
		
		$orderByKeyToField = array('N'=>'REC_NAME_' . Yii::app()->session['lang'],'n'=>'REC_NAME_' . Yii::app()->session['lang'] . ' DESC','K'=>'REC_KCAL','k'=>'REC_KCAL DESC','C'=>'REC_COMPLEXITY','c'=>'REC_COMPLEXITY DESC'/*,'P'=>'PreparationTime','R'=>'Rating',''=>'',*/);
		if (isset($orderByKeyToField[$selectedOrderBy])){
			$criteriaDisplay->order = 'pro DESC, ' . $orderByKeyToField[$selectedOrderBy];
		} else {
			$criteriaDisplay->order = 'pro DESC, ' . 'REC_NAME_' . Yii::app()->session['lang'];
		}
		if ($this->debug) {
			echo 'before run<br/>';
			var_dump($criteria->params);
// 			if (CDbCriteria::$paramCount == 2){
// 				die();
// 			}
// 			die();
		}
		
		$command = $this->criteriaToCommand($criteriaDisplay);
		$rows = $command->queryAll();
		
		if ($this->debug) {
			echo $command->text .'<br/>';
			var_dump($criteria->params);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'REC_ID',
			'keyField'=>'REC_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		if ($view == 'search'){
			if (isset($command)){
				//TypeOfCusine
				$criteriaTypeOfCusine=new CDbCriteria;
				$criteriaTypeOfCusine->join = 'LEFT JOIN cusine_types cut ON cut.CUT_ID = recipes.CUT_ID';
				$criteriaTypeOfCusine->params = $additionalSelectParams;
				$criteriaTypeOfCusine->mergeWith($criteria);
				//unset($criteriaTypeOfCusine->having);
				$criteriaTypeOfCusine->distinct = true;
				$criteriaTypeOfCusine->select = 'cut.CUT_ID,cut.CUT_IMG_ETAG,cut.CUT_DESC_' . Yii::app()->session['lang'] . $additionalSelect;
				$commandTypeOfCusine = $this->criteriaToCommand($criteriaTypeOfCusine);
				$commandTypeOfCusine->bindValues($criteriaTypeOfCusine->params);
				$typeOfCusine = $commandTypeOfCusine->queryAll();
				if ($this->debug) {
					echo $commandTypeOfCusine->text . '<br>';
					var_dump($criteriaTypeOfCusine->params);
				}
				$typeOfCusineList = array();
				$url = $this->createUrl("savedImage/cusineTypes");
				foreach ($typeOfCusine as $row){
					if(isset($row['CUT_IMG_ETAG']) && $row['CUT_IMG_ETAG'] != ''){
						$value = CHtml::image($url . '/' . $row['CUT_ID'] . '.png',  $row['CUT_DESC_' . Yii::app()->session['lang']], array('class'=>'cusineImg')) . $row['CUT_DESC_'.Yii::app()->session['lang']];
					} else  {
						$value = $row['CUT_DESC_'.Yii::app()->session['lang']];
					}
					$typeOfCusineList[$row['CUT_ID']] = $value;
				}
				if(array_key_exists(null,$typeOfCusineList)){
					$typeOfCusineList[null] = $this->trans->GENERAL_UNDEFINED;
				}
				$filters['possibleTypeOfCusine'] = $typeOfCusineList;
				
				//Type
				$criteriaType=new CDbCriteria;
				$criteriaType->join = 'LEFT JOIN recipe_types ret ON recipes.RET_ID=ret.RET_ID';
				$criteriaType->params = $additionalSelectParams;
				$criteriaType->mergeWith($criteria);
				//unset($criteriaType->having);
				$criteriaType->distinct = true;
				$criteriaType->select = 'ret.RET_ID,ret.RET_DESC_' . Yii::app()->session['lang'] . $additionalSelect;
				$commandType = $this->criteriaToCommand($criteriaType);
				$commandType->bindValues($criteriaType->params);
				$type = $commandType->queryAll();
				if ($this->debug) {
					echo $commandType->text . '<br>';
					var_dump($criteriaType->params);
				}
				$type = CHtml::listData($type,'RET_ID','RET_DESC_'.Yii::app()->session['lang']);
				if(array_key_exists(null,$type)){
					$type[null] = $this->trans->GENERAL_UNDEFINED;
				}
				$filters['possibleTypes'] = $type;
				
			} else {
				$commandTypeOfCusine = Yii::app()->db->createCommand()
					->select('DISTINCT CUT_ID,CUT_DESC_' . Yii::app()->session['lang'])
					->from('cusine_types');
				$typeOfCusine = $commandTypeOfCusine->queryAll();
//				echo $commandTypeOfCusine->text . '<br>';
				$typeOfCusine = CHtml::listData($typeOfCusine,'CUT_ID','CUT_DESC_'.Yii::app()->session['lang']);
				if(array_key_exists(null,$typeOfCusine)){
					$typeOfCusine[null] = $this->trans->GENERAL_UNDEFINED;
				}
				$filters['possibleTypeOfCusine'] = $typeOfCusine;
			}
			
			$possibleAutor = array('5'=>'Professionals','2'=>'MyFriends','3'=>'MyFamily','O'=>'other users');
			$possibleBatches = array('1'=>'Vetegarian');
			$possibleTypeOfCusine = array('P'=>'Professionals','2'=>'MyFriends','3'=>'MyFamily','O'=>'other users');
			
			$filters['possibleAutor'] = $possibleAutor;
			$filters['possibleBatches'] = $possibleBatches;
			
			$possibleOrderBys = array('N'=>$this->trans->RECIPES_ORDER_REC_NAME,'n'=>$this->trans->RECIPES_ORDER_REC_NAME_DESC,'K'=>$this->trans->RECIPES_ORDER_REC_KCAL,'k'=>$this->trans->RECIPES_ORDER_REC_KCAL_DESC,'C'=>$this->trans->RECIPES_ORDER_REC_COMPLEXITY,'c'=>$this->trans->RECIPES_ORDER_REC_COMPLEXITY_DESC/*,'P'=>'PreparationTime','R'=>'Rating',''=>'',*/);
			
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
				'filters'=>$filters,
				'selectedOrderBy'=>$selectedOrderBy,
				'possibleOrderBys'=>$possibleOrderBys
				), $ajaxLayout);
		} else {
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
				), $ajaxLayout);
		}
	}
	/*
	public function actionAdvanceSearch()
	{
		$this->prepareSearch('advanceSearch', null, null);
	}
	*/
	public function actionSearch()
	{
		if (isset($_GET['setSolr'])){
			Yii::app()->session['solrSearch'] = $_GET['setSolr'];
		}
		if (isset(Yii::app()->session['solrSearch']) && Yii::app()->session['solrSearch']){
			$this->prepareSolrSearch('searchSolr', null, null);
		} else {
			$this->prepareSearch('search', null, null);
		}
	}
	
	public function actionChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none', null);
	}
	/*
	public function actionAdvanceChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none', null);
	}
	*/
	
	public function actionChooseTemplateRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->isTemplateChoose = true;
		$this->prepareSearch('search', 'none', null);
	}
	/*
	public function actionAdvanceChooseTemplateRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->isTemplateChoose = true;
		$this->prepareSearch('advanceSearch', 'none', null);
	}
	*/
	
	public function actionSearchFridge(){
		if(!isset($_GET['query'])){
			$this->prepareSearch('search', null, null);
			return;
		}
		$query = $_GET['query'];
		unset($_GET['query']);
		$command = Yii::app()->db->createCommand()
			->select('ING_ID')
			->from('ingredients')
			->where('ING_NAME_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'));
		$ids = $command->queryColumn();
		
		$criteria=new CDbCriteria;
		$criteria->compare(Steps::model()->tableName().'.ING_ID',$ids);
		
		$this->prepareSearch('search', null, $criteria);
	}
	
	public function actionShowLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_LIKES_R')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Recipes::model()->tableName().'.REC_ID',$ids);
		
		$layout = null;
		if(isset($_GET['layout']) && strlen($_GET['layout'])>0){
			$layout = $_GET['layout'];
		}
		$this->prepareSearch('like', $layout, $criteria);
	}
	public function actionShowNotLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_NOTLIKES_R')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Recipes::model()->tableName().'.REC_ID',$ids);
		
		$layout = null;
		if(isset($_GET['layout']) && strlen($_GET['layout'])>0){
			$layout = $_GET['layout'];
		}
		$this->prepareSearch('like', $layout, $criteria);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session[$this->createBackup];
		} else {
			$model=Recipes::model()->findByPk($id);
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='recipes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionDisplaySavedImage($id, $ext)
	{
		if (isset($_GET['size'])) {
			$size = $_GET['size'];
		} else {
			$size = 0;
		}
		$this->saveLastAction = false;
		$model=$this->loadModel($id);
		$modified = $model->CHANGED_ON;
		if (!isset($modified)){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model->REC_IMG_ETAG, $model->REC_IMG_FILENAME, $id, 'Recipes', $size);
	}
	
	public function actionDelicious($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'R', true);
		$this->showLastAction();
	}
	
	public function actionDisgusting($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'R', false);
		$this->showLastAction();
	}
	public function actionHide($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'RH', false);
		$this->showLastAction();
	}
	
	
	/**
	 * Check if there are unsaved Changes
	 * @param integer the ID of the model to be loaded
	 */
	public function checkUnsavedChanges($id)
	{
		//TODO check if there are changes for the current $id.
		return false;
	}

	/**
	 * apply the unsaved Changes to the new loaded model
	 * @param integer the ID of the model to be loaded
	 */
	public function applyUnsavedChanges($id)
	{
		//TODO check loop thrue all changes for the current $id and apply them.
		return false;
	}
	
	
	/**
	 * reactivate history version of recipe
	 * @param integer the ID of the model to change
	 * @param integer the time of the history entry
	 */
	public function actionSetHistoryVersion($id, $changeTime){
		
	}
	
	
	/**
	 * show history of recipe
	 * @param integer the ID of the model to show history of
	 */
	public function actionHistory($id){
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
			unset(Yii::app()->session[$this->searchBackupSolr]);
		}
		$model = $this->loadModel($id);
		$history = Yii::app()->db->createCommand()->select('REC_ID,REC_NAME_'.Yii::app()->session['lang'].',REC_SUMMARY,CHANGED_BY,CHANGED_ON')
			->from('recipes_history')
			->where('REC_ID = :id', array(':id'=>$id))
			->order('CHANGED_ON desc')->queryAll();
		
		$this->checkRenderAjax('history',array(
			'model'=>$model,
			'history'=>$history
		));
	}
	
	private function stepToStr($i, $step){
		$cookin = '#cookin';
		/*
		$result = $step['AIN_ID'] . ',' . 
			$step['ING_ID'] . ',' . 
			$step['STE_GRAMS'] . ',' . 
			$step['STE_CELSIUS'] . ',' . 
			$step['STE_KPA'] . ',' . 
			$step['STE_RPM'] . ',' . 
			$step['STE_CLOCKWISE'] . ',' . 
			$step['STE_STIR_RUN'] . ',' . 
			$step['STE_STIR_PAUSE'] . ',' . 
			$step['STE_STEP_DURATION'] . ',' . 
			$step['TOO_ID'];
		return $result;
		*/
		$result = '<div class="step">';
		//$result .= '<span class="stepNo">' . $i . '.</span> ';
		$text = $step->getAsHTMLString($cookin);
		$result .= '<span class="action">' . $text . '</span>';
		/*
		if (isset($step->actionIn) && $step->actionIn != null){
			$text = $step->actionIn->__get('AIN_DESC_' . Yii::app()->session['lang']);
			if (isset($step->ingredient) && $step->ingredient != null){
				$replText = '<span class="ingredient">' . $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']) . '</span> ';
				$text = str_replace('#ingredient', $replText, $text);
			}
			if ($step->STE_GRAMS){
				$replText = '<span class="weight">' . $step->STE_GRAMS . 'g</span> ';
				$text = str_replace('#weight', $replText, $text);
			}
			
			if (isset($step->tool) && $step->tool != null){
				$replText = '<span class="tool">' . $step->tool->__get('TOO_DESC_' . Yii::app()->session['lang']) . '</span> ';
				$text = str_replace('#tool', $replText, $text);
			}
			if ($step->STE_STEP_DURATION){
				$time = date('H:i:s', $step['STE_STEP_DURATION']-3600);
				$replText = '<span class="time">' . $time . 'h</span> ';
				$text = str_replace('#time', $replText, $text);
			}
			if ($step->STE_CELSIUS){
				$replText = '<span class="temp">' . $step->STE_CELSIUS . '°C</span> ';
				$text = str_replace('#temp', $replText, $text);
			}
			if ($step->STE_KPA){
				$replText = '<span class="pressure">' . $step->STE_KPA . 'kpa</span> ';
				$text = str_replace('#press', $replText, $text);
			}
			if ($cookin != "#cookin"){
				$replText = '<span class="cookin">' . $cookin . '</span> ';
				$text = str_replace('#cookin', $replText, $text);
			}
			$result .= '<span class="action">' . $text . '</span>';
		}
		*/
		$result .= '</div>';
		return $result;
	}
	
	private function getChangeClass(&$alignIndex, $leftIndex, $rightIndex, &$movedUp, &$skipped){
		if ($leftIndex==$rightIndex+$alignIndex){
			$changeSign = '';
			if ($alignIndex>0){
				$alignIndex--;
			}
		} else {
			if ($leftIndex<$rightIndex+$alignIndex) {
				$changeSign = ' change_up';
				$moved = $leftIndex-$rightIndex+$alignIndex;
				//if ($this->debug) {echo $rightIndex . ', up:' . $moved . ',' . $alignIndex  . "<br>\n";}
				//$movedUp[$rightIndex] = $moved;
				
				if ($moved != 0){
					if ($moved != -1){//if not exactly one row
						$movedUp[$rightIndex] = $moved;
					}
					$alignIndex++;
				}
				if ($this->debug) {echo $rightIndex . ', up:' . $moved . ',' . $alignIndex  . "<br>\n";}
				
			} else {
				$changeSign = ' change_down';
				$moved = $rightIndex+$alignIndex-$leftIndex;
				//if ($this->debug) {echo $rightIndex . ', down:' . $moved . ',' . $alignIndex  . "<br>\n";}
				//$movedUp[$rightIndex] = $moved;
				
				if (!isset($skipped[$rightIndex])){//not change alignIndex if it is a removed line now new added as moved down
					if ($moved != 0){
						if ($moved != 1){//if not exactly one row
							$movedUp[$rightIndex] = $moved;
						}
						$alignIndex--;
					}
					if ($this->debug) {echo $rightIndex . ', down:' . $moved . ',' . $alignIndex  . "<br>\n";}
				}
			}
		}
		return $changeSign;
	}
	
	private function fixMoveUp(&$alignIndex, $leftIndex, &$movedUp){
		//return;
		//not needed if call updateChangeClass at end of loop (will automatically fixed there)
		if (isset($movedUp[$leftIndex])){
			if ($movedUp[$leftIndex]>0){
				$alignIndex--;
			} else {
				$alignIndex++;
			}
			if ($this->debug) {echo $leftIndex . ', movedUp:' . $movedUp[$leftIndex] . ',' . $alignIndex  . "<br>\n";}
			//unset($movedUp[$leftIndex]);
		}
	}
	
	private function fixMoveDown(&$alignIndex, $leftIndex, $rightIndex, &$movedUp){
		return;
		//not needed if call updateChangeClass at end of loop (will automatically fixed there)
		if (isset($movedUp[$rightIndex])){
			if ($movedUp[$rightIndex]>0){
				$alignIndex--;
			} else {
				$alignIndex++;
			}
			if ($this->debug) {echo $rightIndex . ', movedDown:' . $movedUp[$rightIndex] . ',' . $alignIndex  . "<br>\n";}
			//unset($movedUp[$rightIndex]);
		}
	}
	
	private function checkSkipedLine(&$alignIndex, $leftIndex, $rightIndex, &$skipped, &$changes, $rightLastIndex, $rightSteps){
		if ($rightLastIndex<$rightIndex){
			for($k=$rightLastIndex;$k<$rightIndex;$k++){
				if (isset($rightSteps[$k])){
					$changes[] = array(' change_remove', (($this->debug)?'3 ':'') . 'Step -', '' . (($this->debug)?'('.$alignIndex.')':''), $rightSteps[$k] . ' (Step '.($k+1).')', -1, $k, $alignIndex);
					//unset($rightSteps[$k]); // remove right step, so not reference 2 times
					$skipped[$k] = count($changes)-1;
					//need for remove, but movedown don't work...//$alignIndex--;
					if ($this->debug) {echo $k . ', remove:' . 1 . ',' . $alignIndex  . "<br>\n";}
				}
			}
		}
	}
	
	private function removeSkipedLine(&$alignIndex, $leftIndex, $rightIndex, &$movedUp, &$skipped, &$changes){
		$skipIndex = $rightIndex;
		if (isset($skipped[$skipIndex])){
			$changeIndex=$skipped[$skipIndex];
			$oldAlignIndex = $changes[$changeIndex][6];
			//need for remove, but movedown don't work...//$alignIndex++;
			if ($this->debug) {echo $leftIndex . '-' . $skipIndex . ', skipped:' . -1 . ',' . $alignIndex  . "<br>\n";}
			if ($this->debug) {
				$changes[$changeIndex][1] = $changes[$changeIndex][1] . ' (removed step'.($leftIndex+1).')';
			} else {
				unset($changes[$changeIndex]);
			}
			unset($skipped[$skipIndex]);
			//fix alignIndex
			for ($i=$changeIndex+1;$i<count($changes);$i++){
				if ($changes[$i][4]>=$leftIndex){ //change all where left step grater than new step line index
					$changes[$i][6] = $changes[$i][6]+1;
				} else if ($changes[$i][4]>=$rightIndex && $changes[$i][5]>=$rightIndex){ //change all where left&right index is greater then the removed right line index
					//Perhaps fix (1st part) for => http://localhost/EveryCook/#recipes/historyCompare/8?leftVersion=1414287552&rightVersion=1414273740
					$changes[$i][6] = $changes[$i][6]-2;
				}
				
				//$changes[$i][6] = $changes[$i][6]+1;
				/*
				if ($oldAlignIndex!=0){
					if ($changes[$i][6]>=$oldAlignIndex){
						$changes[$i][6] = $changes[$i][6]-$oldAlignIndex;
					} else {
					//	$changes[$i][6] = $changes[$i][6]+1;
					}
				}
				*/
			}
		}
	}
	
	private function updateChangeClass($stepStartIndex, &$changes, $skipped, $movedUp){
		/*//doesn't do anything
		foreach ($movedUp as $rightIndex=>$amount){
			for($i=$stepStartIndex;$i<count($changes);$i++){
				if ($changes[$i][5] == $rightIndex){
					break;
				}
				if ($changes[$i][4]>=$rightIndex){
					$changes[$i][6] = $changes[$i][6]+$amount+$changes[$i][4];
					echo "hello world<br>\n";
				}
			}
		}
		*/
		
		foreach ($skipped as $rightIndex=>$changeIndex){
			for($i=$changeIndex+1;$i<count($changes);$i++){
				$changes[$i][6] = $changes[$i][6]-2;
			}
		}
		
		
		for($i=$stepStartIndex;$i<count($changes);$i++){
			if (!isset($changes[$i])){
				continue;
			}
			$leftIndex=$changes[$i][4];
			$rightIndex	=$changes[$i][5];
			$alignIndex=$changes[$i][6];
			/* // try to remove "$alignIndex--;" in getChangeClass if it's "same" step
			if ($alignIndex>$leftIndex){
				$alignIndex=0;
				$changes[$i][6]=$alignIndex;
			}
			*/
			/*
			if (isset($movedUp[$leftIndex])){
				$alignIndex = $alignIndex+$movedUp[$leftIndex];
				$changes[$i][6] = $alignIndex;
			}
			*/
			
			if ($leftIndex != -1){ //removed line
				if ($rightIndex != -1){ // added line
					if ($leftIndex==$rightIndex+$alignIndex){
						$changeSign = '';
					} else {
						if ($leftIndex<$rightIndex+$alignIndex) {
							$changeSign = ' change_up';
						} else {
							$changeSign = ' change_down';
						}
					}
					if ($changes[$i][0] != $changeSign){
						if ($this->debug) {echo $leftIndex . ', class:' . $changes[$i][0] . ' =&gt;' . $changeSign  . "<br>\n";}
						$changes[$i][0] = $changeSign;
					}
				}
			}
		}
	}
	
	/**
	 * show history of recipe
	 * @param integer the ID of the model to show history of
	 */
	public function actionHistoryCompare($id){
		$model = $this->loadModel($id);
		if (isset($_GET['leftVersion'])){
			$leftVersion = $_GET['leftVersion'];
		} else {
			//TODO: add message leftVersion missing
			$this->actionHistory($id);
			return;
		}
		if (isset($_GET['rightVersion'])){
			$rightVersion = $_GET['rightVersion'];
		} else {
			//TODO: add message rightVersion missing
			$this->actionHistory($id);
			return;
		}
		
		$left=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$leftVersion));
		if ($left == NULL){
			//TODO: add message left not found
			$this->actionHistory($id);
			return;
		}
		$right=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$rightVersion));
		if ($left == NULL){
			//TODO: add message right not found
			$this->actionHistory($id);
			return;
		}
		
		$changes = array();
		//$changes[] = array('', $model->getAttributeLabel('REC_IMG_FILENAME'), $left->REC_IMG_FILENAME,$right->REC_IMG_FILENAME);
		//$changes[] = array('', $model->getAttributeLabel('REC_IMG_AUTH'), $left->REC_IMG_AUTH,$right->REC_IMG_AUTH);
		//$changes[] = array('', $model->getAttributeLabel('REC_IMG_ETAG'), $left->REC_IMG_ETAG,$right->REC_IMG_ETAG);
		$changes[] = array('', $model->getAttributeLabel('RET_ID'), $left->RET_ID,$right->RET_ID);
		$changes[] = array('', $model->getAttributeLabel('REC_KCAL'), $left->REC_KCAL,$right->REC_KCAL);
		//$changes[] = array('', $model->getAttributeLabel('REC_HAS_ALLERGY_INFO'), $left->REC_HAS_ALLERGY_INFO,$right->REC_HAS_ALLERGY_INFO);
		$changes[] = array('', $model->getAttributeLabel('REC_SUMMARY'), $left->REC_SUMMARY,$right->REC_SUMMARY);
		$changes[] = array('', $model->getAttributeLabel('REC_APPROVED'), $left->REC_APPROVED,$right->REC_APPROVED);
		$changes[] = array('', $model->getAttributeLabel('REC_SERVING_COUNT'), $left->REC_SERVING_COUNT,$right->REC_SERVING_COUNT);
		$changes[] = array('', $model->getAttributeLabel('REC_WIKI_LINK'), $left->REC_WIKI_LINK,$right->REC_WIKI_LINK);
		$changes[] = array('', $model->getAttributeLabel('REC_IS_PRIVATE'), $left->REC_IS_PRIVATE,$right->REC_IS_PRIVATE);
		$changes[] = array('', $model->getAttributeLabel('DIF_ID'), $left->DIF_ID,$right->DIF_ID);
		$changes[] = array('', $model->getAttributeLabel('CUT_ID'), $left->CUT_ID,$right->CUT_ID);
		$changes[] = array('', $model->getAttributeLabel('CST_ID'), $left->CST_ID,$right->CST_ID);
		$changes[] = array('', $model->getAttributeLabel('REC_CUSINE_GPS_LAT'), $left->REC_CUSINE_GPS_LAT,$right->REC_CUSINE_GPS_LAT);
		$changes[] = array('', $model->getAttributeLabel('REC_CUSINE_GPS_LNG'), $left->REC_CUSINE_GPS_LNG,$right->REC_CUSINE_GPS_LNG);
		$changes[] = array('', $model->getAttributeLabel('REC_TOOLS'), $left->REC_TOOLS,$right->REC_TOOLS);
		foreach($this->allLanguages as $lang=>$name){
			$fieldName='REC_NAME_'.strtoupper($lang);
			$changes[] = array(' ', $model->getAttributeLabel($fieldName), $left->__get($fieldName),$right->__get($fieldName));
		}
		foreach($this->allLanguages as $lang=>$name){
			$fieldName='REC_SYNONYM_'.strtoupper($lang);
			$changes[] = array(' ', $model->getAttributeLabel($fieldName), $left->__get($fieldName),$right->__get($fieldName));
		}
		
		$length = count($changes);
		for($i=0;$i<$length;$i++){
			$change = $changes[$i];
			if ($change[2] != $change[3]) {
				if ($change[2] == '' || $change[2] == null || !isset($change[2])){
					$change[0] = ' change_red';
				} else if ($change[3] == '' || $change[3] == null || !isset($change[3])){
					$change[0] = ' change_green';
				} else {
					$change[0] = ' change_change';
				}
				$changes[$i] = $change;
			}
		}
		
		//check steps
		$stepStartIndex = count($changes);
		
		$leftSteps = array();
		foreach($left->steps as $i=>$step){
			$leftSteps[] = $this->stepToStr($i, $step);
		}
		$rightSteps = array();
		foreach($right->steps as $i=>$step){
			$rightSteps[] = $this->stepToStr($i, $step);
		}
		$leftCount = count($leftSteps);
		$rightCount = count($rightSteps);
		//$rightLastIndex=-1; //complex logic
		$rightLastIndex=0;
		$skipped = array();
		$added = array();
		$movedUp = array();
		$alignIndex = 0;
		foreach($leftSteps as $i=>$leftStep){
			$found=false;
			/* complex logic, showing move up/down changes
			$this->fixMoveUp($alignIndex, $i, $movedUp);
			if (!$found && isset($rightSteps[$i-$alignIndex])){
				$j=$i-$alignIndex;
				if ($leftStep == $rightSteps[$j]){
					//$this->addChange($alignIndex, $i, $j, $skipped, $changes, $rightLastIndex, $rightSteps, $changes,    $leftStep, $rightSteps[$j], 1);
					$changeSign = $this->getChangeClass($alignIndex,$i,$j,$movedUp,$skipped);
					$changes[] = array($changeSign, (($this->debug)?'1 ':'') . 'Step ' . ($i+1), $leftStep . (($this->debug)?'('.$alignIndex.')':''), $rightSteps[$j] . ' (Step '.($j+1).')', $i, $j, $alignIndex);
					$this->removeSkipedLine($alignIndex, $i, $j, $movedUp, $skipped, $changes);
					unset($rightSteps[$j]); // remove right step, so not reference 2 times
					$this->fixMoveDown($alignIndex, $i, $j, $movedUp);
					
					$found = true;
				}
			}
			if (!$found){
				foreach($rightSteps as $j=>$rightStep){
					if ($leftStep == $rightStep){
						$this->checkSkipedLine($alignIndex, $i, $j, $skipped, $changes, $rightLastIndex, $rightSteps);
						$rightLastIndex=$j;
						
						//$this->addChange($alignIndex, $i, $j, $skipped, $changes, $rightLastIndex, $rightSteps, $changes,    $leftStep, $rightStep, 4);
						$changeSign = $this->getChangeClass($alignIndex,$i,$j,$movedUp,$skipped);
						$changes[] = array($changeSign, (($this->debug)?'4 ':'') . 'Step ' . ($i+1), $leftStep . (($this->debug)?'('.$alignIndex.')':''), $rightStep . ' (Step '.($j+1).')', $i, $j, $alignIndex);
						$this->removeSkipedLine($alignIndex, $i, $j, $movedUp, $skipped, $changes);
						unset($rightSteps[$j]); // remove right step, so not reference 2 times
						$this->fixMoveDown($alignIndex, $i, $j, $movedUp);
						
						$found = true;
						break;
					}
				}
			}
			*/
			//simple logic only add & remove
			for ($j=$rightLastIndex; $j<$rightCount; $j++){
				$rightStep = $rightSteps[$j];
				if ($leftStep == $rightStep){
					$this->checkSkipedLine($alignIndex, $i, $j, $skipped, $changes, $rightLastIndex, $rightSteps);
					$rightLastIndex=$j+1;
					
					//$this->addChange($alignIndex, $i, $j, $skipped, $changes, $rightLastIndex, $rightSteps, $changes,    $leftStep, $rightStep, 4);
					$changeSign = '';
					$changes[] = array($changeSign, (($this->debug)?'4 ':'') . 'Step ' . ($i+1), $leftStep . (($this->debug)?'('.$alignIndex.')':''), $rightStep . ' (Step '.($j+1).')', $i, $j, $alignIndex);
					unset($rightSteps[$j]); // remove right step, so not reference 2 times
					$found = true;
					break;
				}
			}
			
			if (!$found){
				$changes[] = array(' change_add', (($this->debug)?'5 ':'') . 'Step ' . ($i+1), $leftStep . (($this->debug)?'('.$alignIndex.')':''), '', $i, -1, $alignIndex);
				$added[$i] = count($changes)-1;
			}
		}
		foreach($rightSteps as $k=>$skip){
			if (!isset($skipped[$k])){
				$changes[] = array(' change_remove', (($this->debug)?'6 ':'') . 'Step -', '', $skip . ' (Step '.($k+1).')', -1, $k, $alignIndex);
				$skipped[$k] = count($changes)-1;
			}
		}
		//$this->updateChangeClass($stepStartIndex, $changes, $skipped, $movedUp);
		
		/*
		echo "<pre>\n";
		var_dump($changes);
		echo "</pre>\n";
		return;
		*/
		/*
		$maxCount = ($leftCount>$rightCount)?$leftCount:$rightCount;
		for($i=0;$i<$maxCount;$i++){
			if ($leftCount>$i){
				$leftStepLine = $this->stepToStr($left->steps[$i]);
			} else {
				$leftStepLine = '';
			}
			if ($rightCount>$i){
				$rightStepLine = $this->stepToStr($right->steps[$i]);
			} else {
				$rightStepLine = '';
			}
			$changes[] = array(' ', 'Step ' . ($i+1), $leftStepLine, $rightStepLine);
		}
		
		$stepLength = count($changes);
		for($i=$stepStartIndex;$i<$stepLength;$i++){
			$change = $changes[$i];
			if ($change[2] != $change[3]) {
				if ($change[2] == '' || $change[2] == null || !isset($change[2])){
					$change[0] = 1;
				} else if ($change[3] == '' || $change[3] == null || !isset($change[3])){
					$change[0] = -1;
				} else {
					$change[0] = 0;
				}
				$changes[$i] = $change;
			}
		}
		*/
		
		$this->checkRenderAjax('historyCompare',array(
			'model'=>$model,
			'leftModel'=>$left,
			'rightModel'=>$right,
			'changes'=>$changes,
			'stepStartIndex'=>$stepStartIndex
		));
	}
	

	public function actionCusinesAutocomplete($query, $page){
		header('Content-type: application/json');
		$this->isFancyAjaxRequest = true;
		$url = $this->createUrl("savedImage/cusineTypes");
		$command = Yii::app()->db->createCommand()
		->select('("cusine_types") as type, concat("CUT_ID:", CUT_ID) as id, CUT_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CUT_IMG_ETAG IS NOT NULL AND CUT_IMG_ETAG <> \'\' THEN concat("' . $url . '/", CUT_ID ,".png") ELSE "" END as img')
		->from('cusine_types')
		->where('CUT_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'))
		->order('CUT_DESC_' . Yii::app()->session['lang'])
		->limit(5, ($page-1)*5);
		$data_cut = $command->queryAll();
		
		$url = $this->createUrl("savedImage/cusineSubTypes");
		$command = Yii::app()->db->createCommand()
		->select('("cusine_sub_types") as type, concat("CST_ID:", CST_ID) as id, CST_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CST_IMG_ETAG IS NOT NULL AND CST_IMG_ETAG <> \'\' THEN concat("' . $url . '/", CST_ID ,".png") ELSE "" END as img')
		->from('cusine_sub_types')
		->where('CST_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'))
		->order('CST_DESC_' . Yii::app()->session['lang'])
		->limit(10, ($page-1)*10);
		$data_cst = $command->queryAll();
		
		$url = $this->createUrl("savedImage/cusineSubSubTypes");
		$command = Yii::app()->db->createCommand()
		->select('("cusine_sub_sub_types") as type, concat("CSS_ID:", CSS_ID) as id, CSS_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CSS_IMG_ETAG IS NOT NULL AND CSS_IMG_ETAG <> \'\' THEN concat("' . $url . '/", CSS_ID ,".png") ELSE "" END as img')
		->from('cusine_sub_sub_types')
		->where('CSS_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'))
		->order('CSS_DESC_' . Yii::app()->session['lang'])
		->limit(15, ($page-1)*15);
		$data_css = $command->queryAll();
		//if ($this->debug){echo $command->text;}
	
		$data = array_merge($data_cut, $data_cst, $data_css);
	
		if (count($data_cut) < 5){
			$total_count_cut = ($page-1)*5 + count($data_cut);
			$more_cut = false;
		} else {
// 			$command = Yii::app()->db->createCommand()
// 			->select('count(*)')
// 			->from('cusine_types')
// 			->where('CUT_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'));
// 			$total_count_cut = $command->queryScalar();
			$more_cut = true;
		}
		if (count($data_cst) < 10){
			$total_count_cst = ($page-1)*10 + count($data_cst);
			$more_cst = false;
		} else {
// 			$command = Yii::app()->db->createCommand()
// 			->select('count(*)')
// 			->from('cusine_sub_types')
// 			->where('CST_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'));
// 			$total_count_cst = $command->queryScalar();
			$more_cst = true;
		}
		if (count($data_css) < 15){
			$total_count_css = ($page-1)*15 + count($data_css);
			$more_css = false;
		} else {
// 			$command = Yii::app()->db->createCommand()
// 			->select('count(*)')
// 			->from('cusine_sub_sub_types')
// 			->where('CSS_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':query'=>'%'.$query.'%'));
// 			$total_count_css = $command->queryScalar();
			$more_css = true;
		}
		$result = array(
// 				'total_count'=>$total_count_cut + $total_count_cst + $total_count_css,
				'more_cut'=>$more_cut,
				'more_cst'=>$more_cst,
				'more_css'=>$more_css,
				'items'=>$data
		);
		echo $this->processOutput(CJSON::encode($result));
		Yii::app()->end();
	}
	
	public static function getCusinesValues($ids, $url1, $url2, $url3){
		$criteria=new CDbCriteria;
		if (strlen($ids)>0){
			$querys = explode(',', $ids);
			$cut_ids = array();
			$cst_ids = array();
			$css_ids = array();
			$result = array();
			foreach($querys as $queryText){
				if (strlen($queryText)>0){
					$prefix = substr($queryText,0,7);
					if ($prefix == 'CUT_ID:'){
						$cut_ids[] = substr($queryText,7);
					} else if ($prefix == 'CST_ID:'){
						$cst_ids[] = substr($queryText,7);
					} else if ($prefix == 'CSS_ID:'){
						$css_ids[] = substr($queryText,7);
					} else {
						$result[] = array('id' => $queryText, 'name' => $queryText, 'type'=>'query');
					}
				}
			}
			if (count($cut_ids)>0){
				$criteria=new CDbCriteria;
				$criteria->addInCondition('CUT_ID',$cut_ids);
				$command = Yii::app()->db->createCommand()
				->select('("cusine_types") as type, concat("CUT_ID:", CUT_ID) as id, CUT_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CUT_IMG_ETAG IS NOT NULL AND CUT_IMG_ETAG <> \'\' THEN concat("' . $url1 . '/", CUT_ID ,".png") ELSE "" END as img')
				->from('cusine_types');
				$command->where($criteria->condition, $criteria->params);
				$data = $command->queryAll();
				$result = array_merge($result, $data);
			}
			if (count($cst_ids)>0){
				$criteria=new CDbCriteria;
				$criteria->addInCondition('CST_ID',$cst_ids);
				$command = Yii::app()->db->createCommand()
				->select('("cusine_sub_types") as type, concat("CST_ID:", CST_ID) as id, CST_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CST_IMG_ETAG IS NOT NULL AND CST_IMG_ETAG <> \'\' THEN concat("' . $url2 . '/", CST_ID ,".png") ELSE "" END as img')
				->from('cusine_sub_types');
				$command->where($criteria->condition, $criteria->params);
				$data = $command->queryAll();
				$result = array_merge($result, $data);
			}
			if (count($css_ids)>0){
				$criteria=new CDbCriteria;
				$criteria->addInCondition('CSS_ID',$css_ids);
				$command = Yii::app()->db->createCommand()
				->select('("cusine_sub_sub_types") as type, concat("CSS_ID:", CSS_ID) as id, CSS_DESC_' . Yii::app()->session['lang'] . ' as name, CASE WHEN CSS_IMG_ETAG IS NOT NULL AND CSS_IMG_ETAG <> \'\' THEN concat("' . $url3 . '/", CSS_ID ,".png") ELSE "" END as img')
				->from('cusine_sub_sub_types');
				$command->where($criteria->condition, $criteria->params);
				$data = $command->queryAll();
				$result = array_merge($result, $data);
			}
			return $result;
		} else {
			return array();
		}
	}
	
	public function actionCusinesAutocompleteId($ids){
		header('Content-type: application/json');
		$this->isFancyAjaxRequest = true;
		$result = self::getCusinesValues($ids, $this->createUrl("savedImage/cusineTypes"), $this->createUrl("savedImage/cusineSubTypes"), $this->createUrl("savedImage/cusineSubSubTypes"));
		echo $this->processOutput(CJSON::encode($result));
		Yii::app()->end();
	}
	

	public function actionTagAutocomplete($query, $page){
		$this->isFancyAjaxRequest = true;
		$command = Yii::app()->db->createCommand()
			->select('TAG_ID as id, TAG_DESC_' . Yii::app()->session['lang'] . ' as name')
			->from('tags')
			->where('TAG_IGNORE = :ignore AND TAG_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':ignore'=>'N',':query'=>$query.'%'))
			->order('TAG_DESC_' . Yii::app()->session['lang'])
			->limit(30, ($page-1)*30);
		$data = $command->queryAll();
	
		if (count($data) < 30){
			$total_count = ($page-1)*30 + count($data);
		} else {
			$command = Yii::app()->db->createCommand()
				->select('count(*)')
				->from('tags')
				->where('TAG_IGNORE = :ignore AND TAG_DESC_' . Yii::app()->session['lang'] . ' LIKE :query',array(':ignore'=>'N',':query'=>$query.'%'));
			$total_count = $command->queryScalar();
		}
		$result = array(
				'total_count'=>$total_count,
				'items'=>$data
		);
		header('Content-type: application/json');
		echo $this->processOutput(CJSON::encode($result));
		Yii::app()->end();
	}
	
	public function actionTagAutocompleteId($ids){
		$this->isFancyAjaxRequest = true;
	
		$criteria=new CDbCriteria;
		$ids = explode(',', $ids);
		$criteria->compare('TAG_IGNORE','N');
		$criteria->addInCondition('TAG_ID',$ids);
	
		$command = Yii::app()->db->createCommand()
		->select('TAG_ID as id, CASE WHEN (TAG_DESC_' . Yii::app()->session['lang'] . ' IS NOT NULL) THEN TAG_DESC_' . Yii::app()->session['lang'] . ' ELSE TAG_DESC_EN_GB END as name')
			->from('tags')
			->where($criteria->condition, $criteria->params);
		$data = $command->queryAll();
		header('Content-type: application/json');
		echo $this->processOutput(CJSON::encode($data));
		Yii::app()->end();
	}
	
}
