<?php

class NutrientDataController extends Controller
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
				'actions'=>array('index','view','search','advanceSearch','chooseNutrientData'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$model = $this->loadModel($id);
		$ingredientName = null;
		if (isset($_GET['ing_id'])){
			$ingredientName = Yii::app()->db->createCommand()->select('ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->where('ING_ID = :ing_id',array(':ing_id'=>$_GET['ing_id']))->queryAll();
		} else {
			$ingredientName = Yii::app()->db->createCommand()->select('ING_NAME_'.Yii::app()->session['lang'])->from('ingredients')->where('NUT_ID = :nut_id',array(':nut_id'=>$id))->queryAll();
		}
		if ($ingredientName != null && count($ingredientName)==1){
			$ingredientName = $ingredientName[0]['ING_NAME_'.Yii::app()->session['lang']];
		} else {
			$ingredientName = null;
		}
		
		$this->checkRenderAjax('view',array(
			'model'=>$model,
			'ingredientName'=>$ingredientName,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new NutrientData;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NutrientData']))
		{
			$model->attributes=$_POST['NutrientData'];
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				if($model->save()){
					$this->redirect(array('view','id'=>$model->NUT_ID));
				}
			}
		}

		$this->checkRenderAjax('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NutrientData']))
		{
			$model->attributes=$_POST['NutrientData'];
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				if($model->save()){
					$this->redirect(array('view','id'=>$model->NUT_ID));
				}
			}
		}

		$this->checkRenderAjax('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('NutrientData');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new NutrientData('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NutrientData']))
			$model->attributes=$_GET['NutrientData'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}
	
	
	private function prepareSearch($view, $ajaxLayout){
		$model=new NutrientData('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['NutrientData']))
			$model->attributes=$_POST['NutrientData'];
		
		$model2 = new SimpleSearchForm();
		if(isset($_POST['SimpleSearchForm']))
			$model2->attributes=$_POST['SimpleSearchForm'];
		
		if(isset($_GET['query'])){
			$query = $_GET['query'];
		} else {
			$query = $model2->query;
		}
		
		if ($query != $model2->query){
			$model2->query = $query;
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['NutrientData']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['NutrientData']['time'])){
			$Session_NutrientData = Yii::app()->session['NutrientData'];
			if (isset($Session_NutrientData)){
				if (isset($Session_NutrientData['query'])){
					$query = $Session_NutrientData['query'];
					//echo "query from session\n";
				}
				if (isset($Session_NutrientData['model'])){
					$model = $Session_NutrientData['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, '');
		
		$criteria=$model->getCriteria();
		if (isset($criteriaString) && $criteriaString != ''){
			$Session_NutrientData = array();
			$Session_NutrientData['query'] = $query;
			$Session_NutrientData['time'] = time();
			Yii::app()->session['NutrientData'] = $Session_NutrientData;
			
			$criteria->addCondition($criteriaString);
		}
		
		if ($criteria->condition) {
			$command = Yii::app()->db->commandBuilder->createFindCommand($model->tableName(), $criteria, '');
			$rows = $command->queryAll();
		} else {
			$rows = array();
		}
			
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'NUT_ID',
			'keyField'=>'NUT_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		/*
		$this->renderPartial($view,array(
			'model'=>$model,
			'model2'=>$model2,
			'dataProvider'=>$dataProvider,
		));
		*/
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
	
	/**
	 * Ajax function for select NutrientData in a FancyBox
	 */
	public function actionChooseNutrientData(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=NutrientData::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='nutrient-data-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
