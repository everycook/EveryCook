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
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage','chooseRecipe','advanceChooseRecipe'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage','delicious','disgusting','cancel'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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

	private function updateKCal($id){
		$nutrientData = $this->calculateNutrientData($id);
		if ($nutrientData != null){
			$kcal = $nutrientData->NUT_ENERG;
		} else {
			$kcal = 0;
		}
		Yii::app()->db->createCommand()->update(Recipes::model()->tableName(), array('REC_KCAL'=>$kcal), 'REC_ID = :id', array(':id'=>$id));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->checkRenderAjax('view',array(
			'model'=>$this->loadModel($id),
			'nutrientData'=>$this->calculateNutrientData($id),
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
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
		} else {
			$model=new Recipes;
		}
		
		Functions::uploadImage('Recipes', $model, 'Recipes_Backup', 'REC_IMG');
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
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->REC_IMG;
			$oldAmount = count($oldmodel->steps);
		} else {
			$model=new Recipes;
			$oldPicture = null;
			$oldAmount = 0;
		}
		
		if(isset($_POST['Recipes'])){
			$model->attributes=$_POST['Recipes'];
			//$steps = array();
			$stepsOK = true;
			if (isset($_POST['Steps'])){
				$model = Functions::arrayToRelatedObjects($model, array('steps'=> $_POST['Steps']));
				/*
				foreach($_POST['Steps'] as $index => $values){
					if ($index <= $oldAmount && $index-1>=0){
						$newStep = $oldmodel->steps[$index-1];
					} else {
						$newStep = new Steps;
					}
					$newStep->attributes = $values;
					foreach ($values as $key=>$value){
						if ($value == '' && $key != 'REC_ID' && $key != 'STE_STEP_NO'){
							$this->errorText .= '<li>Value ' . $key . ' of Step' . $index . ' is empty.</li>';
							array_push($this->errorFields, 'Steps_'.$index.'_'.$key);
							$stepsOK = false;
						}
					}
					$newStep->STE_STEP_NO = $index;
					//$steps[$index] = $newStep;
					array_push($steps, $newStep);
				}
				*/
			} else {
				$this->errorText .= '<li>No Steps defined!</li>';
				$stepsOK = false;
			}
			/*
			$stepsToDelete = array();
			if (isset($oldmodel)){
				$newAmount = count($steps);
				if ($oldAmount > $newAmount){
					for($i = $newAmount; $i < $oldAmount; $i++){
						array_push($stepsToDelete, $oldmodel->steps[$i]);
					}
				}
			}
			
			$model->steps = $steps;
			*/
			if (isset($oldPicture)){
				Functions::updatePicture($model,'REC_IMG', $oldPicture);
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if ($stepsOK){
				$transaction=$model->dbConnection->beginTransaction();
				try {
					if($model->save()){
						$saveOK = true;
						Yii::app()->db->createCommand()->delete(Steps::model()->tableName(), 'REC_ID = :id', array(':id'=>$model->REC_ID));
						$stepNo = 0;
						//foreach($steps as $step){
						foreach($model->steps as $step){
							$step->REC_ID = $model->REC_ID;
							$step->STE_STEP_NO = $stepNo;
							$step->setIsNewRecord(true);
							if(!$step->save()){
								$saveOK = false;
								if ($this->debug) echo 'error on save Step: errors:'; print_r($step->getErrors());
							}
							++$stepNo;
						}
						/*
						foreach($stepsToDelete as $step){
							if(!$step->delete()){
								$saveOK = false;
								if ($this->debug) echo 'error on delete Step: '; print_r($step->getErrors());
							}
						}
						*/
						if ($saveOK){
							$this->updateKCal($model->REC_ID);
							$transaction->commit();
							unset(Yii::app()->session[$this->createBackup]);
							unset(Yii::app()->session[$this->createBackup.'_Time']);
							$this->forwardAfterSave(array('view', 'id'=>$model->REC_ID));
							return;
						} else {
							if ($this->debug) echo 'any errors occured, rollback';
							$transaction->rollBack();
						}
					} else {
						if ($this->debug) echo 'error on save: ';  print_r($model->getErrors());
						$transaction->rollBack();
					}
				} catch(Exception $e) {
					if ($this->debug) echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
					$transaction->rollBack();
				}
			} else {
				//To show Recipe errors also
				$model->validate();
			}
		}
		
		$recipeTypes = Yii::app()->db->createCommand()->select('RET_ID,RET_DESC_'.Yii::app()->session['lang'])->from('recipe_types')->queryAll();
		$recipeTypes = CHtml::listData($recipeTypes,'RET_ID','RET_DESC_'.Yii::app()->session['lang']);
		
		$stepTypeConfig = Yii::app()->db->createCommand()->select('STT_ID,STT_DEFAULT,STT_REQUIRED,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->order('STT_ID')->queryAll();
		$stepTypes = CHtml::listData($stepTypeConfig,'STT_ID','STT_DESC_'.Yii::app()->session['lang']);
		$actions = Yii::app()->db->createCommand()->select('ACT_ID,ACT_DESC_'.Yii::app()->session['lang'])->from('actions')->queryAll();
		$actions = CHtml::listData($actions,'ACT_ID','ACT_DESC_'.Yii::app()->session['lang']);
		//$ingredients = Yii::app()->db->createCommand()->select('ING_ID,ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->queryAll();
		//$ingredients = CHtml::listData($ingredients,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
		
		if (isset($model->steps) && isset($model->steps[0]) && !isset($model->steps[0]->ingredient)){
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
		
		$stepsJSON = CJSON::encode($model->steps);
		$stepTypeConfig = CJSON::encode($stepTypeConfig);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'recipeTypes'=>$recipeTypes,
			'stepTypes'=>$stepTypes,
			'actions'=>$actions,
			'ingredients'=>$usedIngredients,
			'stepTypeConfig'=>$stepTypeConfig,
			'stepsJSON'=>$stepsJSON,
		));
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
			$this->loadModel($id)->delete();

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
		$this->prepareSearch('search');
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
	
	private function prepareSearch($view, $ajaxLayout)
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
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Recipes']) && !isset($_GET['ing_id'])  && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['Recipe']['time'])){
			$Session_Recipe = Yii::app()->session['Recipe'];
			if (isset($Session_Recipe)){
				if (isset($Session_Recipe['query'])){
					$query = $Session_Recipe['query'];
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
		if($ing_id !== null){
			$Session_Recipe = array();
			$Session_Recipe['ing_id'] = $ing_id;
			$Session_Recipe['time'] = time();
			Yii::app()->session['Recipe'] = $Session_Recipe;
			
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
				Yii::app()->session['Recipe'] = $Session_Recipe;
				
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
				unset(Yii::app()->session['Recipe']);
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
		$this->prepareSearch('advanceSearch', null);
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search', null);
	}
	
	public function actionChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none');
	}
	
	public function actionAdvanceChooseRecipe(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $withPicture = false)
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
		$this->saveLastAction = false;
		$model=$this->loadModel($id, true);
		$modified = $model->CHANGED_ON;
		if (!isset($modified)){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model->REC_IMG_ETAG, $model->REC_IMG, $id);
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
}
