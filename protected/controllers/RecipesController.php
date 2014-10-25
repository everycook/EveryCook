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
		);
	}
	
	protected $createBackup = 'Recipes_Backup';
	protected $searchBackup = 'Recipes';
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
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage','chooseRecipe','advanceChooseRecipe','chooseTemplateRecipe','advanceChooseTemplateRecipe','updateSessionValues','updateSessionValue','history','historyCompare', 'viewHistory'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage','delicious','disgusting','cancel','showLike', 'showNotLike', 'getRecipeInfos', 'setHistoryVersion'),
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

	private function updateKCal($id, $kcal){
		if ($kcal == null){
			$nutrientData = $this->calculateNutrientData($id);
			if ($nutrientData != null){
				$kcal = round($nutrientData->NUT_ENERG);
			} else {
				$kcal = 0;
			}
		}
		Yii::app()->db->createCommand()->update(Recipes::model()->tableName(), array('REC_KCAL'=>$kcal), 'REC_ID = :id', array(':id'=>$id));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
		}
		$model = $this->loadModel($id);
		$cookin = "#cookin";
		if (isset($model->recToCois) && count($model->recToCois)>0){
			$coi_id = $model->recToCois[0]->COI_ID;
			$cookin = Yii::app()->db->createCommand()->select('COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->where('COI_ID = :id',array(':id'=>$coi_id))->queryScalar();
		}
		
		$nutrientData = $this->calculateNutrientData($id);
		if ($nutrientData != null){
			$kcal = round($nutrientData->NUT_ENERG);
			if ($this->debug) {echo 'recipe kcal:' . $model->REC_KCAL . ', calculate kcal:' . $kcal . '<br>'."\n";}
			if ($model->REC_KCAL != $kcal){
				$this->updateKCal($id, $kcal);
			}
		}
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
	public function actionViewHistory($id, $CHANGED_ON)
	{
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
		}
		$model=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$CHANGED_ON));
		$cookin = "#cookin";
		if (isset($model->recToCois) && count($model->recToCois)>0){
			$coi_id = $model->recToCois[0]->COI_ID;
			$cookin = Yii::app()->db->createCommand()->select('COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->where('COI_ID = :id',array(':id'=>$coi_id))->queryScalar();
		}
		
		$nutrientData = $this->calculateNutrientData($id);
		if ($nutrientData != null){
			$kcal = round($nutrientData->NUT_ENERG);
			if ($this->debug) {echo 'recipe kcal:' . $model->REC_KCAL . ', calculate kcal:' . $kcal . '<br>'."\n";}
			if ($model->REC_KCAL != $kcal){
				$this->updateKCal($id, $kcal);
			}
		}
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
							$defaultNew[] = $key . '='.$val;
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
						$defaultNew[] = $key.'='.$val;
					}
					$details['cookIns'] = $cois;
					$details['required'] = $requiredNew;
					$details['default'] = $defaultNew;
					$details['desc'] = '<span class="ain_desc" id="ain_desc_'.$last_ain_id.'"><input type="hidden" class="actionRequireds" value="'.CHtml::encode(CJSON::encode($requiredNew)).'"/><input type="hidden" class="actionDefaults" value="'.CHtml::encode(CJSON::encode($defaultNew)).'"/>'.$ain_desc.'</span>';
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
						$defaultNew[] = $key.'='.$val;
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
			$details['desc'] = '<span class="ain_desc" id="ain_desc_'.$last_ain_id.'"><input type="hidden" class="actionRequireds" value="'.CHtml::encode(CJSON::encode($requiredNew)).'"/><input type="hidden" class="actionDefaults" value="'.CHtml::encode(CJSON::encode($defaultNew)).'"/>'.$ain_desc.'</span>';
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
		return $changeModel->REC_ID . "_" . $this->CHANGED_BY . "_" . $this->CHANGED_ON;
	}
	private function getStepUndoKey($changeModel){
		return $changeModel->REC_ID . "_" . $this->STE_STEP_NO . "_" . $this->CHANGED_BY . "_" . $this->CHANGED_ON;
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
					} else if (isset($_POST['add'])){
						$action = 'ADD';
						$actionIndex=$_POST['add'];
					}
					if ($this->debug) {echo "action:$action,actionIndex:$actionIndex\n";}
					$dataArray = $_POST['Steps'];
					if ($actionIndex != -1){
						$changeModel = new StepChanges;
						$result;
						if ($action == 'ADD'){
							$entry = $dataArray[$actionIndex];
							if ($this->debug) {print_r($entry);}
							$changeModel->attributes = $entry;
							$changeModel = Functions::arrayToRelatedObjects($changeModel, $entry);
							$result = $entry;
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
						$changeModel->STE_STEP_NO = $actionIndex-1;
						$changeModel->REC_CHANGED_ON = $model->CHANGED_ON;
						
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN'
						$changeModel->CHANGED_ON = $timestamp;

						if($changeModel->save()){
							$result["REC_ID"] = $changeModel->REC_ID;
							$result["STE_STEP_NO"] = $changeModel->STE_STEP_NO;
							echo '{"action":"'.$action.'","stepNr":"'.$actionIndex.'","prefIndex":"'.($actionIndex).'","undoKey":"'.getStepUndoKey($changeModel).'","prevValues":'.CJSON::encode($result).'}';
						} else {
							if ($this->debug) {echo 'error on save: ';  print_r($changeModel->getErrors());}
						}
					} else {
						echo '{"action":"RECIPE","undoKey":"'.getRecipeUndoKey($changeModel).'","prevValues":'.CJSON::encode($oldValues).'}';
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
				$action=$_POST['action'];
				if ($action != 'IGNORE'){
					$prefIndex=$_POST['prefIndex'];
					$prefIndex--;
					$changeModel = new StepChanges;
					$changeModel->attributes = $entry;
					$changeModel = Functions::arrayToRelatedObjects($changeModel, $entry);
					if($changeModel->REC_ID == ''){
						$changeModel->REC_ID = $model->REC_ID;
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
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN','UNDO_CHANGE','UNDO_ADD','UNDO_DELETE','UNDO_UP','UNDO_DOWN'
					} else {
						$changeModel->SCH_ACTION = $action ; //'CHANGE','ADD','DELETE','UP','DOWN','UNDO_CHANGE','UNDO_ADD','UNDO_DELETE','UNDO_UP','UNDO_DOWN'
					}
					//CHANGED_ON set in save
					if($changeModel->save()){
						if (isset($result)){
							$result["REC_ID"] = $changeModel->REC_ID;
							$result["STE_STEP_NO"] = $changeModel->STE_STEP_NO;
							echo '{"action":"'.$action.'","stepNr":"'.$StepNr.'","prefIndex":"'.($prefIndex+1).'","undoKey":"'.getStepUndoKey($changeModel).'","prevValues":'.CJSON::encode($result).'}';
						} else {
							echo '{"action":"'.$action.'","stepNr":"'.$StepNr.'","prefIndex":"'.($prefIndex+1).'","undoKey":"'.getStepUndoKey($changeModel).'"}';
						}
					} else {
						if ($this->debug) {echo 'error on save: ';  print_r($changeModel->getErrors());}
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
				
				$index = 1;
				foreach($model->steps as $step){
					$required = array();
					if (isset($actionsInDetails[$step['AIN_ID']])){
						$ainDetails = $actionsInDetails[$step['AIN_ID']];
						$required = $ainDetails['required'];
					}
					if (isset($required) && count($required)>0){
						foreach($required as $requiredField){
							if (!isset($step[$requiredField]) || $step[$requiredField] == null || $step[$requiredField] == ''){
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
					$this->errorText .= '<li>No Steps defined!</li>';
				}
				$stepsOK = false;
			}
			if (isset($oldPictureFilename)){
				Functions::updatePicture($model,'REC_IMG', $oldPictureFilename);
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			
			if(!isset($_POST['updateCookIn'])){
				if(Yii::app()->user->demo){
					$this->errorText .= sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
					$model->validate();
				} else {
					if ($stepsOK){
						$REC_CHANGED_ON = $model->CHANGED_ON;
						$transaction=$model->dbConnection->beginTransaction();
						try {
							$model->CHANGED_ON = $timestamp;
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
										if ($this->debug) {echo 'error on save recToCoi: errors:'; print_r($recToCoi->getErrors());}
									} else {
										$recToCoi->updateChangeTime = true;
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
											if ($this->debug) {echo 'error on save Step: errors:'; print_r($step->getErrors());}
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
											if ($this->debug) {echo 'error on save Step: errors:'; print_r($step->getErrors());}
										} else {
											$step->updateChangeTime = true;
										}
									}
								}
								
								//finish save
								if ($saveOK){
									$this->updateKCal($model->REC_ID, null);
									
									$saveOK = true;
									$changed = Functions::fixPicturePathAfterSave($model,'REC_IMG', $model->REC_IMG_FILENAME);
									if ($changed){
										$model->updateChangeTime = false;
										if(!$model->save()){
											if ($this->debug) {echo 'error on save after img file: ';  print_r($model->getErrors());}
											$transaction->rollBack();
											$saveOK = false;
										}
									}
									
									if (isset($id)){
										$updateFields = array('RCH_SAVED'=>'Y');
										Yii::app()->db->createCommand()->update(RecipeChanges::model()->tableName(), $updateFields, 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by', array(':id'=>$model->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
										$updateFields = array('SCH_SAVED'=>'Y');
										Yii::app()->db->createCommand()->update(StepChanges::model()->tableName(), $updateFields, 'REC_ID = :id AND REC_CHANGED_ON = :change AND CHANGED_BY = :by', array(':id'=>$model->REC_ID, ':change'=>$REC_CHANGED_ON, ':by'=>Yii::app()->user->id));
										//TODO: update all RecipeChanges & StepChanges without ID from this user to the new generated id: $model->REC_ID
									}
									
									if ($saveOK){
										//copy Entrys to History
										$condition = array(':id'=>$model->REC_ID);
										Yii::app()->db->createCommand()->setText('INSERT INTO recipes_history (SELECT * FROM `' . Recipes::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
										Yii::app()->db->createCommand()->setText('INSERT INTO steps_history (SELECT * FROM `' . Steps::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
										Yii::app()->db->createCommand()->setText('INSERT INTO rec_to_coi_history (SELECT * FROM `' . RecToCoi::model()->tableName() . '` WHERE REC_ID = :id)')->execute($condition);
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
								if ($this->debug) {echo 'error on save: ';  print_r($model->getErrors());}
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
							$model->CHANGED_ON = REC_CHANGED_ON;	
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
		
		$cookIns = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->queryAll();
		$cookIns = CHtml::listData($cookIns,'COI_ID','COI_DESC_'.Yii::app()->session['lang']);
		
		$cookInsSelected = array();
		foreach($coi_ids as $coiId){
			$cookInsSelected[$coiId]=$cookIns[$coiId];
		}
		
		$stepsJSON = CJSON::encode($model->steps);
		$stepTypeConfig = CJSON::encode($stepTypeConfig);
		
		if (!isset($_POST['CookVariant']) || $_POST['CookVariant'] == ''){
			$_POST['CookVariant'] = 0;
		}
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'recipeTypes'=>$recipeTypes,
			'actionsIn'=>$actionsIn,
			'cookIns'=>$cookIns,
			'cookInsSelected'=>$cookInsSelected,
			'tools'=>$tools,
			'ingredients'=>$usedIngredients,
			'stepTypeConfig'=>$stepTypeConfig,
			'stepsJSON'=>$stepsJSON,
			'actionsInDetails'=>$actionsInDetails,
		));
	}
	
	public function actionGetRecipeInfos($id){
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
		} else {
			$query = $model2->query;
		}
		
		$ing_id = null;
		if(isset($_GET['ing_id'])){
			$ing_id = $_GET['ing_id'];
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Recipes']) && !isset($_GET['ing_id'])  && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session[$this->searchBackup]['time'])){
			$Session_Recipe = Yii::app()->session[$this->searchBackup];
			if (isset($Session_Recipe)){
				if (isset($Session_Recipe['query'])){
					$query = $Session_Recipe['query'];
					$model2->query = $query;
					//echo "query from session\n";
				}
				if (isset($Session_Recipe['ing_id'])){
					$ing_id = $Session_Recipe['ing_id'];
					//echo "ing_id from session\n";
				}
				if (isset($Session_Recipe['model'])){
					$model = $Session_Recipe['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		if ($query != $model2->query){
			$model2->query = $query;
		}
		
		$rows = null;
		if ($criteria != null){
			Yii::app()->session[$this->searchBackup] = array('time'=>time());
			
			$rows = Yii::app()->db->createCommand()
				->from('recipes')
				->leftJoin('recipe_types', 'recipes.RET_ID=recipe_types.RET_ID')
				//->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID')
				//->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID')
				//->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID')
				->where($criteria->condition, $criteria->params)
				//->order('steps.STE_STEP_NO')
				->queryAll();
		} else if($ing_id !== null){
			$Session_Recipe = array();
			$Session_Recipe['ing_id'] = $ing_id;
			$Session_Recipe['time'] = time();
			Yii::app()->session[$this->searchBackup] = $Session_Recipe;
			
			$rows = Yii::app()->db->createCommand()
				//->select('recipes.*')
				->from('recipes')
				->leftJoin('recipe_types', 'recipes.RET_ID=recipe_types.RET_ID')
				//->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID')
				->join('steps', 'recipes.REC_ID=steps.REC_ID')
				//->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID')
				//->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID')
				//->where('ingredients.ING_ID=:id', array(':id'=>$ing_id))
				->where('steps.ING_ID=:id', array(':id'=>$ing_id))
				//->order('steps.STE_STEP_NO')
				->queryAll();
		} else {
			$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'recipes.');
			if ($criteriaString != ''){
				$Session_Recipe = array();
				$Session_Recipe['query'] = $query;
				$Session_Recipe['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Recipe;
				
				$rows = Yii::app()->db->createCommand()
					->from('recipes')
					->leftJoin('recipe_types', 'recipes.RET_ID=recipe_types.RET_ID')
					//->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID')
					//->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID')
					//->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID')
					->where($criteriaString)
					//->order('steps.STE_STEP_NO')
					->queryAll();
			} else {
				$rows = array();
				unset(Yii::app()->session[$this->searchBackup]);
			}
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'REC_ID',
			'keyField'=>'REC_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'model2'=>$model2,
			'dataProvider'=>$dataProvider,
		), $ajaxLayout);
	}
	
	public function actionAdvanceSearch()
	{
		$this->prepareSearch('advanceSearch', null, null);
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search', null, null);
	}
	
	public function actionChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none', null);
	}
	
	public function actionAdvanceChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none', null);
	}
	
	public function actionChooseTemplateRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->isTemplateChoose = true;
		$this->prepareSearch('search', 'none', null);
	}
	
	public function actionAdvanceChooseTemplateRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->isTemplateChoose = true;
		$this->prepareSearch('advanceSearch', 'none', null);
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
		
		$this->prepareSearch('like', null, $criteria);
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
		
		$this->prepareSearch('like', null, $criteria);
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
	
	private function stepToStr($step){
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
	}
	
	/**
	 * show history of recipe
	 * @param integer the ID of the model to show history of
	 */
	public function actionHistoryCompare($id){
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
		}
		$model = $this->loadModel($id);
		if (isset($_GET['leftVersion'])){
			$leftVersion = $_GET['leftVersion'];
		} else {
			$this->actionHistory($id);
			return;
		}
		if (isset($_GET['rightVersion'])){
			$rightVersion = $_GET['rightVersion'];
		} else {
			$this->actionHistory($id);
			return;
		}
		
		$left=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$leftVersion));
		$right=RecipesHistory::model()->findByPk(array('REC_ID'=>$id, 'CHANGED_ON'=>$rightVersion));
		
		$changes = array();
		$changes[] = array('-', $model->getAttributeLabel('REC_IMG_FILENAME'), $left->REC_IMG_FILENAME,$right->REC_IMG_FILENAME);
		$changes[] = array('-', $model->getAttributeLabel('REC_IMG_AUTH'), $left->REC_IMG_AUTH,$right->REC_IMG_AUTH);
		$changes[] = array('-', $model->getAttributeLabel('REC_IMG_ETAG'), $left->REC_IMG_ETAG,$right->REC_IMG_ETAG);
		$changes[] = array('-', $model->getAttributeLabel('RET_ID'), $left->RET_ID,$right->RET_ID);
		$changes[] = array('-', $model->getAttributeLabel('REC_KCAL'), $left->REC_KCAL,$right->REC_KCAL);
		$changes[] = array('-', $model->getAttributeLabel('REC_HAS_ALLERGY_INFO'), $left->REC_HAS_ALLERGY_INFO,$right->REC_HAS_ALLERGY_INFO);
		$changes[] = array('-', $model->getAttributeLabel('REC_SUMMARY'), $left->REC_SUMMARY,$right->REC_SUMMARY);
		$changes[] = array('-', $model->getAttributeLabel('REC_APPROVED'), $left->REC_APPROVED,$right->REC_APPROVED);
		$changes[] = array('-', $model->getAttributeLabel('REC_SERVING_COUNT'), $left->REC_SERVING_COUNT,$right->REC_SERVING_COUNT);
		$changes[] = array('-', $model->getAttributeLabel('REC_WIKI_LINK'), $left->REC_WIKI_LINK,$right->REC_WIKI_LINK);
		$changes[] = array('-', $model->getAttributeLabel('REC_IS_PRIVATE'), $left->REC_IS_PRIVATE,$right->REC_IS_PRIVATE);
		$changes[] = array('-', $model->getAttributeLabel('REC_COMPLEXITY'), $left->REC_COMPLEXITY,$right->REC_COMPLEXITY);
		$changes[] = array('-', $model->getAttributeLabel('CUT_ID'), $left->CUT_ID,$right->CUT_ID);
		$changes[] = array('-', $model->getAttributeLabel('CST_ID'), $left->CST_ID,$right->CST_ID);
		$changes[] = array('-', $model->getAttributeLabel('REC_CUSINE_GPS_LAT'), $left->REC_CUSINE_GPS_LAT,$right->REC_CUSINE_GPS_LAT);
		$changes[] = array('-', $model->getAttributeLabel('REC_CUSINE_GPS_LNG'), $left->REC_CUSINE_GPS_LNG,$right->REC_CUSINE_GPS_LNG);
		$changes[] = array('-', $model->getAttributeLabel('REC_TOOLS'), $left->REC_TOOLS,$right->REC_TOOLS);
		foreach($this->allLanguages as $lang=>$name){
			$fieldName='REC_SYNONYM_'.strtoupper($lang);
			$changes[] = array('-', $model->getAttributeLabel($fieldName), $left->__get($fieldName),$right->__get($fieldName));
		}
		foreach($this->allLanguages as $lang=>$name){
			$fieldName='REC_NAME_'.strtoupper($lang);
			$changes[] = array('-', $model->getAttributeLabel($fieldName), $left->__get($fieldName),$right->__get($fieldName));
		}
		
		$length = count($changes);
		for($i=0;$i<$length;$i++){
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
		
		$stepStartIndex = count($changes);
		$leftCount = count($left->steps);
		$rightCount = count($right->steps);
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
			$changes[] = array('-', 'Step ' . ($i+1), $leftStepLine, $rightStepLine);
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
		
		$this->checkRenderAjax('historyCompare',array(
			'model'=>$model,
			'leftModel'=>$left,
			'rightModel'=>$right,
			'changes'=>$changes,
			'stepStartIndex'=>$stepStartIndex
		));
	}
	
}
