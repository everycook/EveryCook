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

	protected $createBackup = 'Meals_Backup';
	protected $searchBackup = 'Meals';
	
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
				'actions'=>array('create','update','mealPlanner','mealList','createShoppingList','cancel'),
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
		if (isset($Session_Backup) && isset($Session_Backup->MEA_ID)){
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->MEA_ID));
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$this->actionMealPlanner();
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$this->actionMealPlanner();
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
	
	public function actionMealList(){
		$this->actionIndex();
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() {
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
		
		if (!isset($_GET['id'])){ // -> create
			if (isset($_GET['newModel']) && isset(Yii::app()->session[$this->createBackup.'_Time']) && $_GET['newModel']>Yii::app()->session[$this->createBackup.'_Time']){
					unset(Yii::app()->session[$this->createBackup]);
					unset(Yii::app()->session[$this->createBackup.'_Time']);
					unset($_GET['newModel']);
			}
		}
		
		$Session_Meals_Backup = Yii::app()->session[$this->createBackup];
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
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			
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
						unset(Yii::app()->session[$this->createBackup]);
						unset(Yii::app()->session[$this->createBackup.'_Time']);
						if (isset($_POST['saveToShoppingList'])){
							if (isset($model->SHO_ID) && $model->SHO_ID != 0){
								$this->forwardAfterSave(array('shoppinglists/view', 'id'=>$model->SHO_ID));
							} else {
								$this->forwardAfterSave(array('createShoppingList', 'id'=>$model->MEA_ID));
							}
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
			
		Yii::app()->session[$this->createBackup] = $model;
		
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
	
	
	
	public function actionCreateShoppingList($id){
		$this->saveLastAction = false;
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
		
		$rows = $command->queryAll();
		
		$ing_to_weights = array();
		
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
			$ing_amount = $row['STE_GRAMS'] * $rec_proz;
			
			if(isset($ing_to_weights[$row['ING_ID']])){
				$ing_to_weights[$row['ING_ID']] += $ing_amount;
			} else {
				$ing_to_weights[$row['ING_ID']] = $ing_amount;
			}
		}
		$command = Yii::app()->dbp->createCommand()
			->select('meals.SHO_ID')
			->from('meals')
			->where('meals.MEA_ID = :id',array(':id'=>$id));
		$sho_id = $command->queryScalar();
		
		if (!isset($sho_id) || $sho_id == 0){
			$shoppingList = new Shoppinglists;
			$shoppingList->SHO_DATE = time(); //TODO
		} else {
			$shoppingList = Shoppinglists::model()->findByPk($sho_id);
			if($shoppingList === null){
				$shoppingList = new Shoppinglists;
				$shoppingList->SHO_DATE = time(); //TODO
				$shoppingList->SHO_ID = $sho_id;
			}
		}
		
		
		$ing_ids_old = explode(';',$shoppingList->SHO_INGREDIENTS);
		$ing_weights_old = explode(';',$shoppingList->SHO_WEIGHTS);
		$pro_ids_old = explode(';',$shoppingList->SHO_PRODUCTS);
		$amounts_old = explode(';',$shoppingList->SHO_QUANTITIES);
		
		$ingToIndex = array();
		//$ing_to_weights_old = array();
		for($i=0;$i<count($ing_ids_old);++$i){
			$ing_id = $ing_ids_old[$i];
			if ($ing_id != ''){
				$ingToIndex[$ing_id]=$i;
				//$ing_to_weights_old[$ing_id] = $ing_weights_old[$i];
				if (!isset($ing_to_weights[$ing_id])){
					$ing_to_weights[$ing_id] = $ing_weights_old[$i];
				}
			}
		}
		
		$ing_id_text = '';
		$ing_weight_text = '';
		$pro_id_text = '';
		$amount_text = '';
		foreach($ing_to_weights as $ing_id=>$ing_weight){		
			if ($ing_id_text != ''){
				$ing_id_text .= ';';
				$ing_weight_text .= ';';
				$pro_id_text .= ';';
				$amount_text .= ';';
			}
			$ing_id_text .= $ing_id;
			$ing_weight_text .= round($ing_weight, 2);
			if(isset($ingToIndex[$ing_id])){
				$pro_id_text .= $pro_ids_old[$ingToIndex[$ing_id]];
				$amount_text .= $amounts_old[$ingToIndex[$ing_id]];
			}
		}
		
		$shoppingList->SHO_INGREDIENTS = $ing_id_text;
		$shoppingList->SHO_WEIGHTS = $ing_weight_text;
		$shoppingList->SHO_PRODUCTS = $pro_id_text;
		$shoppingList->SHO_QUANTITIES = $amount_text;
		if ($shoppingList->save()){		
			Yii::app()->dbp->createCommand()->update(Meals::model()->tableName(), array('SHO_ID'=>$shoppingList->SHO_ID), 'MEA_ID = :id', array(':id'=>$id));
			
			$command = Yii::app()->dbp->createCommand()
				->select('profiles.PRF_SHOPLISTS')
				->from('profiles')
				->where('profiles.PRF_UID = :id',array(':id'=>Yii::app()->user->id));
			$shoppinglists = $command->queryScalar();
			if (!isset($shoppinglists) || $shoppinglists == null || $shoppinglists == ''){
				$shoppinglists = $shoppingList->SHO_ID;
			} else {
				//no dupplicates
				$values = explode(';', $shoppinglists);
				$values[] = $shoppingList->SHO_ID;
				$values = array_unique($values);
				//sort($values, SORT_NUMERIC);
				$shoppinglists = implode(';', $values);
			}
			
			Yii::app()->dbp->createCommand()->update(Profiles::model()->tableName(), array('PRF_SHOPLISTS'=>$shoppinglists), 'PRF_UID = :id', array(':id'=>Yii::app()->user->id));
			
			$this->forwardAfterSave(array('shoppinglists/view', 'id'=>$shoppingList->SHO_ID));
		} else {
			echo '<pre>';
			print_r($shoppingList->getErrors());
			echo '</pre>';
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session[$this->createBackup];
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
