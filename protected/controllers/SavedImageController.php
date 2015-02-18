<?php

class SavedImageController extends Controller
{
	public function actionCusineSubSubTypes($id, $ext){
		if ($id == 'backup'){
			$model=Yii::app()->session['CusineSubSubTypes_Backup'];
		} else {
			$model=CusineSubSubTypes::model()->findByPk($id);
		}
		$this->displaySavedImage($id, $model, 'CSS', 'CusineSubSubTypes');
	}

	public function actionCusineSubTypes($id, $ext){
		if ($id == 'backup'){
			$model=Yii::app()->session['CusineSubTypes_Backup'];
		} else {
			$model=CusineSubTypes::model()->findByPk($id);
		}
		$this->displaySavedImage($id, $model, 'CST', 'CusineSubTypes');
	}

	public function actionCusineTypes($id, $ext){
		if ($id == 'backup'){
			$model=Yii::app()->session['CusineTypes_Backup'];
		} else {
			$model=CusineTypes::model()->findByPk($id);
		}
		$this->displaySavedImage($id, $model, 'CUT', 'CusineTypes');
	}


	private function displaySavedImage($id, $model, $prefix, $type){
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if (isset($_GET['size'])) {
			$size = $_GET['size'];
		} else {
			$size = 0;
		}
		$this->saveLastAction = false;
		$modified = $model->CHANGED_ON;
		if (!$modified){
			$modified = $model->CREATED_ON;
		}
		return Functions::getImage($modified, $model[$prefix . '_IMG_ETAG'], $model[$prefix . '_IMG_FILENAME'], $id, $type, $size);
	}
}