<?php

class MealsController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','mealPlanner','mealList','changePeople','shoppingList'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Meals;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Meals']))
		{
			$model->attributes=$_POST['Meals'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->MEA_ID));
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

		if(isset($_POST['Meals']))
		{
			$model->attributes=$_POST['Meals'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->MEA_ID));
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
		$dataProvider=new CActiveDataProvider('Meals');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Meals('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Meals']))
			$model->attributes=$_GET['Meals'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionMealPlanner()
	{
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$Session_Meals_Backup = Yii::app()->session['Meals_Backup'];
		if (isset($Session_Meals_Backup)){
			$oldmodel = $Session_Meals_Backup;
		}
		if (isset($_GET['id'])){
			if (!isset($oldmodel) || $oldmodel->MEA_ID != $_GET['id']){
				$oldmodel = $this->loadModel($_GET['id'], true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
		} else {
			$model=new Meals;
			
			$model->MEA_PERC_GDA = 33;
			$model->meaToCous = array(new MeaToCou);
			$meaToCou = $model->meaToCous[0];
			$meaToCou->MTC_PERC_MEAL = 100;
			$meaToCou->course = new Courses;
			
			if (isset($_GET['rec_id'])){
				$recipe=Recipes::model()->findByPk($_GET['rec_id']);
			} else {
				$recipe	= null;
			}
			$course = $model->meaToCous[0]->course;
			$course->couToRecs = array(new CouToRec);
			$couToRecs = $model->meaToCous[0]->course->couToRecs[0];
			$couToRecs->CTR_REC_PROC = 100;
			$couToRecs->recipe = $recipe;
		}
		
		if(isset($_POST['Meals'])) {
			$model->attributes=$_POST['Meals'];
			try {
				if (isset($model->date) && $model->date != ''){
					if ($model->hour == ''){
						$model->hour = '12';
						
					}
					if ($model->minute == ''){
						$model->minute = '00';
					}
					$model->MEA_DATE = date_create_from_format('Y-m-d H-i', $model->date . ' ' . $model->hour . '-' . $model->minute)->getTimestamp();
				} else {
					$model->MEA_DATE = null;
				}
			} catch(Exception $e) {
				$model->MEA_DATE = null;
			}
			$model->PRF_UID = Yii::app()->user->id;
			
			$model = Functions::arrayToRelatedObjects($model, $_POST['Meals']);
			
			Yii::app()->session['Meals_Backup'] = $model;
			
			/*
			echo '<pre>';
			print_r($_POST['Meals']);
			echo "\r\n\r\n\r\n\r\n\r\n";
			print_r($model);
			echo '</pre>';
			return;
			*/
			
			$transaction2=Yii::app()->db->beginTransaction();
			$transaction=Yii::app()->dbp->beginTransaction();
			try {
				if($model->save()){
					$saveOK = true;
					$meaToCouIndex = 0;
					$meaToCous = $model->meaToCous;
					Yii::app()->dbp->createCommand()->delete(MeaToCou::model()->tableName(), 'MEA_ID = :id', array(':id'=>$model->MEA_ID));
					foreach($meaToCous as $meaToCou){
						$meaToCou->MEA_ID = $model->MEA_ID;
						$meaToCou->setIsNewRecord(true);
						if($meaToCou->course->save()){
							$meaToCou->COU_ID = $meaToCou->course->COU_ID;
							$meaToCou->MTC_ORDER = $meaToCouIndex;
							++$meaToCouIndex;
							if($meaToCou->save()){
								$CouToRecIndex = 0;
								$couToRecs = $meaToCou->course->couToRecs;
								Yii::app()->db->createCommand()->delete(CouToRec::model()->tableName(), 'COU_ID = :id', array(':id'=>$meaToCou->course->COU_ID));
								foreach($couToRecs as $couToRec){
									$couToRec->COU_ID = $meaToCou->course->COU_ID;
									$couToRec->CTR_ORDER = $CouToRecIndex;
									$couToRec->setIsNewRecord(true);
									++$CouToRecIndex;
									if(!$couToRec->save()){
										$saveOK = false;
										echo 'error on save couToRec: errors:<pre>'; print_r($couToRec->getErrors()); echo '</pre>';
										break 2;
									}
								}
							} else {
								echo 'error on save meaToCou: errors:<pre>'; print_r($meaToCou->getErrors()); echo '</pre>';
								$saveOK = false;
								break 1;
							}
						} else {
							echo 'error on save course: errors:<pre>'; print_r($course->getErrors()); echo '</pre>';
							$saveOK = false;
							break 1;
						}
					}
					if ($saveOK){
						$transaction2->commit();
						$transaction->commit();
						unset(Yii::app()->session['Meals_Backup']);
						if (isset($_POST['saveToShoppingList'])){
							$this->forwardAfterSave(array('shoppingList', 'id'=>$model->MEA_ID));
						} else {
							$this->forwardAfterSave(array('mealList', 'id'=>$model->MEA_ID));
						}
						return;
					} else {
						//TODO: Remove ID's...?
						echo 'rollback because of previous errors.';
						//echo 'any errors occured, rollback';
						$transaction2->rollBack();
						$transaction->rollBack();
					}
				} else {
					echo 'error on save meal: errors:<pre>'; print_r($model->getErrors()); echo '</pre>';
					$transaction2->rollBack();
					$transaction->rollBack();
				}
			} catch(Exception $e) {
				echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
				$transaction2->rollBack();
				$transaction->rollBack();
			}
		}

		$mealType = Yii::app()->db->createCommand()->select('MTY_ID,MTY_DESC_'.Yii::app()->session['lang'])->from('meal_types')->queryAll();
		$mealType = CHtml::listData($mealType,'MTY_ID','MTY_DESC_'.Yii::app()->session['lang']);
		
		$hours = array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23');
		$minutes = array('00'=>'00','15'=>'15','30'=>'30','45'=>'45');
		
		$GDA = Yii::app()->db->createCommand()->select('GDA_ID,GDA_KCAL,GDA_GENDER,GDA_DESC_'.Yii::app()->session['lang'])->from('gda_suggestions')->order('GDA_GENDER, GDA_AGE_FROM')->queryAll();
		$GDA_Man = array();
		$GDA_Woman = array();
		foreach($GDA as $row){
			$id = $row['GDA_ID'] . '_' . $row['GDA_KCAL'];
			if ($row['GDA_GENDER'] == 'M'){
				$GDA_Man[$id] = $row['GDA_DESC_'.Yii::app()->session['lang']];
			} else {
				$GDA_Woman[$id] = $row['GDA_DESC_'.Yii::app()->session['lang']];
			}
		}
		unset($GDA);
		/*
		echo '<pre>';
		foreach($model->meaToCous as $meaToCou){
			echo "meaToCou:\r\n";
			print_r($meaToCou);
			print_r($meaToCou->course);
			echo "\r\n\r\n\r\n";
			return;
			foreach($meaToCou->course->couToRecs as $couToRec){
				echo "couToRec:r\n";
				print_r($couToRec);
				print_r($couToRec->recipe);
				echo "\r\n\r\n\r\n";
			}
		}
		print_r($model);
		echo '</pre>';
		return;
		*/
		
		$couPeopleGDA = array();
		if (isset($model->meaToCous) && count($model->meaToCous) > 0 && $model->meaToCous[0] != null){
			$peopleInfos = explode(';',$model->meaToCous[0]->MTC_EAT_PERS);
			foreach ($peopleInfos as $peopleInfo){
				if (strlen($peopleInfo)>0){
					list($amount, $values) = explode('x',$peopleInfo,2);
					list($gender, $gda) = explode(':',$values,2);
					
					$newCouPeopleGDA = new CouPeopleGDA;
					$newCouPeopleGDA->amount = $amount;
					$newCouPeopleGDA->gender = $gender;
					$newCouPeopleGDA->gda_id_kcal = $gda;
					$couPeopleGDA[] = $newCouPeopleGDA;
				}
			}
		}
		
		if (count($couPeopleGDA)==0){
			$newCouPeopleGDA = new CouPeopleGDA;
			$newCouPeopleGDA->amount = 1;
			$newCouPeopleGDA->gender = 'F'; //TODO: rofiles->PRF_GENDER
			$newCouPeopleGDA->gda_id_kcal = '15_2000'; //TODO: year(time() - progiles->PRF_BIRTHDAY) --> gda_suggestions
			$couPeopleGDA[] = $newCouPeopleGDA;
		}
			
		Yii::app()->session['Meals_Backup'] = $model;
		
		$this->checkRenderAjax('mealplanner',array(
			'model'=>$model,
			'mealType'=>$mealType,
			'hours'=>$hours,
			'minutes'=>$minutes,
			'GDA_Man'=>$GDA_Man,
			'GDA_Woman'=>$GDA_Woman,
			'couPeopleGDA'=>$couPeopleGDA,
		));
	}
	
	public function actionChangePeople(){
		$this->checkRenderAjax('changePeople',array(
		));
	}
	
	public function actionMealList(){
		$selectDate = mktime(0,0,0, date("n"), date("j"), date("Y"));
		$dataProvider=new CActiveDataProvider('Meals', array(
			'criteria'=>array(
				'condition'=>'PRF_UID = ' . Yii::app()->user->id,
				'order'=>'MEA_DATE',
				//'condition'=>'MEA_DATE > ' . $selectDate, //TODO: only show planed meals in future
			),
		));
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	
	
	public function actionShoppingList($id){
		$command = Yii::app()->dbp->createCommand()
			->select('meals.*, mea_to_cou.*')
			->from('meals')
			->leftJoin('mea_to_cou', 'mea_to_cou.MEA_ID=meals.MEA_ID')
			->where('meals.MEA_ID = :id',array(':id'=>$id));
		$meal_Course = $command->queryAll();
		
		$paramName = '';
		$paramArray = array();
		$meaToCous = array();
		for($i=0;$i<count($meal_Course);++$i){
			if ($paramName != ''){
				$paramName .= ', ';
			}
			$paramName .= ':id'.$i;
			$paramArray[':id'.$i] = $meal_Course[$i]['COU_ID'];
			$meaToCous[$meal_Course[$i]['COU_ID']] = $meal_Course[$i];
		}
		unset($meal_Course);
		
		$command = Yii::app()->db->createCommand()
			->select('courses.*, cou_to_rec.*, recipes.*, steps.STE_STEP_NO, steps.STE_GRAMS, ingredients.ING_IMG_AUTH, ingredients.ING_ID, ING_NAME_'.Yii::app()->session['lang'])
			->from('courses')
			->leftJoin('cou_to_rec', 'cou_to_rec.COU_ID=courses.COU_ID')
			->leftJoin('recipes', 'recipes.REC_ID=cou_to_rec.REC_ID')
			->leftJoin('steps', 'steps.REC_ID=recipes.REC_ID')
			->leftJoin('ingredients', 'ingredients.ING_ID=steps.ING_ID')
			->where('courses.COU_ID IN (' . $paramName . ') AND ingredients.ING_ID IS NOT NULL',$paramArray)
			->order('courses.COU_ID, recipes.REC_ID, steps.STE_STEP_NO');
			
			/*
			->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
			->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
			->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
			->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
			->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
			->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
			->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
			->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
			->leftJoin('stores', 'pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID')
			->group('ingredients.ING_ID');
			//echo $command->text;
			*/
		
		$rows = $command->queryAll();
		
		$ingredients = array();
		$rec_id = -1;
		for($i=0; $i<count($rows); ++$i){
			$row = $rows[$i];
			if ($rec_id != $row['REC_ID']){
				$meaToCou = $meaToCous[$row['COU_ID']];
				$meal_gda = $meaToCou['MTC_KCAL_DAY_TOTAL'] * $meaToCou['MEA_PERC_GDA'] / 100;
				$cou_gda = $meal_gda * $meaToCou['MTC_PERC_MEAL'] / 100;
				$rec_gda = $cou_gda * $row['CTR_REC_PROC'] / 100;
				$rec_kcal = $row['REC_KCAL'];
				if ($rec_kcal != 0){
					$rec_proz = $rec_gda / $rec_kcal;
				} else {
					//TODO: this is a data error!, or a recipe without ingredients .... ?
					$rec_proz = 1;
				}
			}
			$ing_amount = round($row['STE_GRAMS'] * $rec_proz, 2);
			$ing = array('ING_ID'=>$row['ING_ID'],'ING_NAME_'.Yii::app()->session['lang']=>$row['ING_NAME_'.Yii::app()->session['lang']],'ing_amount'=>$ing_amount);
			$ingredients[] = $ing;
			
			$rows[$i]['ing_amount'] = $ing_amount;
			$rows[$i]['meal_gda'] = $meal_gda;
			$rows[$i]['cou_gda'] = $cou_gda;
			$rows[$i]['rec_gda'] = $rec_gda;
			$rows[$i]['rec_kcal'] = $rec_kcal;
			$rows[$i]['rec_proz'] = $rec_proz;
		}
		
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'ING_ID',
			'keyField'=>'ING_ID',
			'pagination'=>array(
				'pageSize'=>40,
			),
		));
		
		$this->checkRenderAjax('shoppingList',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionShoppingList2($id){
		$distanceFields = '';
		if (isset(Yii::app()->session['current_gps']) && isset(Yii::app()->session['current_gps'][2])) {
			$distanceFields = ', SUM(IF(cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'' . Yii::app()->session['current_gps'][2] . '\')) <= '. Yii::app()->user->view_distance . ', 1, 0)) as distance_to_you';
			$distanceFields .= ', count(DISTINCT IF(cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'' . Yii::app()->session['current_gps'][2] . '\')) <= '. Yii::app()->user->view_distance . ', products.PRO_ID, NULL)) as distance_to_you_prod';
		} else {
			$distanceFields = ', MIN(-1) as distance_to_you';
			$distanceFields .= ', MIN(-1) as distance_to_you_prod';
		}
		
		if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[2])){
			$distanceFields .= ', SUM(IF(cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'' . Yii::app()->user->home_gps[2] . '\')) <= '. Yii::app()->user->view_distance . ', 1, 0)) as distance_to_home';
			$distanceFields .= ', count(DISTINCT IF(cosines_distance(stores.STO_GPS_POINT, GeomFromText(\'' . Yii::app()->user->home_gps[2] . '\')) <= '. Yii::app()->user->view_distance . ', products.PRO_ID, NULL)) as distance_to_home_prod';
		} else {
			$distanceFields .= ', MIN(-1) as distance_to_home';
			$distanceFields .= ', MIN(-1) as distance_to_home_prod';
		}
		
		$command = Yii::app()->db->createCommand()
			->select('ingredients.*, nutrient_data.*, group_names.*, subgroup_names.*, ingredient_conveniences.*, storability.*, ingredient_states.*, count(DISTINCT products.PRO_ID) as pro_count' . $distanceFields)
			->from('ingredients')
			->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
			->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
			->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
			->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
			->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
			->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
			->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
			->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
			->leftJoin('stores', 'pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID')
			->group('ingredients.ING_ID');
			//echo $command->text;
		
		$suppliersCommand = Yii::app()->db->createCommand()
			->select('ingredients.ING_ID, suppliers.SUP_NAME')
			->from('ingredients')
			->leftJoin('products', 'ingredients.ING_ID=products.ING_ID')
			->leftJoin('pro_to_sto', 'pro_to_sto.PRO_ID=products.PRO_ID')
			->leftJoin('suppliers', 'suppliers.SUP_ID=pro_to_sto.SUP_ID')
			->group('ingredients.ING_ID, suppliers.SUP_ID')
			->order('ingredients.ING_ID, suppliers.SUP_ID');
		
		$rows = $command->queryAll();
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
		
		$dataProvider=new CArrayDataProvider($rows, array(
			'id'=>'ING_ID',
			'keyField'=>'ING_ID',
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		
		$this->checkRenderAjax('shoppingList',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session['Meals_Backup'];
		} else {
			$model=Meals::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='meals-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
