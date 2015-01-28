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

	const RECIPES_AMOUNT = 2;
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'register' action
				'actions'=>array('register', 'verifyRegistration', 'captcha', 'languageChanged', 'displaySavedImage', 'resendActivationMail', 'resetPassword', 'view'),
				'users'=>array('*'),
			),
			array('allow',  // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create','update','uploadImage','admin','delete', 'ChangeLanguageMenu', 'changeDesignMenu','cancel','updateProfile','favorites'),
				'users'=>array('@'),
			),
			
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('addTwitter','removeTwitter','changeTwitter','twitterCallback'),
				//'roles'=>array('tweeter'),
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
	public function actionView($id){
		$model = $this->loadModel($id);
		$roles = explode(';', strtolower($model->PRF_ROLES));
		if (in_array('professional', $roles)){
			include_once 'RecipesController.php';
			$cusines = RecipesController::getCusinesValues($model->PRF_CUT_IDS);
			
			//Field PRF_UID is not filled!
			$recipesAmount = Yii::app()->db->createCommand('SELECT count(REC_ID) FROM recipes WHERE CREATED_BY = :id')->bindParam(':id', $id)->queryScalar();
			$recipes = Yii::app()->db->createCommand()->select('recipes.*')->from('recipes')->where('CREATED_BY = :id', array(':id'=>$id))->order('CHANGED_ON desc')->limit(self::RECIPES_AMOUNT * 2,0)->queryAll();
			 
			$this->checkRenderAjax('view',array(
					'model'=>$model,
					'cusines'=>$cusines,
					'edit'=>$id == Yii::app()->user->id,
					'professional'=>true,
					'recipes'=>$recipes,
					'recipesAmount'=>$recipesAmount,
			));
		} else {
			throw new CHttpException(403,'It\'s not a public profile.');
		}
	}
	
	private function getModelAndOldPic($id){
		$Session_Profiles_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Profiles_Backup)){
			$oldmodel = $Session_Profiles_Backup;
		}
		if (isset($id)){
			if (!isset($oldmodel) || $oldmodel->PRF_UID != $id){
				$oldmodel = $this->loadModel($id);
			}
		}
		
		if (isset($oldmodel)){
			$model = $oldmodel;
			$oldPictureFilename = $oldmodel->PRF_IMG_FILENAME;
		} else {
			$model=new Profiles;
			$oldPictureFilename = null;
		}
		return array($model, $oldPictureFilename);
	}
	
	public function actionUploadImage(){
		$this->saveLastAction = false;
		if (isset($_GET['id'])){
			$id = $_GET['id'];
		}
		
		list($model, $oldPictureFilename) = $this->getModelAndOldPic($id);
		
		Functions::uploadImage('Profiles', $model, $this->createBackup, 'PRF_IMG');
	}
	
	private function prepareCreateOrUpdate($id, $view){
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		list($model, $oldPictureFilename) = $this->getModelAndOldPic($id);
		
		if (isset($id) && $id != Yii::app()->user->id){
			throw new CHttpException(403,'It\'s not allowed to change profile of other user.');
		}
		if(isset($_POST['Profiles'])) {
			$model->attributes=$_POST['Profiles'];
			if (isset($oldPictureFilename)){
				Functions::updatePicture($model,'PRF_IMG', $oldPictureFilename);
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


				if(Yii::app()->user->demo){
					//$this->errorText = sprintf($this->trans->DEMO_USER_CANNOT_CHANGE_DATA, $this->createUrl("profiles/register"));
					
					$home_gps = array($model->PRF_LOC_GPS_LAT, $model->PRF_LOC_GPS_LNG, $model->PRF_LOC_GPS_POINT);
					Yii::app()->user->home_gps = $home_gps;
					Yii::app()->user->view_distance = $model->PRF_VIEW_DISTANCE;
					$this->forwardAfterSave(array('view', 'id'=>$model->PRF_UID));
					return;
				} else {
					$transaction=$model->dbConnection->beginTransaction();
					try {
						if($model->save(false)){
							$saveOK = true;
							$changed = Functions::fixPicturePathAfterSave($model,'PRF_IMG', $model->PRF_IMG_FILENAME);
							if ($changed){
								if(!$model->save()){
									if ($this->debug) {echo 'error on save after img file: ';  print_r($model->getErrors());}
									$transaction->rollBack();
									$saveOK = false;
								}
							}
							if ($saveOK){
								$transaction->commit();
								$home_gps = array($model->PRF_LOC_GPS_LAT, $model->PRF_LOC_GPS_LNG, $model->PRF_LOC_GPS_POINT);
								Yii::app()->user->home_gps = $home_gps;
								Yii::app()->user->view_distance = $model->PRF_VIEW_DISTANCE;
								
								unset(Yii::app()->session[$this->createBackup]);
								unset(Yii::app()->session[$this->createBackup.'_Time']);
								
								$this->forwardAfterSave(array('view', 'id'=>$model->PRF_UID));
								return;
							}
						} else {
							$transaction->rollBack();
						}
					} catch(Exception $e) {
						if ($this->debug) echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
						$transaction->rollBack();
					}
				}
			}
		} else {
			Yii::app()->session[$this->createBackup] = $model;
			Yii::app()->session[$this->createBackup.'_Time'] = time();
		}
		
		$roles = explode(';', strtolower($model->PRF_ROLES));
		$this->checkRenderAjax($view,array(
			'model'=>$model,
			'professional'=>in_array('professional', $roles),
		));
	}
	
	public function actionUpdateProfile($id){
		if ($id != Yii::app()->user->id){
			throw new CHttpException(403,'It\'s not allowed to edit profile of other user.');
		}
		$allowedFields = array('PRF_WORK_TITLE',
		'PRF_WORK_LOCATION',
		'PRF_CUT_IDS',
		'PRF_PHILOSOPHY',
		'PRF_EXPERIENCE',
		'PRF_AWARDS');

		$field = $_POST['field'];
		if (!in_array($field, $allowedFields)){
			//throw new CHttpException(403,'It\'s not allowed to edit this field.');
			throw new CHttpException(500, 'Error while update DB.'); //show incorrect mesage 
			return;
		}
		$data = htmlspecialchars($_POST['data']);
		//echo CJSON::encode(array($field=>$data));
		Yii::app()->dbp->createCommand()->update(Profiles::model()->tableName(), array($field=>$data), 'PRF_UID=:id', array(':id'=>$id));
		
		//update current backup, so it will not overwritten if profile is saved.
		$Session_Profiles_Backup = Yii::app()->session[$this->createBackup];
		if (isset($Session_Profiles_Backup)){
			$model = $Session_Profiles_Backup;
			if (isset($model) && $model->PRF_UID == $id){
				$model->__set($field, $data);
			}
		}
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
	public function actionUpdate(){
		if (isset(Yii::app()->user->id)){
			$this->prepareCreateOrUpdate(Yii::app()->user->id, 'update');
		} else {
			throw new CHttpException(403,'Please login first.');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
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
	public function actionIndex(){
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
	public function actionAdmin(){
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
			$model->setScenario('register');
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
				//while($found !== null) {
				//	$model->PRF_RND = Randomness::blowfishSalt();
				//	$found = Profiles::model()->findByAttributes(array('PRF_RND'=>$model->PRF_RND));
				//}
				
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
					//mail($model->PRF_EMAIL,$subject,$body,$headers);
					
					$this->sendVerificationMail($model);
					Yii::app()->user->setFlash('register', sprintf($this->trans->REGISTER_REGISTER_SUCCESSFULL, $model->PRF_EMAIL));
					
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
	
	public function actionResendActivationMail(){
		if (isset($_GET['nick']) && $_GET['nick'] != ''){
			$model=Profiles::model()->findByAttributes(array('PRF_NICK'=>$_GET['nick']));
			if($model!==null) {
				if ($model->PRF_ACTIVE == 0){
					$this->sendVerificationMail($model);
				}
				return;
			}
		}
		if (isset($_GET['mail']) && $_GET['mail'] != ''){
			$model=Profiles::model()->findByAttributes(array('PRF_EMAIL'=>$_GET['mail']));
			if($model!==null) {
				if ($model->PRF_ACTIVE == 0){
					$this->sendVerificationMail($model);
				}
				return;
			}
			$error = 'mail not found!';
		}
		//TODO: show nick & e-mail enter fields.
	}
	
	private function sendVerificationMail($model){
		$subject = $this->trans->REGISTRATION_MAIL_SUBJECT;
		$link = CController::createAbsoluteUrl("profiles/VerifyRegistration", array("hash"=>$model->PRF_RND));
		$body = sprintf($this->trans->REGISTRATION_MAIL_CONTENT, $link, $link, Yii::app()->params['verificationRegardsName']);
		$body = 
			sprintf($this->trans->REGISTRATION_MAIL_CONTENT_MESSAGE, '') . "<br>\r\n" . //here could be ' <anrede> <name>'.
			sprintf($this->trans->REGISTRATION_MAIL_CONTENT_LINK, $link, $link) . "<br>\r\n" .
			$this->trans->REGISTRATION_MAIL_CONTENT_CONTAKT . "<br>\r\n" .
			sprintf($this->trans->REGISTRATION_MAIL_CONTENT_REGARDS, Yii::app()->params['verificationRegardsName']) . "<br><br><br>\r\n";
		
		if (Yii::app()->session['lang'] == 'EN_GB'){
			$otherLanguage = 'DE_CH';
		} else {
			$otherLanguage = 'EN_GB';
		}
		$result = Yii::app()->db->createCommand()->select('TXT_NAME,'.$otherLanguage)->from('textes')->where("TXT_NAME like 'REGISTRATION_MAIL_CONTENT%'")->queryAll();
		$otherLanguageTexts = CHtml::listData($result,'TXT_NAME',$otherLanguage);
		$body .= 
			sprintf($otherLanguageTexts['REGISTRATION_MAIL_CONTENT_MESSAGE'], '') . "<br>\r\n" . //here could be ' <anrede> <name>'.
			sprintf($otherLanguageTexts['REGISTRATION_MAIL_CONTENT_LINK'], $link, $link) . "<br>\r\n" .
			$otherLanguageTexts['REGISTRATION_MAIL_CONTENT_CONTAKT'] . "<br>\r\n" .
			sprintf($otherLanguageTexts['REGISTRATION_MAIL_CONTENT_REGARDS'], Yii::app()->params['verificationRegardsName']) . "<br>\r\n";
		/*
		$headers="From: <".Yii::app()->params['verificationEmail'].">\r\nReply-To: <".Yii::app()->params['verificationEmail'].">\r\nReturn-Path: <".Yii::app()->params['verificationEmail'].">\r\nBcc: <".Yii::app()->params['verificationBCCEmail'].">\r\nContent-Type: text/html";
		$programmParam = "-f".Yii::app()->params['verificationEmail'];
		
		ini_set('sendmail_from', Yii::app()->params['verificationEmail']); 
		mail($model->PRF_EMAIL, $subject, $body, $headers, $programmParam);
		*/
		
		Yii::import('application.extensions.phpmailer.JPhpMailer');
		try {
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
			$mail->AddAddress($model->PRF_EMAIL, $model->PRF_NICK);
			$mail->Send();
		} catch(phpmailerException $e){
			if ($this->debug){
				echo $e->errorMessage();
			}
		}
	}
	

	/*
	 * Verifies registration and activates the user profile
	 */
	public function actionVerifyRegistration(){
		// get hash code from url
		$hash = Yii::app()->getRequest()->getQuery('hash');
		if (isset($hash) && strlen($hash) > 12){
			$model = Profiles::model()->findByAttributes(array('PRF_RND'=>$hash));
		} else {
			$model=null;
		}
		if($model!==null && $model->PRF_ACTIVE == 0){
			// activate account
			$model->PRF_ACTIVE = '1';
			$model->save();
			Yii::app()->user->setFlash('register',$this->trans->REGISTER_FLASH_SUCCESSFUL);
			//$this->refresh();
		} else {
			Yii::app()->user->setFlash('register',$this->trans->REGISTER_FLASH_HASH_INVALID);
		}
		//echo $hash . "<br>\r\n";
		//print_r($model);
		$this->checkRenderAjax('register',array('model'=>$model,));
	}
	
	private function hiddenLogin($nickname, $password){
		$form=new LoginForm;
		$form->LIF_NICKNAME = $nickname;
		$form->LIF_PASSWORD = $password; //must be the original, not the hashed one!
		$form->LIF_REMEMBER = false;
		if($form->login()){
			Yii::app()->user->setFlash('forgottenPassword',$this->trans->PASSWORD_FLASH_SUCCESSFUL);
		} else {
			Yii::app()->user->setFlash('forgottenPassword',$this->trans->PASSWORD_FLASH_LOGIN_ERROR); //$form->errorCode
		}
		$this->redirect(array('site/login'));
	}
	
	public function actionResetPassword(){
		// get hash code from url
		$hash = Yii::app()->getRequest()->getQuery('hash');
		if (isset($hash) && strlen($hash) > 12){
			$model = Profiles::model()->findByAttributes(array('PRF_RND'=>$hash));
		} else {
			$model=null;
		}
		if($model!==null){
			//check link is still valid / not older than 3 hours
			$value = $model->PRF_RND;
			$pos = strrpos($value, "_");
			if ($pos !== false){
				$value = substr($value, $pos+1);
			}
			if (is_numeric($value) && $value-time() < 10800){ //3h in s
				//$model->PRF_RND = '';
				if ($model->PRF_ACTIVE == 0){
						$model->PRF_ACTIVE = '1';
						$model->save();
				}
				$model->setScenario('pw_change');
				if(isset($_POST['Profiles'])) {
					$model->attributes = array_merge($model->attributes, $_POST['Profiles']);
					if($model->validate()){
						if (isset($model->new_pw) && $model->new_pw != ''){
							$model->PRF_PW = crypt($model->new_pw, Randomness::blowfishSalt());
						}
						if($model->save(false)){
							$this->hiddenLogin($model->PRF_NICK, $model->new_pw);
							return;
						}
					}
				}
				$this->checkRenderAjax('resetPassword',array('model'=>$model));
				return;
			} else {
				Yii::app()->user->setFlash('forgottenPassword',$this->trans->PASSWORD_FLASH_HASH_INVALID);
			}
		} else {
			Yii::app()->user->setFlash('forgottenPassword',$this->trans->PASSWORD_FLASH_HASH_INVALID);
		}
		//echo $hash . "<br>\r\n";
		//print_r($model);
		
		//TODO: do directly log in
		//$this->redirect('../site/login');
		$this->redirect(array('site/login'));
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
		if(isset($_POST['Profiles']))
		{
			$model->attributes=array_merge($model->attributes,$_POST['Profiles']);
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
		
		if(!Yii::app()->user->demo){
			$model = $this->loadModel(Yii::app()->user->id);	
			$model->PRF_LANG = $_GET['lang'];	
			$model->save();
		}
		
		$this->showLastAction();
	}
	
	public function actionChangeDesignMenu() {
		$this->saveLastAction = false;
		Yii::app()->user->design = $_GET['design'];
		
		if(!Yii::app()->user->demo){
			$model = $this->loadModel(Yii::app()->user->id);
			$model->PRF_DESIGN = $_GET['design'];
			$model->save();
		}
	}
	
	
	public function actionTwitterCallback() {
		$this->saveLastAction = false;
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && isset($_SESSION['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
			$_SESSION['oauth_status'] = 'oldtoken';
			//header('Location: ./clearsessions.php');
		}

		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth(Yii::app()->params['twitterConsumerKey'], Yii::app()->params['twitterConsumerSecret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		
		/* Request access tokens from twitter */
		try {
			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		} catch (Exception $e){
			echo "\r\naccess token expired\r\n";
		}
		
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;
		
		if(!Yii::app()->user->demo){
			$model = $this->loadModel(Yii::app()->user->id);
			$model->PRF_TWITTER_OAUTH_TOKEN = $access_token['oauth_token'];
			$model->PRF_TWITTER_OAUTH_TOKEN_SECRET = $access_token['oauth_token_secret'];
			if ($model->save()){
				/* Remove no longer needed request tokens */
				unset($_SESSION['oauth_token']);
				unset($_SESSION['oauth_token_secret']);
				
				Yii::app()->user->setState('twitterOauthToken', $access_token['oauth_token']);
				Yii::app()->user->setState('twitterOauthTokenSecret', $access_token['oauth_token_secret']);
				
				echo "\r\nsave successfull\r\n";
			} else {
				print_r($model->getErrors());
				echo "\r\nsave error\r\n";
			}
		}
		
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			$_SESSION['status'] = 'verified';
			//header('Location: ./index.php');
		} else {
			/* Save HTTP status for error dialog on connnect page.*/
			//header('Location: ./clearsessions.php');
		}
	}
	
	private function twitterAccessRequest($type) {
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth(Yii::app()->params['twitterConsumerKey'], Yii::app()->params['twitterConsumerSecret']);
		
		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken($this->createAbsoluteUrl("profiles/twitterCallback", array('type'=>$type)));

		/* Save temporary credentials to session. */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		
		/* If last connection failed don't display authorization link. */
		switch ($connection->http_code) {
		case 200:
			/* Build authorize URL and redirect user to Twitter. */
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
		default:
			/* Show notification if something went wrong. */
			echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
	}
	
	public function actionAddTwitter() {
		$this->saveLastAction = false;
		$this->twitterAccessRequest('add');
	}
	
	public function actionChangeTwitter() {
		$this->saveLastAction = false;
		$this->twitterAccessRequest('change');
	}
	
	public function actionRemoveTwitter() {
		$this->saveLastAction = false;
		
		if(!Yii::app()->user->demo){
			$model = $this->loadModel(Yii::app()->user->id);
			$model->PRF_TWITTER_OAUTH_TOKEN = null;
			$model->PRF_TWITTER_OAUTH_TOKEN_SECRET = null;
			$model->save();
		}
	}
	
	public function actionFavorites(){
		if (!isset(Yii::app()->user->id)){
			throw new CHttpException(403,'Please login first.');
		}
		$id = Yii::app()->user->id;

		$model = $this->loadModel($id);
		$roles = explode(';', strtolower($model->PRF_ROLES));
		$this->checkRenderAjax('favorites',array(
			'model'=>$model,
			'professional'=>array_key_exists('professional', array_flip($roles)),
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
			if(Yii::app()->user->demo){
				$model=new Profiles;
				$model->PRF_UID = 0;
				$model->PRF_FIRSTNAME = 'Demo';
				$model->PRF_LASTNAME = 'Demo';
				$model->PRF_NICK = 'Demo';
				$model->PRF_EMAIL = 'demo@demo.ch';
				$model->PRF_LANG = Yii::app()->user->lang;
				$model->PRF_LOC_GPS_LAT = Yii::app()->user->home_gps[0];
				$model->PRF_LOC_GPS_LNG = Yii::app()->user->home_gps[1];
				$model->PRF_LOC_GPS_POINT = Yii::app()->user->home_gps[2];
				$model->PRF_VIEW_DISTANCE = Yii::app()->user->view_distance;
				$model->PRF_PW = 'demo';
				$model->isNewRecord = false;
			} else {
				/*
				if ($id != Yii::app()->user->id){
					throw new CHttpException(403,'It\'s not allowed to open profile of other user.');
				}
				*/
				$model=Profiles::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='profiles-form')
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
		return Functions::getImage($modified, $model->PRF_IMG_ETAG, $model->PRF_IMG_FILENAME, $id, 'Profiles', $size);
	}
}
