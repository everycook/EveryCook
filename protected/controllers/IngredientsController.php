<?php
class IngredientsController extends Controller
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
	
	protected $createBackup = 'Ingredients_Backup';
	protected $searchBackup = 'Ingredients';
	protected $getNextAmountBackup = 'Ingredients_GetNextAmount';
	const RECIPES_AMOUNT = 2;
	const PRODUCTS_AMOUNT = 2;
	const PRELOAD_AMOUNT = 3;
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','advanceSearch','displaySavedImage','getSubGroupSearch','getSubGroupForm','chooseIngredient','advanceChooseIngredient','getNext'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadImage','delicious','disgusting','cancel','showLike', 'showNotLike'),
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
		/*
		if (isset($Session_Backup) && isset($Session_Backup->ING_ID)){
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->ING_ID));
		} else {
		*/
			unset(Yii::app()->session[$this->createBackup]);
			$this->showLastNotCreateAction();
			//$this->forwardAfterSave(array('search'));
		//}
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$model = $this->loadModel($id);
		
		if (isset($_GET['nosearch']) && $_GET['nosearch'] == 'true'){
			unset(Yii::app()->session[$this->searchBackup]);
		}
		
		if ($model->NUT_ID >0){
			$nutrientData = NutrientData::model()->findByPk($model->NUT_ID);
		} else {
			$nutrientData = null;
		}
		
		//read max amount
		$ing_id = $model->ING_ID;
		$otherItemsAmount = array();
		$sql = 'SELECT count(*) FROM (SELECT recipes.REC_ID FROM recipes JOIN steps ON recipes.REC_ID = steps.REC_ID WHERE steps.ING_ID = :id GROUP BY recipes.REC_ID) as recipesView';
		$command = Yii::app()->db->createCommand($sql)
			->bindParam(':id', $ing_id);
		$otherItemsAmount['recipes'] = $command->queryScalar();
		
		$command = Yii::app()->db->createCommand()
			->select('count(*)')
			->from('products')
			->where('ING_ID = :id', array(':id'=>$ing_id));
		$otherItemsAmount['products'] = $command->queryScalar();
		
		Yii::app()->session[$this->getNextAmountBackup] = $otherItemsAmount;
		
		//read current shown
		$recipes = Yii::app()->db->createCommand()->select('recipes.*')->from('recipes')->join('steps', 'recipes.REC_ID = steps.REC_ID')->where('steps.ING_ID = :id', array(':id'=>$model->ING_ID))->order('CHANGED_ON desc')->group('recipes.REC_ID')->limit(self::RECIPES_AMOUNT * 2,0)->queryAll();
		$products = Yii::app()->db->createCommand()->from('products')->where('ING_ID = :id', array(':id'=>$model->ING_ID))->order('CHANGED_ON desc')->limit(self::PRODUCTS_AMOUNT * 2,0)->queryAll();
		
		$this->checkRenderAjax('view', array(
			'model'=>$model,
			'nutrientData'=>$nutrientData,
			'recipes'=>$recipes,
			'products'=>$products,
			'otherItemsAmount'=>$otherItemsAmount,
		));
	}
	
	public function actionGetNext($type, $index, $ing_id){
		if ($type == 'recipe' || $type == 'product'){
			if ($index<0){
				$otherItemsAmount = Yii::app()->session[$this->getNextAmountBackup];
				$index = $otherItemsAmount[$type.'s'] + $index;
			}
			$command = Yii::app()->db->createCommand()
				->from($type.'s');
			if ($type == 'recipe'){
				$command->join('steps', 'recipes.REC_ID = steps.REC_ID')
					->where('steps.ING_ID = :id', array(':id'=>$ing_id))
					->group('recipes.REC_ID');
			} else {
				$command->where('ING_ID = :id', array(':id'=>$ing_id));
			}
			$command->order($type.'s.CHANGED_ON desc')
				->limit(self::PRELOAD_AMOUNT,$index);
			$rows = $command->queryAll();
			if (!isset($rows) || $rows == null || count($rows) == 0){
				$otherItemsAmount = Yii::app()->session[$this->getNextAmountBackup];
				$index = $index - $otherItemsAmount[$type.'s'];
				
				$command = Yii::app()->db->createCommand()
					->from($type.'s');
				if ($type == 'recipe'){
					$command->join('steps', 'recipes.REC_ID = steps.REC_ID')
						->where('steps.ING_ID = :id', array(':id'=>$ing_id))
						->group('recipes.REC_ID');
				} else {
					$command->where('ING_ID = :id', array(':id'=>$ing_id));
				}
				$command->order($type.'s.CHANGED_ON desc')
					->limit(self::PRELOAD_AMOUNT,$index);
				$rows = $command->queryAll();
			}
			
			echo '{"preloadAmount": '.self::PRELOAD_AMOUNT.', "datas": [';
			foreach($rows as $model){
				if ($type == 'recipe'){
					echo '{img:"'.$this->createUrl('recipes/displaySavedImage', array('id'=>$model['REC_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('recipes/view', array('id'=>$model['REC_ID'])).'", auth:"'.$model['REC_IMG_AUTH'].'", name:"'.$model['REC_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'}';
				} else if ($type == 'product'){
					echo '{img:"'.$this->createUrl('products/displaySavedImage', array('id'=>$model['PRO_ID'], 'ext'=>'.png')).'", url:"'.Yii::app()->createUrl('products/view', array('id'=>$model['PRO_ID'])).'", auth:"'.$model['PRO_IMG_CR'].'", name:"'.$model['PRO_NAME_' . Yii::app()->session['lang']].'", index: '.$index.'}';
				}
				echo ',';
				++$index;
			}
			echo ']}';
		}
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
				array_push($dup_rows, $row['ING_ID'] . ': ' . $row['ING_NAME_EN_GB'] . ' / ' . $row['ING_NAME_DE_CH']);
			}
			$duplicates = array_merge($duplicates, array ('NUT_ID'=>$dup_rows));
		}
		
		$command = Yii::app()->db->createCommand()
				->from('ingredients');
		if ($model->ING_NAME_EN_GB != '' && $model->ING_NAME_DE_CH != ''){
			$command->where('ingredients.ING_NAME_EN_GB like :en or ingredients.ING_NAME_DE_CH like :de', array(':en'=>'%' . $model->ING_NAME_EN_GB . '%', ':de'=>'%' . $model->ING_NAME_DE_CH . '%'));
		} else if ($model->ING_NAME_EN_GB != ''){
			$command->where('ingredients.ING_NAME_EN_GB like :en', array(':en'=>'%' . $model->ING_NAME_EN_GB . '%'));
		} else if ($model->ING_NAME_DE_CH != ''){
			$command->where('ingredients.ING_NAME_DE_CH like :de', array(':de'=>'%' . $model->ING_NAME_DE_CH . '%'));
		}
		$rows = $command->queryAll();
		if (count($rows)>0){
			$dup_rows = array();
			foreach($rows as $row){
				array_push($dup_rows, $row['ING_ID'] . ': ' . $row['ING_NAME_EN_GB'] . ' / ' . $row['ING_NAME_DE_CH']);
			}
			$duplicates = array_merge($duplicates, array ('TITLE'=>$dup_rows));
		}
		return $duplicates;
	}
	
	public function actionUploadImage(){
		$this->saveLastAction = false;
		if (isset($_GET['id'])){
			$id = $_GET['id'];
		}
		
		$Session_Ingredients_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Ingredients_Backup)){
			$oldmodel = $Session_Ingredients_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->ING_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->ING_IMG;
		} else {
			$model=new Ingredients;
			$oldPicture = null;
		}
		Functions::uploadImage('Ingredients', $model, $this->createBackup, 'ING_IMG');
	}
		
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Ingredients_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Ingredients_Backup)){
			$oldmodel = $Session_Ingredients_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->ING_ID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->ING_IMG;
		} else {
			$model=new Ingredients;
			$oldPicture=null;
		}
		if (isset($model->ING_IMG) && $model->ING_IMG != ''){
			$model->setScenario('withPic');
		}
		
		if(isset($_POST['Ingredients'])){
			$model->attributes=$_POST['Ingredients'];
			if (isset($oldPicture)){
				Functions::updatePicture($model,'ING_IMG', $oldPicture);
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if ($model->validate()){
				$duplicates = null;
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
					if(Yii::app()->user->demo){
						$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
					} else {
						if($model->save()){
							unset(Yii::app()->session[$this->createBackup]);
							unset(Yii::app()->session[$this->createBackup.'_Time']);
							$this->forwardAfterSave(array('view', 'id'=>$model->ING_ID));
							//$this->forwardAfterSave(array('search', 'query'=>$model->__get('ING_NAME_' . Yii::app()->session['lang'])));
							return;
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
		if (isset($_GET['newModel']) && isset(Yii::app()->session[$this->createBackup.'_Time']) && $_GET['newModel'] > Yii::app()->session[$this->createBackup.'_Time']){
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
	
	private function prepareSearch($view, $ajaxLayout, $criteria){
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
		
		$Session_Ingredient = Yii::app()->session[$this->searchBackup];
		if (isset($Session_Ingredient)){
			if(!isset($_POST['SimpleSearchForm']) && !isset($_GET['query']) && !isset($_POST['Ingredients']) && (!isset($_GET['newSearch']) || $_GET['newSearch'] < $Session_Ingredient['time'])){
				if (isset($Session_Ingredient['query'])){
					$query = $Session_Ingredient['query'];
					$model2->query = $query;
					//echo "query from session\n";
				}
				if (isset($Session_Ingredient['model'])){
					$model = $Session_Ingredient['model'];
					$modelAvailable = true;
					//echo "model from session\n";
				}
			}
		}
		
		$criteriaString = $model->commandBuilder->createSearchCondition($model->tableName(),$model->getSearchFields(),$query, 'ingredients.');
		
		if ($modelAvailable || $criteriaString != '' || $criteria != null){
			if (!$this->isFancyAjaxRequest){
				$distanceForGroupSQL = 'SELECT storeAmountView.ING_ID, storeAmountView.min_dist, storeAmountView.store_count, productAmountView.pro_count
					FROM
					(
						SELECT
						storeView.ING_ID,
						MAX(min_dist) as min_dist,
						SUM(amount_range) as store_count
						FROM (
							(SELECT
								@count := 0,
								@oldId := 0 AS ING_ID,
								0 AS min_dist,
								0 As amount_range)
							UNION
							(SELECT
								@count := if(@oldId = id, @count+1, 0),
								@oldId := id,
								if(@count = 0, value, 0),
								if(value < :view_distance, 1, 0)
							FROM
								(SELECT products.ING_ID as id, cosines_distance(stores.STO_GPS_POINT, GeomFromText(\':point\')) as value
								FROM products
								LEFT JOIN pro_to_sto ON pro_to_sto.PRO_ID=products.PRO_ID 
								LEFT JOIN stores ON pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID
								WHERE stores.STO_GPS_POINT IS NOT NULL
								GROUP BY products.ING_ID, stores.STO_ID
								ORDER BY products.ING_ID, value ASC) AS ingStoreTable
							)
						) AS storeView
						WHERE storeView.ING_ID != 0 AND amount_range != 0
						GROUP BY storeView.ING_ID
					) as storeAmountView
					LEFT JOIN
					(
						SELECT
						productView.ING_ID,
						SUM(amount_range) as pro_count
						FROM (
							(SELECT
								@count := 0,
								@oldId := 0 AS PRO_ID,
								0 as ING_ID,
								0 As amount_range)
							UNION
							(SELECT
								@count := if(@oldId = id, @count+1, 0),
								@oldId := id,
								ing_id,
								if(@count = 0 AND value < :view_distance, 1, 0)
							FROM
								(SELECT products.PRO_ID as id, products.ING_ID as ing_id, cosines_distance(stores.STO_GPS_POINT, GeomFromText(\':point\')) as value
								FROM products
								LEFT JOIN pro_to_sto ON pro_to_sto.PRO_ID=products.PRO_ID 
								LEFT JOIN stores ON pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID
								WHERE stores.STO_GPS_POINT IS NOT NULL
								GROUP BY products.PRO_ID, stores.STO_ID
								ORDER BY products.PRO_ID, value ASC) AS proStoreTable
							)
						) AS productView
						WHERE productView.ING_ID != 0 AND amount_range != 0
						GROUP BY productView.ING_ID
					) as productAmountView ON productAmountView.ING_ID = storeAmountView.ING_ID;';
								
				if (isset(Yii::app()->session['current_gps']) && isset(Yii::app()->session['current_gps'][2])) {
					$point = Yii::app()->session['current_gps'][2];
					$count = 5;
					$youDistanceForGroupSQL = str_replace(':point', $point, $distanceForGroupSQL);
					$youDistanceForGroupSQL = str_replace(':count', $count, $youDistanceForGroupSQL);
					$youDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $youDistanceForGroupSQL);
					$youDistCommand = Yii::app()->db->createCommand($youDistanceForGroupSQL);
					$hasYouDist = true;
				} else {
					$hasYouDist = false;
				}
				
				if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[2])){
					$point = Yii::app()->user->home_gps[2];
					$count = 5;
					$homeDistanceForGroupSQL = str_replace(':point', $point, $distanceForGroupSQL);
					$homeDistanceForGroupSQL = str_replace(':count', $count, $homeDistanceForGroupSQL);
					$homeDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $homeDistanceForGroupSQL);
					$HomeDistCommand = Yii::app()->db->createCommand($homeDistanceForGroupSQL);
					$hasHomeDist = true;
				} else {
					$hasHomeDist = false;
				}
			} else {
				$hasYouDist = false;
				$hasHomeDist = false;
			}
			
			$command = Yii::app()->db->createCommand()
				->select('ingredients.*, nutrient_data.*, group_names.*, subgroup_names.*, ingredient_conveniences.*, storability.*, ingredient_states.*')
				->from('ingredients')
				->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
				->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
				->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
				->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
				->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
				->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID');
				if (!$this->isFancyAjaxRequest){
					$command->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
					->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
					->leftJoin('stores', 'pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID');
				}
				$command->group('ingredients.ING_ID');
				//echo $command->text;
				
			/*
			$suppliersCommand = Yii::app()->db->createCommand()
				->select('ingredients.ING_ID, suppliers.SUP_NAME')
				->from('ingredients')
				->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
				->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
				->leftJoin('suppliers', 'suppliers.SUP_ID=pro_to_sto.SUP_ID')
				->group('ingredients.ING_ID, suppliers.SUP_ID')
				->order('ingredients.ING_ID, suppliers.SUP_ID');
			*/
			if ($criteria != null){
				if (isset($criteria->condition) && $criteria->condition != '') {
					Yii::app()->session[$this->searchBackup] = array('time'=>time());
					
					if ($criteriaString != ''){
						$command->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
						//$suppliersCommand->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
					} else {
						$command->where($criteria->condition, $criteria->params);
						//$suppliersCommand->where($criteria->condition, $criteria->params);
					}
					$this->validSearchPerformed = true;
				} else if ($criteriaString != ''){
					$command->where($criteriaString);
					//$suppliersCommand->where($criteriaString);
					$this->validSearchPerformed = true;
				}
			} else if($modelAvailable) {
				$Session_Ingredient = array();
				if (isset($query)){
					$Session_Ingredient['query'] = $query;
				}
				$Session_Ingredient['model'] = $model;
				$Session_Ingredient['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Ingredient;
				
				$criteria = $model->getCriteriaString();
				//$command = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteriaString())
				
				
				if (isset($criteria->condition) && $criteria->condition != '') {
					if ($criteriaString != ''){
						$command->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
						//$suppliersCommand->where($criteria->condition . ' AND ' . $criteriaString, $criteria->params);
					} else {
						$command->where($criteria->condition, $criteria->params);
						//$suppliersCommand->where($criteria->condition, $criteria->params);
					}
					$this->validSearchPerformed = true;
				} else if ($criteriaString != ''){
					$command->where($criteriaString);
					//$suppliersCommand->where($criteriaString);
					$this->validSearchPerformed = true;
				}
				
				//print_r($rows);
			} else if ($criteriaString != ''){
				$Session_Ingredient = array();
				$Session_Ingredient['query'] = $query;
				$Session_Ingredient['time'] = time();
				Yii::app()->session[$this->searchBackup] = $Session_Ingredient;
				
				//$rows = $model->commandBuilder->createFindCommand($model->tableName(),$model->commandBuilder->createCriteria($criteriaString))->queryAll();
				//$rows = $model->commandBuilder->createFindCommand($model->tableName(), $model->getCriteria())->queryAll();
				
				$command->where($criteriaString);
				//$suppliersCommand->where($criteriaString);
				$this->validSearchPerformed = true;
			}
			
			$rows = $command->queryAll();
			
			if (!$this->isFancyAjaxRequest){
				if ($hasYouDist){
					$youDistRows = $youDistCommand->queryAll();
					$youDistArray = array();
					foreach ($youDistRows as $row){
						$youDistArray[$row['ING_ID']] = array($row['pro_count'], $row['store_count'], $row['min_dist']);
					}
				}
				
				if ($hasHomeDist){
					$homeDistRows = $HomeDistCommand->queryAll();
					$homeDistArray = array();
					foreach ($homeDistRows as $row){
						$homeDistArray[$row['ING_ID']] = array($row['pro_count'], $row['store_count'], $row['min_dist']);
					}
				}
				
				$productCountCommand = Yii::app()->db->createCommand()
					->select('ING_ID, count(PRO_ID) as pro_total_count')
					->from('products')
					->group('ING_ID');
				$productCountRows = $productCountCommand->queryAll();
				$productCountArray = array();
				foreach ($productCountRows as $row){
					$productCountArray[$row['ING_ID']] = $row['pro_total_count'];
				}
				
				for ($i=0; $i<count($rows); $i++){
					if (isset($productCountArray[$rows[$i]['ING_ID']])){
						$rows[$i]['pro_count'] = $productCountArray[$rows[$i]['ING_ID']];
					} else {
						$rows[$i]['pro_count'] = 0;
					}
					if ($hasYouDist){
						if (isset($youDistArray[$rows[$i]['ING_ID']])){
							$values = $youDistArray[$rows[$i]['ING_ID']];
							$rows[$i]['distance_to_you_prod'] = $values[0];
							$rows[$i]['distance_to_you'] = $values[1];
							$rows[$i]['distance_to_you_min'] = $values[2];
						} else  {
							$rows[$i]['distance_to_you_prod'] = -2;
						}
					} else {
						$rows[$i]['distance_to_you_prod'] = -1;
					}
					if ($hasHomeDist){
						if (isset($homeDistArray[$rows[$i]['ING_ID']])){
							$values = $homeDistArray[$rows[$i]['ING_ID']];
							$rows[$i]['distance_to_home_prod'] = $values[0];
							$rows[$i]['distance_to_home'] = $values[1];
							$rows[$i]['distance_to_home_min'] = $values[2];
						} else  {
							$rows[$i]['distance_to_home_prod'] = -2;
						}
					} else {
						$rows[$i]['distance_to_home_prod'] = -1;
					}
				}
			}
			
			
			/*
			$suppliers = $suppliersCommand->queryAll();
			
			$ingredient_id = 0;
			$supplier_texts = array();
			$supplier_text = '';
			foreach($suppliers as $supplier){
				if ($supplier['ING_ID'] != $ingredient_id){
					$supplier_texts[$ingredient_id] = $supplier_text;
					$supplier_text = '';
					$ingredient_id = $supplier['ING_ID'];
				}
				if ($supplier_text != ''){
					$supplier_text .= ', ';
				}
				$supplier_text .= $supplier['SUP_NAME'];
			}
			for($i = 0; $i < count($rows); $i++){
				if (isset($supplier_texts[$rows[$i]['ING_ID']]) && $supplier_texts[$rows[$i]['ING_ID']] != null){
					$rows[$i]['sup_names'] = $supplier_texts[$rows[$i]['ING_ID']];
				} else {
					$rows[$i]['sup_names'] = '';
				}
			}
			*/
		} else {
			$rows = array();
			unset(Yii::app()->session[$this->searchBackup]);
		}
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'ING_ID',
			'keyField'=>'ING_ID',
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
				//'nutrientData'=>$nutrientData,
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
		$this->prepareSearch('advanceSearch', null, null);
	}
	
	public function actionSearch()
	{
		$this->prepareSearch('search', null, null);
	}
	
	public function actionChooseIngredient(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('search', 'none', null);
	}
	
	public function actionAdvanceChooseIngredient(){
		$this->isFancyAjaxRequest = true;
		$this->prepareSearch('advanceSearch', 'none', null);
	}
	
	public function actionShowLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_LIKES_I')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Ingredients::model()->tableName().'.ING_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
	}
	public function actionShowNotLike(){
		$command = Yii::app()->dbp->createCommand()
			->select('PRF_NOTLIKES_I')
			->from('profiles')
			->where('PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$ids = $command->queryScalar();
		
		$ids = explode(',', $ids);
		$criteria=new CDbCriteria;
		$criteria->compare(Ingredients::model()->tableName().'.ING_ID',$ids);
		
		$this->prepareSearch('like', null, $criteria);
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
		if (isset($id)){
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
		if (isset($id)){
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
			$model=Yii::app()->session[$this->createBackup];
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
		if (isset($_GET['size'])) {
			$size = $_GET['size'];
		} else {
			$size = 0;
		}
		$this->saveLastAction = false;
		$model=$this->loadModel($id, true);
		$modified = $model->CHANGED_ON;
		if (!$modified){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model->ING_IMG_ETAG, $model->ING_IMG, $id, 'Ingredients', $size);
    }
	
	public function actionDelicious($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'I', true);
		$this->showLastAction();
	}
	
	public function actionDisgusting($id){
		$this->saveLastAction = false;
		Functions::addLikeInfo($id, 'I', false);
		$this->showLastAction();
	}
}
