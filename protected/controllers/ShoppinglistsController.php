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

	protected $createBackup = 'Shoppinglists_Backup';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('preview','print','setHaveIt'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','index','create','update','removeFromList','setProduct','showAllAsOne', 'mail','save'),
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
	
	public function actionSetHaveIt($id, $ing_id, $value){
		if ($id != 'backup'){
			$shoppinglists = Yii::app()->user->shoppinglists;
			if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			} else {
				$values = array_flip($shoppinglists);
				if (!isset($values[$id])){
					throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
				}
			}
		}
		
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$haveIt_values = explode(';',$model->SHO_HAVE_IT);
		
		for($i=0;$i<count($ing_ids);++$i){
			if ($ing_ids[$i] == $ing_id){
				$haveIt_values[$i] = $value;
				break;
			}
		}
		$model->SHO_HAVE_IT = implode(';',$haveIt_values);

		Yii::app()->session[$this->createBackup] = $model;
		Yii::app()->session[$this->createBackup.'_Time'] = time();
		
// 		if(Yii::app()->user->demo){
// 			echo '{"sucessfull":false, "error":"' . sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
// 		} else {
// 			if($model->save()){
// 				echo '{"sucessfull":true}';
// 			} else {
// 				echo '{"sucessfull":false, "error":"';
// 				print_r($model->getErrors());
// 				echo '"}';
// 			}
// 		}
	}
	
	public function actionSetProduct($id, $ing_id){
		$shoppinglists = Yii::app()->user->shoppinglists;
		if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
			throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
		} else {
			$values = array_flip($shoppinglists);
			if (!isset($values[$id])){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			}
		}
		
		$pro_id = $_GET['pro_id'];
		$gramms = $_GET['gramms'];
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$gramm_values = explode(';',$model->SHO_QUANTITIES);
		
		for($i=0;$i<count($ing_ids);++$i){
			if ($ing_ids[$i] == $ing_id){
				$pro_ids[$i] = $pro_id;
				if ($gramms > 0){
					$gramm_values[$i] = $gramms;
				} else {
					$gramm_values[$i] = '';
				}
				break;
			}
		}
		
		$model->SHO_PRODUCTS = implode(';',$pro_ids);
		$model->SHO_QUANTITIES = implode(';',$gramm_values);
		
		if(Yii::app()->user->demo){
			echo '{"sucessfull":false, "error":"' . sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
		} else {
			if($model->save()){
				echo '{"sucessfull":true}';
			} else {
				echo '{"sucessfull":false, "error":"';
				print_r($model->getErrors());
				echo '"}';
			}
		}
	}
	
	public function actionRemoveFromList($id, $ing_id){
		$shoppinglists = Yii::app()->user->shoppinglists;
		if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
			throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
		} else {
			$values = array_flip($shoppinglists);
			if (!isset($values[$id])){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			}
		}
		
		$model = $this->loadModel($id);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$gramm_values = explode(';',$model->SHO_QUANTITIES);
		
		for($i=0;$i<count($ing_ids);++$i){
			if ($ing_ids[$i] == $ing_id){
				unset($ing_ids[$i]);
				unset($ing_weights[$i]);
				unset($pro_ids[$i]);
				unset($gramm_values[$i]);
				break;
			}
		}
		
		$model->SHO_INGREDIENTS = implode(';',$ing_ids);
		$model->SHO_WEIGHTS = implode(';',$ing_weights);
		$model->SHO_PRODUCTS = implode(';',$pro_ids);
		$model->SHO_QUANTITIES = implode(';',$gramm_values);
		
		if(Yii::app()->user->demo){
			echo '{"sucessfull":false, "error":"' . sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
		} else {
			if($model->save()){
				echo '{"sucessfull":true}';
			} else {
				echo '{"sucessfull":false, "error":"';
				print_r($model->getErrors());
				echo '"}';
			}
		}
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->prepareView($id, 'view');
	}
	public function actionPreview() {
		$this->prepareView('backup', 'view');
	}

	public function actionPrint($id) {
		$this->isFancyAjaxRequest = true;
		$this->prepareView($id, 'print');
	}
	
	public function actionMail($id) {
		$values = $this->prepareView($id, null);
		$datas = $values['data'];
		$text = sprintf($this->trans->TITLE_SHOPPINGLISTS_VIEW, $values['SHO_ID']);
		if ($id == 'backup'){
			$link = $this->createUrl('recipes/viewShoppingList', array('ids'=>$values['recipes_id'])); 
		} else {
			$link = $this->createUrl('shoppinglist/view', array('id'=>$id));
		}
		foreach($datas as $data){
			$text .= CHtml::encode($data['ING_NAME']) . ' ';
			$text .= $data['ing_weight'] . 'g' . "\t";
			if ($data['haveIt'] == 1){
				$text .= $this->trans->SHOPPINGLISTS_HAVE_IT;
			}
			$text .= "\r\n";
		}
		if(Yii::app()->user->demo){
			$result = array('error'=>sprintf($this->trans->DEMO_USER_CANNOT_SEND_MAIL, $this->createUrl("profiles/register")));
		} else {
			$result = $this->sendMail($text, $link, Yii::app()->user->nick, Yii::app()->user->email);
		}
		


		$this->checkRenderAjax('mail',$result,'inline');
		
		/*
		Yii::app()->user->setFlash('shoppinglistsView', $result);
		
		//now continue show view
		$dataProvider=new CArrayDataProvider($values['data'], array(
				'id'=>'ING_ID',
				'keyField'=>'ING_ID',
		));
		
		if (isset($_GET['ajaxPaging']) && $_GET['ajaxPaging']){
			$_GET['ajaxPagingView'] = 'item_paging';
		}
		unset($values['data']);
		$values['dataProvider'] = $dataProvider;
		
		$this->checkRenderAjax('view', $values);
		*/
	}

	private function sendMail($text, $link, $name, $email){
		$subject = $this->trans->SHOPPINGLISTS_MAIL_SUBJECT;
		$body =
			sprintf($this->trans->SHOPPINGLISTS_MAIL_CONTENT_MESSAGE, '') . "<br>\r\n" . //here could be ' <anrede> <name>'.
			sprintf($this->trans->SHOPPINGLISTS_MAIL_CONTENT_LINK, $link, $link) . "<br>\r\n" .
			sprintf($this->trans->SHOPPINGLISTS_MAIL_CONTENT_MESSAGE2, '') . "<br>\r\n" .
			$text . "<br>\r\n" .
			sprintf($this->trans->SHOPPINGLISTS_MAIL_CONTENT_REGARDS, Yii::app()->params['verificationRegardsName']) . "<br><br><br>\r\n";
		
		try {
			Yii::import('application.extensions.phpmailer.JPhpMailer');
			$mail = new JPhpMailer(true);
			//$mail->SMTPDebug = true;
			$mail->IsSMTP();
			$mail->Host = Yii::app()->params['SMTPMailHost'];
			$mail->SMTPAuth = true;
			$mail->Username = Yii::app()->params['SMTPMailUser'];
			$mail->Password = Yii::app()->params['SMTPMailPW'];
			$mail->SMTPSecure = "tls";
			$mail->SetFrom(Yii::app()->params['verificationEmail'], Yii::app()->params['verificationEmailName']);
			$mail->Subject = $subject;
			//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
			$mail->MsgHTML($body);
			$mail->AddAddress($email, $name);
			$mail->Send();
		} catch(phpmailerException $e){
			if($this->debug){
				return array('error'=>$e->getMessage());
			} else {
				return array('error'=>$this->trans->SHOPPINGLISTS_MAIL_ERROR);
			}
		}
		return array('success'=>$this->trans->SHOPPINGLISTS_MAIL_SUCCESS);
	}
	
	
	private function prepareView($id, $view) {
		if ($id != 'backup'){
			$shoppinglists = Yii::app()->user->shoppinglists;
			if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			} else {
// 				$values = array_flip($shoppinglists);
// 				if (!isset($values[$id])){
				if(!in_array($id, $shoppinglists)){
					throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
				}
			}
		}
		
		$model = $this->loadModel($id);
		if ($id == 'backup'){
			$id = $model->SHO_ID;
		}
		
		$recipeEntrys = explode(';',$model->SHO_RECIPES);
		$recipes = array();
		foreach ($recipeEntrys as $entry){
			$values = explode(':',$entry);
			if (count($values)>1){
				$recipes[$values[0]] = $values[1];
			} else {
				$recipes[$values[0]] = 1;
			}
		}
		$rec_ids = array_keys($recipes);
		$ing_ids = explode(';',$model->SHO_INGREDIENTS);
		$ing_weights = explode(';',$model->SHO_WEIGHTS);
		$pro_ids = explode(';',$model->SHO_PRODUCTS);
		$gramm_values = explode(';',$model->SHO_QUANTITIES);
		$haveIt_values = explode(';',$model->SHO_HAVE_IT);

		$changed = false;
		if (count($rec_ids)>0){
			$criteria=new CDbCriteria;
			$criteria->addInCondition('REC_ID',$rec_ids);
			$command = Yii::app()->db->createCommand()
				->select('recipes.REC_ID, recipes.CHANGED_ON')
				->from('recipes')
				->where($criteria->condition, $criteria->params);
			$data = $command->queryAll();
			foreach ($data as $recipe){
				if (isset($meal['CHANGED_ON']) && $meal['CHANGED_ON'] > $model->CHANGED_ON){
					$changed = true;
					break;
				}
			}
		}
		$model=Yii::app()->session[$this->createBackup] = $model;
		
		return $this->viewList($rec_ids, $ing_ids, $ing_weights, $pro_ids, $gramm_values, $haveIt_values, $id, null, $changed, $model->SHO_RECIPES, $view);
	}
	
	
	public function actionShowAllAsOne() {
		$shoppinglists = Yii::app()->user->shoppinglists;
		if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
			$dataProvider=new CArrayDataProvider(array(), array(
				'id'=>'ING_ID',
				'keyField'=>'ING_ID',
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
			
			$this->checkRenderAjax('view',array(
				'SHO_ID'=>-1,
				'dataProvider'=>$dataProvider,
			));
		} else {
			$values = $shoppinglists;
			
			$selectDate = mktime(0,0,0, date("n"), date("j")-7, date("Y"));
			
			$criteria=new CDbCriteria;
			$criteria->compare('SHO_ID',$values,true);
			//$criteria->addCondition('SHO_DATE > '.$selectDate); //TODO: only show shoppinglists in future.
			
			$command = Yii::app()->dbp->createCommand()
				->from('shoppinglists')
				->where($criteria->condition,$criteria->params)
				->order('SHO_DATE');
			$shoppinglists = $command->queryAll();
			
			$recipes_total = array();
			$rec_ids_total = array();
			$ing_ids_total = array();
			$ing_weights_total = array();
			$pro_ids_total = array();
			$gramm_values_total = array();
			$ing_id_to_index = array();
			$haveIt_values_total = array();
			$total_index = 0;
			foreach($shoppinglists as $shoppinglist){
				$recipeEntrys = explode(';',$shoppinglist['SHO_RECIPES']);
				$recipes = array();
				foreach ($recipeEntrys as $entry){
					$values = explode(':',$entry);
					if (count($values)>1){
						$recipes[$values[0]] = $values[1];
					} else {
						$recipes[$values[0]] = 1;
					}
				}
				$rec_ids = array_keys($recipes);
				$ing_ids = explode(';',$shoppinglist['SHO_INGREDIENTS']);
				$ing_weights = explode(';',$shoppinglist['SHO_WEIGHTS']);
				$pro_ids = explode(';',$shoppinglist['SHO_PRODUCTS']);
				$gramm_values = explode(';',$shoppinglist['SHO_QUANTITIES']);
				
				for($i=0; $i<count($ing_ids);++$i){
					$ing_id = $ing_ids[$i];
					if (isset($ing_id_to_index[$ing_id])){
						$index = $ing_id_to_index[$ing_id];
						
						//$ing_ids_total[$index] = $ing_ids[$i];
						$ing_weights_total[$index] = $ing_weights_total[$index] + $ing_weights[$i];
						if (!isset($pro_ids_total[$index]) || $pro_ids_total[$index] == ''){
							$pro_ids_total[$index] = $pro_ids[$i];
							$gramm_values_total[$index] = $gramm_values[$i];
						}
					} else {
						$ing_ids_total[$total_index] = $ing_ids[$i];
						$ing_weights_total[$total_index] = $ing_weights[$i];
						$pro_ids_total[$total_index] = $pro_ids[$i];
						$gramm_values_total[$total_index] = $gramm_values[$i];
						$ing_id_to_index[$ing_id] = $total_index;
						$haveIt_values_total[$total_index] = 0;
						++$total_index;
					}
				}
				foreach ($recipes as $id => $amount){
					if(isset($recipes_total[$id])){
						$recipes_total[$id] += $amount;
					} else {
						$recipes_total[$id] = $amount;
					}
				}
			}
			$rec_ids_total = array_keys($recipes_total);
			$this->viewList($rec_ids_total, $ing_ids_total, $ing_weights_total, $pro_ids_total, $gramm_values_total, $haveIt_values_total, 0, 0, false, null, 'view');
		}
	}
	
	private function viewList($rec_ids, $ing_ids, $ing_weights, $pro_ids, $gramm_values, $haveIt_values, $SHO_ID, $MEA_ID, $mealChanged, $recipes_id, $view) {
		$rec_criteria=new CDbCriteria;
		$rec_criteria->compare('REC_ID',$rec_ids,true);
		
		$ing_criteria=new CDbCriteria;
		$ing_criteria->compare('ING_ID',$ing_ids,true);
		
		$pro_criteria=new CDbCriteria;
		$pro_criteria->compare('products.PRO_ID',$pro_ids,true);
		

		$command = Yii::app()->db->createCommand()
			->select('REC_ID, REC_IMG_AUTH, REC_IMG_ETAG, REC_NAME_'.Yii::app()->session['lang'])
			->from('recipes')
			->where($rec_criteria->condition, $rec_criteria->params);
		
		$recipe_rows = $command->queryAll();
		
		//sort recipes like in id string
		$recipe_indexed = array();
		foreach ($recipe_rows as $row){
			$recipe_indexed[$row['REC_ID']] = $row;
		}
		$recipe_names = array();
		foreach($rec_ids as $rec_id){
			$recipe_names[] = $recipe_indexed[$rec_id]; 
		}
		
		
		$command = Yii::app()->db->createCommand()
			->select('ingredients.ING_ID, ingredients.ING_IMG_AUTH, ingredients.ING_IMG_ETAG, ING_NAME_'.Yii::app()->session['lang'])
			->from('ingredients')
			->where($ing_criteria->condition, $ing_criteria->params);
			
		$ingredient_names = $command->queryAll();
		
		
// 		$distanceForGroupSQL = 'SELECT
// 			theView.PRO_ID,
// 			MAX(min_dist) as min_dist,
// 			MAX(dist) as dist,
// 			SUM(amount) as amount,
// 			SUM(amount_range) as amount_range
// 			FROM (
// 				(SELECT
// 					@count := 0,
// 					@oldId := 0 AS PRO_ID,
// 					0 AS min_dist,
// 					0 AS dist,
// 					0 AS amount,
// 					0 As amount_range)
// 				UNION
// 				(SELECT
// 					@count := if(@oldId = id, @count+1, 0),
// 					@oldId := id,
// 					if(@count = 0, value, 0),
// 					if(@count < :count, value, 0),
// 					if(@count < :count, 1, 0),
// 					if(value < :view_distance, 1, 0)
// 				FROM
// 					(SELECT products.PRO_ID as id, cosines_distance(stores.STO_GPS_POINT, GeomFromText(\':point\')) as value
// 					FROM products
// 					LEFT JOIN pro_to_sto ON pro_to_sto.PRO_ID=products.PRO_ID 
// 					LEFT JOIN stores ON pro_to_sto.SUP_ID=stores.SUP_ID AND pro_to_sto.STY_ID=stores.STY_ID
// 					WHERE stores.STO_GPS_POINT IS NOT NULL
// 					ORDER BY products.PRO_ID, value ASC) AS theTable
// 				)
// 			) AS theView
// 			WHERE theView.PRO_ID != 0 AND (dist != 0 OR amount_range != 0)
// 			GROUP BY theView.PRO_ID;';
		
// 		if (isset(Yii::app()->session['current_gps']) && isset(Yii::app()->session['current_gps'][2])) {
// 			$point = Yii::app()->session['current_gps'][2];
// 			$count = 5;
// 			$youDistanceForGroupSQL = str_replace(':point', $point, $distanceForGroupSQL);
// 			$youDistanceForGroupSQL = str_replace(':count', $count, $youDistanceForGroupSQL);
// 			$youDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $youDistanceForGroupSQL);
// 			$youDistCommand = Yii::app()->db->createCommand($youDistanceForGroupSQL);
// 			$hasYouDist = true;
// 		} else {
// 			$hasYouDist = false;
// 		}
		
// 		if (!Yii::app()->user->isGuest && isset(Yii::app()->user->home_gps) && isset(Yii::app()->user->home_gps[2])){
// 			$point = Yii::app()->user->home_gps[2];
// 			$count = 5;
// 			$homeDistanceForGroupSQL = str_replace(':point', $point, $distanceForGroupSQL);
// 			$homeDistanceForGroupSQL = str_replace(':count', $count, $homeDistanceForGroupSQL);
// 			$homeDistanceForGroupSQL = str_replace(':view_distance', Yii::app()->user->view_distance, $homeDistanceForGroupSQL);
// 			$HomeDistCommand = Yii::app()->db->createCommand($homeDistanceForGroupSQL);
// 			$hasHomeDist = true;
// 		} else {
// 			$hasHomeDist = false;
// 		}
		
// 		$command = Yii::app()->db->createCommand()
// 			->select('products.PRO_ID, products.PRO_IMG_AUTH, products.PRO_IMG_ETAG, PRO_NAME_'.Yii::app()->session['lang'])
// 			->from('products')
// 			->group('products.PRO_ID');
		
// 		$command->where($pro_criteria->condition, $pro_criteria->params);
// 		if ($hasYouDist){
// 			$youDistCommand->where($pro_criteria->condition, $pro_criteria->params);
// 		}
// 		if ($hasHomeDist){
// 			$HomeDistCommand->where($pro_criteria->condition, $pro_criteria->params);
// 		}
// 		$rows = $command->queryAll();
		
// 		if ($hasYouDist){
// 			$youDistRows = $youDistCommand->queryAll();
// 			$youDistArray = array();
// 			foreach ($youDistRows as $row){
// 				$youDistArray[$row['PRO_ID']] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
// 			}
// 		}
// 		if ($hasHomeDist){
// 			$homeDistRows = $HomeDistCommand->queryAll();
// 			$homeDistArray = array();
// 			foreach ($homeDistRows as $row){
// 				$homeDistArray[$row['PRO_ID']] = array($row['dist'], $row['amount'], $row['min_dist'], $row['amount_range']);
// 			}
// 		}
		
		
		//Merge data
		$data = array();
		$proToIng = array();
		for ($i=0; $i<count($ing_ids);++$i){
			$ing_id = $ing_ids[$i];
			$proToIng[$pro_ids[$i]] = $ing_id;
			if (!isset($haveIt_values[$i])){
				$haveIt = 0;
			} else {
				$haveIt = $haveIt_values[$i];
			}
			$data[$ing_id] = array('ING_ID'=>$ing_id, 'ing_weight'=>$ing_weights[$i], 'PRO_ID'=>$pro_ids[$i], 'amount'=>$gramm_values[$i], 'haveIt'=>$haveIt, 'SHO_ID'=>$SHO_ID);
		}
		foreach($ingredient_names as $row){
			$ing_id = $row['ING_ID'];
			$data[$ing_id]['ING_NAME'] = $row['ING_NAME_'.Yii::app()->session['lang']];
			$data[$ing_id]['ING_IMG_AUTH'] = $row['ING_IMG_AUTH'];
			$data[$ing_id]['ING_IMG_ETAG'] = $row['ING_IMG_ETAG'];
		}
		
// 		foreach($rows as $row){
// 			$pro_id = $row['PRO_ID'];
// 			$ing_id = $proToIng[$pro_id];
			
// 			$data[$ing_id]['PRO_NAME'] = $row['PRO_NAME_'.Yii::app()->session['lang']];
// 			$data[$ing_id]['PRO_IMG_AUTH'] = $row['PRO_IMG_AUTH'];
// 			$data[$ing_id]['PRO_IMG_ETAG'] = $row['PRO_IMG_ETAG'];
			
// 			if ($hasYouDist){
// 				if (isset($youDistArray[$pro_id])){
// 					$data[$ing_id]['distance_to_you'] = $youDistArray[$pro_id];
// 				} else  {
// 					$data[$ing_id]['distance_to_you'] = -2;
// 				}
// 			} else {
// 				$data[$ing_id]['distance_to_you'] = -1;
// 			}
// 			if ($hasHomeDist){
// 				if (isset($homeDistArray[$pro_id])){
// 					$data[$ing_id]['distance_to_home'] = $homeDistArray[$pro_id];
// 				} else  {
// 					$data[$ing_id]['distance_to_home'] = -2;
// 				}
// 			} else {
// 				$data[$ing_id]['distance_to_home'] = -1;
// 			}
// 		}
		
		if (!isset($view)){
			return array(
				'data'=>$data,
				'SHO_ID'=>$SHO_ID,
				'MEA_ID'=>$MEA_ID,
				'mealChanged'=>$mealChanged,
				'recipes_id'=>$recipes_id,
				'recipe_names'=>$recipe_names,
			);
		}
		
		$dataProvider=new CArrayDataProvider($data, array(
			'id'=>'ING_ID',
			'keyField'=>'ING_ID',
			/*'pagination'=>array(
				'pageSize'=>300, // no paging...
			),*/
		));
		
		if (isset($_GET['ajaxPaging']) && $_GET['ajaxPaging']){
			$_GET['ajaxPagingView'] = 'item_paging';
		}
		
		$this->checkRenderAjax($view,array(
			'SHO_ID'=>$SHO_ID,
			'MEA_ID'=>$MEA_ID,
			'mealChanged'=>$mealChanged,
			'recipes_id'=>$recipes_id,
			'dataProvider'=>$dataProvider,
			'recipe_names'=>$recipe_names, 
		),($view=='print'?'inline':null));
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
			
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				if($model->save()){
					$this->redirect(array('view','id'=>$model->SHO_ID));
				}
			}
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
			
			if(Yii::app()->user->demo){
				$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
			} else {
				if($model->save()) {
					Yii::app()->user->addShoppingListId($model->SHO_ID);
					$this->redirect(array('view','id'=>$model->SHO_ID));
				}
			}
		}

		$this->checkRenderAjax('update',array(
			'model'=>$model,
		));
	}
	
	public function actionSave($id){
		if ($id != 'backup'){
			$shoppinglists = Yii::app()->user->shoppinglists;
			if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
				throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
			} else {
				// 				$values = array_flip($shoppinglists);
				// 				if (!isset($values[$id])){
				if(!in_array($id, $shoppinglists)){
					throw new CHttpException(403,'It\'s not allowed to open shoppinglists of other users, expect they share it with you.');
				}
			}
		}
		$model=$this->loadModel($id);
		if($model->save()){
			Yii::app()->user->addShoppingListId($model->SHO_ID);
			//Yii::app()->user->setFlash('shoppinglistsView', array('sucessfull'=>'???'));
			$this->redirect(array('view','id'=>$model->SHO_ID));
		} else {
			Yii::app()->user->setFlash('shoppinglistsView', array('error'=>print_r($model->getErrors(), true)));
			//switching to preview not needed because user logged in to perform save action.
			$this->redirect(array('view','id'=>$id));
		}
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
	public function actionIndex() {
		$shoppinglists = Yii::app()->user->shoppinglists;
		if (!isset($shoppinglists) || $shoppinglists == null || count($shoppinglists) == 0){
			$dataProvider=new CArrayDataProvider(array(), array(
				'id'=>'SHO_ID',
				'keyField'=>'SHO_ID',
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
		} else {
			$values = $shoppinglists;
			
			$selectDate = mktime(0,0,0, date("n"), date("j")-7, date("Y"));
			
			$criteria=new CDbCriteria;
			$criteria->compare('SHO_ID',$values,true);
			//$criteria->addCondition('SHO_DATE > '.$selectDate); //TODO: only show shoppinglists in future.
			
			$command = Yii::app()->dbp->createCommand()
				->from('shoppinglists')
				->where($criteria->condition,$criteria->params)
				->order('SHO_DATE');
			$shoppinglists = $command->queryAll();
			
			$rows = array();
			foreach($shoppinglists as $shoppinglist){
				$row = array();
				$row['SHO_ID'] = $shoppinglist['SHO_ID'];
				$row['SHO_DATE'] = $shoppinglist['SHO_DATE'];
				
				$ing_ids = explode(';',$shoppinglist['SHO_INGREDIENTS']);
				//$ing_weights = explode(';',$shoppinglist['SHO_WEIGHTS']);
				$pro_ids = explode(';',$shoppinglist['SHO_PRODUCTS']);
				//$gramm_values = explode(';',$shoppinglist['SHO_QUANTITIES']);
				
				//ingredients
				$ing_criteria=new CDbCriteria;
				$ing_criteria->compare('ING_ID',$ing_ids,true);
				
				$command = Yii::app()->db->createCommand()
					->select('ingredients.ING_ID, ingredients.ING_IMG_AUTH, ING_NAME_'.Yii::app()->session['lang'])
					->from('ingredients')
					->where($ing_criteria->condition, $ing_criteria->params);
					
				$ingredient_names = $command->queryAll();
				$ingredient_names = CHtml::listData($ingredient_names,'ING_ID','ING_NAME_'.Yii::app()->session['lang']);
				$row['ingredients'] = $ingredient_names;
				
				//products
				$row['total_products'] = count($ing_ids);
				$notAssigned = 0;
				$pro_ids_clear = array();
				foreach($pro_ids as $pro_id){
					if ($pro_id == ''){
						++$notAssigned;
					} else {
						$pro_ids_clear[] = $pro_id;
					}
				}
				$row['not_assigned'] = $notAssigned;
				
				if (count($pro_ids_clear)>0){
					$pro_criteria=new CDbCriteria;
					$pro_criteria->compare('PRO_ID',$pro_ids_clear,true);
					
					$command = Yii::app()->db->createCommand()
						->select('count(distinct pro_to_sto.PRO_ID) as amount, suppliers.SUP_NAME as supplier')
						->from('pro_to_sto')
						->leftJoin('suppliers','pro_to_sto.SUP_ID = suppliers.SUP_ID')
						->group('pro_to_sto.SUP_ID')
						->where($pro_criteria->condition, $pro_criteria->params);
					$supplier = $command->queryAll();
					
					$row['products_from'] = $supplier;
				} else {
					$row['products_from'] = array();
				}
				
				$rows[] = $row;
			}
			
			$dataProvider=new CArrayDataProvider($rows, array(
				'id'=>'SHO_ID',
				'keyField'=>'SHO_ID',
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
		if ($id == 'backup'){
			$model=Yii::app()->session[$this->createBackup];
		} else {
			if (isset(Yii::app()->session[$this->createBackup]) && Yii::app()->session[$this->createBackup]['SHO_ID'] == $id){
				$model=Yii::app()->session[$this->createBackup];
			} else {
				$model=Shoppinglists::model()->findByPk($id);
			}
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='shoppinglists-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
