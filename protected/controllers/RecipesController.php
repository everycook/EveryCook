<?php

require_once('functions.php');
class RecipesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('create','update'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Recipes;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Recipes']))
		{
			$model->attributes=$_POST['Recipes'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->REC_ID));
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

		if(isset($_POST['Recipes']))
		{
			$model->attributes=$_POST['Recipes'];
			$file = CUploadedFile::getInstance($model,'filename');
			if ($file){
				resizePicture($file->getTempName(), $file->getTempName(), 400, 400, 0.8, 3);
				$model->REC_PICTURE=file_get_contents($file->getTempName());
			} else {
				if ($model->REC_ID){
					$oldModel = $this->loadModel($id);
					$model->REC_PICTURE = $oldModel->REC_PICTURE;
				}
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->REC_ID));
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
		$dataProvider=new CActiveDataProvider('Recipes');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
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
		if(isset($_POST['Recipes']))
			$model->attributes=$_POST['Recipes'];
		
		$model2 = new SimpleSearchForm();
		if(isset($_POST['SimpleSearchForm']))
			$model2->attributes=$_POST['SimpleSearchForm'];
		
		$rows = null;
		if(isset($_GET['ing_id'])){
			$rows = Yii::app()->db->createCommand()
				->from('recipes')
				->leftJoin('recipe_types', 'recipes.REC_TYPE=recipe_types.RET_ID')
				->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID')
				->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID')
				->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID')
				->where('ingredients.ING_ID=:id', array(':id'=>$_GET['ing_id']))
				->order('steps.STE_STEP_NO')
				->queryAll();
		} else {
			if(isset($_GET['query'])){
				$query = $_GET['query'];
			} else {
				$query = $model2->query;
			}
			$criteryaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'recipes.');
			if ($criteryaString != ''){
				$rows = Yii::app()->db->createCommand()
					->from('recipes')
					->leftJoin('recipe_types', 'recipes.REC_TYPE=recipe_types.RET_ID')
					->leftJoin('steps', 'recipes.REC_ID=steps.REC_ID')
					->leftJoin('ingredients', 'steps.ING_ID=ingredients.ING_ID')
					->leftJoin('step_types', 'steps.STT_ID=step_types.STT_ID')
					->where($criteryaString)
					->order('steps.STE_STEP_NO')
					->queryAll();
			} else {
				$rows = array();
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
	public function loadModel($id)
	{
		$model=Recipes::model()->findByPk($id);
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
    public function actionDisplaySavedImage()
    {
            $model=$this->loadModel($_GET['id']);
            Yii::app()->request->sendFile('image.png', $model->REC_PICTURE, 'image/png');
    }
}
