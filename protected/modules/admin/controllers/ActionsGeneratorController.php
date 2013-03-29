<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

class ActionsGeneratorController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('copy','change'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndexOld(){
		$model = new AinToAou;
		
		$actionsIns = Yii::app()->db->createCommand()->select('AIN_ID,AIN_DESC_'.Yii::app()->session['lang'])->from('actions_in')->order('AIN_DESC_'.Yii::app()->session['lang'])->queryAll();
		$actionsIns = CHtml::listData($actionsIns,'AIN_ID','AIN_DESC_'.Yii::app()->session['lang']);
		
		$cookIns = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->order('COI_DESC_'.Yii::app()->session['lang'])->queryAll();
		$cookIns = CHtml::listData($cookIns,'COI_ID','COI_DESC_'.Yii::app()->session['lang']);
		
		$actionsOuts = Yii::app()->db->createCommand()/*->select('AOU_ID,AOU_DESC_'.Yii::app()->session['lang'])*/->from('actions_out')->order('AOU_DESC_'.Yii::app()->session['lang'])->queryAll();
		$actionsOutsList = CHtml::listData($actionsOuts,'AOU_ID','AOU_DESC_'.Yii::app()->session['lang']);
		
		$cookInPreps = Yii::app()->db->createCommand()->select('COI_PREP,COI_PREP_DESC')->from('cook_in_prep')/*->order('COI_PREP_DESC')*/->queryAll();
		$cookInPreps = CHtml::listData($cookInPreps,'COI_PREP','COI_PREP_DESC');
		
		/*
		$stepTypes = Yii::app()->db->createCommand()->select('STT_ID,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->order('STT_ID')->queryAll();
		$stepTypes = CHtml::listData($stepTypes,'STT_ID','STT_DESC_'.Yii::app()->session['lang']);
		
		$tools = Yii::app()->db->createCommand()->select('TOO_ID,TOO_DESC_'.Yii::app()->session['lang'])->from('tools')->order('TOO_DESC_'.Yii::app()->session['lang'])->queryAll();
		$tools = CHtml::listData($tools,'TOO_ID','TOO_DESC_'.Yii::app()->session['lang']);
		
		$ainToAous = Yii::app()->db->createCommand()->from('ain_to_aou')->order('AIN_ID, COI_ID, ATA_NO')->queryAll();
		
//		$stepsJSON = CJSON::encode($ainToAous);
		*/
		$stepsJSON = '[]';
		
		$this->checkRenderAjax('index',array(
			'model'=>$model,
			'actionsIns'=>$actionsIns,
			'cookIns'=>$cookIns,
			'actionsOuts'=>$actionsOuts,
			'actionsOutsList'=>$actionsOutsList,
			'cookInPreps'=>$cookInPreps,
			/*'stepTypes'=>$stepTypes,
			'tools'=>$tools,*/
			'stepsJSON'=>$stepsJSON,
		));
	}
	
	public function actionIndex(){
		$model = new AinToAou;
		
		if(isset($_POST['AinToAou'])){
			$model->attributes=$_POST['AinToAou'];
		} else {
			if (isset($_GET['ain_id']) && $_GET['ain_id'] != ''){
				$model->AIN_ID = $_GET['ain_id'];
			}
			if (isset($_GET['coi_id']) && $_GET['coi_id'] != ''){
				$model->COI_ID = $_GET['coi_id'];
			}
		}
		
		if (isset($model->COI_ID) && $model->COI_ID != ''){
			$ainToAous = Yii::app()->db->createCommand()->from('ain_to_aou')->where('AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$model->AIN_ID, ':coi_id'=>$model->COI_ID))->order('AIN_ID, COI_ID, ATA_NO')->queryAll();
		} else {
			$ainToAous = Yii::app()->db->createCommand()->from('ain_to_aou')->where('AIN_ID = :ain_id', array(':ain_id'=>$model->AIN_ID))->order('AIN_ID, COI_ID, ATA_NO')->queryAll();
		}
		
		$actionsIns = Yii::app()->db->createCommand()->select('AIN_ID,AIN_DESC_'.Yii::app()->session['lang'])->from('actions_in')->order('AIN_DESC_'.Yii::app()->session['lang'])->queryAll();
		$actionsIns = CHtml::listData($actionsIns,'AIN_ID','AIN_DESC_'.Yii::app()->session['lang']);
		
		$cookIns = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->queryAll();
		$cookIns = CHtml::listData($cookIns,'COI_ID','COI_DESC_'.Yii::app()->session['lang']);
		
		$tools = Yii::app()->db->createCommand()->select('TOO_ID,TOO_DESC_'.Yii::app()->session['lang'])->from('tools')->order('TOO_DESC_'.Yii::app()->session['lang'])->queryAll();
		$tools = CHtml::listData($tools,'TOO_ID','TOO_DESC_'.Yii::app()->session['lang']);
		
		$actionsOuts = Yii::app()->db->createCommand()/*->select('AOU_ID,AOU_DESC_'.Yii::app()->session['lang'])*/->from('actions_out')->order('AOU_DESC_'.Yii::app()->session['lang'])->queryAll();
		//$actionsOutsList = CHtml::listData($actionsOuts,'AOU_ID','AOU_DESC_'.Yii::app()->session['lang']);
		$actionsOutsList = array();
		foreach($actionsOuts as $actionsOut){
			$actionText = $actionsOut['AOU_DESC_'.Yii::app()->session['lang']];
			if (isset($tools[$actionsOut['TOO_ID']])){
				$tool = $tools[$actionsOut['TOO_ID']];
				$actionText = str_replace('#tool',$tool, $actionText);
			} else if ($actionsOut['TOO_ID'] > 0){
				$actionText = str_replace('#tool','ToolId-' . $actionsOut['TOO_ID'], $actionText);
			}
			$actionsOutsList[$actionsOut['AOU_ID']] = $actionText;
		}
		
		$cookInPreps = Yii::app()->db->createCommand()->select('COI_PREP,COI_PREP_DESC')->from('cook_in_prep')->order('COI_PREP_DESC')->queryAll();
		$cookInPreps = CHtml::listData($cookInPreps,'COI_PREP','COI_PREP_DESC');
		
		$this->checkRenderAjax('index',array(
			'ainToAous'=>$ainToAous,
			'model'=>$model,
			'actionsIns'=>$actionsIns,
			'cookIns'=>$cookIns,
			'actionsOuts'=>$actionsOutsList,
			'cookInPreps'=>$cookInPreps,
		));
	}
	
	public function actionChange($ain_id, $coi_id){
		$this->changeOrCopy($ain_id, $coi_id, 'change');
	}
	
	public function actionCopy($ain_id, $coi_id){
		$this->changeOrCopy($ain_id, $coi_id, 'copy');
	}
	
	public function changeOrCopy($ain_id, $coi_id, $view){
		$model = new ActionsIn;
		$ainToCoi = new AinToCoi;
		
		if(isset($_POST['AinToCoi'])){
			$ainToCoi->attributes=$_POST['AinToCoi'];
			$ain_id = $ainToCoi->AIN_ID;
			$coi_id = $ainToCoi->COI_ID;
		} else {
			$ainToCoi->AIN_ID = $ain_id;
			$ainToCoi->COI_ID = $coi_id;
		}
		$ainToCois = array($ainToCoi);
		$model->AIN_ID = $ain_id;
		
		$stepsJSON = null;
		if(isset($_POST['AinToAou'])){
			$model = Functions::arrayToRelatedObjects($model, array('ainToAous'=> $_POST['AinToAou']));
			$model->ainToCois = $ainToCois;
			
			$transaction=$model->dbConnection->beginTransaction();
			try {
				//if($model->save()){
					$saveOK = true;
					Yii::app()->db->createCommand()->delete(AinToCoi::model()->tableName(), 'AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$ain_id, ':coi_id'=>$coi_id));
					foreach($model->ainToCois as $ainToCoiLoop){
						$ainToCoiLoop->AIN_ID = $ain_id;
						$ainToCoiLoop->setIsNewRecord(true);
						
						if(!$ainToCoiLoop->save()){
							//$saveOK = false;
							if ($this->debug) {echo 'error on save AinToCoi: errors:'; print_r($ainToCoiLoop->getErrors());}
						}
					}
					Yii::app()->db->createCommand()->delete(AinToAou::model()->tableName(), 'AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$ain_id, ':coi_id'=>$coi_id));
					$stepNo = 0;
					foreach($model->ainToAous as $ainToAou){
						$ainToAou->AIN_ID = $ain_id;
						$ainToAou->COI_ID = $coi_id;
						$ainToAou->ATA_NO = $stepNo;
						$ainToAou->setIsNewRecord(true);
						if(!$ainToAou->save()){
							$saveOK = false;
							if ($this->debug) {echo 'error on save AinToAou: errors:'; print_r($ainToAou->getErrors());}
						}
						++$stepNo;
					}
					
					if ($saveOK){
						$transaction->commit();
						//unset(Yii::app()->session[$this->createBackup]);
						//unset(Yii::app()->session[$this->createBackup.'_Time']);
						$this->forwardAfterSave(array('index', 'ain_id'=>$ain_id));
						return;
					} else {
						if ($this->debug) echo 'any errors occured, rollback';
						$transaction->rollBack();
					}
				/*} else {
					if ($this->debug) {echo 'error on save: ';  print_r($model->getErrors());}
					$transaction->rollBack();
				}*/
			} catch(Exception $e) {
				if ($this->debug) echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
				$transaction->rollBack();
			}
		} else {
			if(isset($_POST['AinToCoi'])){
				//Ask for remove assign
				if (isset($_POST['deleteAinToAou'])){
					$transaction=$model->dbConnection->beginTransaction();
					try {
						Yii::app()->db->createCommand()->delete(AinToCoi::model()->tableName(), 'AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$ain_id, ':coi_id'=>$coi_id));
						Yii::app()->db->createCommand()->delete(AinToAou::model()->tableName(), 'AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$ain_id, ':coi_id'=>$coi_id));
						
						$transaction->commit();
						//unset(Yii::app()->session[$this->createBackup]);
						//unset(Yii::app()->session[$this->createBackup.'_Time']);
						$this->forwardAfterSave(array('index', 'ain_id'=>$ain_id));
						return;
					} catch(Exception $e) {
						if ($this->debug) echo 'Exception occured -&gt; rollback. Exeption was: ' . $e;
						$transaction->rollBack();
					}
				}
				//remove steps
				$model = Functions::arrayToRelatedObjects($model, array('ainToAous'=> array()));
				$model->ainToCois = $ainToCois;
				
				$this->errorText = CHtml::label('Delete this CookIn assign to the ActionsIn','deleteAinToAou') . CHtml::checkBox('deleteAinToAou');
			} else {
				$ainToAous = Yii::app()->db->createCommand()->from('ain_to_aou')->where('AIN_ID = :ain_id and COI_ID = :coi_id', array(':ain_id'=>$ain_id, ':coi_id'=>$coi_id))->order('AIN_ID, COI_ID, ATA_NO')->queryAll();
				$model = Functions::arrayToRelatedObjects($model, array('ainToAous'=>$ainToAous));
				$model->ainToCois = $ainToCois;
			}
		}
		
		$tools = Yii::app()->db->createCommand()->select('TOO_ID,TOO_DESC_'.Yii::app()->session['lang'])->from('tools')->order('TOO_DESC_'.Yii::app()->session['lang'])->queryAll();
		$tools = CHtml::listData($tools,'TOO_ID','TOO_DESC_'.Yii::app()->session['lang']);
		
		$actionsOuts = Yii::app()->db->createCommand()/*->select('AOU_ID,AOU_DESC_'.Yii::app()->session['lang'])*/->from('actions_out')->order('AOU_DESC_'.Yii::app()->session['lang'])->queryAll();
		//$actionsOutsList = CHtml::listData($actionsOuts,'AOU_ID','AOU_DESC_'.Yii::app()->session['lang']);
		$actionsOutsList = array();
		foreach($actionsOuts as $actionsOut){
			$actionText = $actionsOut['AOU_DESC_'.Yii::app()->session['lang']];
			if (isset($tools[$actionsOut['TOO_ID']])){
				$tool = $tools[$actionsOut['TOO_ID']];
				$actionText = str_replace('#tool',$tool, $actionText);
			} else if ($actionsOut['TOO_ID'] > 0){
				$actionText = str_replace('#tool','ToolId-' . $actionsOut['TOO_ID'], $actionText);
			}
			$actionsOutsList[$actionsOut['AOU_ID']] = $actionText;
		}
		
		$cookInPreps = Yii::app()->db->createCommand()->select('COI_PREP,COI_PREP_DESC')->from('cook_in_prep')/*->order('COI_PREP_DESC')*/->queryAll();
		$cookInPreps = CHtml::listData($cookInPreps,'COI_PREP','COI_PREP_DESC');
		
		//$stepTypeConfig = Yii::app()->db->createCommand()->select('STT_ID,STT_DEFAULT,STT_REQUIRED,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->order('STT_ID')->queryAll();
		$stepTypeConfig = Yii::app()->db->createCommand()->select('STT_ID,STT_DESC_'.Yii::app()->session['lang'])->from('step_types')->order('STT_ID')->queryAll();
		$stepTypes = CHtml::listData($stepTypeConfig,'STT_ID','STT_DESC_'.Yii::app()->session['lang']);
		
		
		$stepsJSON = CJSON::encode($model->ainToAous);
		
		if ($view == 'change'){
			$actionsIn = Yii::app()->db->createCommand()->select('AIN_ID,AIN_DESC_'.Yii::app()->session['lang'])->from('actions_in')->where('AIN_ID = :ain_id', array(':ain_id'=>$ain_id))->queryRow();
			
			$cookIn = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->where('COI_ID = :coi_id', array(':coi_id'=>$coi_id))->queryRow();
			
			$this->checkRenderAjax('_form',array(
				'model'=>$model,
				'view'=>$view,
				'ainToCoi'=>$ainToCoi,
				'actionsIn'=>$actionsIn,
				'cookIn'=>$cookIn,
				'actionsOuts'=>$actionsOuts,
				'actionsOutsList'=>$actionsOutsList,
				'cookInPreps'=>$cookInPreps,
				'stepTypes'=>$stepTypes,
				'tools'=>$tools,
				'stepsJSON'=>$stepsJSON,
			));
		} else if ($view == 'copy'){
			$actionsIns = Yii::app()->db->createCommand()->select('AIN_ID,AIN_DESC_'.Yii::app()->session['lang'])->from('actions_in')->order('AIN_DESC_'.Yii::app()->session['lang'])->queryAll();
			$actionsIns = CHtml::listData($actionsIns,'AIN_ID','AIN_DESC_'.Yii::app()->session['lang']);
			
			$cookIns = Yii::app()->db->createCommand()->select('COI_ID,COI_DESC_'.Yii::app()->session['lang'])->from('cook_in')->queryAll();
			$cookIns = CHtml::listData($cookIns,'COI_ID','COI_DESC_'.Yii::app()->session['lang']);
			
			$this->checkRenderAjax('_form',array(
				'model'=>$model,
				'view'=>$view,
				'ainToCoi'=>$ainToCoi,
				'actionsIns'=>$actionsIns,
				'cookIns'=>$cookIns,
				'actionsOuts'=>$actionsOuts,
				'actionsOutsList'=>$actionsOutsList,
				'cookInPreps'=>$cookInPreps,
				'stepTypes'=>$stepTypes,
				'tools'=>$tools,
				'stepsJSON'=>$stepsJSON,
			));
		}
	}
	

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}