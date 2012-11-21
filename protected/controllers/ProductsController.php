<?php

class ProductsController extends Controller
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
	
	protected $createBackup = 'Products_Backup';
	protected $searchBackup = 'Products';
	protected $getNextAmountBackup = 'Products_GetNextAmount';
	const RECIPES_AMOUNT = 2;
	const PRELOAD_AMOUNT = 3;
	
	protected $distanceForGroupSQL = 'SELECT
		theView.PRO_ID,
		MAX(min_dist) as min_dist,
		MAX(dist) as dist,
		SUM(amount) as amount,
		SUM(amount_range) as amount_range
		FROM (
			(SELECT
				@count := 0,
				@oldId := 0 AS PRO_ID,
				0 AS min_dist,
				0 AS dist,
				0 AS amount,
				0 As amount_range)
			UNION
			(SELECT
				@count := if(@oldId = id, @count+1, 0),
				@oldId := id,
				if(@count = 0, value, 0),
				if(@count < :count, value, 0),
				if(@count < :count, 1, 0),
				if(value < :view_distance, 1, 0)
			FROM
				(SELECT products.PRO_ID as id, cosines_distance(stores.STO_GPS_POINT, GeomFromText(\':point\')) as value
				FROM products
				LEFT JOIN pro_to_sto ON pro_to_sto.PRO_ID=products.PRO_ID 
				LEFT JOIN stores ON pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID
				WHERE stores.STO_GPS_POINT IS NOT NULL
				ORDER BY products.PRO_ID, value ASC) AS theTable
			)
		) AS theView
		WHERE theView.PRO_ID != 0 AND (dist != 0 OR amount_range != 0)
		GROUP BY theView.PRO_ID;';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','displaySavedImage','chooseProduct','getNext'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage','delicious','disgusting','cancel','showLike', 'showNotLike'),
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
	
	public function actionCancel(){
		$this->saveLastAction = false;
		$Session_Backup = Yii::app()->session[$this->createBackup];
		unset(Yii::app()->session[$this->createBackup.'_Time']);
		if (isset($Session_Backup) && isset($Session_Backup->PRO_ID)){
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->PRO_ID));
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
	public function actionView($id) {
		$model = $this->loadModel($id);
		
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
			unset(Yii::app()->session['Ingredients']);
		}
		
		//read max amount
		$ing_id = $model->ING_ID;
		$otherItemsAmount = array();
		$sql = 'SELECT count(*) FROM (SELECT recipes.REC_ID FROM recipes JOIN steps ON recipes.REC_ID = steps.REC_ID WHERE steps.ING_ID = :id GROUP BY recipes.REC_ID) as recipesView';
		$command = Yii::app()->db->createCommand($sql)
			->bindParam(':id', $ing_id);
		$otherItemsAmount['recipes'] = $command->queryScalar();
		
		Yii::app()->session[$this->getNextAmountBackup] = $otherItemsAmount;
		
		
		$recipes = Yii::app()->db->createCommand()->select('recipes.*')->from('recipes')->join('steps', 'recipes.REC_ID = steps.REC_ID')->where('steps.ING_ID = :id', array(':id'=>$model->ING_ID))->order('CHANGED_ON desc')->group('recipes.REC_ID')->limit(self::RECIPES_AMOUNT * 2,0)->queryAll();
		
		//Store distance
		$data = array();
		if (isset(Yii::app()->session['current_gps']) && isset(Yii::app()->session['current_gps'][2])) {
			$point = Yii::app()->session['current_gps'][2];
			$count = 5;
			$youDistanceForGroupSQL = str_replace(':point', $point, $this->distanceForGroupSQL);
			$youDistanceForGroupSQL = str_replace(':count', $count, $youDistanceForGroupSQL);
			$youDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $youDistanceForGroupSQL);
			$youDistCommand = Yii::app()->db->createCommand($youDistanceForGroupSQL);
			/*
			$youDistCommand = Yii::app()->db->createCommand($this->distanceForGroupSQL);
			$youDistCommand->bindParam(':point',$point);
			$youDistCommand->bindParam(':count1',$count);
			$youDistCommand->bindParam(':count2',$count);
			*/
			$youDistCommand->where('products.PRO_ID=:id', array(':id'=>$id));
			$row = $youDistCommand->queryRow();
			if ($row === false){
				$data['distance_to_you'] = -2;
			} else {
				$data['distance_to_you'] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
			}
			$hasYouDist = true;
		} else {
			$hasYouDist = false;
			$data['distance_to_you'] = -1;
		}
		
		if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[2])){
			$point = Yii::app()->user->home_gps[2];
			$count = 5;
			$homeDistanceForGroupSQL = str_replace(':point', $point, $this->distanceForGroupSQL);
			$homeDistanceForGroupSQL = str_replace(':count', $count, $homeDistanceForGroupSQL);
			$homeDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $homeDistanceForGroupSQL);
			$HomeDistCommand = Yii::app()->db->createCommand($homeDistanceForGroupSQL);
			/*
			$HomeDistCommand = Yii::app()->db->createCommand($this->distanceForGroupSQL);
			$HomeDistCommand->bindParam(':point',$point);
			$HomeDistCommand->bindParam(':count',$count);
			*/
			$HomeDistCommand->where('products.PRO_ID=:id', array(':id'=>$id));
			$row = $HomeDistCommand->queryRow();
			if ($row === false){
				$data['distance_to_home'] = -2;
			} else {
				$data['distance_to_home'] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
			}
			$hasHomeDist = true;
		} else {
			$hasHomeDist = false;
			$data['distance_to_home'] = -1;
		}
		$this->checkRenderAjax('view', array(
			'model'=>$model,
			'recipes'=>$recipes,
			'otherItemsAmount'=>$otherItemsAmount,
			'data'=>$data,
		));
	}
	
	public function actionGetNext($index, $ing_id){
		if ($type == 'recipe' || $type == 'ingredient' || $type == 'product'){
			if ($index<0){
				$command = Yii::app()->db->createCommand()
					->select('count(*)')
					->from($type.'s')
					->join('steps', 'recipes.REC_ID = steps.REC_ID')
					->where('steps.ING_ID = :id', array(':id'=>$ing_id))
					->group('recipes.REC_ID');
				$amount = $command->queryScalar();
				$index = $amount-1;
			}
			$command = Yii::app()->db->createCommand()
				->from('recipes')
				->join('steps', 'recipes.REC_ID = steps.REC_ID')
				->where('steps.ING_ID = :id', array(':id'=>$ing_id))
				->group('recipes.REC_ID')
				->order('CHANGED_ON desc')
				->limit(1,$index);
			$rows = $command->queryAll();
			
			if (!isset($rows) || $rows == null || count($rows) == 0){
				$index = 0;
				$command = Yii::app()->db->createCommand()
					->from('recipes')
					->join('steps', 'recipes.REC_ID = steps.REC_ID')
					->where('steps.ING_ID = :id', array(':id'=>$ing_id))
					->group('recipes.REC_ID')
					->order('CHANGED_ON desc')
					->limit(self::PRELOAD_AMOUNT,$index);
				$rows = $command->queryAll();
			}
			
			echo '{"preloadAmount": '.self::PRELOAD_AMOUNT.', "datas": [';
			foreach($rows as $model){
				echo '{img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$model['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$model['REC_ID'])).'", auth:"'.$model['REC_IMG_AUTH'].'", name:"'.$model['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'}';
				echo ',';
				++$index;
			}
			echo ']}';
		}
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
				array_push($dup_rows, $row['PRO_ID'] . ': ' . $row['PRO_NAME_EN_GB'] . ' / ' . $row['PRO_NAME_DE_CH']);
			}
			$duplicates = array_merge($duplicates, array ('ING_ID'=>$dup_rows));
		}
		
		$command = Yii::app()->db->createCommand()
				->from('products');
		if ($model->PRO_NAME_EN_GB != '' && $model->PRO_NAME_DE_CH != ''){
			$command->where('products.PRO_NAME_EN_GB like :en or products.PRO_NAME_DE_CH like :de', array(':en'=>'%' . $model->PRO_NAME_EN_GB . '%', ':de'=>'%' . $model->PRO_NAME_DE_CH . '%'));
		} else if ($model->PRO_NAME_EN_GB != ''){
			$command->where('products.PRO_NAME_EN_GB like :en', array(':en'=>'%' . $model->PRO_NAME_EN_GB . '%'));
		} else if ($model->PRO_NAME_DE_CH != ''){
			$command->where('products.PRO_NAME_DE_CH like :de', array(':de'=>'%' . $model->PRO_NAME_DE_CH . '%'));
		}
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['PRO_ID'] . ': ' . $row['PRO_NAME_EN_GB'] . ' / ' . $row['PRO_NAME_DE_CH']);
			}
			$duplicates = array_merge($duplicates, array ('TITLE'=>$dup_rows));
		}
		return $duplicates;
	}
	
	private function getModelAndOldPic($id){
		$Session_Products_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Products_Backup)){
			$oldmodel = $Session_Products_Backup;
		}
		if (isset($id) && $id != null){
			if (!isset($oldmodel) || $oldmodel->PRO_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->PRO_IMG;
		} else {
			$model=new Products;
			$oldPicture = null;
		}
		
		if (isset($model->PRO_IMG) && $model->PRO_IMG != ''){
			$model->setScenario('withPic');
		}
		return array($model, $oldPicture);
	}
	
	public function actionUploadImage(){
		$this->saveLastAction = false;
		if (isset($_GET['id'])){
			$id = $_GET['id'];
		} else {
			$id=null;
		}
		list($model, $oldPicture) = $this->getModelAndOldPic($id);
		
		Functions::uploadImage('Products', $model, 'Products_Backup', 'PRO_IMG');
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		list($model, $oldPicture) = $this->getModelAndOldPic($id);
		
		$ing_id = null;
		if(isset($_GET['ing_id'])){
			$ing_id = $_GET['ing_id'];
			
			if (!isset($model->ING_ID) && $ing_id){
				$model->ING_ID = $ing_id;
			}
		}
		
		if(isset($_POST['Products'])){
			$model->attributes=$_POST['Products'];
			if (isset($oldPicture)){
				Functions::updatePicture($model,'PRO_IMG', $oldPicture);
			}
			
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
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if ($model->validate()){
				$duplicates = null;
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
					if(Yii::app()->user->demo){
						$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
					} else {
						$transaction=$model->dbConnection->beginTransaction();
						try {
							if($model->save()){
								//Check Producer to add / to remove
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
									if(Yii::app()->user->demo){
										$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
									} else {
										ProToPrd::model()->deleteAllByAttributes(array('PRO_ID'=>$model->PRO_ID, 'PRD_ID'=>$removeIDs));
									}
								}
								
								unset(Yii::app()->session[$this->createBackup]);
								unset(Yii::app()->session[$this->createBackup.'_Time']);
								if (isset($_POST['saveAddAssing'])){
									//create search with used ingredient, for view after save (if there is not set a search already)
									$Session_Product = Yii::app()->session[$this->searchBackup];
									if (isset($Session_Product)){
										if (!isset($Session_Product['query']) && !isset($Session_Product['ing_id']) && !isset($Session_Product['model'])){
											$Session_Product = array();
											$Session_Product['ing_id'] = $model->ING_ID;
											$Session_Product['time'] = time();
											Yii::app()->session[$this->searchBackup] = $Session_Product;
										}
									} else {
										$Session_Product = array();
										$Session_Product['ing_id'] = $model->ING_ID;
										$Session_Product['time'] = time();
										Yii::app()->session[$this->searchBackup] = $Session_Product;
									}
									
									$dest = array('stores/assign', 'pro_id'=>$model->PRO_ID);
								} else {
									$dest = array('view', 'id'=>$model->PRO_ID);
								}
								$this->forwardAfterSave($dest);
								return;
							}
						} catch(Exception $e) {
							$this->errorText .= 'Exception occured -&gt; rollback. Exception was: ' . $e . '<br />';
							$transaction->rollBack();
						}
					}
				}
			}
		}
		if (isset($model->ING_ID) && (!$model->ingredient || !$model->ingredient->__get('ING_NAME_'.Yii::app()->session['lang']) || $model->ING_ID != $model->ingredient->ING_ID)){
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
	
	private function prepareSearch($view, $ajaxLayout, $criteria){
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
		
		if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Products']) && !isset($_GET['ing_id']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < Yii::app()->session[$this->searchBackup]['time'])){
			$Session_Product = Yii::app()->session[$this->searchBackup];
			if (isset($Session_Product)){
				if (isset($Session_Product['query'])){
					$query = $Session_Product['query'];
					$model2->query = $query;
					//echo "query from session\n";
				}
				if (isset($Session_Product['ing_id'])){
					$ing_id = $Session_Product['ing_id'];
					//echo "ing_id from session\n";
				}
				if (isset($Session_Product['model'])){
					$model = $Session_Product['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		if ($query != $model2->query){
			$model2->query = $query;
		}
		
		if ($criteria != null){
			Yii::app()->session[$this->searchBackup] = array('time'=>time());
			$criteria = array($criteria->condition, $criteria->params);
		} else if($ing_id !== null){
			$Session_Product = array();
			$Session_Product['ing_id'] = $ing_id;
			$Session_Product['time'] = time();
			Yii::app()->session[$this->searchBackup] = $Session_Product;
			
			$criteria = array('products.ING_ID=:id', array(':id'=>$ing_id));
		} else {
			$criteria = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'products.');
			if ($criteria != ''){
				$Session_Product = array();
				$Session_Product['query'] = $query;
				$Session_Product['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Product;
			} else {
				unset(Yii::app()->session[$this->searchBackup]);
			}
		}
		if ($criteria != ''){
			if (isset(Yii::app()->session['current_gps']) && isset(Yii::app()->session['current_gps'][2])) {
				$point = Yii::app()->session['current_gps'][2];
				$count = 5;
				$youDistanceForGroupSQL = str_replace(':point', $point, $this->distanceForGroupSQL);
				$youDistanceForGroupSQL = str_replace(':count', $count, $youDistanceForGroupSQL);
				$youDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $youDistanceForGroupSQL);
				$youDistCommand = Yii::app()->db->createCommand($youDistanceForGroupSQL);
				/*
				$youDistCommand = Yii::app()->db->createCommand($this->distanceForGroupSQL);
				$youDistCommand->bindParam(':point',$point);
				$youDistCommand->bindParam(':count1',$count);
				$youDistCommand->bindParam(':count2',$count);
				*/
				$hasYouDist = true;
			} else {
				$hasYouDist = false;
			}
			
			if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[2])){
				$point = Yii::app()->user->home_gps[2];
				$count = 5;
				$homeDistanceForGroupSQL = str_replace(':point', $point, $this->distanceForGroupSQL);
				$homeDistanceForGroupSQL = str_replace(':count', $count, $homeDistanceForGroupSQL);
				$homeDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $homeDistanceForGroupSQL);
				$HomeDistCommand = Yii::app()->db->createCommand($homeDistanceForGroupSQL);
				/*
				$HomeDistCommand = Yii::app()->db->createCommand($this->distanceForGroupSQL);
				$HomeDistCommand->bindParam(':point',$point);
				$HomeDistCommand->bindParam(':count',$count);
				*/
				$hasHomeDist = true;
			} else {
				$hasHomeDist = false;
			}
			
			$command = Yii::app()->db->createCommand()
				->select('products.*, ecology.*, ethical_criteria.*')
				->from('products')
				->leftJoin('ingredients', 'products.ING_ID=ingredients.ING_ID')
				->leftJoin('ecology', 'products.ECO_ID=ecology.ECO_ID')
				->leftJoin('ethical_criteria', 'products.ETH_ID=ethical_criteria.ETH_ID')
				->group('products.PRO_ID');
			
			if (is_array($criteria)){
				$command->where($criteria[0],$criteria[1]);
				if ($hasYouDist){
					$youDistCommand->where($criteria[0],$criteria[1]);
				}
				if ($hasHomeDist){
					$HomeDistCommand->where($criteria[0],$criteria[1]);
				}
			} else {
				$command->where($criteria);
				if ($hasYouDist){
					$youDistCommand->where($criteria);
				}
				if ($hasHomeDist){
					$HomeDistCommand->where($criteria);
				}
			}
			$rows = $command->queryAll();
			if ($hasYouDist){
				$youDistRows = $youDistCommand->queryAll();
				$youDistArray = array();
				foreach ($youDistRows as $row){
					$youDistArray[$row['PRO_ID']] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
				}
			}
			if ($hasHomeDist){
				$homeDistRows = $HomeDistCommand->queryAll();
				$homeDistArray = array();
				foreach ($homeDistRows as $row){
					$homeDistArray[$row['PRO_ID']] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
				}
			}
			$order_you = array();
			$order_home = array();
			for ($i=0; $i<count($rows); $i++){
				if ($hasYouDist){
					if (isset($youDistArray[$rows[$i]['PRO_ID']])){
						$rows[$i]['distance_to_you'] = $youDistArray[$rows[$i]['PRO_ID']];
						$order_you[$youDistArray[$rows[$i]['PRO_ID']][2]] = $i;
					} else  {
						$rows[$i]['distance_to_you'] = -2;
					}
				} else {
					$rows[$i]['distance_to_you'] = -1;
				}
				if ($hasHomeDist){
					if (isset($homeDistArray[$rows[$i]['PRO_ID']])){
						$rows[$i]['distance_to_home'] = $homeDistArray[$rows[$i]['PRO_ID']];
						$order_home[$homeDistArray[$rows[$i]['PRO_ID']][2]] = $i;
					} else  {
						$rows[$i]['distance_to_home'] = -2;
					}
				} else {
					$rows[$i]['distance_to_home'] = -1;
				}
			}
			if (isset($_GET['order'])){
				if ($_GET['order'] == 'you'){
					$orderArray = $order_you;
				} else if ($_GET['order'] == 'home'){
					$orderArray = $order_home;
				} else {
					$orderArray = array();
				}
				if (count($orderArray)>0){
					ksort($orderArray, SORT_NUMERIC);
					$newRows = array();
					foreach($orderArray as $min_dist=>$index){
						$newRows[]=$rows[$index];
					}
					$rows = $newRows;
				}
			}
		} else {
			$rows = array();
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'PRO_ID',
			'keyField'=>'PRO_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'model2'=>$model2,
			'dataProvider'=>$dataProvider,
			'ing_id'=>$ing_id,
		), $ajaxLayout);
	}
	
	
	public function actionSearch() {
		$this->prepareSearch('search', null, null);
	}
	
	public function actionChooseProduct(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none', null);
	}

	public function actionShowLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_LIKES_P')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Products::model()->tableName().'.PRO_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
	}
	
	public function actionShowNotLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_NOTLIKES_P')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Products::model()->tableName().'.PRO_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
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
			$model=Yii::app()->session[$this->createBackup];
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
		if (isset($_GET['size'])) {
			$size = $_GET['size'];
		} else {
			$size = 0;
		}
		$this->saveLastAction = false;
		$model=$this->loadModel($id, true);
		$modified = $model->CHANGED_ON;
		if (!isset($modified)){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model->PRO_IMG_ETAG, $model->PRO_IMG, $id, 'Products', $size);
    }
	
	public function actionDelicious($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'P', true);
		$this->showLastAction();
	}
	
	public function actionDisgusting($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'P', false);
		$this->showLastAction();
	}
}
