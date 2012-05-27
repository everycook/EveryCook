<?php
class RecipesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $errorText = '';
	public $errorFields = array();
	public $allLanguages = array("de-CH", "en-GB");

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
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->checkRenderAjax('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionUploadImage(){
		$id = $_GET['id'];
		
		$Session_Recipe_Backup = Yii::app()->session['Recipe_Backup'];
		if ($Session_Recipe_Backup){
			$oldmodel = $Session_Recipe_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->REC_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
		} else {
			$model=new Recipes;
		}
		
		if(isset($_POST['Recipes'])){
			$model->attributes=$_POST['Recipes'];
			$sucessfull = Functions::uploadPicture($model,'REC_IMG');
			Yii::app()->session['Recipe_Backup'] = $model;
			
			if ($sucessfull){
				echo "{imageId:'backup'}";
				exit;
			} else {
				echo "{error:'nofile'}";
				exit;
			}
		} else {
			echo "{error:'nodata'}";
			exit;
		}
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Recipe_Backup = Yii::app()->session['Recipe_Backup'];
		if ($Session_Recipe_Backup){
			$oldmodel = $Session_Recipe_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->REC_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldmodel->REC_IMG;
			$oldAmount = count($oldmodel->steps);
		} else {
			$model=new Recipes;
			$oldAmount = 0;
		}
		
		if(isset($_POST['Recipes'])){
			$model->attributes=$_POST['Recipes'];
			$steps = array();
			$stepsOK = true;
			if (isset($_POST['Steps'])){
				foreach($_POST['Steps'] as $index => $values){
					if ($index <= $oldAmount){
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
			} else {
				$this->errorText .= '<li>No Steps defined!</li>';
				$stepsOK = false;
			}
			
			$stepsToDelete = array();
			if ($oldmodel){
				$newAmount = count($steps);
				if ($oldAmount > $newAmount){
					for($i = $newAmount; $i < $oldAmount; $i++){
						array_push($stepsToDelete, $oldmodel->steps[$i]);
					}
				}
			}
			
			$model->steps = $steps;
			
			Functions::updatePicture($model,'REC_IMG', $oldPicture);
			/*
			if (isset($model->REC_ID)){
				$model->REC_CHANGED = time();
			} else {
				//$model->PRF_UID = Yii::app()->session['userID'];
				$model->PRF_UID = 1;
				$model->REC_CREATED = time();
			}
			*/
			
			Yii::app()->session['Recipe_Backup'] = $model;
			if ($stepsOK){
				$transaction=$model->dbConnection->beginTransaction();
				try {
					if($model->save()){
						$saveOK = true;
						foreach($steps as $step){
							$step->REC_ID = $model->REC_ID;
							if(!$step->save()){
								$saveOK = false;
								//echo 'error on save Step: errors:' . $step->getErrors(); /* print_r($step);*/
							}
						}
						foreach($stepsToDelete as $step){
							if(!$step->delete()){
								$saveOK = false;
								//echo 'error on delete Step: ' . $step->getErrors(); /* print_r($step);*/
							}
						}
						if ($saveOK){
							$transaction->commit();
							unset(Yii::app()->session['Recipe_Backup']);
							if($this->useAjaxLinks){
								echo "{hash:'" . $this->createUrlHash('view', array('id'=>$model->REC_ID)) . "'}";
								exit;
							} else {
								$this->redirect(array('view', 'id'=>$model->REC_ID));
							}
						} else {
							//echo 'any errors occured, rollback';
							$transaction->rollBack();
						}
					} else {
						//echo 'error on save: ' . $model->getErrors();
						$transaction->rollBack();
					}
				} catch(Exception $e) {
					echo 'Eception occured -&gt; rollback. Exeption was: ' . $e;
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
		
		if ($model->steps && $model->steps[0] && !$model->steps[0]->ingredient){
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
			if (count(neededIngredients)>0){
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
	
	private function prepareSearch($view)
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
			if ($Session_Recipe){
				if ($Session_Recipe['query']){
					$query = $Session_Recipe['query'];
					//echo "query from session\n";
				}
				if ($Session_Recipe['ing_id']){
					$ing_id = $Session_Recipe['ing_id'];
					//echo "ing_id from session\n";
				}
				if ($Session_Recipe['model']){
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
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'model2'=>$model2,
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionAdvanceSearch()
	{
		$this->prepareSearch('advanceSearch');
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $withPicture = false)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session['Recipe_Backup'];
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
		$model=$this->loadModel($id, true);
		$modified = $model->CREATED_ON;
		if (!$modified){
			$modified = $model->CHANGED_ON;
		}
		return Functions::getImage($modified, $model->REC_IMG_ETAG, $model->REC_IMG, $id);
    }
}
