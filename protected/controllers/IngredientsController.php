<?php
class IngredientsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $errorText = '';
	
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
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage','getSubGroupSearch','getSubGroupForm','chooseIngredient','advanceChooseIngredient'),
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
		$this->checkRenderAjax('view', array(
			'model'=>$this->loadModel($id),
		));
	}
	
	private function checkDuplicate($model){
		$duplicates = array();
		$command = Yii::app()->db->createCommand()
				->from('ingredients')
				->where('ingredients.NUT_ID=:nut_id', array(':nut_id'=>$model->NUT_ID));
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['ING_ID'] . ': ' . $row['ING_NAME_EN'] . ' / ' . $row['ING_NAME_DE']);
			}
			$duplicates = array_merge($duplicates, array ('NUT_ID'=>$dup_rows));
		}
		
		$command = Yii::app()->db->createCommand()
				->from('ingredients');
		if ($model->ING_NAME_EN != '' && $model->ING_NAME_DE != ''){
			$command->where('ingredients.ING_NAME_EN like :en or ingredients.ING_NAME_DE like :de', array(':en'=>'%' . $model->ING_NAME_EN . '%', ':de'=>'%' . $model->ING_NAME_DE . '%'));
		} else if ($model->ING_NAME_EN != ''){
			$command->where('ingredients.ING_NAME_EN like :en', array(':en'=>'%' . $model->ING_NAME_EN . '%'));
		} else if ($model->ING_NAME_DE != ''){
			$command->where('ingredients.ING_NAME_DE like :de', array(':de'=>'%' . $model->ING_NAME_DE . '%'));
		}
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['ING_ID'] . ': ' . $row['ING_NAME_EN'] . ' / ' . $row['ING_NAME_DE']);
			}
			$duplicates = array_merge($duplicates, array ('TITLE'=>$dup_rows));
		}
		return $duplicates;
	}
	
	public function actionUploadImage(){
		$id = $_GET['id'];
		
		$Session_Ingredient_Backup = Yii::app()->session['Ingredient_Backup'];
		if ($Session_Ingredient_Backup){
			$oldmodel = $Session_Ingredient_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->ING_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldmodel->ING_IMG;
		} else {
			$model=new Ingredients;
		}
		
		if(isset($_POST['Ingredients'])){
			$model->attributes=$_POST['Ingredients'];
			$sucessfull = Functions::uploadPicture($model,'ING_IMG');
			Yii::app()->session['Ingredient_Backup'] = $model;
			
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
		
		$Session_Ingredient_Backup = Yii::app()->session['Ingredient_Backup'];
		if ($Session_Ingredient_Backup){
			$oldmodel = $Session_Ingredient_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->ING_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldmodel->ING_IMG;
		} else {
			$model=new Ingredients;
		}
		
		if(isset($_POST['Ingredients'])){
			$model->attributes=$_POST['Ingredients'];
			
			Functions::updatePicture($model,'ING_IMG', $oldPicture);
			/*
			if (isset($model->ING_ID)){
				$model->ING_CHANGED = time();
			} else {
				//$model->PRF_UID = Yii::app()->session['userID'];
				$model->PRF_UID = 1;
				$model->ING_CREATED = time();
			}
			*/
			Yii::app()->session['Ingredient_Backup'] = $model;
			if ($model->validate()){
				if (!isset($model->ING_ID)){
					$duplicates = $this->checkDuplicate($model);
				}
				if ($duplicates != null && count($duplicates)>0 && !isset($_POST['ignoreDuplicates'])){
					foreach($duplicates as $dup_type => $values){
						if ($this->errorText != ''){
							$this->errorText .= '<br />';
						}
						if ($dup_type == 'TITLE'){
							$this->errorText .='<p>There are already Ingredients with a similar title:</p>';
						} else {
							$this->errorText .='<p>There are already Ingredients using the same NutrientData entry:</p>';
						}
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}
					}
					$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
				} else {
					if($model->save()){
						unset(Yii::app()->session['Ingredient_Backup']);
						if($this->useAjaxLinks){
							echo "{hash:'" . $this->createUrlHash('view', array('id'=>$model->ING_ID)) . "'}";
							exit;
						} else {
							$this->redirect(array('view', 'id'=>$model->ING_ID));
						}
					}
				}
			}
			if ($model->NUT_ID && (!$model->nutrientData || !$model->nutrientData->NUT_DESC || $model->NUT_ID != $model->nutrientData->NUT_ID)){
				$model->nutrientData = NutrientData::model()->findByPk($model->NUT_ID);
			}
		}
		
		$nutrientData = Yii::app()->db->createCommand()->select('NUT_ID,NUT_DESC')->from('nutrient_data')->queryAll();
		$nutrientData = CHtml::listData($nutrientData,'NUT_ID','NUT_DESC');
		$groupNames = Yii::app()->db->createCommand()->select('GRP_ID,GRP_DESC_'.Yii::app()->session['lang'])->from('group_names')->queryAll();
		$groupNames = CHtml::listData($groupNames,'GRP_ID','GRP_DESC_'.Yii::app()->session['lang']);
		$subgroupNames = $this->getSubGroupDataById($model->GRP_ID);
		$ingredientConveniences = Yii::app()->db->createCommand()->select('ICO_ID,ICO_DESC_'.Yii::app()->session['lang'])->from('ingredient_conveniences')->queryAll();
		$ingredientConveniences = CHtml::listData($ingredientConveniences,'ICO_ID','ICO_DESC_'.Yii::app()->session['lang']);
		$storability = Yii::app()->db->createCommand()->select('STB_ID,STB_DESC_'.Yii::app()->session['lang'])->from('storability')->queryAll();
		$storability = CHtml::listData($storability,'STB_ID','STB_DESC_'.Yii::app()->session['lang']);
		$ingredientStates = Yii::app()->db->createCommand()->select('IST_ID,IST_DESC_'.Yii::app()->session['lang'])->from('ingredient_states')->queryAll();
		$ingredientStates = CHtml::listData($ingredientStates,'IST_ID','IST_DESC_'.Yii::app()->session['lang']);
		
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
		if(isset($_POST['Ingredients']))
			$model->attributes=$_POST['Ingredients'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}
	
	private function prepareSearch($view, $ajaxLayout){
		$model=new Ingredients('search');
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
		if(isset($_POST['Ingredients'])) {
			$model->attributes=$_POST['Ingredients'];
			$modelAvailable = true;
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Ingredients']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['Ingredient']['time'])){
			$Session_Ingredient = Yii::app()->session['Ingredient'];
			if ($Session_Ingredient){
				if ($Session_Ingredient['query']){
					$query = $Session_Ingredient['query'];
					//echo "query from session\n";
				}
				if ($Session_Ingredient['model']){
					$model = $Session_Ingredient['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'ingredients.');
		
		if($modelAvailable) {
			$Session_Ingredient = array();
			if ($query){
				$Session_Ingredient['query'] = $query;
			}
			$Session_Ingredient['model'] = $model;
			$Session_Ingredient['time'] = time();
			Yii::app()->session['Ingredient'] = $Session_Ingredient;
			
			$criteria = $model->getCriteriaString();
			//$command = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteriaString())
			$command = Yii::app()->db->createCommand()
				->select('ingredients.*, nutrient_data.*, group_names.*, subgroup_names.*, ingredient_conveniences.*, storability.*, ingredient_states.*, count(products.PRO_ID) as pro_count' /*, count(pro_to_sto.SUP_ID) as sup_count'*/)
				->from('ingredients')
				->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
				->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
				->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
				->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
				->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
				->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
				->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
				//->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
				->group('ingredients.ING_ID')
				//->order('actor.first_name, actor.last_name, film.title')
				;
				//echo $command->text;
			$supplierCommand = Yii::app()->db->createCommand()
				->select('ingredients.id, count(products.PRO_ID) as pro_count, count(pro_to_sto.SUP_ID) as sup_count')
				->from('ingredients')
				->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
				->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
				->leftJoin('suppliers', 'suppliers.SUP_ID=pro_to_sto.SUP_ID')
				->group('ingredients.ING_ID suppliers.SUP_ID')
				
				
				//TODO: where ingredients.id in command values
			
			
			
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
				//TODO verify: bind params seams not to work on "IN" condition...
				$command = Functions::preparedStatementToStatement($command, $criteria->params);
				$this->validSearchPerformed = true;
			} else if ($criteriaString){
				$command->where($criteriaString);
				$this->validSearchPerformed = true;
			}
			$rows = $command->queryAll();
			
			//print_r($rows);
		} else if ($criteriaString != ''){
			$Session_Ingredient = array();
			$Session_Ingredient['query'] = $query;
			$Session_Ingredient['time'] = time();
			Yii::app()->session['Ingredient'] = $Session_Ingredient;
			
			
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteria())->queryAll();
			$rows = Yii::app()->db->createCommand()
				->from('ingredients')
				->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
				->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
				->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
				->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
				->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
				->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
				->where($criteriaString)
				//->order('actor.first_name, actor.last_name, film.title')
				->queryAll();
			
			$this->validSearchPerformed = true;
		} else {
			$rows = array();
			unset(Yii::app()->session['Ingredient']);
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
			$ingredientConveniences = Yii::app()->db->createCommand()->select('ICO_ID,ICO_DESC_'.Yii::app()->session['lang'])->from('ingredient_conveniences')->queryAll();
			$ingredientConveniences = CHtml::listData($ingredientConveniences,'ICO_ID','ICO_DESC_'.Yii::app()->session['lang']);
			$storability = Yii::app()->db->createCommand()->select('STB_ID,STB_DESC_'.Yii::app()->session['lang'])->from('storability')->queryAll();
			$storability = CHtml::listData($storability,'STB_ID','STB_DESC_'.Yii::app()->session['lang']);
			$ingredientStates = Yii::app()->db->createCommand()->select('IST_ID,IST_DESC_'.Yii::app()->session['lang'])->from('ingredient_states')->queryAll();
			$ingredientStates = CHtml::listData($ingredientStates,'IST_ID','IST_DESC_'.Yii::app()->session['lang']);
		
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
			), $ajaxLayout);
		} else {
			$this->checkRenderAjax($view,array(
				'model'=>$model,
				'model2'=>$model2,
				'dataProvider'=>$dataProvider,
			), $ajaxLayout);
		}
	}
	
	public function actionAdvanceSearch()
	{
		$this->prepareSearch('advanceSearch', null);
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search', null);
	}
	
	public function actionChooseIngredient(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none');
	}
	
	public function actionAdvanceChooseIngredient(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none');
	}
	
	private function getSubGroupData($model){
		if(isset($model) && $model->GRP_ID){
			$criteria=new CDbCriteria;
			$criteria->select = 'SGR_ID,SGR_DESC_'.Yii::app()->session['lang'];
			//$criteria->from('subgroup_names');
			
			$criteria->compare('GRP_ID',$model->GRP_ID);
			//$criteria->compare('SGR_ID',$model->SGR_ID, false, 'OR');
		
			$command = Yii::app()->db->commandBuilder->createFindCommand('subgroup_names', $criteria, '');
			$subgroupNames = $command->queryAll();
			$subgroupNames = CHtml::listData($subgroupNames,'SGR_ID','SGR_DESC_'.Yii::app()->session['lang']);
			return $subgroupNames;
		} else {
			return array();
		}
	}
	
	public function actionGetSubGroupSearch(){
		if(isset($_POST['Ingredients'])) {
			$model=new Ingredients('search');
			$model->unsetAttributes();  // clear any default values
			$model->attributes=$_POST['Ingredients'];
		}
		$subgroupNames = $this->getSubGroupData($model);
		
		$htmlOptions_type1 = array('template'=>'<li>{input} {label}</li>', 'separator'=>"\n", 'checkAll'=>$this->trans->INGREDIENTS_SEARCH_CHECK_ALL, 'checkAllLast'=>false);
		$output = Functions::searchCriteriaInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, 1, 'subgroupNames', $htmlOptions_type1);
		echo $this->processOutput($output);
	}
	
	private function getSubGroupDataById($id){
		if ($id){
			$criteria=new CDbCriteria;
			$criteria->select = 'SGR_ID,SGR_DESC_'.Yii::app()->session['lang'];
			$criteria->compare('GRP_ID', $id);
			
			$command = Yii::app()->db->commandBuilder->createFindCommand('subgroup_names', $criteria, '');
			$subgroupNames = $command->queryAll();
			$subgroupNames = CHtml::listData($subgroupNames,'SGR_ID','SGR_DESC_'.Yii::app()->session['lang']);
			return $subgroupNames;
		} else {
			return array();
		}
	}
	
	public function actionGetSubGroupForm(){
		$id = $_GET['id'];
		$subgroupNames = $this->getSubGroupDataById($id);
		
		$model=new Ingredients('form');
		if ($id){
			$htmlOptions_subGroup = array('empty'=>$this->trans->GENERAL_CHOOSE);
		} else {
			$htmlOptions_subGroup = array('empty'=>$this->trans->INGREDIENTS_CHOOSE_GROUP_FIRST);
		}
		//$output = createInput($this->trans->INGREDIENTS_SUBGROUP, $model, 'SGR_ID', $subgroupNames, 0, 'subgroupNames', $htmlOptions_subGroup, null);
		$fieldName = 'SGR_ID';
		$output = CHtml::dropDownList(CHtml::resolveName($model,$fieldName), $model->__get($fieldName), $subgroupNames, $htmlOptions_subGroup); 
		echo $this->processOutput($output);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $withPicture = false)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session['Ingredient_Backup'];
		} else {
			$model=Ingredients::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='ingredients-form')
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
		return Functions::getImage($modified, $model->ING_IMG_ETAG, $model->ING_IMG, $id);
    }
}
