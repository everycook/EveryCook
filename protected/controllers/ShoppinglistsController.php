<?php

class ShoppinglistsController extends Controller
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
				'actions'=>array('none'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','removeFromList','setProduct'),
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
	
	public function actionSetProduct($id, $ing_id){
		$pro_id = $_GET['pro_id'];
		$gramms = $_GET['gramms'];
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$amounts = explode(';',$model->SHO_QUANTITIES);
		
		for($i=0;$i<count($ing_ids);++$i){
			if ($ing_ids[$i] == $ing_id){
				$pro_ids[$i] = $pro_id;
				if ($gramms > 0){
					$amounts[$i] = ceil($ing_weights[$i] / $gramms);
				} else {
					$amounts[$i] = '';
				}
				break;
			}
		}
		
		$model->SHO_PRODUCTS = implode(';',$pro_ids);
		$model->SHO_QUANTITIES = implode(';',$amounts);
		
		if($model->save()){
			echo '{"sucessfull":true}';
		} else {
			echo '{"sucessfull":false, "error":"';
			print_r($model->getErrors());
			echo '"}';
		}
	}
	
	public function actionRemoveFromList($id, $ing_id){
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$amounts = explode(';',$model->SHO_QUANTITIES);
		
		for($i=0;$i<count($ing_ids);++$i){
			if ($ing_ids[$i] == $ing_id){
				unset($ing_ids[$i]);
				unset($ing_weights[$i]);
				unset($pro_ids[$i]);
				unset($amounts[$i]);
				break;
			}
		}
		
		$model->SHO_INGREDIENTS = implode(';',$ing_ids);
		$model->SHO_WEIGHTS = implode(';',$ing_weights);
		$model->SHO_PRODUCTS = implode(';',$pro_ids);
		$model->SHO_QUANTITIES = implode(';',$amounts);
		
		if($model->save()){
			echo '{"sucessfull":true}';
		} else {
			echo '{"sucessfull":false, "error":"';
			print_r($model->getErrors());
			echo '"}';
		}
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$command = Yii::app()->dbp->createCommand()
			->select('profiles.PRF_SHOPLISTS')
			->from('profiles')
			->where('profiles.PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$shoppinglists = $command->queryScalar();
		
		if (!isset($shoppinglists) || $shoppinglists == null || $shoppinglists == ''){
			throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
		} else {
			$values = explode(';', $shoppinglists);
			$values = array_flip($values);
			if (!isset($values[$id])){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			}
		}
		
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$amounts = explode(';',$model->SHO_QUANTITIES);
		
		$ing_criteria=new CDbCriteria;
		$ing_criteria->compare('ING_ID',$ing_ids,true);
		
		$pro_criteria=new CDbCriteria;
		$pro_criteria->compare('products.PRO_ID',$pro_ids,true);
		
		
		$command = Yii::app()->db->createCommand()
			->select('ingredients.ING_ID, ingredients.ING_IMG_AUTH, ING_NAME_'.Yii::app()->session['lang'])
			->from('ingredients')
			->where($ing_criteria->condition, $ing_criteria->params);
			
		$ingredient_names = $command->queryAll();
		
		
		$distanceForGroupSQL = 'SELECT
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
					if(@count = 1, value, 0),
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
			WHERE theView.PRO_ID != 0 AND dist != 0
			GROUP BY theView.PRO_ID;';
		
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
		
		$command = Yii::app()->db->createCommand()
			->select('products.PRO_ID, products.PRO_IMG_CR, PRO_NAME_'.Yii::app()->session['lang'])
			->from('products')
			->group('products.PRO_ID');
		
		$command->where($pro_criteria->condition, $pro_criteria->params);
		if ($hasYouDist){
			$youDistCommand->where($pro_criteria->condition, $pro_criteria->params);
		}
		if ($hasHomeDist){
			$HomeDistCommand->where($pro_criteria->condition, $pro_criteria->params);
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
		
		
		//Merge data
		$data = array();
		$proToIng = array();
		for ($i=0; $i<count($ing_ids);++$i){
			$ing_id = $ing_ids[$i];
			$proToIng[$pro_ids[$i]] = $ing_id;
			$data[$ing_id] = array('ING_ID'=>$ing_id, 'ing_weight'=>$ing_weights[$i], 'PRO_ID'=>$pro_ids[$i], 'amount'=>$amounts[$i], 'SHO_ID'=>$model->SHO_ID);
		}
		foreach($ingredient_names as $row){
			$ing_id = $row['ING_ID'];
			$data[$ing_id]['ING_NAME'] = $row['ING_NAME_'.Yii::app()->session['lang']];
			$data[$ing_id]['ING_IMG_AUTH'] = $row['ING_IMG_AUTH'];
		}
		
		foreach($rows as $row){
			$pro_id = $row['PRO_ID'];
			$ing_id = $proToIng[$pro_id];
			
			$data[$ing_id]['PRO_NAME'] = $row['PRO_NAME_'.Yii::app()->session['lang']];
			$data[$ing_id]['PRO_IMG_CR'] = $row['PRO_IMG_CR'];
			
			if ($hasYouDist){
				if (isset($youDistArray[$pro_id])){
					$data[$ing_id]['distance_to_you'] = $youDistArray[$pro_id];
				} else  {
					$data[$ing_id]['distance_to_you'] = -2;
				}
			} else {
				$data[$ing_id]['distance_to_you'] = -1;
			}
			if ($hasHomeDist){
				if (isset($homeDistArray[$pro_id])){
					$data[$ing_id]['distance_to_home'] = $homeDistArray[$pro_id];
				} else  {
					$data[$ing_id]['distance_to_home'] = -2;
				}
			} else {
				$data[$ing_id]['distance_to_home'] = -1;
			}
		}
		
		
		$dataProvider=new CArrayDataProvider($data, array(
			'id'=>'ING_ID',
			'keyField'=>'ING_ID',
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
		
		if (isset($_GET['ajaxPaging']) && $_GET['ajaxPaging']){
			$_GET['ajaxPagingView'] = 'item_paging';
		}
		
		$this->checkRenderAjax('view',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Shoppinglists;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shoppinglists']))
		{
			$model->attributes=$_POST['Shoppinglists'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->SHO_ID));
		}

		$this->checkRenderAjax('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shoppinglists']))
		{
			$model->attributes=$_POST['Shoppinglists'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->SHO_ID));
		}

		$this->checkRenderAjax('update',array(
			'model'=>$model,
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
		$command = Yii::app()->dbp->createCommand()
			->select('profiles.PRF_SHOPLISTS')
			->from('profiles')
			->where('profiles.PRF_UID = :id',array(':id'=>Yii::app()->user->id));
		$shoppinglists = $command->queryScalar();
		
		if (!isset($shoppinglists) || $shoppinglists == null || $shoppinglists == ''){
			$dataProvider=new CArrayDataProvider(array(), array(
				'id'=>'SHO_ID',
				'keyField'=>'SHO_ID',
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
		} else {
			$values = explode(';', $shoppinglists);
			
			$selectDate = mktime(0,0,0, date("n"), date("j")-7, date("Y"));
			
			$criteria=new CDbCriteria;
			$criteria->compare('SHO_ID',$values,true);
			//$criteria->addCondition('SHO_DATE > ',$selectDate); //TODO: only show shoppinglists in future.
			
			$dataProvider=new CActiveDataProvider('Shoppinglists', array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
		}
		
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Shoppinglists('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Shoppinglists']))
			$model->attributes=$_GET['Shoppinglists'];

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
		$model=Shoppinglists::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='shoppinglists-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
