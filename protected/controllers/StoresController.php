<?php

class StoresController extends Controller
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
	
	protected $createBackup = 'Stores_Backup';
	protected $searchBackup = 'Stores';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','advanceSearch','chooseStores','advanceChooseStores','displaySavedImage','getStoresInRange', 'getStoresInRangeWithProduct', 'currentGPSForStores', 'storeFinder','addressInput'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','assign','cancel'),
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
		if (isset($Session_Backup) && isset($Session_Backup->STO_ID)){
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->STO_ID));
		} else {
			unset(Yii::app()->session[$this->createBackup]);
			$this->showLastNotCreateAction();
			//$this->forwardAfterSave(array('search'));
		}
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
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Stores = Yii::app()->session[$this->createBackup];
		if (isset($Session_Stores)){
			$oldmodel = $Session_Stores;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->STO_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->STO_IMG;
		} else {
			$model=new Stores;
			$oldPicture = null;
		}
		if (isset($model->STO_IMG) && $model->STO_IMG != ''){
			$model->setScenario('withPic');
		}
		
		if(isset($_POST['Stores'])){
			$model->attributes=$_POST['Stores'];
			
			Functions::updatePicture($model,'STO_IMG', $oldPicture);
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if ($model->validate()){
				$duplicates = null;
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
							$this->errorText .='<p>There are already Stores on same address:</p>';
						}
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}
					}
					$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
				} else {
					if ($model->STO_GPS_LAT != '' && $model->STO_GPS_LNG != '' ){
						$model->STO_GPS_POINT = 'POINT(' . $model->STO_GPS_LAT . ' ' . $model->STO_GPS_LNG . ')';
					} else {
						$model->STO_GPS_POINT = null;
					}
					if($model->save()){
						unset(Yii::app()->session[$this->createBackup]);
						unset(Yii::app()->session[$this->createBackup.'_Time']);
						$this->forwardAfterSave(array('view', 'id'=>$model->STO_ID));
						return;
					}
				}
			}
		}
		$supplier = Yii::app()->db->createCommand()->select('SUP_ID,SUP_NAME')->from('suppliers')->queryAll();
		$supplier = CHtml::listData($supplier,'SUP_ID','SUP_NAME');
		$storeType = Yii::app()->db->createCommand()->select('STY_ID,STY_TYPE_'.Yii::app()->session['lang'])->from('store_types')->queryAll();
		$storeType = CHtml::listData($storeType,'STY_ID','STY_TYPE_'.Yii::app()->session['lang']);
		$countrys = Yii::app()->db->createCommand()->select('CRY_ID,CRY_NAME_'.Yii::app()->session['lang'])->from('countrys')->order('CRY_NAME_'.Yii::app()->session['lang'])->queryAll();
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
	
	private function checkDuplicateAssing($model){
		$duplicates = array();
		$command = Yii::app()->db->createCommand()
			->from('pro_to_sto')
			->leftJoin('suppliers', 'suppliers.SUP_ID=pro_to_sto.SUP_ID')
			->leftJoin('store_types', 'store_types.STY_ID=pro_to_sto.STY_ID')
			->where('pro_to_sto.PRO_ID = :pro_id AND pro_to_sto.SUP_ID = :sup_id AND pro_to_sto.STY_ID = :sty_id', array(':pro_id'=>$model->PRO_ID, ':sup_id'=>$model->SUP_ID, ':sty_id'=>$model->STY_ID));
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['PRO_ID'] . ': ' . $row['SUP_NAME'] . ', ' . $row['STY_TYPE_'.Yii::app()->session['lang']]);
			}
			$duplicates = array_merge($duplicates, array ('ID'=>$dup_rows));
		}
		return $duplicates;
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
				$duplicates = $this->checkDuplicateAssing($model);
				if ($duplicates != null && count($duplicates)>0){
					foreach($duplicates as $dup_type => $values){
						if ($this->errorText != ''){
							$this->errorText .= '<br />';
						}
						$this->errorText .='<p>There are already a Product to Shop assign for the defined product / shop(supplier, store type) combination.</p>';
						/*
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}*/
					}
				} else {
					try {
						if($model->save()){
							if(!isset($_POST['saveAddNext'])){
								$this->forwardAfterSave(array('products/search'));
								return;
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
		if (count($productName) == 0){
			throw new CHttpException(404,'No Product with id ' . $model->PRO_ID . ' found.');
		}
		$productName = $productName[0]['PRO_NAME_'.Yii::app()->session['lang']];
		
		$this->checkRenderAjax('assign',array(
			'model'=>$model,
			'model2'=>$model2,
			'supplier'=>$supplier,
			'storeType'=>$storeType,
			'productName'=>$productName,
		));
	}
	
	public function actionStoreFinder(){
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
		
		$supplier = Yii::app()->db->createCommand()->select('SUP_ID,SUP_NAME')->from('suppliers')->queryAll();
		$supplier = CHtml::listData($supplier,'SUP_ID','SUP_NAME');
		$storeType = Yii::app()->db->createCommand()->select('STY_ID,STY_TYPE_'.Yii::app()->session['lang'])->from('store_types')->queryAll();
		$storeType = CHtml::listData($storeType,'STY_ID','STY_TYPE_'.Yii::app()->session['lang']);
		
		$productName = Yii::app()->db->createCommand()->select('PRO_NAME_'.Yii::app()->session['lang'])->from('products')->where('PRO_ID=:id', array(':id'=>$model->PRO_ID))->queryAll();
		if (count($productName) == 0){
			$productName = '';
		} else {
			$productName = $productName[0]['PRO_NAME_'.Yii::app()->session['lang']];
		}
		
		$this->checkRenderAjax('storeFinder',array(
			'model'=>$model,
			'model2'=>$model2,
			'supplier'=>$supplier,
			'storeType'=>$storeType,
			'productName'=>$productName,
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
		
		if ($query != $model2->query){
			$model2->query = $query;
		}
		
		$modelAvailable = false;
		if(isset($_POST['Stores'])) {
			$model->attributes=$_POST['Stores'];
			$modelAvailable = true;
		}
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Stores']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session[$this->searchBackup]['time'])){
			$Session_Stores = Yii::app()->session[$this->searchBackup];
			if (isset($Session_Stores)){
				if (isset($Session_Stores['query'])){
					$query = $Session_Stores['query'];
					//echo "query from session\n";
				}
				if (isset($Session_Stores['model'])){
					$model = $Session_Stores['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'stores.');
		
		if($modelAvailable) {
			$Session_Stores = array();
			if (isset($query)){
				$Session_Stores['query'] = $query;
			}
			$Session_Stores['model'] = $model;
			$Session_Stores['time'] = time();
			Yii::app()->session[$this->searchBackup] = $Session_Stores;
			
			$criteria = $model->getCriteriaString();
			//$command = $model->commandBuilder->createFindCommand($model->tableName(), $criteria);
			
			if (isset($criteria->condition) && $criteria->condition != '') {
				if ($criteriaString != ''){
					$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
				} else {
					$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteria->condition, $criteria->params);
				}
				$this->validSearchPerformed = true;
			} else if (isset($criteriaString) && $criteriaString != ''){
				$command->where($criteriaString);
				$this->validSearchPerformed = true;
			}
			$rows = $command->queryAll();
			//TODO: return SUP_NAME
			
			//print_r($rows);
		} else if ($criteriaString != ''){
			$Session_Stores = array();
			$Session_Stores['query'] = $query;
			$Session_Stores['time'] = time();
			Yii::app()->session[$this->searchBackup] = $Session_Stores;
			
			//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
			$command = Yii::app()->db->createCommand()->from($model->tableName())->where($criteriaString);
			$rows = $command->queryAll();
			
			$this->validSearchPerformed = true;
		} else {
			$rows = array();
			unset(Yii::app()->session[$this->searchBackup]);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'STO_ID',
			'keyField'=>'STO_ID',
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
			$model=Yii::app()->session[$this->createBackup];
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
		$this->saveLastAction = false;
		$model=$this->loadModel($id, true);
		$modified = $model->CREATED_ON;
		if (!$modified){
			$modified = $model->CHANGED_ON;
		}
		return Functions::getImage($modified, $model->STO_IMG_ETAG, $model->STO_IMG, $id);
    }
	
	public function actionGetStoresInRange(){
		$this->saveLastAction = false;
		$southWestLat = $_POST["southWestLat"];
		$southWestLng = $_POST["southWestLng"];
		$northEastLat = $_POST["northEastLat"];
		$northEastLng = $_POST["northEastLng"];
		$zoom = $_POST["zoom"];
		
		//$profile=Profiles::model()->findByPk(Yii::app()->user->id);
		//if($profile===null || $profile->PRF_LOC_GPS_POINT == null || $profile->PRF_LOC_GPS_POINT == ''){
		if (Yii::app()->user->isGuest || !isset(Yii::app()->user->home_gps) || !isset(Yii::app()->user->home_gps[2])){
			//TODO: change text
			$distanceSql = "concat('No Home set...')";
		} else {
			$distanceSql = 'cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'' . Yii::app()->user->home_gps[2] . '\'))';
		}
		
		$stores = Yii::app()->db->createCommand()->select('stores.*, STY_TYPE_'.Yii::app()->session['lang']. ' as STY_TYPE, SUP_NAME, ' . $distanceSql . ' as distance')
			->from('stores')			
			->leftJoin('store_types', 'stores.STY_ID=store_types.STY_ID')
			->leftJoin('suppliers', 'stores.SUP_ID=suppliers.SUP_ID')
			->where("MBRContains(GeomFromText('POLYGON(($northEastLat $northEastLng, $southWestLat $northEastLng,$southWestLat $southWestLng,$northEastLat $southWestLng,$northEastLat $northEastLng))'), stores.STO_GPS_POINT) = 1")
			//->limit(100)
			->queryAll();
		
		$this->renderPartial('store_xml',array('stores'=>$stores,'zoom'=>$zoom));
	}
	
	public function actionGetStoresInRangeWithProduct(){
		$this->saveLastAction = false;
		$southWestLat = $_POST["southWestLat"];
		$southWestLng = $_POST["southWestLng"];
		$northEastLat = $_POST["northEastLat"];
		$northEastLng = $_POST["northEastLng"];
		$zoom = $_POST["zoom"];
		$pro_id = $_POST["product_id"];
		$startLat = $_POST["startLat"];
		$startLng = $_POST["startLng"];
		if ($startLat == '' || $startLng == ''){
			//TODO: change text
			$distanceSql = "concat('No center for Distance set.')";
		} else {
			$distanceSql = 'cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'POINT(' . $startLat . ' ' . $startLng . ')\'))';
		}
		
		$stores = Yii::app()->db->createCommand()->select('stores.*, STY_TYPE_'.Yii::app()->session['lang']. ' as STY_TYPE, SUP_NAME, ' . $distanceSql . ' as distance')
			->from('stores')
			->leftJoin('store_types', 'stores.STY_ID=store_types.STY_ID')
			->leftJoin('suppliers', 'stores.SUP_ID=suppliers.SUP_ID')
			->leftJoin('pro_to_sto', 'pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID')
			->where("pro_to_sto.PRO_ID = " . $pro_id . " AND MBRContains(GeomFromText('POLYGON(($northEastLat $northEastLng, $southWestLat $northEastLng,$southWestLat $southWestLng,$northEastLat $southWestLng,$northEastLat $northEastLng))'), stores.STO_GPS_POINT) = 1")
			//->limit(100)
			->queryAll();
		
		$this->renderPartial('store_xml',array('stores'=>$stores,'zoom'=>$zoom));
	}
	
	public function actionCurrentGPSForStores(){
		$this->saveLastAction = false;
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$time = $_POST['time'];
		if ($lat != null && $lng != null){
			$current_gps = array($lat, $lng, 'POINT(' . $lat . ' ' . $lng . ')');
			Yii::app()->session['current_gps'] = $current_gps;
			Yii::app()->session['current_gps_time'] = $time;
		}
	}
	
	
	public function actionAddressInput(){
		$this->saveLastAction = false;
		$model=new Stores;
		$countrys = Yii::app()->db->createCommand()->select('CRY_ID,CRY_NAME_'.Yii::app()->session['lang'])->from('countrys')->order('CRY_NAME_'.Yii::app()->session['lang'])->queryAll();
		$countrys = CHtml::listData($countrys,'CRY_ID','CRY_NAME_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax('address',array(
			'model'=>$model,
			'countrys'=>$countrys,
		), 'none');
	}
}
