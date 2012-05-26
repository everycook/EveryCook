<?php

class StoresController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $errorText='';

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
				'actions'=>array('index','view','search','advanceSearch','chooseStores','advanceChooseStores','displaySavedImage'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','assign'),
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
		/*
		$command = Yii::app()->db->createCommand()
				->from('store')
				->where('products.ING_ID=:ing_id', array(':ing_id'=>$model->ING_ID));
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['PRO_ID'] . ': ' . $row['PRO_NAME_EN'] . ' / ' . $row['PRO_NAME_DE']);
			}
			$duplicates = array_merge($duplicates, array ('ING_ID'=>$dup_rows));
		}
		
		$command = Yii::app()->db->createCommand()
				->from('products');
		if ($model->PRO_NAME_EN != '' && $model->PRO_NAME_DE != ''){
			$command->where('products.PRO_NAME_EN like :en or products.PRO_NAME_DE like :de', array(':en'=>'%' . $model->PRO_NAME_EN . '%', ':de'=>'%' . $model->PRO_NAME_DE . '%'));
		} else if ($model->PRO_NAME_EN != ''){
			$command->where('products.PRO_NAME_EN like :en', array(':en'=>'%' . $model->PRO_NAME_EN . '%'));
		} else if ($model->PRO_NAME_DE != ''){
			$command->where('products.PRO_NAME_DE like :de', array(':de'=>'%' . $model->PRO_NAME_DE . '%'));
		}
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['PRO_ID'] . ': ' . $row['PRO_NAME_EN'] . ' / ' . $row['PRO_NAME_DE']);
			}
			$duplicates = array_merge($duplicates, array ('TITLE'=>$dup_rows));
		}
		*/
		return $duplicates;
	}
	
	private function addressToGPS($model){
	
	}
	
	private function GPSToAddress($model){
	
	}
	
	private function saveModel($model){
		if ($model->validate()){
			if (!isset($model->STO_ID)){
				$duplicates = $this->checkDuplicate($model);
			}
			if ($duplicates != null && count($duplicates)>0 && !isset($_POST['ignoreDuplicates'])){
				foreach($duplicates as $dup_type => $values){
					if ($this->errorText != ''){
						$this->errorText .= '<br />';
					}
					if ($dup_type == 'NAME'){
						$this->errorText .='<p>There are already Stores with similar names:</p>';
					} else {
						$this->errorText .='<p>There are already Stores on same adsress:</p>';
					}
					foreach($values as $dup){
						$this->errorText .= $dup . '<br />';
					}
				}
				$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
			} else {
				if($model->save()){
					unset(Yii::app()->session['Stores_Backup']);
					if($this->useAjaxLinks){
						echo "{hash:'" . $this->createUrlHash('view', array('id'=>$model->STO_ID)) . "'}";
						exit;
					} else {
						$this->redirect(array('view', 'id'=>$model->STO_ID));
					}
				}
			}
		}
	}
	
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Stores = Yii::app()->session['Stores_Backup'];
		if ($Session_Stores){
			$oldmodel = $Session_Stores;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->STO_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldmodel->STO_IMG;
		} else {
			$model=new Stores;
		}
		
		if(isset($_POST['Stores'])){
			$model->attributes=$_POST['Stores'];
			
			Functions::updatePicture($model,'STO_IMG', $oldPicture);
			
			/*
			if (isset($model->STO_ID)){
				$model->CHANGED_ON = time();
			} else {
				//$model->PRF_UID = Yii::app()->session['userID'];
				$model->CREATED_BY = 1;
				$model->CREATED_ON = time();
			}
			*/
			
			Yii::app()->session['Stores_Backup'] = $model;
			if (isset($_POST['save'])){
				saveModel($model);
			} else if (isset($_POST['Address_to_GPS'])){
				addressToGPS($model);
			} else if (isset($_POST['GPS_to_Address'])){
				GPSToAddress($model);
			}
		}
		$supplier = Yii::app()->db->createCommand()->select('SUP_ID,SUP_NAME')->from('suppliers')->queryAll();
		$supplier = CHtml::listData($supplier,'SUP_ID','SUP_NAME');
		$storeType = Yii::app()->db->createCommand()->select('STY_ID,STY_TYPE_'.Yii::app()->session['lang'])->from('store_types')->queryAll();
		$storeType = CHtml::listData($storeType,'STY_ID','STY_TYPE_'.Yii::app()->session['lang']);
		$countrys = Yii::app()->db->createCommand()->select('CRY_ID,CRY_NAME_'.Yii::app()->session['lang'])->from('countrys')->queryAll();
		$countrys = CHtml::listData($countrys,'CRY_ID','CRY_NAME_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'supplier'=>$supplier,
			'storeType'=>$storeType,
			'countrys'=>$countrys,
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
	
	private function checkDuplicateAssing($model){
		return null;
	}
	
	public function actionAssign(){
		$model = new ProToSto;
		$model2 = new SimpleSearchForm();
		$model2->query = $this->trans->STORES_ASSIGN_ADDRESS_OR_NAME;
		
		if(isset($_POST['ProToSto'])){
			$model->attributes = $_POST['ProToSto'];
		}
		
		$pro_id = null;
		if(isset($_GET['pro_id'])){
			$pro_id = $_GET['pro_id'];
			
			if (!$model->PRO_ID && $pro_id){
				$model->PRO_ID = $pro_id;
			}
		}
		if(isset($_POST['ProToSto'])){
			if ($model->validate()){
				if (!isset($model->PRO_ID)){
					$duplicates = $this->checkDuplicateAssing($model);
				}
				if ($duplicates != null && count($duplicates)>0 && !isset($_POST['ignoreDuplicates'])){
					foreach($duplicates as $dup_type => $values){
						if ($this->errorText != ''){
							$this->errorText .= '<br />';
						}
						$this->errorText .='<p>There are already Product to Shop assign for the defined product/shop, entry are:</p>';
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}
					}
					$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
				} else {
					try {
						if($model->save()){
							if(!isset($_POST['saveAddNext'])){
								if($this->useAjaxLinks){
									echo "{hash:'" . $this->createUrlHash('products/view', array('id'=>$model->PRO_ID)) . "'}";
									exit;
								} else {
									$this->redirect(array('products/view', 'id'=>$model->PRO_ID));
								}
							}
						}
					} catch (Exception $e) {
						$this->errorText = 'Caught exception: ' .$e->getMessage() . "\n";
					}
				}
			}
		}
		
		$supplier = Yii::app()->db->createCommand()->select('SUP_ID,SUP_NAME')->from('suppliers')->queryAll();
		$supplier = CHtml::listData($supplier,'SUP_ID','SUP_NAME');
		$storeType = Yii::app()->db->createCommand()->select('STY_ID,STY_TYPE_'.Yii::app()->session['lang'])->from('store_types')->queryAll();
		$storeType = CHtml::listData($storeType,'STY_ID','STY_TYPE_'.Yii::app()->session['lang']);
		
		$productName = Yii::app()->db->createCommand()->select('PRO_NAME_'.Yii::app()->session['lang'])->from('products')->where('PRO_ID=:id', array(':id'=>$model->PRO_ID))->queryAll();
		$productName = $productName[0]['PRO_NAME_'.Yii::app()->session['lang']];
		
		//TODO get shops form current location
		$nearShops = array('1_1_6'=>'Basel Gundeli','2_1_5'=>'Basel Banhof SBB');
		
		$this->checkRenderAjax('assign',array(
			'model'=>$model,
			'model2'=>$model2,
			'supplier'=>$supplier,
			'storeType'=>$storeType,
			'productName'=>$productName,
			'nearShops'=>$nearShops,
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
		$this->prepareSearch('search', null);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Stores('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stores']))
			$model->attributes=$_GET['Stores'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}

	private function prepareSearch($view, $ajaxLayout){
		$model=new Stores('search');
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
		if(isset($_POST['Stores'])) {
			$model->attributes=$_POST['Stores'];
			$modelAvailable = true;
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Stores']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['Stores']['time'])){
			$Session_Stores = Yii::app()->session['Stores'];
			if ($Session_Stores){
				if ($Session_Stores['query']){
					$query = $Session_Stores['query'];
					//echo "query from session\n";
				}
				if ($Session_Stores['model']){
					$model = $Session_Stores['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'stores.');
		
		if($modelAvailable) {
			$Session_Stores = array();
			if ($query){
				$Session_Stores['query'] = $query;
			}
			$Session_Stores['model'] = $model;
			$Session_Stores['time'] = time();
			Yii::app()->session['Stores'] = $Session_Stores;
			
			$criteria = $model->getCriteriaString();
			//$command = $model->commandBuilder->createFindCommand($model->tableName(), $criteria);
			
			if ($criteria->condition) {
				if ($criteriaString != ''){
					$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteria->condition . ' AND ' . $criteriaString);
				} else {
					$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteria->condition);
				}
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
			$Session_Stores = array();
			$Session_Stores['query'] = $query;
			$Session_Stores['time'] = time();
			Yii::app()->session['Stores'] = $Session_Stores;
			
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
			$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteriaString);
			$rows = $command->queryAll();
			
			$this->validSearchPerformed = true;
		} else {
			$rows = array();
			unset(Yii::app()->session['Stores']);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'STO_ID',
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
	
	public function actionChooseStores(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none');
	}
	
	public function actionAdvanceChooseStores(){
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
			$model=Yii::app()->session['Stores_Backup'];
		} else {
			$model=Stores::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='stores-form')
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
		return Functions::getImage($modified, $model->STO_IMG_ETAG, $model->STO_IMG, $id);
    }
}
