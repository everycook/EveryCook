<?php

class StepsController extends Controller
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
				'actions'=>array('index','view'),
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
	public function actionView($id, $id2)
	{
		$this->checkRenderAjax('view',array(
			'model'=>$this->loadModel($id,$id2),
		));
	}

	private function prepareCreateOrUpdate($oldmodel, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($oldmodel)){
			$model = $oldmodel;
		} else {
			$model = new Steps;
		}
		
		if(isset($_POST['Steps'])) {
			$model->attributes=$_POST['Steps'];
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				if($model->save()){
					$this->redirect(array('view','id'=>$model->REC_ID,'id2'=>$model->STE_STEP_NO));
				}
			}
		}
		
		$stepTypes = Yii::app()->db->createCommand()->select('STT_ID,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->queryAll();
		$stepTypes = CHtml::listData($stepTypes,'STT_ID','STT_DESC_'.Yii::app()->session['lang']);
		$actions = Yii::app()->db->createCommand()->select('ACT_ID,ACT_DESC_AUTO_'.Yii::app()->session['lang'].',ACT_DESC_MAN_'.Yii::app()->session['lang'].',ACT_SKIP')->from('actions')->queryAll();
		$actions_auto = CHtml::listData($actions,'ACT_ID','ACT_DESC_AUTO_'.Yii::app()->session['lang']);
		$actions_man = CHtml::listData($actions,'ACT_ID','ACT_DESC_MAN_'.Yii::app()->session['lang']);
		$ingredients = Yii::app()->db->createCommand()->select('ING_ID,ING_DESC_'.Yii::app()->session['lang'])->from('ingredients')->queryAll();
		$ingredients = CHtml::listData($ingredients,'ING_ID','ING_DESC_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'stepTypes'=>$stepTypes,
			'actions'=>$actions_auto, //TODO submit both
			'ingredients'=>$ingredients,
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
	public function actionUpdate($id, $id2)
	{
		$model=$this->loadModel($id, $id2, true);
		$this->prepareCreateOrUpdate($model, 'update');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $id2)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				$this->loadModel($id,$id2)->delete();
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
		$dataProvider=new CActiveDataProvider('Steps');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Steps('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Steps']))
			$model->attributes=$_GET['Steps'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id,$id2)
	{
		$model=Steps::model()->findByPk(array(array('REC_ID'=>$id),array('STE_STEP_NO'=>$id2)));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='Steps-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
