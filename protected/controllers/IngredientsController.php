<?php

require_once('functions.php');
class IngredientsController extends Controller
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
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage','getSubGroupSearch','getSubGroupForm'),
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
		$this->checkRenderAjax('view', array(
			'model'=>$this->loadModel($id),
		));
	}
	private function prepareCreateOrUpdate($oldmodel, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldModel->ING_PICTURE;
		} else {
			$model=new Ingredients;
		}
		
		if(isset($_POST['Ingredients']))
		{
			$model->attributes=$_POST['Ingredients'];
			$file = CUploadedFile::getInstance($model,'filename');
			if ($file){
				resizePicture($file->getTempName(), $file->getTempName(), 400, 400, 0.8, 3);
				$model->ING_PICTURE=file_get_contents($file->getTempName());
			} else {
				if ($oldPicture){
					$model->ING_PICTURE = $oldPicture;
				}
			}
			if ($oldmodel){
				$model->ING_CHANGED = time();
			} else {
				//$model->PRF_UID = Yii::app()->session['userID'];
				$model->PRF_UID = 1;
				$model->ING_CREATED = time();
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->ING_ID));
		}
		
		$nutrientData = Yii::app()->db->createCommand()->select('NUT_ID,NUT_DESC')->from('nutrient_data')->queryAll();
		$nutrientData = CHtml::listData($nutrientData,'NUT_ID','NUT_DESC');
		$groupNames = Yii::app()->db->createCommand()->select('GRP_ID,GRP_DESC_'.Yii::app()->session['lang'])->from('group_names')->queryAll();
		$groupNames = CHtml::listData($groupNames,'GRP_ID','GRP_DESC_'.Yii::app()->session['lang']);
		$subgroupNames = $this->getSubGroupDataById($model->ING_GROUP);
		$ingredientConveniences = Yii::app()->db->createCommand()->select('CONV_ID,CONV_DESC_'.Yii::app()->session['lang'])->from('ingredient_conveniences')->queryAll();
		$ingredientConveniences = CHtml::listData($ingredientConveniences,'CONV_ID','CONV_DESC_'.Yii::app()->session['lang']);
		$storability = Yii::app()->db->createCommand()->select('STORAB_ID,STORAB_DESC_'.Yii::app()->session['lang'])->from('storability')->queryAll();
		$storability = CHtml::listData($storability,'STORAB_ID','STORAB_DESC_'.Yii::app()->session['lang']);
		$ingredientStates = Yii::app()->db->createCommand()->select('STATE_ID,STATE_DESC_'.Yii::app()->session['lang'])->from('ingredient_states')->queryAll();
		$ingredientStates = CHtml::listData($ingredientStates,'STATE_ID','STATE_DESC_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'nutrientData'=>$nutrientData,
			'groupNames'=>$groupNames,
			'subgroupNames'=>$subgroupNames,
			'ingredientConveniences'=>$ingredientConveniences,
			'storability'=>$storability,
			'ingredientStates'=>$ingredientStates,
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
		$model=$this->loadModel($id, true);
		$this->prepareCreateOrUpdate($model, 'update');
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
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Ingredients');
		
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Forward to search.
	 */
	public function actionIndex()
	{
		$this->prepareSearch('search');
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ingredients('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ingredients']))
			$model->attributes=$_GET['Ingredients'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}
	
	private function prepareSearch($view){
		$model=new Ingredients('search');
		$model->unsetAttributes();  // clear any default values
		
		$model2 = new SimpleSearchForm();
		if(isset($_GET['SimpleSearchForm']))
			$model2->attributes=$_GET['SimpleSearchForm'];
		
		if(isset($_GET['query'])){
			$query = $_GET['query'];
		} else {
			$query = $model2->query;
		}
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$model2->query, 'ingredients.');
		
		if(isset($_GET['Ingredients'])) {
			$model->attributes=$_GET['Ingredients'];
			$criteria = $model->getCriteriaString();
			//$command = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteriaString())
			$command = Yii::app()->db->createCommand()
				->from('ingredients')
				->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
				->leftJoin('group_names', 'ingredients.ING_GROUP=group_names.GRP_ID')
				->leftJoin('subgroup_names', 'ingredients.ING_SUBGROUP=subgroup_names.SUBGRP_ID')
				->leftJoin('ingredient_conveniences', 'ingredients.ING_CONVENIENCE=ingredient_conveniences.CONV_ID')
				->leftJoin('storability', 'ingredients.ING_STORABILITY=storability.STORAB_ID')
				->leftJoin('ingredient_states', 'ingredients.ING_STATE=ingredient_states.STATE_ID')
				//->order('actor.first_name, actor.last_name, film.title')
				;
			
			if ($criteria->condition) {
				if ($criteriaString != ''){
					$command->where($criteria->condition . ' AND ' . $criteriaString);
				} else {
					$command->where($criteria->condition);
				}
				/*
				foreach($criteria->params as $key => $value){
					$command = $command->bindParam($key, $value);
					echo $key . " => " .$value . "\n";
				}
				*/
				//bind params seams not to work on "IN" condition...
				$command = preparedStatementToStatement($command, $criteria->params);
			} else if ($criteriaString){
				$command->where($criteriaString);
			}
			$rows = $command->queryAll();
			
			//print_r($rows);
		} else if ($criteriaString != ''){
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteria())->queryAll();
			$rows = Yii::app()->db->createCommand()
				->from('ingredients')
				->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
				->leftJoin('group_names', 'ingredients.ING_GROUP=group_names.GRP_ID')
				->leftJoin('subgroup_names', 'ingredients.ING_SUBGROUP=subgroup_names.SUBGRP_ID')
				->leftJoin('ingredient_conveniences', 'ingredients.ING_CONVENIENCE=ingredient_conveniences.CONV_ID')
				->leftJoin('storability', 'ingredients.ING_STORABILITY=storability.STORAB_ID')
				->leftJoin('ingredient_states', 'ingredients.ING_STATE=ingredient_states.STATE_ID')
				->where($criteriaString)
				//->order('actor.first_name, actor.last_name, film.title')
				->queryAll();
		} else {
			$rows = array();
		}
			
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'ING_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		
		if ($view == 'advanceSearch'){
			//$nutrientData = Yii::app()->db->createCommand()->select('NUT_ID,NUT_DESC')->from('nutrient_data')->queryAll();
			//$nutrientData = CHtml::listData($nutrientData,'NUT_ID','NUT_DESC');
			$groupNames = Yii::app()->db->createCommand()->select('GRP_ID,GRP_DESC_'.Yii::app()->session['lang'])->from('group_names')->queryAll();
			$groupNames = CHtml::listData($groupNames,'GRP_ID','GRP_DESC_'.Yii::app()->session['lang']);
			$subgroupNames = $this->getSubGroupData($model);
			$ingredientConveniences = Yii::app()->db->createCommand()->select('CONV_ID,CONV_DESC_'.Yii::app()->session['lang'])->from('ingredient_conveniences')->queryAll();
			$ingredientConveniences = CHtml::listData($ingredientConveniences,'CONV_ID','CONV_DESC_'.Yii::app()->session['lang']);
			$storability = Yii::app()->db->createCommand()->select('STORAB_ID,STORAB_DESC_'.Yii::app()->session['lang'])->from('storability')->queryAll();
			$storability = CHtml::listData($storability,'STORAB_ID','STORAB_DESC_'.Yii::app()->session['lang']);
			$ingredientStates = Yii::app()->db->createCommand()->select('STATE_ID,STATE_DESC_'.Yii::app()->session['lang'])->from('ingredient_states')->queryAll();
			$ingredientStates = CHtml::listData($ingredientStates,'STATE_ID','STATE_DESC_'.Yii::app()->session['lang']);
		
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
				'nutrientData'=>$nutrientData,
				'groupNames'=>$groupNames,
				'subgroupNames'=>$subgroupNames,
				'ingredientConveniences'=>$ingredientConveniences,
				'storability'=>$storability,
				'ingredientStates'=>$ingredientStates,
			));
		} else {
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
			));
		}
	}
	
	public function actionAdvanceSearch()
	{
		$this->prepareSearch('advanceSearch');
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search');
	}
	
	private function getSubGroupData($model){
		$criteria=new CDbCriteria;
		$criteria->select = 'SUBGRP_ID,SUBGRP_DESC_'.Yii::app()->session['lang'];
		//$criteria->from('subgroup_names');
		
		if(isset($model)) {
			if ($model->ING_GROUP){
				$criteria->compare('SUBGRP_OF',$model->ING_GROUP);
				$criteria->compare('SUBGRP_ID',$model->ING_SUBGROUP, false, 'OR');
			}
		}
		
		$command = Yii::app()->db->commandBuilder->createFindCommand('subgroup_names', $criteria, '');
		$subgroupNames = $command->queryAll();
		$subgroupNames = CHtml::listData($subgroupNames,'SUBGRP_ID','SUBGRP_DESC_'.Yii::app()->session['lang']);
		return $subgroupNames;
	}
	
	public function actionGetSubGroupSearch(){
		if(isset($_POST['Ingredients'])) {
			$model=new Ingredients('search');
			$model->unsetAttributes();  // clear any default values
			$model->attributes=$_POST['Ingredients'];
		}
		$subgroupNames = $this->getSubGroupData($model);
		
		$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
		$output = searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'ING_SUBGROUP', $subgroupNames, 1, 'subgroupNames', $htmlOptions_type1);
		echo $this->processOutput($output);
	}
	
	private function getSubGroupDataById($id){
		$criteria=new CDbCriteria;
		$criteria->select = 'SUBGRP_ID,SUBGRP_DESC_'.Yii::app()->session['lang'];
		if ($id){
			$criteria->compare('SUBGRP_OF', $id);
		}
		
		$command = Yii::app()->db->commandBuilder->createFindCommand('subgroup_names', $criteria, '');
		$subgroupNames = $command->queryAll();
		$subgroupNames = CHtml::listData($subgroupNames,'SUBGRP_ID','SUBGRP_DESC_'.Yii::app()->session['lang']);
		return $subgroupNames;
	}
	
	public function actionGetSubGroupForm(){
		$id = $_GET['id'];
		$subgroupNames = $this->getSubGroupDataById($id);
		
		$model=new Ingredients('form');
		$htmlOptions_type0 = array('empty'=>$this->trans->INGREDIENTS_SEARCH_CHOOSE);
		//$output = createInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'ING_SUBGROUP', $subgroupNames, 0, 'subgroupNames', $htmlOptions_type0, null);
		$fieldName = 'ING_SUBGROUP';
		$output = CHtml::dropDownList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $subgroupNames, $htmlOptions_type0); 
		echo $this->processOutput($output);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $withPicture = false)
	{
		$model=Ingredients::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='ingredients-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    public function actionDisplaySavedImage($id)   // STL show image
    {
		$model=$this->loadModel($id, true);
		//Yii::app()->request->sendFile('image.png', $model->ING_PICTURE, 'image/png');
		//Not using function to have posibility to set Cache control...
		header('Pragma: public');
		header('Expires: 1000');
		//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header("Content-type: image/png");
		if(ini_get("output_handler")=='')
			header('Content-Length: '.(function_exists('mb_strlen') ? mb_strlen($model->ING_PICTURE,'8bit') : strlen($model->ING_PICTURE)));
		header("Content-Disposition: attachment; filename=\"image_" . $id . ".png\"");
		header('Content-Transfer-Encoding: binary');
		echo $model->ING_PICTURE;
    }
}
