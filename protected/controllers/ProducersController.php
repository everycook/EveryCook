<?php

class ProducersController extends Controller
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
				'actions'=>array('index','view','chooseProducer'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','createFancy'),
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
	
	private function checkDuplicate($model){
		$duplicates = array();
		$command = Yii::app()->db->createCommand()
				->from('producers')
				->where('PRD_NAME like :name', array(':name'=>'%' . $model->PRD_NAME . '%'));
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				$dup_rows[] = $row['PRD_ID'] . ': ' . $row['PRD_NAME'];
			}
			$duplicates = array_merge($duplicates, array ('PRD_NAME'=>$dup_rows));
		}
		return $duplicates;
	}
	
	private function prepareCreateOrUpdate($id, $view, $ajaxLayout){
		if (isset($id)){
			$model=$this->loadModel($id);
		} else {
			$model=new Producers;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Producers'])) {
			$model->attributes=$_POST['Producers'];
			
			if ($model->validate()){
				$duplicates = $this->checkDuplicate($model);
				if ($duplicates != null && count($duplicates)>0 && !isset($_POST['ignoreDuplicates'])){
					foreach($duplicates as $dup_type => $values){
						if ($this->errorText != ''){
							$this->errorText .= '<br />';
						}
						if ($dup_type == 'PRD_NAME'){
							$this->errorText .='<p>There are already producers with a similar name:</p>';
						}
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}
					}
					$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
				} else {
					if($model->save()){
						$this->forwardAfterSave(array('view','id'=>$model->PRD_ID));
						return;
					}
				}
			}
		}

		$this->checkRenderAjax($view,array(
			'model'=>$model,
		), $ajaxLayout);
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$this->prepareCreateOrUpdate(null, 'create', null);
	}
	
	public function actionCreateFancy() {
		$this->isFancyAjaxRequest = true;
		$this->prepareCreateOrUpdate(null, 'create', 'none');
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$this->prepareCreateOrUpdate($id, 'update', 'none');
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
	
	public function actionChooseProducer(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none');
	}
	
	private function prepareSearch($view, $ajaxLayout){
		$model=new Producers('search');
		$model->unsetAttributes();  // clear any default values
		
		$model2 = new SimpleSearchForm();
		if(isset($_POST['SimpleSearchForm']))
			$model2->attributes=$_POST['SimpleSearchForm'];
		
		if(isset($_GET['query'])){
			$query = $_GET['query'];
		} else {
			$query = $model2->query;
		}
		
		$modelAvailable = false;
		if(isset($_POST['Producers'])) {
			$model->attributes=$_POST['Producers'];
			$modelAvailable = true;
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Producers']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['Producer']['time'])){
			$Session_Producer = Yii::app()->session['Producer'];
			if (isset($Session_Producer)){
				if (isset($Session_Producer['query'])){
					$query = $Session_Producer['query'];
					//echo "query from session\n";
				}
				if (isset($Session_Producer['model'])){
					$model = $Session_Producer['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'producers.');
		if ($criteriaString != ''){
			$Session_Producer = array();
			$Session_Producer['query'] = $query;
			$Session_Producer['time'] = time();
			Yii::app()->session['Producer'] = $Session_Producer;
			
			
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
			
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteria())->queryAll();
			$rows = Yii::app()->db->createCommand()
				->from('producers')
				->where($criteriaString)
				//->order('actor.first_name, actor.last_name, film.title')
				->queryAll();
			$this->validSearchPerformed = true;
		} else {
			$rows = array();
			unset(Yii::app()->session['Producer']);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'PRD_ID',
			'keyField'=>'PRD_ID',
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
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$this->prepareSearch('search', null);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Producers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Producers']))
			$model->attributes=$_GET['Producers'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Producers::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='producers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
