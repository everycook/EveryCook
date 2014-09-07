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
	
	public function actionTweet($id, $start, $everycook){
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
						http_response_code(500);
						echo 'Status is a duplicate';
					} else if ($response->errors[0]->code == 89) { //Invalid or expired token -> permission is revoced?
						http_response_code(500);
						echo 'ERROR: Please re grant everycook to post in your account: activate in twitter or relogin to your account on profile'."\r\n";
					} else {
						http_response_code(500);
						echo 'ERROR: Error tweeting: ' . $response->errors[0]->code . ': ' . $response->errors[0]->message;
						//print_r($connection);
					}
				} else {				
					//Do something if the request was unsuccessful
					http_response_code(500);
					echo 'ERROR: There was an error posting the message.';
				}
//			} else {
//				echo 'sucessfull';
//				print_r($response);
			}
		} else {
			http_response_code(403);
			echo 'ERROR: invalid user';
		}
	}
}