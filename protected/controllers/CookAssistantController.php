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

class CookAssistantController extends Controller {
	const STANDBY=0;
	const CUT=1;
	const MOTOR=1;
	const SCALE=2;
	const HEADUP=10;
	const COOK=11;
	const COOLDOWN=12;
	const PRESSUP=20;
	const PRESSHOLD=21;
	const PRESSDOWN=22;
	const PRESSVENT=23;
	
	const HOT=30;
	const PRESSURIZED=31;
	const COLD=32;
	const PRESSURELESS=33;
	const WEIGHT_REACHED=34;
	const COOK_TIMEEND=35;
	const RECIPE_END=39;
	
	const INPUT_ERROR=40;
	const EMERGANCY_SHUTDOWN=41;
	const MOTOR_OVERLOAD=42;
	
	const COMMUNICATION_ERROR=53;
	
	
	const COOK_WITH_OTHER = 0;
	const COOK_WITH_LOCAL = 1;
	const COOK_WITH_IP = 2;
	const COOK_WITH_IP_DEFAULT = '10.0.0.1';
	const COOK_WITH_EVERYCOOK_COI = 1;
	
	
	const STEP_DURATION_SPECIAL_USE_STEP = -1;
	const STEP_DURATION_SPECIAL_CALC_HEADUP = -2;
	const STEP_DURATION_SPECIAL_CALC_COOLDOWN = -3;
	const STEP_DURATION_SPECIAL_CALC_PRESSUP = -4;
	const TEMP_DEFAULT_START = 20;
	
	const ATY_ID_NOT_SPECIAL = 1;
	const ATY_ID_ADD_TO_COOK_IN = 2;
	const ATY_ID_PREPARE_MACHINE = 3;
	const ATY_ID_WEIGHT_RELEVANT_STEP = 4;
	
	const COOKING_INFOS = 'cookingInfo';
	const COOKING_INFOS_CHANGEAMOUNT = 'cookingInfoChangeAmount';
	
	protected $cookingInfoChangeCounter;
	
	public $debugErrorLog=false;
	
	private function preloadData($info){
		for ($courseNr=0; $courseNr<count($info->meal->meaToCous); ++$courseNr){
			$course = $info->meal->meaToCous[$courseNr]->course;
			for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
				$recipe = $course->couToRecs[$recipeNr]->recipe;
				foreach($recipe->steps as $step){
					$actionIn = $step->actionIn;
					foreach($actionIn->ainToAous as $ainToAou){
						$actionsOut = $ainToAou->actionsOut;
					}
					$ingredient = $step->ingredient;
				}
				foreach($recipe->recToCois as $recToCoi){
				}
				$recipeType = $recipe->recipeTypes;
			}
		}
	}
	
	public function actionStart(){
		if (isset($_GET['id'])){
			$meal = $this->loadModel($_GET['id'], true);
			
			/*
			echo "<pre>\r\n";
			print_r($meal);
			echo "</pre>\r\n";
			die();
			*/
			$info = $this->loadInfoForCourse($meal, 0);
			
			$this->preloadData($info);
			$meal = $info->meal;
			$meal = Functions::mapCActiveRecordToSimpleClass($meal);
			$info->meal = $meal;
			
			$ingredientIdToNutrient = $info->ingredientIdToNutrient;
			$ingredientIdToNutrient = Functions::mapCActiveRecordToSimpleClass($ingredientIdToNutrient);
			$info->ingredientIdToNutrient = $ingredientIdToNutrient;
			Functions::saveToCache(self::COOKING_INFOS, $info);
			Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, 0);
			//error_log("actionStart, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
			
			/*
			echo "<pre>\r\n";
			print_r($info);
			echo "</pre>\r\n";
			die();
			*/
			
			//$this->checkRenderAjax('index', array('info'=>$info));
			$this->checkRenderAjax('overview', array('info'=>$info));
			//$this->redirect('cookassistant/index');
		} else {
			echo "Error: Please select meal to cook.";
		}
	}

	public function actionGotoCourse($number){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionGotoCourse, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		$meal = $info->meal;
		if (isset($meal->meaToCous[$number])){
			$info = $this->loadInfoForCourse($meal, $number);
			$info = Functions::mapCActiveRecordToSimpleClass($info);
			
			$cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
			//error_log("actionGotoCourse save, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
			if ($this->cookingInfoChangeCounter == $cookingInfoChangeCounter){
				Functions::saveToCache(self::COOKING_INFOS, $info);
				Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, $cookingInfoChangeCounter+1);
			} else {
				//TODO: concurrentModification Exception
			}
		} else {
			//TODO error course not exist for meal
		}
		
		$this->checkRenderAjax('index', array('info'=>$info));
	}

	private function loadInfoForCourse($meal, $courseNumber){
		$course = $meal->meaToCous[$courseNumber]->course;
		//error_log("loadInfoForCourse: initialize info");
		$info = new CookAsisstantInfo();
		$info->meal = $meal;
		$info->courseNr = $courseNumber;
		$info->courseFinished[$courseNumber] = false;
		$stepNumbers = array();
		$stepStartTime = array();
		$recipeStartTime = array();
		$cookWith = array();
		$totalTimes = array();
		$prepareTimes = array();
		$cookTimes = array();
		$usedTimes = array();
		$timeDiff = array();
		$recipeUsedTime = array();
		$maxTime = 0;
		$totalWeight = array();
		$voted = array();
		
		$physics = Yii::app()->db->createCommand()->select('PHA_NAME,PHA_VALUE')->from('physics_assumptions')->queryAll();
		$physics = CHtml::listData($physics,'PHA_NAME','PHA_VALUE');
		$info->physics = $physics;
		
		$recipeCookedInfos = array();
		
		$ingredientIdToNutrient = array();
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$stepNumbers[] = -1;
			$stepStartTime[] = time();
			$cookWith[] = array();
			$totalTime = 0;
			$recipe = $course->couToRecs[$recipeNr]->recipe;
			
			//Calculate Weight
			$meaToCou = $meal->meaToCous[$courseNumber];
			$couToRec = $course->couToRecs[$recipeNr];
			$meal_gda = $meaToCou['MTC_KCAL_DAY_TOTAL'] * $meal['MEA_PERC_GDA'] / 100;
			$cou_gda = $meal_gda * $meaToCou['MTC_PERC_MEAL'] / 100;
			$rec_gda = $cou_gda * $couToRec['CTR_REC_PROC'] / 100;
			$rec_kcal = $recipe->REC_KCAL;
			if ($rec_kcal != 0){
				$rec_proz = $rec_gda / $rec_kcal;
			} else {
				//TODO: this is a data error!, or a recipe without ingredients .... ?
				$rec_proz = 1;
			}
			foreach($recipe->steps as $step){
				$totalTime += $step->STE_STEP_DURATION;
				if (isset($step->ingredient)){
					//$ingredientIdToNutrient[$step->ingredient->ING_ID] = $step->ingredient->NUT_ID;
					$ingredientIdToNutrient[$step->ingredient->ING_ID] = $step->ingredient->nutrientData;
					$step->ingredient->nutrientData = null;
				}
				if ($step->STE_GRAMS > 0){
					//$step->STE_GRAMS = round($step->STE_GRAMS * $rec_proz,2);
					$step->STE_GRAMS = round($step->STE_GRAMS * $rec_proz);
				}
			}
			$totalTimes[] = $totalTime;
			$prepareTimes[] = 0;
			$cookTimes[] = 0;
			if ($totalTime>$maxTime){
				$maxTime=$totalTime;
			}
			$usedTimes[] = 0;
			$timeDiff[] = 0;
			$recipeUsedTime[] = 0;
			$recipeStartTime[] = 0;
			$totalWeight[] = 0;
			$voted[] = 0;
			$recipeCookedInfos[] = array();
		}
		$info->stepNumbers = $stepNumbers;
		$info->stepStartTime = $stepStartTime;
		$info->recipeStartTime = $recipeStartTime;
		$info->cookWith = $cookWith;
		$info->totalTime = $totalTimes;
		$info->prepareTime = $prepareTimes;
		$info->cookTime = $cookTimes;
		$info->usedTime = $usedTimes;
		$info->timeDiff = $timeDiff;
		//$info->finishedIn = $maxTime;
		$info->finishedIn = 0; //set correct value after choose of cookIn options
		$info->recipeUsedTime = $recipeUsedTime;
		$info->totalWeight = $totalWeight;
		$info->ingredientIdToNutrient = $ingredientIdToNutrient;
		$info->voted = $voted;
		$info->recipeCookedInfos = $recipeCookedInfos;
		
		$this->loadSteps($info);
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$this->sendActionToFirmware($info, $recipeNr);
		}
		return $info;
	}
	
	public function actionNext($recipeNr, $step){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionNext, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		//if ($this->debug) {error_log("actionNext called with recipeNr: $recipeNr, step: $step\r\n");}
		if (isset($info->stepNumbers[$recipeNr])){
			/*
			if (isset($info->steps[$recipeNr])){
				$mealStep = $info->steps[$recipeNr];
				error_log("actionNext, mealStep->recipeNr:$mealStep->recipeNr, mealStep->stepNr:$mealStep->stepNr,info->stepNumbers[$recipeNr]:" .($info->stepNumbers[$recipeNr])." begin");
			}
			*/
			if ($info->stepNumbers[$recipeNr] == $step){
				if (isset($info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]+1])){
					$currentTime = time();
					$course = $info->meal->meaToCous[$info->courseNr]->course;
					if (!$info->started){
						for ($recipeNrLoop=0; $recipeNrLoop<count($course->couToRecs); ++$recipeNrLoop){
							$info->stepStartTime[$recipeNrLoop] = $currentTime;
						}
					}
					$info->started = true;
					
					//error_log("recipeNr: $recipeNr, stepNumber: ".$info->stepNumbers[$recipeNr]." before, submitted stepNr: $step");
					++$info->stepNumbers[$recipeNr];
					$timeDiff = $currentTime - $info->stepStartTime[$recipeNr];
					
					// echo "<pre>\n";
					// echo "next steps:\n";
					// echo "timeDiff:".$timeDiff."\n";
					// echo "info->usedTime[$recipeNr]:".$info->usedTime[$recipeNr]."\n";
					
					if ($step != -1){
						$info->usedTime[$recipeNr] = $info->usedTime[$recipeNr] + $timeDiff;
						
						$info->recipeUsedTime[$recipeNr] += $info->steps[$recipeNr]->stepDuration;
						$timeDiff = $timeDiff - $info->steps[$recipeNr]->stepDuration;
						$info->timeDiff[$recipeNr] = $info->timeDiff[$recipeNr] + $timeDiff;
						$info->recipeStartTime[$recipeNr] = $currentTime;
						$info->recipeCookedInfos[$recipeNr][$step]['timeDiff']=$timeDiff;
						
						$mealStep = $info->steps[$recipeNr];
						
						$state = Functions::getFromCache('HWValues');
						if (isset($state)){
							if ($this->debugErrorLog) {error_log("actionNext, HWValues: ". CJSON::encode($state));}
							$mealStep->HWValues = $state;
							if (isset($mealStep->HWValues)){
								if ($mealStep->HWValues->T0 > 0){
									$mealStep->currentTemp = $mealStep->HWValues->T0;
								}
								if ($mealStep->HWValues->P0 > 0){
									$mealStep->currentPress = $mealStep->HWValues->P0;
								}
							}
						/*} else {
							error_log("actionNext, no HWValues...");*/
						}
						
						$stepAttributes = $info->recipeSteps[$recipeNr][$step];
						
						if ($mealStep->ingredientId != 0){
							if (isset($mealStep->HWValues->W0) && $mealStep->HWValues->W0>0){
								$ingWeight = $mealStep->HWValues->W0;
							} else if ($info->cookWith[$recipeNr][1] != self::COOK_WITH_EVERYCOOK_COI || $stepAttributes['ATY_ID'] == self::ATY_ID_WEIGHT_RELEVANT_STEP){
								$ingWeight = $stepAttributes['STE_GRAMS'];
							} else {
								$ingWeight = 0;
							}
							$info->recipeCookedInfos[$recipeNr][$step]['ing_id'] = $mealStep->ingredientId;
							$info->recipeCookedInfos[$recipeNr][$step]['weight'] = $ingWeight;
							if (isset($stepAttributes['STE_GRAMS'])){
								$info->recipeCookedInfos[$recipeNr][$step]['neededWeight'] = $stepAttributes['STE_GRAMS'];
							}
							
							//Save scaled Value
							if ($ingWeight>0){
								if (!isset($info->ingredientWeight[$recipeNr])){
									$info->ingredientWeight[$recipeNr] = array();
								}
								if (!isset($info->ingredientWeight[$recipeNr][$mealStep->ingredientId])){
									$info->ingredientWeight[$recipeNr][$mealStep->ingredientId] = $ingWeight;
								} else {
									$info->ingredientWeight[$recipeNr][$mealStep->ingredientId] += $ingWeight;
								}
								if ($this->debug) {echo '<script type="text/javascript"> if(console && console.log){ console.log(\'actionNext(ingredientWeight), ingredientId: '.$mealStep->ingredientId.', weight: '.$info->ingredientWeight[$recipeNr][$mealStep->ingredientId].'\')}</script>';}
								if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeight), ingredientId: '.$mealStep->ingredientId.', weight: '.$info->ingredientWeight[$recipeNr][$mealStep->ingredientId]);}
							} else {
								if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeight), ingredientId: '.$mealStep->ingredientId.', ingWeight: '.$ingWeight);}
							}
							//Mark as in pan
							if ($stepAttributes['ATY_ID'] == self::ATY_ID_ADD_TO_COOK_IN){
								if (isset($info->ingredientWeight[$recipeNr])){
									if (!isset($info->ingredientWeightInPan[$recipeNr])){
										$info->ingredientWeightInPan[$recipeNr] = array();
									}
									if (isset($info->ingredientWeight[$recipeNr][$mealStep->ingredientId])){
										$weight = $info->ingredientWeight[$recipeNr][$mealStep->ingredientId];
										if (!isset($info->ingredientWeightInPan[$recipeNr][$mealStep->ingredientId])){
											$info->ingredientWeightInPan[$recipeNr][$mealStep->ingredientId] = $weight;
										} else {
											$info->ingredientWeightInPan[$recipeNr][$mealStep->ingredientId] += $ingWeight;
										}
										//Remove value
										unset($info->ingredientWeight[$recipeNr][$mealStep->ingredientId]);
										
										if ($this->debug) {echo '<script type="text/javascript"> if(console && console.log){ console.log(\'actionNext(ingredientWeightInPan), ingredientId: '.$mealStep->ingredientId.', weight: '.$info->ingredientWeightInPan[$recipeNr][$mealStep->ingredientId].'\')}</script>';}
										if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeightInPan), ingredientId: '.$mealStep->ingredientId.', weight: '.$info->ingredientWeightInPan[$recipeNr][$mealStep->ingredientId]);}
									} else {
										if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeightInPan), ingredientId: '.$mealStep->ingredientId.', ingredientWeight is not set');}
									}
								} else {
									if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeightInPan), ingredientId: '.$mealStep->ingredientId.', not ingredientWeight for recipe');}
								}
							} else {
								if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeightInPan), ingredientId: '.$mealStep->ingredientId.', ATY_ID: '.$stepAttributes['ATY_ID']);}
							}
						}
					} else {
						if ($this->debugErrorLog) {error_log($step . ' actionNext(ingredientWeightInPan), step == -1');}
					}
					
					// echo "timeDiff:".$timeDiff."\n";
					// echo "info->timeDiff[$recipeNr]:".$info->timeDiff[$recipeNr]."\n";
					// echo "</pre>\n";
					
					/* //the follow code would calculate finishTime
					if ($timeDiff < 0){
						$timeDiff = 0;
					}
					$info->totalTime[$recipeNr] = $info->totalTime[$recipeNr] - $info->steps[$recipeNr]->stepDuration + $timeDiff;
					*/
					
					$info->stepStartTime[$recipeNr] = $currentTime;
					
					
					$maxtime = 0;
					for ($recipeNrLoop=0; $recipeNrLoop<count($course->couToRecs); ++$recipeNrLoop){
						if ($maxtime < $info->totalTime[$recipeNrLoop] - $info->recipeUsedTime[$recipeNrLoop]){
							$maxtime = $info->totalTime[$recipeNrLoop] - $info->recipeUsedTime[$recipeNrLoop];
						}
					}
					$info->finishedIn = $maxtime;
					
					$this->loadSteps($info);
					try {
						if (!isset($info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]+1])){
							//this is the last step, Save recipeCookedInfos
							$cookedInfo = new RecipeCookedInfos();
							$cookedInfo->PRF_UID = Yii::app()->user->id;
							$cookedInfo->MEA_ID = $info->meal->MEA_ID;
							$cookedInfo->COU_ID = $info->meal->meaToCous[$info->courseNr]->course->COU_ID;
							$cookedInfo->REC_ID = $info->meal->meaToCous[$info->courseNr]->course->couToRecs[$recipeNr]->recipe->REC_ID;
							$cookedInfo->RCI_COOK_DATE = time();
							$cookedInfo->RCI_JSON = CJSON::encode($info->recipeCookedInfos[$recipeNr]);
							if(!$cookedInfo->save()){
								//TODO error while save recipeCookedInfos...
							}
						}
						
						//error_log("recipeNr: $recipeNr, stepNumber: ".$info->stepNumbers[$recipeNr]." after");
						$cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
						//error_log("actionNext save, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
						if ($this->cookingInfoChangeCounter == $cookingInfoChangeCounter){
							Functions::saveToCache(self::COOKING_INFOS, $info);
							Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, $cookingInfoChangeCounter+1);
						} else {
							error_log("cookingInfoChangeCounter is not same!");
							//TODO: concurrentModification Exception
						}
					} catch(Exception $e){
						error_log('Exception occured in actionNext, Exeption was: ' . $e);
					}
					$this->sendActionToFirmware($info, $recipeNr);
				} else {
					error_log("recipeNr: $recipeNr, submitted stepNr: $step, recipe ended, no next step available.");
					//TODO recipe ended, no next step available.
				}
			} else {
				error_log("recipeNr: $recipeNr, submitted stepNr: $step, Don't change step, it is already next (->F5), but update time");
				//Don't change step, it is already next (->F5), but update time
				$this->loadSteps($info);
			}
		} else {
			error_log("recipeNr: $recipeNr, submitted stepNr: $step, error recipeNr doesnt exist.");
			//TODO error recipeNr doesnt exist.
		}
		/*
		if (isset($info) && isset($info->steps[$recipeNr])){
			$mealStep = $info->steps[$recipeNr];
			error_log("actionNext, mealStep->recipeNr:$mealStep->recipeNr, mealStep->stepNr:$mealStep->stepNr, end");
		}*/
		$this->checkRenderAjax('index', array('info'=>$info));
		//$this->redirect('index');
	}
	
	private function changeCookInState($state, $change, $tool){
		if ($this->debug) {echo 'change: stateBefore: '; print_r($state); echo ', change: ' . $change;}
		if ($change !== ''){
			$change = json_decode($change);
			foreach($change as $coi_id=>$data){
				$coiState = $state->$coi_id;
				foreach($data as $key=>$value){
					if ($value === '#tool'){
						if (isset($tool) && $tool != null){
							$value = $tool['TOO_ID'];
						} else {
							$value = null;
						}
					}
					$coiState->$key = $value;
				}
			}
		}
		if ($this->debug) {echo ', stateAfter: '; print_r($state); echo "<br>\r\n";}
		return $state;
	}
	
	private function hasCookInState($state, $needed, $tool){
		if ($this->debug) {echo 'check: state: '; print_r($state); echo ', needed: ' . $needed . "<br>\r\n";}
		
		$hasState = true;
		if ($needed !== ''){
			$needed = json_decode($needed);
			foreach($needed as $coi_id=>$data){
				if (isset($state->$coi_id)){
					$coiState = $state->$coi_id;
					foreach($data as $key=>$value){
						if ($value === '#tool'){
							if (isset($tool) && $tool != null){
								$value = $tool['TOO_ID'];
							} else {
								$value = null;
							}
						}
						if (isset($coiState->$key)){
							if ($coiState->$key !== $value){
								$hasState = false;
								break 2;
							}
						} else {
							if ($value !== false){
								$hasState = false;
								break 2;
							}
						}
					}
				} else {
					$hasState = false;
					break;
				}
			}
		}
		return $hasState;
	}
	
	//TODO fill/create this function
	private function isCookInStateReverse($old, $oldTool, $new, $newTool, $state){
		if ($this->debug) {echo 'isReverse: old: ' . $old .', new: ' . $new ;}
	
		$isReverse = true;
		if ($old !== $new){
			$old = json_decode($old);
			$new = json_decode($new);
			if (is_object($old)){
				foreach($old as $coi_id=>$data){
					if (isset($new->$coi_id)){
						$coiState = $new->$coi_id;
						foreach($data as $key=>$value){
							/*
							if ($value === '#tool'){
								if (isset($oldTool) && $tool != null){
									$value = $oldTool['TOO_ID'];
								} else {
									$value = null;
								}
							}
							*/
							if (isset($coiState->$key)){
								$newVal = $coiState->$key;
								/*
								if ($newVal === '#tool'){
									if (isset($newTool) && $tool != null){
										$newVal = $newTool['TOO_ID'];
									} else {
										$newVal = null;
									}
								}
								*/
								if ($this->debug) {echo ", new value exist, new: $newVal old:$value";}
								if ($newVal === $value){
									$isReverse = false;
									break 2;
								}
							} else {
								if ($this->debug) {echo ", new value don't exist, old:$value";}
								if ($value === false){
									$isReverse = false;
									break 2;
								}
							}
						}
					} else {
						if ($this->debug) {echo "new value has no coi_id ($coi_id) part";}
						$isReverse = false;
						break;
					}
				}
			} else {
				if ($this->debug) {echo "old is not a object";}
				$isReverse = false;
			}
			if ($isReverse){
				if (is_object($new)){
					foreach($new as $coi_id=>$data){
						if (isset($old->$coi_id)){
							$coiState = $old->$coi_id;
							foreach($data as $key=>$value){
								if (isset($coiState->$key)){
									$oldVal = $coiState->$key;
									if ($this->debug) {echo ", new value exist, old: $oldVal new:$value";}
									if ($oldVal === $value){
										$isReverse = false;
										break 2;
									}
								} else {
									if ($this->debug) {echo ", old value don't exist, new:$value";}
									if ($value !== false){
										$isReverse = false;
										break 2;
									} else if (isset($state->$coi_id) && isset($state->$coi_id->$key) && $state->$coi_id->$key != $value){
										$isReverse = false;
										break 2;
									}
								}
							}
						} else {
							if ($this->debug) {echo "old value has no coi_id ($coi_id) part";}
							$isReverse = false;
							break;
						}
					}
				} else {
					if ($this->debug) {echo "new is not a object";}
					$isReverse = false;
				}
			}
		} else {
			if ($this->debug) {echo ", old == new";}
			$isReverse = false;
		}
		if ($this->debug) {echo ", isReverse: $isReverse<br>\r\n";}
		
		return $isReverse;
	}
	
	private function loadSteps($info){
		$course = $info->meal->meaToCous[$info->courseNr]->course;
		$currentSteps = array();
		$currentTime = time();
		$maxtime = 0;
		$maxDiff = 0;
		//$actionsOuts = null;
		$tools = null;
		$cookIns = null;
		$allFinished = true;
		$allCookWithSet = true;
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$mealStep = new CookAsisstantStep();
			$stepNr = $info->stepNumbers[$recipeNr];
			$recipe = $course->couToRecs[$recipeNr]->recipe;
			
			$mealStep->recipeNr = $recipeNr;
			$mealStep->stepNr = $stepNr;
			//error_log("loadSteps, mealStep->recipeNr:$mealStep->recipeNr, mealStep->stepNr:$mealStep->stepNr, begin");
			$mealStep->recipeName = $recipe->__get('REC_NAME_'.Yii::app()->session['lang']);
			$stepStartTime = $info->stepStartTime[$recipeNr];
			if ($stepNr == -1){
				//error_log("loadSteps: stepNr == -1, initialize infos");
				//get recipe detail steps
				if (count($info->cookWith[$recipeNr])>1){
					$coi_id = $info->cookWith[$recipeNr][1];
					if ($tools == null){
						$tools = Yii::app()->db->createCommand()->from('tools')->queryAll();
						$toolsIndexedArray = array();
						foreach($tools as $tool){
							$toolsIndexedArray[$tool['TOO_ID']] = $tool;
						}
						$tools = $toolsIndexedArray;
					}
					/*
					//read all actionsOuts
					if ($actionsOuts == null) {
						$actionsOuts = Yii::app()->db->createCommand()->from('actions_out')->order('AOU_ID')->queryAll();
						$actionsOutsIndexedArray = array();
						foreach($actionsOuts as $actionsOut){
							$actionsOutsIndexedArray[$actionsOut['AOU_ID']] = $actionsOut;
						}
					}
					*/
					$recipeSteps = array();
					$prepareSteps = array();
					$otherSteps = array();
					$totalTime = 0;
					$prepareTime = 0;
					$cookTime = 0;
					$detailStepNr = 0;
					//$cookInState = '{"1":{"lid":true, "lidcrew":false, "cutter":null, "scalpot":false}}'; //Annahme deckel geschlossen (bei coi_id==1)
					$cookInState = '{"1":{"lid":true,"pusher":true}}'; //Annahme deckel geschlossen und stössel eingesetzt (bei coi_id==1)
					$cookInState = json_decode($cookInState);
					$lastStepAttributes = null;
					
					
					//simulate ingredient/total weight and current temp for time calculation
					//error_log("loadSteps: stepNr == -1, initialize ingredientWeightInPan initialisieren");
					$info->ingredientWeightInPan[$recipeNr] = array();
					$info->totalWeight[$recipeNr] = 0;
					$currentTemp = self::TEMP_DEFAULT_START;
					
					foreach($recipe->steps as $step){
						$actionIn = $step->actionIn;;
						$detailSteps = array();
						$detailSteps['all'] = array();
						
						/*
						if (!isset($actionIn) || $actionIn == null){
							print_r($step);
							die();
						}
						*/
						
						foreach($actionIn->ainToAous as $ainToAou){
							if ($ainToAou->COI_ID == $coi_id){
								$actionsOut = $ainToAou->actionsOut;
								$stepAttributes = $step->attributes;
								$stepAttributes['ATA_COI_PREP'] = $ainToAou['ATA_COI_PREP'];
								$stepAttributes['ATA_NO'] = $ainToAou['ATA_NO'];
								$stepAttributes = array_merge($stepAttributes, $actionIn->attributes);
								$stepAttributes = array_merge($stepAttributes, $actionsOut->attributes);
								
								unset($stepAttributes['actionIn']);
								unset($stepAttributes['ainToAous']);
								
								if (isset($stepAttributes['STT_ID']) && $stepAttributes['STT_ID'] == self::STANDBY){
									$stepAttributes['STE_CELSIUS'] = 0;
									$stepAttributes['STE_KPA'] = 0;
									$stepAttributes['STE_RPM'] = 0;
									$stepAttributes['STE_CLOCKWISE'] = 0;
									$stepAttributes['STE_STIR_RUN'] = 0;
									$stepAttributes['STE_STIR_PAUSE'] = 0;
								}
								
								if ($stepAttributes['ATY_ID'] == self::ATY_ID_PREPARE_MACHINE){
									//remove informations not needed for prepare. for example don't show ingredient image on open lid step
									unset($step->ingredient);
									$stepAttributes['STE_GRAMS'] = 0;
									$stepAttributes['ING_ID'] = 0;
								}
								
								if (isset($step->ingredient) && $stepAttributes['ING_ID'] != 0){
									$stepAttributes['ING_ID'] = $step->ingredient->ING_ID;
									$stepAttributes['ING_IMG_AUTH'] = $step->ingredient->ING_IMG_AUTH;
									//$stepAttributes['ING_NAME_' . Yii::app()->session['lang']] = $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']);
									$stepAttributes['ING_NAME'] = $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']);
									
									//for simulation:
									if (isset($step['STE_GRAMS']) && $step['STE_GRAMS']>0){
										if (!isset($info->ingredientWeightInPan[$recipeNr][$step->ingredient->ING_ID])){
											$info->ingredientWeightInPan[$recipeNr][$step->ingredient->ING_ID] = $step['STE_GRAMS'];
										} else {
											$info->ingredientWeightInPan[$recipeNr][$step->ingredient->ING_ID] += $step['STE_GRAMS'];
										}
										$info->totalWeight[$recipeNr] += $step['STE_GRAMS'];
										
										//TODO change current Temp if ingredient added?
										//has this calculation any real relevance?
										$percentOfContent = $step['STE_GRAMS'] / $info->totalWeight[$recipeNr];
										$currentTemp = $currentTemp * (1-$percentOfContent) + self::TEMP_DEFAULT_START * $percentOfContent;
									}
								} else {
									$stepAttributes['ING_ID'] = 0;
									$stepAttributes['ING_IMG_AUTH'] = '';
									//$stepAttributes['ING_NAME_' . Yii::app()->session['lang']] = '';
									$stepAttributes['ING_NAME'] = '';
								}
								unset($stepAttributes['ingredient']);
								
								if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_USE_STEP){
									$stepAttributes['AOU_DURATION'] = $stepAttributes['STE_STEP_DURATION'];
								}
								if ($stepAttributes['AOU_DUR_PRO'] > 0 && (isset($stepAttributes['STE_GRAMS']) && $stepAttributes['STE_GRAMS'] == -1)){
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'] * ( $info->totalWeight[$recipeNr] / $stepAttributes['AOU_DUR_PRO']) ;
								//} else if ($stepAttributes['AOU_DUR_PRO'] > 0 && (isset($stepAttributes['STE_GRAMS']) && $stepAttributes['STE_GRAMS']>0){
								//	$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'] * ( $stepAttributes['STE_GRAMS'] / $stepAttributes['AOU_DUR_PRO']) ;
								} else if ($stepAttributes['AOU_DUR_PRO'] > 0){
									if (isset($info->ingredientWeightInPan[$recipeNr][$stepAttributes['ING_ID']])){
										$ingWeight = $info->ingredientWeightInPan[$recipeNr][$stepAttributes['ING_ID']];
									} else {
										$ingWeight = 0;
									}
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'] * ( $ingWeight / $stepAttributes['AOU_DUR_PRO']) ;
								} else if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_HEADUP){
									$stepAttributes['CALC_DURATION'] = $this->calcHeadUpTime($info, $recipeNr, $currentTemp, $stepAttributes['STE_CELSIUS']);
								} else if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_COOLDOWN){
									$stepAttributes['CALC_DURATION'] = $this->calcCoolDownTime($info, $recipeNr, $currentTemp, $stepAttributes['STE_CELSIUS']);
								} else if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_PRESSUP){
									$stepAttributes['CALC_DURATION'] = $this->calcPressUpTime($info, $recipeNr, $currentTemp, $stepAttributes['STE_KPA']);
								} else {
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'];
								}
								//Check vor min Step duration
								if ($stepAttributes['CALC_DURATION'] < Yii::app()->params['stepMinTime']){
									$stepAttributes['CALC_DURATION'] = Yii::app()->params['stepMinTime'];
								}
								
								if (isset($step->STE_CELSIUS) && $step->STE_CELSIUS>0){
									//for simulation
									$currentTemp = $step->STE_CELSIUS;
								}
								
								if (isset($step->TOO_ID) && $step->TOO_ID != ''){
									$stepAttributes['step_tool'] = $tools[$step->TOO_ID];
									//current TOO_ID is from ActionsOut
									if ($stepAttributes['TOO_ID'] == -1){
										$stepAttributes['TOO_ID'] = $step->TOO_ID;
									}
								} else {
									$stepAttributes['step_tool'] = null;
								}
								if(isset($tools[$stepAttributes['TOO_ID']])){
									$stepAttributes['aou_tool'] = $tools[$stepAttributes['TOO_ID']];
								} else {
									$stepAttributes['aou_tool'] = null;
								}
								unset($stepAttributes['tool']);
								$prep = $stepAttributes['ATA_COI_PREP'];
								if (!isset($detailSteps[$prep])){
									$detailSteps[$prep] = array();
								}
								$detailSteps[$prep][] = $stepAttributes;
								$detailSteps['all'][] = $stepAttributes;
							}
						}
						
						/*
						COI_PREP 	COI_PREP_DESC
						0	not a prepare step
						//0 	before coi prepare / step prepare
						1 	prepare for condition
						2 	do condition
						3 	prepare for action
						0	not a prepare step
						//5	action
						4 	cleanup after action
						*/
						$conditionNeeded = false;
						if (isset($detailSteps[2])){
							foreach($detailSteps[2] as $stepAttributes){
								if(!$this->hasCookInState($cookInState, $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool'])){
									$conditionNeeded = true;
									break;
								}
							}
						}
						foreach($detailSteps['all'] as $stepAttributes){
							$prep = $stepAttributes['ATA_COI_PREP'];
							$add = false;
							
							if ($this->debug) {echo '<br>Step ' . $stepAttributes['STE_STEP_NO'] . ' ' . $stepAttributes['ATA_NO'] . ', conditionNeeded: ' . ($conditionNeeded?'true':'false') . ', prep: ' . $prep . "<br>\r\n";}
							
							if ($conditionNeeded || ($prep != 1 && $prep != 2)){
								if ($prep != 0){
									if ($lastStepAttributes != null && $this->isCookInStateReverse($lastStepAttributes['AOU_CIS_CHANGE'], ($lastStepAttributes['aou_tool']==null)?$lastStepAttributes['step_tool']:$lastStepAttributes['aou_tool'], $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool'], $cookInState)){
										//TODO test, isCookInStateReverse function not yet created.
										if ($stepAttributes['AOU_PREP'] == 'Y'){
											--$detailStepNr;
											array_pop($prepareSteps);
										} else {
											array_pop($otherSteps);
										}
										//do state change anyway so it is realy reversed.
										$cookInState = $this->changeCookInState($cookInState, $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool']);
									} else if(!$this->hasCookInState($cookInState, $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool'])){
										$add = true;
									}
								} else {
									$add = true;
								}
							}
							if ($add){
								if ($this->debug) {echo "+++added<br>\r\n";}
								$cookInState = $this->changeCookInState($cookInState, $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool']);
								
								if ($stepAttributes['AOU_PREP'] == 'Y'){
									$stepAttributes['detailStepNr'] = $detailStepNr;
									$prepareSteps[] = $stepAttributes;
									++$detailStepNr;
									$prepareTime += $stepAttributes['CALC_DURATION'];
									if ($this->debug) {echo $detailStepNr . " " . $stepAttributes['CALC_DURATION'] ." ". $stepAttributes['AOU_DURATION'] . "<br>\r\n";}
								} else {
									$otherSteps[] = $stepAttributes;
									$cookTime += $stepAttributes['CALC_DURATION'];
									if ($this->debug) {echo 'N ' . $stepAttributes['CALC_DURATION'] ." ". $stepAttributes['AOU_DURATION'] . "<br>\r\n";}
								}
							} else {
								if ($this->debug) {echo "---NOT added<br>\r\n";}
							}
							if ($stepAttributes['AOU_PREP'] != 'Y'){
								if ($this->debug) {echo "update laststep<br>\r\n";}
								$lastStepAttributes = $stepAttributes;
							}
						}
					}
					//remove all "cleanup after action" steps before finished step
					$index = count($otherSteps)-2; //skip "finished" step and check the one before
					if ($index >= 0){
						while($otherSteps[$index]['ATA_COI_PREP'] == 4){
							$cookTime -= $otherSteps[$index]['CALC_DURATION'];
							if ($this->debug) {echo 'Remove Step	(index:'.$index.')' . $stepAttributes['STE_STEP_NO'] . ' ' . $stepAttributes['ATA_NO'] . ': ' . $otherSteps[$index]['AIN_DESC_' . Yii::app()->session['lang']]. ' /' . $otherSteps[$index]['AOU_DESC_' . Yii::app()->session['lang']] . "<br>\r\n";}
							$otherSteps[$index] = $otherSteps[$index+1];
							unset($otherSteps[$index+1]);
							--$index;
						}
					}
					$recipeSteps = $prepareSteps;
					foreach($otherSteps as $stepAttributes){
						$stepAttributes['detailStepNr'] = $detailStepNr;
						$recipeSteps[] = $stepAttributes;
						++$detailStepNr;
					}
					$totalTime = $prepareTime + $cookTime;
					$info->recipeSteps[$recipeNr] = $recipeSteps;
					$info->totalTime[$recipeNr] = $totalTime;
					$info->prepareTime[$recipeNr] = $prepareTime;
					$info->cookTime[$recipeNr] = $cookTime;
					
					//Reset ingredientWeightInPan / totalWeight after simulation
					//error_log("loadSteps: stepNr == -1, initialize ingredientWeightInPan zurücksetzen");
					$info->ingredientWeightInPan[$recipeNr] = array();
					$info->totalWeight[$recipeNr] = 0;
					
					$info->recipeCookedInfos[$recipeNr]['coi_id']=$coi_id;
				} else {
					//Cook in not defined
					$allCookWithSet = false;
				}
				
				//TODO Calculate Starttime
				$startIn = $info->finishedIn - $info->totalTime[$recipeNr];
				
				$mealStep->stepDuration = $startIn;
				$mealStep->nextStepTotal = $startIn;
				$mealStep->nextStepIn = $stepStartTime - $currentTime + $mealStep->nextStepTotal;
				
				$mealStep->finishedIn = $mealStep->nextStepIn;
				$mealStep->lowestFinishedIn = 0;
				
				if ($info->finishedIn == $info->totalTime[$recipeNr] || $mealStep->nextStepIn <= 0){
					if ($info->finishedIn == $info->totalTime[$recipeNr]){
						$mealStep->nextStepIn = 0; //sometimes it is -1;, time difference between nextStep & loadSteps...
					}
					$mealStep->actionText = $this->trans->COOKASISSTANT_START_COOKING;
				} else {
					$mealStep->actionText = $this->trans->COOKASISSTANT_WAIT_UNTIL_STARTTIME;
				}
				$mealStep->mainActionText = '&nbsp;';
				
				$mealStep->mustWait  = false;
				$mealStep->autoClick = false;
				
				$currentSteps[] = $mealStep;
				
				if ($maxtime < $info->totalTime[$recipeNr]){
					$maxtime = $info->totalTime[$recipeNr];
				}
				
				$allFinished = false;
			} else {
				$step = $info->recipeSteps[$recipeNr][$stepNr];
				$coi_id = $info->cookWith[$recipeNr][1];
				
				$info->recipeCookedInfos[$recipeNr][$stepNr]=array('stepNr'=>$stepNr, 'stepType'=>$step['STT_ID']);
				
				//Get last HWValues and add them to step.
				$state = Functions::getFromCache('HWValues');
				if (isset($state)){
					$mealStep->HWValues = $state;
					/*error_log("loadSteps, HWValues: ". CJSON::encode($state));
				} else {
					error_log("loadSteps, no HWValues...");*/
				}
				
				if (isset($info->steps[$recipeNr])){
					$oldMealstep = $info->steps[$recipeNr];
					if (isset($oldMealstep) && $oldMealstep != null){
						$currentTemp = $oldMealstep->currentTemp;
					}
					if (!isset($currentTemp) || $currentTemp == null || $currentTemp == ''){
						if (isset($mealStep->HWValues) && isset($mealStep->HWValues->T0)){
							$currentTemp = $mealStep->HWValues->T0;
						}
					}
					if (!isset($currentTemp) || $currentTemp == null || $currentTemp == ''){
						$currentTemp = self::TEMP_DEFAULT_START;
					}
				} else {
					$oldMealstep = null;
					if (!isset($currentTemp) || $currentTemp == null || $currentTemp == ''){
						if (isset($mealStep->HWValues) && isset($mealStep->HWValues->T0)){
							$currentTemp = $mealStep->HWValues->T0;
						}
					}
					if (!isset($currentTemp) || $currentTemp == null || $currentTemp == ''){
						$currentTemp = self::TEMP_DEFAULT_START;
					}
				}
				$mealStep->currentTemp = $currentTemp;
				if (isset($mealStep->HWValues) && isset($mealStep->HWValues->P0)){
					$mealStep->currentPress = $mealStep->HWValues->P0;
				}
				//error_log("loadSteps, currentTemp: " . $mealStep->currentTemp . ", currentPress: " . $mealStep->currentPress);
				
				if ($cookIns == null){
					$cookIns = Yii::app()->db->createCommand()->from('cook_in')->queryAll();
					$cookInsIndexedArray = array();
					foreach($cookIns as $tool){
						$cookInsIndexedArray[$tool['COI_ID']] = $tool;
					}
					$cookIns = $cookInsIndexedArray;
				}
				if ($step['AOU_DUR_PRO'] > 0 && (isset($step['STE_GRAMS']) && $step['STE_GRAMS'] == -1)){
					$stepDuration = $step['AOU_DURATION'] * ( $info->totalWeight[$recipeNr] / $step['AOU_DUR_PRO']) ;
					$info->recipeCookedInfos[$recipeNr][$stepNr]['weightDuration'] = $stepDuration;
					//TODO: total time anpassen????
				} else if ($step['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_HEADUP){
					$stepDuration = $this->calcHeadUpTime($info, $recipeNr, $currentTemp, $step['STE_CELSIUS']);
					$info->recipeCookedInfos[$recipeNr][$stepNr]['headUpTime'] = $stepDuration;
					//TODO: total time anpassen????
				} else if ($step['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_COOLDOWN){
					$stepDuration = $this->calcCoolDownTime($info, $recipeNr, $currentTemp, $step['STE_CELSIUS']);
					$info->recipeCookedInfos[$recipeNr][$stepNr]['coolDownTime'] = $stepDuration;
					//TODO: total time anpassen????
				} else if ($step['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_PRESSUP){
					$stepDuration = $this->calcPressUpTime($info, $recipeNr, $currentTemp, $step['STE_KPA']);
					$info->recipeCookedInfos[$recipeNr][$stepNr]['pressUpTime'] = $stepDuration;
					//TODO: total time anpassen????
				} else {
					$stepDuration = $step['CALC_DURATION'];
				}
				if ($this->debug){echo "stepDuration = $stepDuration \r\n";}
				$mealStep->stepDuration = $stepDuration;
				$mealStep->nextStepTotal = $stepDuration;
				$mealStep->stepType = $step['STT_ID'];
				$mealStep->nextStepIn = $stepStartTime - $currentTime + $mealStep->nextStepTotal;
				
				
				// echo "<pre>\n";
				// echo "loadSteps:\n";
				// echo "stepDuration:".$mealStep->stepDuration."\n";
				// echo "nextStepIn:".$mealStep->nextStepIn."\n";
				
				/*
				$stepUsed = $currentTime+$stepStartTime;
				if ($stepUsed>$mealStep->nextStepTotal){
					$stepUsed = $mealStep->nextStepTotal;
				}
				$mealStep->finishedIn = $info->totalTime[$recipeNr] - $info->usedTime[$recipeNr] - $stepUsed + $info->timeDiff[$recipeNr];
				*/
				
				$timeDiff = $currentTime-$stepStartTime;
				$usedTime = $info->usedTime[$recipeNr] + $timeDiff;
				
				// echo "info->usedTime[$recipeNr] :".$info->usedTime[$recipeNr] ."\n";
				// echo "timeDiff:".$timeDiff."\n";
				// echo "usedTime:".$usedTime."\n";
				
				$mealStep->finishedIn = $info->totalTime[$recipeNr] - ($info->recipeUsedTime[$recipeNr] + $timeDiff);
				
				$timeDiff = $timeDiff - $mealStep->stepDuration;
				
				if ($usedTime > $info->recipeUsedTime[$recipeNr]+$mealStep->stepDuration){
					$usedTime = $info->recipeUsedTime[$recipeNr]+$mealStep->stepDuration;
				}
				
				//$mealStep->finishedIn = $info->totalTime[$recipeNr] - $usedTime;
				//$mealStep->finishedIn = $info->totalTime[$recipeNr] - $info->usedTime[$recipeNr] - $timeDiff;
				//$mealStep->finishedIn = $info->recipeUsedTime[$recipeNr];
				$mealStep->lowestFinishedIn = $info->totalTime[$recipeNr] - $info->recipeUsedTime[$recipeNr]-$mealStep->stepDuration;
				
				// echo "info->recipeUsedTime[$recipeNr] :".$info->recipeUsedTime[$recipeNr] ."\n";
				// echo "info->totalTime[$recipeNr] :".$info->totalTime[$recipeNr] ."\n";
				// echo "finishedIn:".$mealStep->finishedIn."\n";
				// echo "lowestFinishedIn:".$mealStep->lowestFinishedIn."\n";
				// echo "</pre>\n";
				
				//$mealStep->inTime = $stepStartTime + $mealStep->nextStepTotal > $currentTime;
				$mealStep->inTime = $mealStep->finishedIn > $mealStep->lowestFinishedIn;
				//TODO nur wenn mit everycook gekocht wird?
				$mealStep->mustWait = $mealStep->stepType >= 10 || ($info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER && $mealStep->stepType == self::SCALE);
				$mealStep->autoClick = false;
				
				//$finishedAt = $currentTime + $mealStep->finishedIn;
				//$mealStep->finishedAt = date('H:i:s', $finishedAt);
				
				if (!isset($info->recipeSteps[$recipeNr][$stepNr+1])){
					$mealStep->endReached = true;
					$mealStep->inTime = true;
					$mealStep->nextStepIn = 0;
				} else {
					$allFinished = false;
				}
				$mealStep->ingredientId = $step['ING_ID'];
				$mealStep->ingredientCopyright = $step['ING_IMG_AUTH'];
				
				$mainText = $step['AIN_DESC_' . Yii::app()->session['lang']];
				$text = $step['AOU_DESC_' . Yii::app()->session['lang']];
				if (isset($step['ING_ID']) && $step['ING_ID'] > 0){
					//$replText = '<span class="ingredient">' . $step['ING_NAME_' . Yii::app()->session['lang']] . '</span> ';
					$replText = '<span class="ingredient">' . $step['ING_NAME'] . '</span> ';
					$mainText = str_replace('#ingredient', $replText, $mainText);
					$text = str_replace('#ingredient', $replText, $text);
				}
				if (isset($step['STE_GRAMS']) && $step['STE_GRAMS'] > 0){
					$replText = '<span class="weight">' . $step['STE_GRAMS'] . 'g</span> ';
					$mainText = str_replace('#weight', $replText, $mainText);
					$text = str_replace('#weight', $replText, $text);
				}
				
				$cookIn = $cookIns[$coi_id];
				$replText = '<span class="cookIn">' . $cookIn['COI_DESC_' . Yii::app()->session['lang']] . '</span> ';
				$mainText = str_replace('#cookin', $replText, $mainText);
				
				if (isset($step['step_tool']) && $step['step_tool'] != null){
					$tool = $step['step_tool'];
					$replText = '<span class="tool">' . $tool['TOO_DESC_' . Yii::app()->session['lang']] . '</span> ';
					$mainText = str_replace('#tool', $replText, $mainText);
				}
				if (isset($step['aou_tool']) && $step['aou_tool'] != null){
					$tool = $step['aou_tool'];
				}
				if (isset($tool) && $tool != null){
					$replText = '<span class="tool">' . $tool['TOO_DESC_' . Yii::app()->session['lang']] . '</span> ';
					$text = str_replace('#tool', $replText, $text);
				}
				//if ($step['STE_STEP_DURATION']){
					$time = date('H:i:s', $stepDuration-3600);
					$replText = '<span class="time">' . $time . 'h</span> ';
					$mainText = str_replace('#time', $replText, $mainText);
					$text = str_replace('#time', $replText, $text);
				//}
				if (isset($step['STE_CELSIUS']) && $step['STE_CELSIUS'] > 0){
					$replText = '<span class="temp">' . $step['STE_CELSIUS'] . '°C</span> ';
					$mainText = str_replace('#temp', $replText, $mainText);
					$text = str_replace('#temp', $replText, $text);
				}
				if (isset($step['STE_KPA']) && $step['STE_KPA'] > 0){
					$replText = '<span class="pressure">' . $step['STE_KPA'] . 'kpa</span> ';
					$mainText = str_replace('#press', $replText, $mainText);
					$text = str_replace('#press', $replText, $text);
				}
				$mealStep->mainActionText = $mainText;
				$mealStep->actionText = $text;
				if ($this->debug) {
					$mealStep->mainActionText = $step['STE_STEP_NO'] . ' ' . $step['ATA_NO'] . ' ' . $step['AOU_PREP'] . ' ' . $mainText;
					$mealStep->actionText = $step['STE_STEP_NO'] . ' ' . $step['ATA_NO'] . ' ' . $step['AOU_PREP'] . ' ' . $text;
				}
				
				$mealStep->actionText = ($mealStep->stepNr+1) . '. ' . $mealStep->actionText;
				if ($mealStep->stepDuration == 0){
					$mealStep->percent = 1;
				} else {
					$mealStep->percent = 1 - ($mealStep->nextStepIn / $mealStep->stepDuration);
				}
				if ($mealStep->percent > 1){
					$mealStep->percent = 1;
				}
				
				if (isset($info->cookWith[$recipeNr]) && ($info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER) && isset($info->steps[$recipeNr])){
					$oldMealstep = $info->steps[$recipeNr];
					if ($oldMealstep->stepNr == $stepNr){
						//no step change, only update
						$mealStep->percent = $oldMealstep->percent;
						$mealStep->currentTemp = $oldMealstep->currentTemp;
						$mealStep->currentPress = $oldMealstep->currentPress;
					}
				}
				
				$currentSteps[] = $mealStep;
				
				if ($maxtime < $info->totalTime[$recipeNr] - $info->recipeUsedTime[$recipeNr]){
					$maxtime = $info->totalTime[$recipeNr] - $info->recipeUsedTime[$recipeNr];
				}
				//TODO: is this correct?
				if ($maxDiff < $timeDiff){
					$maxDiff = $timeDiff;
				}
			}
		}
		
		//error_log("loadSteps, mealStep->recipeNr:$mealStep->recipeNr, mealStep->stepNr:$mealStep->stepNr, end");
		if($allCookWithSet){
			$info->finishedIn = $maxtime;
			$info->timeDiffMax = $maxDiff;
		}
		$courseFinished = $info->courseFinished;
		$courseFinished[$info->courseNr] = $allFinished;
		$info->courseFinished = $courseFinished;
		
		if ($allFinished && count($courseFinished) == 1){
			$info->allFinished = true;
		}
		
		$info->steps = $currentSteps;
	}
	
	public function actionUpdateState($recipeNr){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionUpdateState, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		if (isset($info->cookWith[$recipeNr]) && isset($info->cookWith[$recipeNr][0]) && $info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER){
			$state = $this->readActionFromFirmware($info, $recipeNr);
			if (is_string($state) && strpos($state,"ERROR: ") !== false){
				echo '{"error":"' . substr($state, 7) . '"}';
				return;
			}
			
			$mealStep = $info->steps[$recipeNr];
			$mealStep->HWValues = $state;
			
			if ($info->steps[$recipeNr]->endReached){
				$additional=', T0:' . $state->T0;
				$additional.=', P0:' . $state->P0;
				$mealStep->currentTemp = $state->T0;
				$mealStep->currentPress = $state->P0;
				echo '{percent:1, restTime:0' .$additional . /*', startTime:'.$_GET['startTime'] .*/ '}';
			}
			
			if ($state->heaterHasPower == 0 && $state->SMODE!=self::STANDBY && $state->SMODE!=self::SCALE && $state->SMODE < self::HOT){
				echo '{"error":"Please press Power Button at front to enable Header & Motor"}';
				return;
			}
			if ($state->noPan == 1  && $state->SMODE!=self::STANDBY && $state->SMODE!=self::SCALE && $state->SMODE < self::HOT){
				echo '{"error":"Please add Pan"}';
				return;
			}
			
			$recipe = $info->meal->meaToCous[$info->courseNr]->course->couToRecs[$recipeNr]->recipe;
			if ($info->stepNumbers[$recipeNr] == -1){
				echo '{"error":"' . 'Not yet start cooking."}';
				return;
			}
			$step = $info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]];
			$executetTime = time() - $info->stepStartTime[$recipeNr];
			
			$currentTime = time();
			$stepStartTime = $info->stepStartTime[$recipeNr];
			$mealStep->nextStepIn = $stepStartTime - $currentTime + $mealStep->nextStepTotal;
			$mealStep->inTime = $stepStartTime + $mealStep->nextStepTotal < $currentTime;
			$restTime = $mealStep->nextStepIn;
			
			if (isset($mealStep->nextStepTotal) && $mealStep->nextStepTotal >0){
				$percent = 1 - ($restTime / $mealStep->nextStepTotal);
			} else {
				$percent = 1;
			}
			//$restTime = $state->STIME;
			$additional='';
			if ($state->SMODE==self::STANDBY || $state->SMODE==self::CUT || $state->SMODE==self::MOTOR || $state->SMODE==self::COOK || $state->SMODE==self::PRESSHOLD || $state->SMODE==self::COOK_TIMEEND || $state->SMODE==self::RECIPE_END){
				//$percent = 1 - ($state->STIME / $step['STE_STEP_DURATION']);
				if (isset($mealStep->nextStepTotal) && $mealStep->nextStepTotal >0){
					$percent = 1 - ($restTime / $mealStep->nextStepTotal);
				} else {
					$percent = 1;
				}
			} else if ($state->SMODE==self::SCALE || $state->SMODE==self::WEIGHT_REACHED){
				$weight = floor($state->W0);
				if ($step['STE_GRAMS'] != 0){
					$percent = $weight / $step['STE_GRAMS'];
				} else {
					$percent = 1;
				}
				$additional=', W0:' . $state->W0;
				if ($percent>0.05){ //>5%
					//$restTime = round(($executetTime / $percent) - $executetTime);
					//$text = '<span class=\"ingredient\">' . $step['ING_NAME_' . Yii::app()->session['lang']] . '</span> <span class=\"amount\">' . $step['STE_GRAMS'] . 'g' . '</span>: ' . round($percent*100) . '% / ' . round($state->W0) . 'g';
					$text = '<span class=\"ingredient\">' . $step['ING_NAME'] . '</span> <span class=\"amount\">' . $step['STE_GRAMS'] . 'g' . '</span>: ' . round($percent*100) . '% / ' . round($state->W0) . 'g';
					if ($percent>1.05){
						$text = '<span class=\"toMuch\">' . $text . '</span>';
					}
					$additional .= ', text: "' . $text . '"';
				}
			} else if ($state->SMODE==self::HEADUP || $state->SMODE==self::HOT){
				/*
				if ($step['STE_CELSIUS'] != 0){
					$percent = $state->T0 / $step['STE_CELSIUS'];
				} else {
					$percent = 1;
				}
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
				*/
			} else if ($state->SMODE==self::COOLDOWN || $state->SMODE==self::COLD){
				/*
				$percent = $step['STE_CELSIUS'] / $state->T0; //TODO: correct?
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
				*/
			} else if ($state->SMODE==self::PRESSUP || $state->SMODE==self::PRESSURIZED){
				/*
				if ($step['STE_KPA'] != 0){
					$percent = $state->P0 / $step['STE_KPA'];
				} else {
					$percent = 1;
				}
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
				*/
			} else if ($state->SMODE==self::PRESSDOWN  || $state->SMODE==self::PRESSVENT ||$state->SMODE==self::PRESSURELESS){
				/*
				$percent = $step['STE_KPA'] / $state->P0; //TODO: correct?
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
				*/
			} else if ($state->SMODE==self::INPUT_ERROR){
				echo '{"error":"' . 'Input Error' . '"}';
				return;
			} else if ($state->SMODE==self::EMERGANCY_SHUTDOWN){
				echo '{"error":"' . 'Emergency shutdown!'  . '"}';
				return;
			} else if ($state->SMODE==self::MOTOR_OVERLOAD){
				echo '{"error":"' . 'Motor overload'  . '"}';
				return;
			} else {
				echo '{"error":"' . 'Unknown EveryCook State/Mode:' . $state->SMODE . '"}';
				return;
			}
			$percent = round($percent, 2);
			if ($state->SMODE >= 30 && $state->SMODE <= 39){
				//Auto Next:
				if ($state->SMODE == self::WEIGHT_REACHED){
					//weight value are to "jummpy", so goto next if reached weight immedialy for now
					if ($percent>=0.95 && $percent<=1.05){
						$additional.=', gotoNext: true';
					} else {
						$mealStep->weightReachedTime = 0;
					}
					/* alernative logic
					//Wait 5 Sec with only small changes (between 90% and 110%)
					if ($percent>=0.90 && $percent<=1.10 && $mealStep->weightReachedTime != 0){
						if ($currentTime - $mealStep->weightReachedTime >=5){
							$additional.=', gotoNext: true';
						} else {
							$additional.=', gotoNextTime: ' . ($currentTime - $mealStep->weightReachedTime);
						}
					} else if ($percent>=0.95 && $percent<=1.05){
						$mealStep->weightReachedTime = $currentTime;
						$additional.=', gotoNextTime: 5';
					} else {
						$mealStep->weightReachedTime = 0;
					}
					*/
					/* old "exact" logic
					if ($percent>=0.95 && $percent<=1.05){
						//Wait 5 Sec with no change
						if ($mealStep->percent == $percent && $mealStep->weightReachedTime != 0){
							if ($currentTime - $mealStep->weightReachedTime >=5){
								$additional.=', gotoNext: true';
							} else {
								$additional.=', gotoNextTime: ' . ($currentTime - $mealStep->weightReachedTime);
							}
						} else {
							$mealStep->weightReachedTime = $currentTime;
							$additional.=', gotoNextTime: 5';
						}
					} else {
						$mealStep->weightReachedTime = 0;
					}
					*/
				} else {
					$additional.=', gotoNext: true';
				}
			}
			
			$mealStep->percent = $percent;
			$mealStep->nextStepIn = $restTime;
			
			$cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
			//error_log("actionUpdateState save, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
			if ($this->cookingInfoChangeCounter == $cookingInfoChangeCounter){
				Functions::saveToCache(self::COOKING_INFOS, $info);
				Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, $cookingInfoChangeCounter+1);
			} else {
				//TODO: concurrentModification Exception
			}
			
			$additional.=', T0:' . $state->T0;
			$additional.=', P0:' . $state->P0;
			$additional.=', SMODE:' . $state->SMODE;
			
			echo '{percent:' . $percent . ', restTime:' . $restTime .$additional . ', startTime:'.$_GET['startTime'] . ', SID:' . $state->SID . '}';
			
			//{"T0":100,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":30,"SMODE":10,"SID":0}
		} else {
			echo '{"error":"cook with not yet set"}';
			return;
		}
	}
	
	private function sendActionToFirmware($info, $recipeNr){
		//TODO: remove return
		//return;
		
		try{
			if (isset($info->cookWith[$recipeNr]) && count($info->cookWith[$recipeNr])>0 && $info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER){
				if (isset($info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]])){
					$step = $info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]];
					if ($info->steps[$recipeNr]->endReached){
						$command='{"T0":0,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":0,"SMODE":'.self::RECIPE_END.',"SID":'.$step['detailStepNr'].'}';
					} else {
						$command='{"T0":'.$step['STE_CELSIUS'].',"P0":'.$step['STE_KPA'].',"M0RPM":'.$step['STE_RPM'].',"M0ON":'.$step['STE_STIR_RUN'].',"M0OFF":'.$step['STE_STIR_PAUSE'].',"W0":'.$step['STE_GRAMS'].',"STIME":'.$step['CALC_DURATION'].',"SMODE":'.$step['STT_ID'].',"SID":'.$step['detailStepNr'].'}';
					}
					if ($this->debug){
						echo '<script type="text/javascript"> if(console && console.log){ console.log(\'sendActionToFirmware, command: '.$command.'\')}</script>';
					}
					
					$dest = $info->cookWith[$recipeNr];
					//TODO: remove return
					//return;
					if ($dest[0] == self::COOK_WITH_LOCAL){
						//error_log("sendActionToFirmware, local: $command");
						$fw = fopen(Yii::app()->params['deviceWritePath'], "w");
						if (fwrite($fw, $command)) {
						} else {
							//TODO error an send command...
						}
						fclose($fw);
					} else if ($dest[0] == self::COOK_WITH_IP){
						//error_log("sendActionToFirmware, ip: $command");
						require_once("remotefileinfo.php");
						$inhalt=remote_fileheader('http://'.$dest[2].Yii::app()->params['deviceWriteUrl'].$command); //remote_file
						if (is_string($inhalt) && strpos($inhalt, 'ERROR: ') !== false){
							//TODO error an send command...
						}
					} else {
						//error_log("sendActionToFirmware, command not send, destination was $dest[0], command: $command");
					}
				} else {
					error_log('sendActionToFirmware, $info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]] not set');
					if ($this->debug){
						echo '<script type="text/javascript"> if(console && console.log){';
						echo 'console.log(\'sendActionToFirmware, $info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]] not set\');';
						//echo 'console.log(\'sendActionToFirmware, count($info->meal->meaToCous[$info->courseNr]->course->couToRecs['.$recipeNr.']->recipe->steps) = '.count($info->meal->meaToCous[$info->courseNr]->course->couToRecs[$recipeNr]->recipe->steps).'\');';
						//echo 'console.log(\'sendActionToFirmware, $info->stepNumbers['.$recipeNr.'] = '.$info->stepNumbers[$recipeNr].'\');';
						echo '}</script>';
					}
				}
			} else {
				error_log('sendActionToFirmware, isset($info->cookWith['.$recipeNr.'])='.isset($info->cookWith[$recipeNr]).'\'');
				if (isset($info->cookWith[$recipeNr])){
					error_log('sendActionToFirmware, count($info->cookWith['.$recipeNr.'])='.count($info->cookWith[$recipeNr]).' \'');
					if (count($info->cookWith[$recipeNr])>0){
						error_log('sendActionToFirmware, $info->cookWith['.$recipeNr.'][0]= '.$info->cookWith[$recipeNr][0].'\'');
					}
				}
				if ($this->debug){
					echo '<script type="text/javascript"> if(console && console.log){';
					echo 'console.log(\'sendActionToFirmware, isset($info->cookWith['.$recipeNr.'])='.isset($info->cookWith[$recipeNr]).'\');';
					if (isset($info->cookWith[$recipeNr])){
						echo 'console.log(\'sendActionToFirmware, count($info->cookWith['.$recipeNr.'])='.count($info->cookWith[$recipeNr]).' \');';
						if (count($info->cookWith[$recipeNr])>0){
							echo 'console.log(\'sendActionToFirmware, $info->cookWith['.$recipeNr.'][0]= '.$info->cookWith[$recipeNr][0].'\');';
						}
					}
					echo '}</script>';
				}
			}
		} catch(Exception $e) {
			error_log('Exception occured in sendActionToFirmware for recipeIndex ' . $recipeNr . ', Exeption was: ' . $e);
			if ($this->debug) echo 'Exception occured in sendActionToFirmware for recipeIndex ' . $recipeNr . ', Exeption was: ' . $e;
		}
	}
	
	private function readActionFromFirmware($info, $recipeNr){
		//TODO remove debug output
		//return json_decode('{"T0":100,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":5,"SMODE":1,"SID":' . $info->steps[$recipeNr]->stepNr . ',"heaterHasPower":1,"noPan":0}');
		
		$dest = $info->cookWith[$recipeNr];
		$inhalt = '';
		if ($dest[0] == self::COOK_WITH_LOCAL){
			$fw = fopen(Yii::app()->params['deviceReadPath'], "r");
			if ($fw !== false){
				while (!feof($fw)) {
					$inhalt .= fread($fw, 128);
				}
				fclose($fw);
			} else {
				//TODO: error on read status
				$inhalt = 'ERROR: $errstr ($errno)';
			}
		} else if ($dest[0] == self::COOK_WITH_IP){
			require_once("remotefileinfo.php");
			$inhalt=remote_file('http://'.$dest[2].Yii::app()->params['deviceReadUrl']);
		}
		
		//$inhalt='{"T0":100,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":5,"SMODE":1,"SID":0}';
		if (strpos($inhalt,"ERROR: ") !== false){
			$inhalt = str_replace("\r\n","", $inhalt);
			$inhalt = str_replace("<br />","", $inhalt);
			$inhalt = trim($inhalt);
			return $inhalt;
		} else {
			$jsonValue=json_decode($inhalt);
			return $jsonValue;
		}
	}
	
	private function sendStopToFirmware($info){
		for ($recipeNr=0; $recipeNr<count($info->meal->meaToCous[$info->courseNr]->course->couToRecs); ++$recipeNr){
			if (isset($info->cookWith[$recipeNr]) && $info->cookWith[$recipeNr]){
				$command='{"T0":0,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":0,"SMODE":'.self::RECIPE_END.',"SID":-1}';
				
				$dest = $info->cookWith[$recipeNr];
				
				if ($dest[0] == self::COOK_WITH_LOCAL){
					$fw = fopen(Yii::app()->params['deviceWritePath'], "w");
					if (fwrite($fw, $command)) {
					} else {
						//TODO error an send command...
					}
					fclose($fw);
				} else if ($dest[0] == self::COOK_WITH_IP){
					require_once("remotefileinfo.php");
					$inhalt=remote_fileheader('http://'.$dest[2].Yii::app()->params['deviceWriteUrl'].$command); //remote_file
					if (strpos($inhalt, 'ERROR: ') !== false){
						//TODO error an send command...
					}
				}
			}
		}
	}
	
	
	private function calcHeadUpTime($info, $recipeNr, $T_start, $T_end){
		$P_heating = 800; //W
		$cp_H2O=$info->physics['cp_H2O']; //4.18 J/g*K
		$cp_lipid=$info->physics['cp_lipid']; //1.8 J/g*K
		$cp_prot=$info->physics['cp_prot']; //1.7 J/g*K
		$cp_carb=$info->physics['cp_carb']; //1.3 J/g*K
		
		if ($this->debug) {echo 'calcHeadUpTime: from: ' . $T_start . ', to: ' . $T_end . '...' . "<br>\n";}
		$m_H2O = 0.0;
		$m_lipid = 0.0;
		$m_prot = 0.0;
		$m_carb = 0.0;
		foreach($info->ingredientWeightInPan[$recipeNr] as $ing_id=>$weight){
			$nutrientData = $info->ingredientIdToNutrient[$ing_id];
			
			$m_H2O += $nutrientData->NUT_WATER * $weight / 100.0;
			$m_lipid += $nutrientData->NUT_LIPID * $weight / 100.0;
			$m_prot += $nutrientData->NUT_PROT * $weight / 100.0;
			$m_carb += $nutrientData->NUT_CARB * $weight / 100.0;
			
			if ($this->debug) {echo "\ting_id: " . $ing_id . ', weight: ' . $weight . "<br>\n";}
			if ($this->debugErrorLog) {error_log("calcHeadUpTime:\ting_id: " . $ing_id . ', weight: ' . $weight);}
		}
		if ($this->debug) {echo "\tm_H2O: " . $m_H2O . ', m_lipid: ' . $m_lipid . ', m_prot: ' . $m_prot . ', m_carb: ' . $m_carb . "<br>\n";}
		if ($this->debugErrorLog) {error_log("calcHeadUpTime:\tm_H2O: " . $m_H2O . ', m_lipid: ' . $m_lipid . ', m_prot: ' . $m_prot . ', m_carb: ' . $m_carb);}
		try {
			$t_heatup=($cp_H2O*$m_H2O+$cp_lipid*$m_lipid+$cp_prot*$m_prot+$cp_carb*$m_carb)*($T_end-$T_start)/$P_heating;
			
			//Add standard heatUp time for pan 1700g metal: 0.48 J / g*K
			$cp_pan = 0.48;
			$m_pan = 1700;
			$t_pan = ($cp_pan*$m_pan) * ($T_end-$T_start)/$P_heating;
			
			$t_heatup += $t_pan;
			if ($this->debug) {echo "$t_heatup=($cp_H2O*$m_H2O+$cp_lipid*$m_lipid+$cp_prot*$m_prot+$cp_carb*$m_carb)*($T_end-$T_start)/$P_heating + $t_pan";}
			if ($this->debugErrorLog) {error_log("calcHeadUpTime: $t_heatup=($cp_H2O*$m_H2O+$cp_lipid*$m_lipid+$cp_prot*$m_prot+$cp_carb*$m_carb)*($T_end-$T_start)/$P_heating + $t_pan");}
		} catch(Exception $e) {
			$t_heatup = -1;
		}
		if ($this->debug) {echo 'Done! Time is: ' . $t_heatup . "<br>\n";}
		return $t_heatup;
	}
	
	private function calcCoolDownTime($info, $recipeNr, $T_start, $T_end){
		$P_cooling = -30; //W
		$cp_H2O=$info->physics['cp_H2O']; //4.18 J/g*K
		$cp_lipid=$info->physics['cp_lipid']; //1.8 J/g*K
		$cp_prot=$info->physics['cp_prot']; //1.7 J/g*K
		$cp_carb=$info->physics['cp_carb']; //1.3 J/g*K
		
		if ($this->debug) {echo 'calcCoolDownTime: from: ' . $T_start + ', to: ' + $T_end + '...' . "<br>\n";}
		$m_H2O = 0.0;
		$m_lipid = 0.0;
		$m_prot = 0.0;
		$m_carb = 0.0;
		foreach($info->ingredientWeightInPan[$recipeNr] as $ing_id=>$weight){
			$nutrientData = $info->ingredientIdToNutrient[$ing_id];
			
			$m_H2O += $nutrientData->NUT_WATER * $weight / 100.0;
			$m_lipid += $nutrientData->NUT_LIPID * $weight / 100.0;
			$m_prot += $nutrientData->NUT_PROT * $weight / 100.0;
			$m_carb += $nutrientData->NUT_CARB * $weight / 100.0;
			
			if ($this->debug) {echo "\ting_id: " + $ing_id + ', weight: ' + $weight . "<br>\n";}
		}
		if ($this->debug) {echo "\tm_H2O: " + $m_H2O + ', m_lipid: ' + $m_lipid + ', m_prot: ' + $m_prot + ', m_carb: ' + $m_carb . "<br>\n";}
		try {
			$t_cooldown=($cp_H2O*$m_H2O+$cp_lipid*$m_lipid+$cp_prot*$m_prot+$cp_carb*$m_carb)*($T_end-$T_start)/$P_heating;
		} catch(Exception $e) {
			$t_cooldown = -1;
		}
		if ($this->debug) {echo 'Done! Time is: ' . $t_cooldown . "<br>\n";}
		return $t_cooldown;
	}
	
	private function calcPressUpTime($info, $recipeNr, $T_start, $P_end){
		if ($this->debug) {echo "calcPressUpTime start temp:$T_start, dest press:$P_end";}
		$heatUpTime = $this->calcHeadUpTime($info, $recipeNr, $T_start, 100);
		//PresUp speed: 0.45kPa/sec
		$pressUpTime = $P_end / 0.45;
		if ($this->debugErrorLog) {error_log("calcPressUpTime: heatUpTime:$heatUpTime + static wait (befor PressUpBegin): 120 + pressUpTime:$pressUpTime ($P_end / 0.45)");}
		return $heatUpTime + 120 + $pressUpTime;
	}
	
	public function actionNextCourse(){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionNextCourse, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		$this->actionGotoCourse($info->courseNr + 1);
	}
	
	public function actionAbort() {
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionAbort, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		$this->sendStopToFirmware($info);
		$info->allFinished = true;
		Functions::saveToCache(self::COOKING_INFOS, NULL);
		Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, $this->cookingInfoChangeCounter+1);
		$this->checkRenderAjax('abort');
	}

	public function actionIndex(){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionIndex, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		$this->loadSteps($info);
		
		$allCookWithSet = true;
		foreach($info->steps as $mealStep){
			if (count($info->cookWith[$mealStep->recipeNr])<=1){
				$allCookWithSet = false;
			}
		}
		if ($allCookWithSet){
			$this->checkRenderAjax('index', array('info'=>$info));
		} else {
			$this->forwardTo(array('overview'));
			//$this->checkRenderAjax('overview', array('info'=>$info));
		}
	}
	
	public function actionSave(){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionSave, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		if(!isset($info) || $info == null){
			$this->actionStart();
			return;
		}
		if(isset($_POST['cookwith'])){
			$cookWith = $info->cookWith;
			foreach($_POST['cookwith'] as $i=>$val){
			//for($i=0; $i<count($_POST['cookwith']);++$i){
				if ($val === 'remote'){
					$cookWith[$i] = array(self::COOK_WITH_IP,self::COOK_WITH_EVERYCOOK_COI,$_POST['remoteip'][$i]);
				} else if ($val == self::COOK_WITH_EVERYCOOK_COI){
					$cookWith[$i] = array(self::COOK_WITH_LOCAL,self::COOK_WITH_EVERYCOOK_COI);
				} else {
					$cookWith[$i] = array(self::COOK_WITH_OTHER, $val);
				}
			}
			$info->cookWith = $cookWith;
		}
		$this->loadSteps($info);
		
		$cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionSave save, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		if ($this->cookingInfoChangeCounter == $cookingInfoChangeCounter){
			Functions::saveToCache(self::COOKING_INFOS, $info);
			Functions::saveToCache(self::COOKING_INFOS_CHANGEAMOUNT, $cookingInfoChangeCounter+1);
		} else {
			//TODO: concurrentModification Exception
		}
		
		$allCookWithSet = true;
		foreach($info->steps as $mealStep){
			if (count($info->cookWith[$mealStep->recipeNr])<=1){
				$allCookWithSet = false;
			}
		}
		if ($allCookWithSet){
			$this->forwardTo(array('index'));
			//$this->checkRenderAjax('index', array('info'=>$info));
		} else {
			$this->checkRenderAjax('overview', array('info'=>$info));
		}
	}
	
	public function actionOverview() {
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionOverview, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		$this->loadSteps($info);
		$this->checkRenderAjax('overview', array('info'=>$info));
	}
	
	public function actionPrev() {
		//TODO is a "back" action needed?
		$this->checkRenderAjax('prev');
	}
	
	public function actionEnd(){
		//TODO stop cooking
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionEnd, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		sendStopToFirmware($info);
		$this->checkRenderAjax('end');
	}
	
	public function actionVote($recipeNr, $value){
		$info = Functions::getFromCache(self::COOKING_INFOS);
		if (!isset($info)){
			$this->checkRenderAjax('error', array('errorMsg'=>$this->trans->COOKASISSTANT_ERROR_NO_RECIPE));
			return;
		}
		$this->cookingInfoChangeCounter = Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT);
		//error_log("actionVote, cookingInfoChangeCounter: ". Functions::getFromCache(self::COOKING_INFOS_CHANGEAMOUNT));
		if (isset($info->voted[$recipeNr]) && $info->voted[$recipeNr]>0){
			$vote=RecipeVotings::model()->findByPk($info->voted[$recipeNr]);
		} else {
			$vote = null;
		}
		if ($vote===null){
			$vote = new RecipeVotings();
			$vote->PRF_UID = Yii::app()->user->id;
			$vote->MEA_ID = $info->meal->MEA_ID;
			$vote->COU_ID = $info->meal->meaToCous[$info->courseNr]->course->COU_ID;
			$vote->REC_ID = $info->meal->meaToCous[$info->courseNr]->course->couToRecs[$recipeNr]->recipe->REC_ID;
			$vote->RVO_COOK_DATE = time();
		}
		$vote->RVO_VALUE = $value;
		unset($vote->RVR_ID);
		unset($vote->RVO_REASON);
		
		if ($vote->save()){
			$info->voted[$recipeNr] = $vote->RVO_ID;
			if ($value > 0){
				echo '{sucessfull: true}';
			} else {
				$reasons = Yii::app()->db->createCommand()->select('RVR_ID,RVR_DESC_'.Yii::app()->session['lang'])->from('recipe_voting_reasons')->order('RVR_DESC_'.Yii::app()->session['lang'])->queryAll();
				$reasons = CHtml::listData($reasons,'RVR_ID','RVR_DESC_'.Yii::app()->session['lang']);
				$reasons['other'] = $this->trans->COOKASISSTANT_VOTE_REASON_OTHER;
				$this->checkRenderAjax('vote', array(
					'model'=>$vote,
					'reasons'=>$reasons,
				), 'none');
			}
		} else {
			print_r($vote->getErrors());
			//throw new CHttpException(500, 'Error saving vote...');
		}
	}
	
	public function actionVoteReason($RVO_ID){
		$vote=RecipeVotings::model()->findByPk($RVO_ID);
		if ($vote===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if(isset($_POST['RecipeVotings'])){
			$vote->setScenario('reason');
			$vote->attributes = $_POST['RecipeVotings']; //$vote->RVR_ID / $vote->RVO_REASON
			if ($vote->RVR_ID == 'other'){
				$vote->RVR_ID = 0;
			}
			if ($vote->save()){
				echo '{sucessfull: true}';
				return;
			}
		}
		throw new CHttpException(500, 'Error update vote for reason...');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Meals::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}