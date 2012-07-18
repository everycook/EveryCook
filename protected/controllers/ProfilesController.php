<?php

class ProfilesController extends Controller
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
	
	protected $createBackup = 'Profiles_Backup';
	protected $searchBackup = 'Profiles';
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'register' action
				'actions'=>array('register', 'verifyRegistration', 'captcha', 'languageChanged', 'displaySavedImage'),
				'users'=>array('*'),
			),
			array('allow',  // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','uploadImage','admin','delete', 'ChangeLanguageMenu', 'changeDesignMenu','cancel'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the register page
			'captcha'=>array(
				//'class'=>'CCaptchaAction',
            //'class'=>'CaptchaAction',
            'class'=>'CaptchaExtendedAction',
            //'minLength' => 1,
            //'maxLength' => 10,
				'backColor'=>0xFFFFFF,
			),
		);
	}
	
	public function actionCancel(){
		$this->saveLastAction = false;
		$Session_Backup = Yii::app()->session[$this->createBackup];
		unset(Yii::app()->session[$this->createBackup.'_Time']);
		if (isset($Session_Backup) && isset($Session_Backup->PRF_UID)){
			unset(Yii::app()->session[$this->createBackup]);
			$this->forwardAfterSave(array('view', 'id'=>$Session_Backup->PRF_UID));
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
	
	private function getModelAndOldPic($id){
		$Session_Profiles_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Profiles_Backup)){
			$oldmodel = $Session_Profiles_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->PRF_UID != $id){
				$oldmodel = $this->loadModel($id, true);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPicture = $oldmodel->PRF_IMG;
		} else {
			$model=new Profiles;
			$oldPicture = null;
		}
		return array($model, $oldPicture);
	}
	
	public function actionUploadImage(){
		$this->saveLastAction = false;
		if (isset($_GET['id'])){
			$id = $_GET['id'];
		}
		
		list($model, $oldPicture) = $this->getModelAndOldPic($id);
		
		Functions::uploadImage('Profiles', $model, 'Profiles_Backup', 'PRF_IMG');
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		list($model, $oldPicture) = $this->getModelAndOldPic($id);
		
		if (isset($id) && $id != Yii::app()->user->id){
			throw new CHttpException(403,'It\'s not allowed to change profile of other user.');
		}
		if(isset($_POST['Profiles'])) {
			$model->attributes=$_POST['Profiles'];
			if (isset($oldPicture)){
				Functions::updatePicture($model,'PRF_IMG', $oldPicture);
			}
			
			if (isset($model->birthday_year) && $model->birthday_year != ''){
				if ($model->birthday_month == ''){
					$model->birthday_month = '01';
					
				}
				if ($model->birthday_day == ''){
					$model->birthday_day = '01';
				}
				$model->PRF_BIRTHDAY = date_create_from_format('Y-m-d', $model->birthday_year . '-' . $model->birthday_month . '-' . $model->birthday_day)->getTimestamp();
			} else {
				$model->PRF_BIRTHDAY = null;
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if (isset($model->new_pw) && $model->new_pw != ''){
				$model->setScenario('pw_change');
			} else {
				$model->setScenario('insert');
			}
			if($model->validate()){
				if (isset($model->new_pw) && $model->new_pw != ''){
					$model->PRF_PW = crypt($model->new_pw, Randomness::blowfishSalt());
				}
				if ($model->PRF_LOC_GPS_LAT != '' && $model->PRF_LOC_GPS_LNG != '' ){
					$model->PRF_LOC_GPS_POINT = 'POINT(' . $model->PRF_LOC_GPS_LAT . ' ' . $model->PRF_LOC_GPS_LNG . ')';
				} else {
					$model->PRF_LOC_GPS_POINT = null;
				}
				if ($model->PRF_VIEW_DISTANCE == '' || $model->PRF_VIEW_DISTANCE < 1){
					$model->PRF_VIEW_DISTANCE = 1;
				}
				
				if($model->save(false)){
					$home_gps = array($model->PRF_LOC_GPS_LAT, $model->PRF_LOC_GPS_LNG, $model->PRF_LOC_GPS_POINT);
					Yii::app()->user->home_gps = $home_gps;
					Yii::app()->user->view_distance = $model->PRF_VIEW_DISTANCE;
					
					unset(Yii::app()->session[$this->createBackup]);
					unset(Yii::app()->session[$this->createBackup.'_Time']);
					
					$this->forwardAfterSave(array('view', 'id'=>$model->PRF_UID));
					return;
				}
			}
		} else {
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
		}

		$this->checkRenderAjax($view,array(
			'model'=>$model,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->actionRegister();
		/*
		if (isset($_GET['newModel']) && isset(Yii::app()->session[$this->createBackup.'_Time']) && $_GET['newModel']>Yii::app()->session[$this->createBackup.'_Time']){
				unset(Yii::app()->session[$this->createBackup]);
				unset(Yii::app()->session[$this->createBackup.'_Time']);
				unset($_GET['newModel']);
		}
		$this->prepareCreateOrUpdate(null, 'create');
		*/
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
	public function actionIndex()
	{
		$this->actionView(Yii::app()->user->id);
		/*
		$dataProvider=new CActiveDataProvider('Profiles');
		$this->checkRenderAjax('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Profiles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Profiles']))
			$model->attributes=$_GET['Profiles'];

		$this->checkRenderAjax('admin',array(
			'model'=>$model,
		));
	}


	/**
	 * Displays the register page
	 */
	public function actionRegister() {
		if (isset($_GET['newModel']) && isset(Yii::app()->session[$this->createBackup.'_Time']) && $_GET['newModel']>Yii::app()->session[$this->createBackup.'_Time']){
				unset(Yii::app()->session[$this->createBackup]);
				unset(Yii::app()->session[$this->createBackup.'_Time']);
				unset($_GET['newModel']);
		}
		
		$Session_Profiles_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Profiles_Backup)){
			$model = $Session_Profiles_Backup;
		} else {
			$model=new Profiles('register');
		}
		// uncomment the following code to enable ajax-based validation 
		if(isset($_POST['ajax']) && $_POST['ajax']==='profiles-register-form') { 
			echo CActiveForm::validate($model); 
			Yii::app()->end(); 
		}
		if(isset($_POST['Profiles'])) 
		{
			$model->attributes=$_POST['Profiles'];
			if (isset($model->birthday_year) && $model->birthday_year != ''){
				if ($model->birthday_month == ''){
					$model->birthday_month = '01';
					
				}
				if ($model->birthday_day == ''){
					$model->birthday_day = '01';
				}
				$model->PRF_BIRTHDAY = date_create_from_format('Y-m-d', $model->birthday_year . '-' . $model->birthday_month . '-' . $model->birthday_day)->getTimestamp();
			} else {
				$model->PRF_BIRTHDAY = null;
			}
			
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
			if($model->validate()) {
				// all entered values are valid and no constraint has been violated
				
				// set default values
				$model->PRF_ACTIVE = 0;

				// generate random number for registration link:: needs to be unique
				$model->PRF_RND = Randomness::blowfishSalt();
				//$found = Profiles::model()->findByAttributes(array('PRF_RND'=>$model->PRF_RND));
		 //       while($found !== null) {
		 //          $model->PRF_RND = Randomness::blowfishSalt();
		 //          $found = Profiles::model()->findByAttributes(array('PRF_RND'=>$model->PRF_RND));
		 //       }
				
				// encrypt password
				$model->PRF_PW = crypt($model->PRF_PW, Randomness::blowfishSalt());
				
				// prepare encryption
				//$iterations = 0;
				
				// generate random number for salt and apply repeated crypt as hash to
				// reduce reversed brute-force decryption
				//$model->PRF_SALT = Randomness::blowfishSalt();
				//$model->PRF_PW = crypt($model->PRF_PW, $model->PRF_SALT);
				//for($i = 1; $i < $iterations; $i++) {
				//	$hash = crypt($hash . $password, $salt);
				//}
				if($model->save(false)) { //false = not validate again
					unset(Yii::app()->session[$this->createBackup]);
					unset(Yii::app()->session[$this->createBackup.'_Time']);
					// no errors occured during save, send verification mail & and post message
					$subject = 'EveryCook Verification Mail';
					$link = CController::createAbsoluteUrl("Profiles/VerifyRegistration/", array("hash"=>$model->PRF_RND));
					$body = "Tank you for your registration. Please follow the following link for registration verification.<br>\n" . '<a href="'.$link . '" target="_blank">' . $link . '</a>';
					$headers="From: {".Yii::app()->params['adminEmail']."}\r\nReply-To: {".Yii::app()->params['adminEmail']."}\r\nContent-Type: text/html";
					
					mail($model->PRF_EMAIL . ', wiasmitinow@gmail.com',$subject,$body,$headers);//Yii::app()->params['adminEmail']
					//mail($model->PRF_EMAIL,$subject,$body,$headers);
					
					Yii::app()->user->setFlash('register','Thank you for your registration. A verification mail has been sent to your email address '.$model->PRF_EMAIL.'. Please check your emails for verification of your EveryCook account.');
					
					
					// set the session language to the newly chosen one
					Yii::app()->session['lang'] = $model->PRF_LANG;
					
					// refresh the page to show the flash message
					$this->refresh();
					
					//$this->redirect(array('view','id'=>$model->PRF_UID));
				}
			} else {
				//print_r($model->getErrors());
			}
		}
		//$this->render('register',array('model'=>$model));
		$this->checkRenderAjax('register',array('model'=>$model,));
	}

   /*
    * Verifies registration and activates the user profile
    */
   public function actionVerifyRegistration()
   {
      // get hash code from url
      $hash = Yii::app()->getRequest()->getQuery('hash');
      // activate account
      $model = Profiles::model()->findByAttributes(array('PRF_RND'=>$hash));
      if($model!==null){
        $model->PRF_ACTIVE = '1';
        $model->save();
		Yii::app()->user->setFlash('register','Thank you for your verification. You can now login using the following link.');
//$this->refresh();
      } else {
		Yii::app()->user->setFlash('register','Hash value invalid, cannot verification user.');
	  }
	  //echo $hash . "<br>\r\n";
	  //print_r($model);
      $this->checkRenderAjax('register',array('model'=>$model,));
   }

   /*
    * Changes to the selected language
    */
	public function actionLanguageChanged() {
		$action = $_GET['action'];
		Yii::app()->session['lang'] = $_GET['lang'];
		if (!Yii::app()->user->isGuest){
			Yii::app()->user->lang = $_GET['lang'];
		}
		self::$trans=new Translations($_GET['lang']);
		
		$Session_Profiles_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Profiles_Backup)){
			$model = $Session_Profiles_Backup;
		} else {
			if ($action == 'register'){
				$model=new Profiles('register');
			} else {
				$model=new Profiles();
			}
		}
		$model->PRF_LANG = $_GET['lang'];

		Yii::app()->session[$this->createBackup] = $model;
		if (isset($_GET['id'])){
			$this->redirect(array($action, 'id'=>$_GET['id']));
		} else {
			$this->redirect(array($action));
		}
	}
	
	public function actionChangeLanguageMenu() {
		$this->saveLastAction = false;
		Yii::app()->session['lang'] = $_GET['lang'];
		if (!Yii::app()->user->isGuest){
			Yii::app()->user->lang = $_GET['lang'];
		}
		self::$trans=new Translations($_GET['lang']);
		
		$model = $this->loadModel(Yii::app()->user->id);
		$model->PRF_LANG = $_GET['lang'];
		$model->save();
		
		$this->showLastAction();
	}
	
	public function actionChangeDesignMenu() {
		$this->saveLastAction = false;
		Yii::app()->user->design = $_GET['design'];
		$model = $this->loadModel(Yii::app()->user->id);
		$model->PRF_DESIGN = $_GET['design'];
		$model->save();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public static function loadModel($id)
	{
		if ($id == 'backup'){
			$model=Yii::app()->session[$this->createBackup];
		} else {
			if ($id != Yii::app()->user->id){
				throw new CHttpException(403,'It\'s not allowed to open profile of other user.');
			}
			$model=Profiles::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='profiles-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
    public function actionDisplaySavedImage($id, $ext)
    {
		$this->saveLastAction = false;
		$model=$this->loadModel($id, true);
		$modified = $model->CHANGED_ON;
		if (!$modified){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model->PRF_IMG_ETAG, $model->PRF_IMG, $id);
    }
}
