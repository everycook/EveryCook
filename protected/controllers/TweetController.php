<?php
class TweetController extends Controller
{
	/**
	 * @return array action filters
	 * /
	public function filters()
	{
		return array(
			'accessControl', // perform access control
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 * /
	public function accessRules()
	{
		return array(
			array('allow', // allow tweeter user to perform 'tweet' actions
				'actions'=>array('tweet'),
				'roles'=>array('tweeter'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	*/
	
	private function getConnection() {
		$connection = new TwitterOAuth(Yii::app()->params['twitterConsumerKey'], Yii::app()->params['twitterConsumerSecret']);
		return $connection;
	}
	private function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
		if (!isset($oauth_token) || $oauth_token == '' || !isset($oauth_token_secret) || $oauth_token_secret == ''){
			//throw new CHttpException(503, 'Please autenticate your twitter account in your profile first.');
			http_response_code(503);
			exit('Please autenticate your twitter account in your profile first.');
		} else {
			$connection = new TwitterOAuth(Yii::app()->params['twitterConsumerKey'], Yii::app()->params['twitterConsumerSecret'], $oauth_token, $oauth_token_secret);
		}
		return $connection;
	}
	
	private function checkLogin(){
		if (isset($_GET['user']) && isset($_GET['pw'])){
			$_identity=new UserIdentity(trim($_GET['user']), trim($_GET['pw']));
			$_identity->authenticate();
			
			if($_identity->errorCode===UserIdentity::ERROR_NONE){
				Yii::app()->user->login($_identity, 0);
			}
		}
		if (Yii::app()->user->id != 0){
			for($i=0;$i<count(Yii::app()->user->roles);$i++){
				if (Yii::app()->user->roles[$i] == 'tweeter'){
					return true;
				}
			}
			return false;
		} else {
			return false;
		}
	}
	
	private function returnError($code, $message, $internal){
		if (!$internal){
			http_response_code($code);
			echo $message;
		}
	}
	
	public function actionTweet($id, $start, $everycook){
		$this->doTweet($id, $start, $everycook, false);
	}
	
	public function doTweet($id, $start, $everycook, $internal){
		require_once("modernizeFunctions.php");
		$this->saveLastAction = false;
		if ($this->checkLogin()){
			$recipe=Recipes::model()->findByPk($id);
			if($recipe===null) {
				//throw new CHttpException(404,'The requested page does not exist.');
				http_response_code(404);
				exit('The requested page does not exist.');
			}
			//$recipeName = $recipe->__get('REC_NAME_'.Yii::app()->session['lang']);
			$recipeName = $recipe->__get('REC_NAME_EN_GB');
			$message = date('Y-m-d H:i:s') . ' - ';
			if ($everycook){
				if ($start){
					$message .= Yii::app()->user->nick . ' starts cooking ' . $recipeName;
				} else {
					$message .= Yii::app()->user->nick . ' finished cooking ' . $recipeName;
				}
				$connection = $this->getConnectionWithAccessToken(Yii::app()->params['twitterOauthToken'], Yii::app()->params['twitterOauthTokenSecret']);
			} else {
				if ($start){
					$message .= 'start cooking ' . $recipeName . ' on #EveryCook';
				} else {
					$message .= 'finished cooking ' . $recipeName . ' on #EveryCook';
				}
				$connection = $this->getConnectionWithAccessToken(Yii::app()->user->twitterOauthToken, Yii::app()->user->twitterOauthTokenSecret);
			}
			$response = $connection->post("statuses/update", array('status' => $message));
			if (isset($response->errors)){
				if (isset($response->errors[0])){
					//https://dev.twitter.com/docs/error-codes-responses
					if ($response->errors[0]->code == 187) {//Status is a duplicate
						//Nothing to do, same message
						$this->returnError(500,'Status is a duplicate', $internal);
					} else if ($response->errors[0]->code == 89) { //Invalid or expired token -> permission is revoced?
						$this->returnError(500,'ERROR: Please re grant everycook to post in your account: activate in twitter or relogin to your account on profile'."\r\n", $internal);
					} else {
						$this->returnError(500,'ERROR: Error tweeting: ' . $response->errors[0]->code . ': ' . $response->errors[0]->message, $internal);
						//print_r($connection);
					}
				} else {				
					//Do something if the request was unsuccessful
					$this->returnError(500,'ERROR: There was an error posting the message.', $internal);
				}
//			} else {
//				echo 'sucessfull';
//				print_r($response);
			}
		} else {
			$this->returnError(403,'ERROR: invalid user', $internal);
		}
	}
}