<?php

class AdminModule extends CWebModule {
	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action) {
		if(parent::beforeControllerAction($controller, $action)) {
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			//TODO Check is admin
			if (Yii::app()->user->checkAccess('admin')){
				return true;
			} else {
				throw new CHttpException(403, 'You need admin privileges to do this.');
				//return false;
			}
		} else {
			return false;
		}
	}
}
