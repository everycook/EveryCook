<?php

class ProductsController extends Controller
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
				'actions'=>array('index','view','search','displaySavedImage'),
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

	private function checkDuplicate($model){
		$duplicates = array();
		$command = Yii::app()->db->createCommand()
				->from('products')
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
		return $duplicates;
	}
	
	public function actionUploadImage(){
		$id = $_GET['id'];
		
		$Session_Product_Backup = Yii::app()->session['Product_Backup'];
		if ($Session_Product_Backup){
			$oldmodel = $Session_Product_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->PRO_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
		} else {
			$model=new Products;
		}
		
		if(isset($_POST['Products'])){
			$model->attributes=$_POST['Products'];
			$sucessfull = Functions::uploadPicture($model,'PRO_IMG');
			Yii::app()->session['Product_Backup'] = $model;
			
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
		
		$Session_Product_Backup = Yii::app()->session['Product_Backup'];
		if ($Session_Product_Backup){
			$oldmodel = $Session_Product_Backup;
		}
		if ($id){
			if (!$oldmodel || $oldmodel->PRO_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if ($oldmodel){
			$model = $oldmodel;
			$oldPicture = $oldmodel->PRO_IMG;
		} else {
			$model=new Products;
		}
		
		$ing_id = null;
		if(isset($_GET['ing_id'])){
			$ing_id = $_GET['ing_id'];
			
			if (!$model->ING_ID && $ing_id){
				$model->ING_ID = $ing_id;
			}
		}
		
		if(isset($_POST['Products'])){
			$model->attributes=$_POST['Products'];
			
			Functions::updatePicture($model,'PRO_IMG', $oldPicture);
			
			
			/*
			if (isset($model->PRO_ID)){
				$model->PRO_CHANGED = time();
			} else {
				//$model->PRF_UID = Yii::app()->session['userID'];
				$model->PRF_UID = 1;
				$model->PRO_CREATED = time();
			}
			*/
			if(isset($_POST['PRD_ID'])){
				if (!isset($model->oldProducers) || $model->oldProducers == null){
					$model->oldProducers = $model->producers;
				}
				$criteria=new CDbCriteria;
				$criteria->compare('PRD_ID',$_POST['PRD_ID']);
				$producers = new Producers;
				$model->producers = $producers->findAll($criteria, true);
				//$model->producers = Products::model()->findall($criteria);
			} else {
				$model->producers = array();
			}
			
			if (isset($_POST['PACKAGE_MULT'])){
				$model->PRO_PACKAGE_GRAMMS = $model->PRO_PACKAGE_GRAMMS * $_POST['PACKAGE_MULT'];
			}
			
			Yii::app()->session['Product_Backup'] = $model;
			if ($model->validate()){
				if (!isset($model->PRO_ID)){
					$duplicates = $this->checkDuplicate($model);
				}
				if ($duplicates != null && count($duplicates)>0 && !isset($_POST['ignoreDuplicates'])){
					foreach($duplicates as $dup_type => $values){
						if ($this->errorText != ''){
							$this->errorText .= '<br />';
						}
						if ($dup_type == 'TITLE'){
							$this->errorText .='<p>There are already Products with a similar title:</p>';
						} else {
							$this->errorText .='<p>There are already Products connecting to the same Ingredient entry:</p>';
						}
						foreach($values as $dup){
							$this->errorText .= $dup . '<br />';
						}
					}
					$this->errorText .= CHtml::label('Ignore possible duplicates','ignoreDuplicates') . CHtml::checkBox('ignoreDuplicates');
				} else {
					$transaction=$model->dbConnection->beginTransaction();
					try {
						if($model->save()){
							//Check Producer to add / to remove
							//TODO
							$producersExist = array();
							if (isset($model->oldProducers) && $model->oldProducers != null && count($model->oldProducers)>0){
								$toRemove = array_merge(array(),$model->oldProducers);
								for($i=0; $i<count($model->producers); $i++){
									$found = false;
									$producersStatus[$i] = 'exist';
									$j=0;
									foreach($toRemove as $oldProducer){
										if ($model->producers[$i]->PRD_ID == $oldProducer->PRD_ID){
											$producersExist[$i] = true;
											unset($toRemove[$j]);
											break;
										}
										$j++;
									}
								}
							}
							for($i=0; $i<count($model->producers); $i++){
								if (!isset($producersExist[$i]) || !$producersExist[$i]){
									$ProToPrd = new ProToPrd;
									$ProToPrd->PRO_ID = $model->PRO_ID;
									$ProToPrd->PRD_ID = $model->producers[$i]->PRD_ID;
									//try {
										$ProToPrd->Save();
									//} catch(Exception $e) {
									//	$this->errorText .= 'Exception occured: ' . $e . '<br />';
									//}
								}
							}
							$model->oldProducers = $model->producers;
							
							if (isset($toRemove) && $toRemove!=null && count($toRemove)>0){
								$removeIDs = array();
								foreach($toRemove as $producer){
									if (isset($producer)){
										array_push($removeIDs, $producer->PRD_ID);
									}
								}
								ProToPrd::model()->deleteAllByAttributes(array('PRO_ID'=>$model->PRO_ID, 'PRD_ID'=>$removeIDs));
							}
							
							unset(Yii::app()->session['Product_Backup']);
							if (isset($_POST['saveAddAssing'])){
								$dest = array('stores/assign',array('pro_id'=>$model->PRO_ID));
							} else {
								$dest = array('view', array('id'=>$model->PRO_ID));
							}
							if($this->useAjaxLinks){
								echo "{hash:'" . $this->createUrlHash($dest[0],$dest[1]) . "'}";
								exit;
							} else {
								$this->redirect($dest);
							}
						}
					} catch(Exception $e) {
						$this->errorText .= 'Exception occured -&gt; rollback. Exception was: ' . $e . '<br />';
						$transaction->rollBack();
					}
				}
			}
		}
		if ($model->ING_ID && (!$model->ingredient || !$model->ingredient->__get('ING_NAME_'.Yii::app()->session['lang']) || $model->ING_ID != $model->ingredient->ING_ID)){
			$model->ingredient = Ingredients::model()->findByPk($model->ING_ID);
		}
		$ecology = Yii::app()->db->createCommand()->select('ECO_ID,ECO_DESC_'.Yii::app()->session['lang'])->from('ecology')->queryAll();
		$ecology = CHtml::listData($ecology,'ECO_ID','ECO_DESC_'.Yii::app()->session['lang']);
		$ethicalCriteria = Yii::app()->db->createCommand()->select('ETH_ID,ETH_DESC_'.Yii::app()->session['lang'])->from('ethical_criteria')->queryAll();
		$ethicalCriteria = CHtml::listData($ethicalCriteria,'ETH_ID','ETH_DESC_'.Yii::app()->session['lang']);
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'ecology'=>$ecology,
			'ethicalCriteria'=>$ethicalCriteria,
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
	
	public function actionSearch()
	{
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];
		
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
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Products']) && !isset($_GET['ing_id']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session['Product']['time'])){
			$Session_Product = Yii::app()->session['Product'];
			if ($Session_Product){
				if ($Session_Product['query']){
					$query = $Session_Product['query'];
					//echo "query from session\n";
				}
				if ($Session_Product['ing_id']){
					$ing_id = $Session_Product['ing_id'];
					//echo "ing_id from session\n";
				}
				if ($Session_Product['model']){
					$model = $Session_Product['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		if ($query != $model2->query){
			$model2->query = $query;
		}
		
		if($ing_id !== null){
			$Session_Product = array();
			$Session_Product['ing_id'] = $ing_id;
			$Session_Product['time'] = time();
			Yii::app()->session['Product'] = $Session_Product;
			
			$rows = Yii::app()->db->createCommand()
				->from('products')
				->leftJoin('ingredients', 'products.ING_ID=ingredients.ING_ID')
				->leftJoin('ecology', 'products.ECO_ID=ecology.ECO_ID')
				->leftJoin('ethical_criteria', 'products.ETH_ID=ethical_criteria.ETH_ID')
				->where('products.ING_ID=:id', array(':id'=>$ing_id))
				->queryAll();
		} else {
			$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'products.');
			if ($criteriaString != ''){
				$Session_Product = array();
				$Session_Product['query'] = $query;
				$Session_Product['time'] = time();
				Yii::app()->session['Product'] = $Session_Product;
				
				$rows = Yii::app()->db->createCommand()
					->from('products')
					->leftJoin('ingredients', 'products.ING_ID=ingredients.ING_ID')
					->leftJoin('ecology', 'products.ECO_ID=ecology.ECO_ID')
					->leftJoin('ethical_criteria', 'products.ETH_ID=ethical_criteria.ETH_ID')
					->where($criteriaString)
					->queryAll();
			} else {
				$rows = array();
				unset(Yii::app()->session['Product']);
			}
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'REC_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		
		$this->checkRenderAjax('search',array(
			'model'=>$model,
			'model2'=>$model2,
			'dataProvider'=>$dataProvider,
			'ing_id'=>$ing_id,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Products');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $withPicture = false)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session['Product_Backup'];
		} else {
			$model=Products::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='products-form')
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
		return Functions::getImage($modified, $model->PRO_IMG_ETAG, $model->PRO_IMG, $id);
    }
}
