<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

class CusineSubSubTypesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	protected $createBackup = 'CusineSubSubTypes_Backup';
	protected $searchBackup = 'CusineSubSubTypes';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','advanceSearch','chooseCusineSubSubTypes','advanceChooseCusineSubSubTypes'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','cancel','delicious','disgusting','showLike', 'showNotLike'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
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
		unset(Yii::app()->session[$this->createBackup]);
		$this->showLastNotCreateAction();
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		$this->checkRenderAjax('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	private function getModel($id){
		$Session_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Backup)){
			$oldmodel = $Session_Backup;
		}
		if (isset($id) && $id != null){
			if (!isset($oldmodel) || $oldmodel->CSS_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
		} else {
			$model=new CusineSubSubTypes;
		}
		return $model;
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$model = $this->getModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CusineSubSubTypes']))
		{
			$model->attributes=$_POST['CusineSubSubTypes'];
			if($model->save()){
				unset(Yii::app()->session[$this->createBackup]);
				unset(Yii::app()->session[$this->createBackup.'_Time']);
				$this->forwardAfterSave(array('view', 'id'=>$model->CSS_ID));
				//$this->forwardAfterSave(array('search', 'query'=>$model->__get('CSS_DESC_' . Yii::app()->session['lang'])));
				return;
			}
		}

		$cusineSubTypes = Yii::app()->db->createCommand()->select('CST_ID,CST_DESC_'.Yii::app()->session['lang'])->from('cusine_sub_types')->order('CST_DESC_'.Yii::app()->session['lang'])->queryAll();
		$cusineSubTypes = CHtml::listData($cusineSubTypes,'CST_ID','CST_DESC_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'cusineSubTypes'=>$cusineSubTypes,
		));
	}
		
		
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
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
	public function actionUpdate($id){
		$this->prepareCreateOrUpdate($id, 'update');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
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
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('CusineSubSubTypes');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	
	private function prepareSearch($view, $ajaxLayout, $criteria){
		$model=new CusineSubSubTypes('search');
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
		if(isset($_POST['CusineSubSubTypes'])) {
			$model->attributes=$_POST['CusineSubSubTypes'];
			$modelAvailable = true;
		}
		
		$Session_Data = Yii::app()->session[$this->searchBackup];
		if (isset($Session_Data)){
			if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['CusineSubSubTypes']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < $Session_Data['time'])){
				if (isset($Session_Data['query'])){
					$query = $Session_Data['query'];
					$model2->query = $query;
					//echo "query from session\n";
				}
				if (isset($Session_Data['model'])){
					$model = $Session_Data['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(), $model->getSearchFields(), $query, $model->tableName() . '.');
		if ($modelAvailable || $criteriaString != '' || $criteria != null){
			$command = Yii::app()->db->createCommand()
					->from($model->tableName());
				//Add aditional conditions, and joins here...
			if ($criteria != null){
				Yii::app()->session[$this->searchBackup] = array('time'=>time());
				
				$command->where($criteria->condition, $criteria->params);
				$this->validSearchPerformed = true;
			} else if($modelAvailable) {
				$Session_Data = array();
				if (isset($query)){
					$Session_Data['query'] = $query;
				}
				$Session_Data['model'] = $model;
				$Session_Data['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Data;
				
				$criteria = $model->getCriteriaString();
				//$command = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteriaString())
				
				if (isset($criteria->condition) && $criteria->condition != '') {
					if ($criteriaString != ''){
						$command->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
					} else {
						$command->where($criteria->condition, $criteria->params);
					}
				} else if ($criteriaString != ''){
					$command->where($criteriaString);
				}
				$this->validSearchPerformed = true;
			} else if ($criteriaString != ''){
				$Session_Data = array();
				$Session_Data['query'] = $query;
				$Session_Data['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Data;
				
				$command->where($criteriaString);
				$this->validSearchPerformed = true;
			} else {
				//$command->where('1=0');
				unset(Yii::app()->session[$this->searchBackup]);
			}
			$rows = $command->queryAll();
		} else {
			$rows = array();
			unset(Yii::app()->session[$this->searchBackup]);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'CSS_ID',
			'keyField'=>'CSS_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		/*
		if ($view == 'advanceSearch'){	
			//$groupNames = Yii::app()->db->createCommand()->select('GRP_ID,GRP_DESC_'.Yii::app()->session['lang'])->from('group_names')->queryAll();
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
				'groupNames'=>$groupNames,
			), $ajaxLayout);
		} else {*/
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
			), $ajaxLayout);
		//}
	}	
		
	public function actionAdvanceSearch(){
		$this->prepareSearch('advanceSearch', null, null);
	}
	
	public function actionSearch(){
		$this->prepareSearch('search', null, null);
	}
	
	public function actionChooseCusineSubSubTypes(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none', null);
	}
	
	public function actionAdvanceChooseCusineSubSubTypes(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none', null);
	}
	
	public function actionShowLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_LIKES_???')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(CusineSubSubTypes::model()->tableName().'.CSS_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
	}
	
	public function actionShowNotLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_NOTLIKES_???')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(CusineSubSubTypes::model()->tableName().'.CSS_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
	}
	
	public function actionDelicious($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, '???', true);
		$this->showLastAction();
	}
	
	public function actionDisgusting($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, '???', false);
		$this->showLastAction();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id){
		if ($id == 'backup'){
			$model=Yii::app()->session[$this->createBackup];
		} else {
			$model=CusineSubSubTypes::model()->findByPk($id);
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='cusine-sub-sub-types-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
