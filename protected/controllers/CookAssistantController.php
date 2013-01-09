<?php

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
	const TEMP_DEFAULT_START = 20;
	
	public function actionStart(){
		if (isset($_GET['id'])){
			$meal = $this->loadModel($_GET['id'], true);
			
			$info = $this->loadInfoForCourse($meal, 0);
			
			Yii::app()->session['cookingInfo'] = $info;
			
			//$this->checkRenderAjax('index', array('info'=>$info));
			$this->checkRenderAjax('overview', array('info'=>$info));
			//$this->redirect('cookassistant/index');
		} else {
			echo "Error: Please select meal to cook.";
		}
	}

	public function actionGotoCourse($number){
		$info = Yii::app()->session['cookingInfo'];
		$meal = $info->meal;
		if (isset($meal->meaToCous[$number])){
			$info = $this->loadInfoForCourse($meal, $number);
			
			Yii::app()->session['cookingInfo'] = $info;
		} else {
			//TODO error course not exist for meal
		}
		
		$this->checkRenderAjax('index', array('info'=>$info));
	}

	private function loadInfoForCourse($meal, $courseNumber){
		$course = $meal->meaToCous[$courseNumber]->course;
		
		$info = new CookAsisstantInfo();
		$info->meal = $meal;
		$info->courseNr = $courseNumber;
		$info->course = $course;
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
		
		$ingredientIdToNutrient = array();
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$stepNumbers[] = -1;
			$stepStartTime[] = time();
			$cookWith[] = array();
			$totalTime = 0;
			$recipe = $course->couToRecs[$recipeNr]->recipe;
			$recipe->REC_IMG = NULL;
			
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
					$step->ingredient->ING_IMG = null;
					//$ingredientIdToNutrient[$step->ingredient->ING_ID] = $step->ingredient->NUT_ID;
					$ingredientIdToNutrient[$step->ingredient->ING_ID] = $step->ingredient->nutrientData;
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
		$info->finishedIn = $maxTime;
		$info->recipeUsedTime = $recipeUsedTime;
		$info->totalWeight = $totalWeight;
		$info->ingredientIdToNutrient = $ingredientIdToNutrient;
		$info->voted = $voted;
		
		$this->loadSteps($info);
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$this->sendActionToFirmware($info, $recipeNr);
		}
		return $info;
	}
	
	public function actionNext($recipeNr, $step){
		$info = Yii::app()->session['cookingInfo'];
		
		if (isset($info->stepNumbers[$recipeNr])){
			if ($info->stepNumbers[$recipeNr] == $step){
				if (isset($info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]+1])){
					$currentTime = time();
					$course = $info->course;
					if (!$info->started){
						for ($recipeNrLoop=0; $recipeNrLoop<count($course->couToRecs); ++$recipeNrLoop){
							$info->stepStartTime[$recipeNrLoop] = $currentTime;
						}
					}
					$info->started = true;
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
						
						$mealStep = $info->steps[$recipeNr];
						
						$stepAttributes = $info->recipeSteps[$recipeNr][$step];
						
						if ($mealStep->ingredientId != 0){
							if (isset($mealStep->HWValues['W0']) && $mealStep->HWValues['W0']>0){
								$ingWeight = $mealStep->HWValues['W0'];
							} else if ($info->cookWith[$recipeNr][1] != self::COOK_WITH_EVERYCOOK_COI){
								$ingWeight = $stepAttributes['STE_GRAMS'];
							} else {
								$ingWeight = 0;
							}
							
							if ($ingWeight>0){
								if (!isset($info->ingredientWeight[$recipeNr])){
									$info->ingredientWeight[$recipeNr] = array();
								}
								if (!isset($info->ingredientWeight[$recipeNr][$mealStep->ingredientId])){
									$info->ingredientWeight[$recipeNr][$mealStep->ingredientId] = $ingWeight;
								} else {
									$info->ingredientWeight[$recipeNr][$mealStep->ingredientId] += $ingWeight;
								}
							}
						}
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
					$this->sendActionToFirmware($info, $recipeNr);
					Yii::app()->session['cookingInfo'] = $info;
				} else {
					//TODO recipe ended, no next step available.
				}
			} else {
				//Don't change step, it is already next (->F5), but update time
				$this->loadSteps($info);
			}
		} else {
			//TODO error recipeNr doesnt exist.
		}
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
	private function isCookInStateReverse($old, $oldTool, $new, $newTool){
		if ($this->debug) {echo 'isReverse: old: ' . $old .', new: ' . $new . "<br>\r\n";}
		
		/*
		$isReverse = true;
		if ($old !== $new){
			$old = json_decode($old);
			$new = json_decode($new);
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
						* /
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
							* /
							if ($newVal !== $value){
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
		*/
		return false;
	}
	
	private function loadSteps($info){
		$course = $info->course;
		$currentSteps = array();
		$currentTime = time();
		$maxtime = 0;
		$maxDiff = 0;
		//$actionsOuts = null;
		$tools = null;
		$cookIns = null;
		$allFinished = true;
		for ($recipeNr=0; $recipeNr<count($course->couToRecs); ++$recipeNr){
			$mealStep = new CookAsisstantStep();
			$stepNr = $info->stepNumbers[$recipeNr];
			$recipe = $course->couToRecs[$recipeNr]->recipe;
			
			$mealStep->recipeNr = $recipeNr;
			$mealStep->stepNr = $stepNr;
			$mealStep->recipeName = $recipe->__get('REC_NAME_'.Yii::app()->session['lang']);
			$stepStartTime = $info->stepStartTime[$recipeNr];
			if ($stepNr == -1){
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
					$cookInState = '{"1":{"lid":true}}'; //Annahme deckel geschlossen (bei coi_id==1)
					$cookInState = json_decode($cookInState);
					$lastStepAttributes = null;
					
					
					//simulate ingredient/total weight and current temp for time calculation
					$info->ingredientWeight[$recipeNr] = array();
					$info->totalWeight[$recipeNr] = 0;
					$currentTemp = self::TEMP_DEFAULT_START;
					
					foreach($recipe->steps as $step){
						$actionIn = $step->actionIn;
						$detailSteps = array();
						$detailSteps['all'] = array();
						
						foreach($actionIn->ainToAous as $ainToAou){
							if ($ainToAou->COI_ID == $coi_id){
								$actionsOut = $ainToAou->actionsOut;
								$stepAttributes = $step->attributes;
								$stepAttributes['ATA_COI_PREP'] = $ainToAou['ATA_COI_PREP'];
								$stepAttributes['ATA_NO'] = $ainToAou['ATA_NO'];
								$stepAttributes = array_merge($stepAttributes, $actionIn->attributes);
								$stepAttributes = array_merge($stepAttributes, $actionsOut->attributes);
								if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_USE_STEP){
									$stepAttributes['AOU_DURATION'] = $stepAttributes['STE_STEP_DURATION'];
								}
								if ($stepAttributes['AOU_DUR_PRO'] > 0 && isset($stepAttributes['STE_GRAMS']) && $stepAttributes['STE_GRAMS']>0){
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'] * ( $stepAttributes['STE_GRAMS'] / $stepAttributes['AOU_DUR_PRO']) ;
								} else if ($stepAttributes['AOU_DUR_PRO'] > 0 && (!isset($stepAttributes['STE_GRAMS']) || $stepAttributes['STE_GRAMS']<=0)){
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'] * ( $info->totalWeight[$recipeNr] / $stepAttributes['AOU_DUR_PRO']) ;
								} else if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_HEADUP){
									$stepAttributes['CALC_DURATION'] = $this->calcHeadUpTime($info, $recipeNr, $currentTemp, $stepAttributes['STE_CELSIUS']);
								} else if ($stepAttributes['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_COOLDOWN){
									$stepAttributes['CALC_DURATION'] = $this->calcCoolDownTime($info, $recipeNr, $currentTemp, $stepAttributes['STE_CELSIUS']);
								} else {
									$stepAttributes['CALC_DURATION'] = $stepAttributes['AOU_DURATION'];
								}
								
								if (isset($step->ingredient)){
									$stepAttributes['ING_ID'] = $step->ingredient->ING_ID;
									$stepAttributes['ING_IMG_AUTH'] = $step->ingredient->ING_IMG_AUTH;
									$stepAttributes['ING_NAME_' . Yii::app()->session['lang']] = $step->ingredient->__get('ING_NAME_' . Yii::app()->session['lang']);
									
									//for simulation:
									if (isset($step['STE_GRAMS']) && $step['STE_GRAMS']>0){
										if (!isset($info->ingredientWeight[$recipeNr][$step->ingredient->ING_ID])){
											$info->ingredientWeight[$recipeNr][$step->ingredient->ING_ID] = $step['STE_GRAMS'];
										} else {
											$info->ingredientWeight[$recipeNr][$step->ingredient->ING_ID] += $step['STE_GRAMS'];
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
									$stepAttributes['ING_NAME_' . Yii::app()->session['lang']] = '';
								}
								if (isset($step->STE_CELSIUS) && $step->STE_CELSIUS>0){
									//for simulation
									$currentTemp = $step->STE_CELSIUS;
								}
								
								$stepAttributes['step_tool'] = $step->tool;
								//TOO_ID is from ActionsOut
								if ($stepAttributes['TOO_ID'] == -1 && isset($step->tool)){
									$stepAttributes['TOO_ID'] = $step->tool->TOO_ID;
								}
								if(isset($tools[$stepAttributes['TOO_ID']])){
									$stepAttributes['aou_tool'] = $tools[$stepAttributes['TOO_ID']];
								} else {
									$stepAttributes['aou_tool'] = null;
								}
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
						foreach($detailSteps['all']  as $stepAttributes){
							$prep = $stepAttributes['ATA_COI_PREP'];
							$add = false;
							
							if ($this->debug) {echo 'Step ' . $stepAttributes['STE_STEP_NO'] . ' ' . $stepAttributes['ATA_NO'] . ', conditionNeeded: ' . ($conditionNeeded?'true':'false') . ', prep: ' . $prep . "<br>\r\n";}
							
							if ($conditionNeeded || ($prep != 1 && $prep != 2)){
								if ($prep == 3){
									if ($lastStepAttributes != null && $this->isCookInStateReverse($lastStepAttributes['AOU_CIS_CHANGE'], ($lastStepAttributes['aou_tool']==null)?$lastStepAttributes['step_tool']:$lastStepAttributes['aou_tool'], $stepAttributes['AOU_CIS_CHANGE'], ($stepAttributes['aou_tool']==null)?$stepAttributes['step_tool']:$stepAttributes['aou_tool'])){
										//TODO test, isCookInStateReverse function not yet created.
										if ($stepAttributes['AOU_PREP'] == 'Y'){
											--$detailStepNr;
											array_pop($prepareSteps);
										} else {
											array_pop($otherSteps);
										}
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
						}
						$lastStepAttributes = $stepAttributes;
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
					
					//Reset ingredientWeight / totalWeight after simulation
					$info->ingredientWeight[$recipeNr] = array();
					$info->totalWeight[$recipeNr] = 0;
				} else {
					//Cook in not defined
					
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
				
				if (isset($info->steps[$recipeNr])){
					$oldMealstep = $info->steps[$recipeNr];
					if (isset($oldMealstep) && $oldMealstep != null){
						$currentTemp = $oldMealstep->currentTemp;
					}
					if (!isset($currentTemp) || $currentTemp == null){
						$currentTemp = self::TEMP_DEFAULT_START;
					}
				} else {
					$oldMealstep = null;
					$currentTemp = self::TEMP_DEFAULT_START;
				}
				
				if ($cookIns == null){
					$cookIns = Yii::app()->db->createCommand()->from('cook_in')->queryAll();
					$cookInsIndexedArray = array();
					foreach($cookIns as $tool){
						$cookInsIndexedArray[$tool['COI_ID']] = $tool;
					}
					$cookIns = $cookInsIndexedArray;
				}
				
				if ($step['AOU_DUR_PRO'] > 0 && (!isset($step['STE_GRAMS']) || $step['STE_GRAMS']<=0)){
					$stepDuration = $step['AOU_DURATION'] * ( $info->totalWeight[$recipeNr] / $step['AOU_DUR_PRO']) ;
					//TODO: total time anpassen????
				} else if ($step['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_HEADUP){
					$stepDuration = $this->calcHeadUpTime($info, $recipeNr, $currentTemp, $step['STE_CELSIUS']);
					//TODO: total time anpassen????
				} else if ($step['AOU_DURATION'] == self::STEP_DURATION_SPECIAL_CALC_COOLDOWN){
					$stepDuration = $this->calcCoolDownTime($info, $recipeNr, $currentTemp, $step['STE_CELSIUS']);
					//TODO: total time anpassen????
				} else {
					$stepDuration = $step['CALC_DURATION'];
				}
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
				if ($step['ING_ID'] > 0){
					$replText = '<span class="ingredient">' . $step['ING_NAME_' . Yii::app()->session['lang']] . '</span> ';
					$mainText = str_replace('#ingredient', $replText, $mainText);
					$text = str_replace('#ingredient', $replText, $text);
				}
				if ($step['STE_GRAMS']){
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
				if ($step['STE_STEP_DURATION']){
					$time = date('H:i:s', $step['STE_STEP_DURATION']-3600);
					$replText = '<span class="time">' . $time . 'h</span> ';
					$mainText = str_replace('#time', $replText, $mainText);
					$text = str_replace('#time', $replText, $text);
				}
				if ($step['STE_CELSIUS']){
					$replText = '<span class="temp">' . $step['STE_CELSIUS'] . '°C</span> ';
					$mainText = str_replace('#temp', $replText, $mainText);
					$text = str_replace('#temp', $replText, $text);
				}
				if ($step['STE_KPA']){
					$replText = '<span class="pressure">' . $step['STE_KPA'] . 'kpa</span> ';
					$mainText = str_replace('#pressure', $replText, $mainText);
					$text = str_replace('#pressure', $replText, $text);
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
		$info->finishedIn = $maxtime;
		$info->timeDiffMax = $maxDiff;
		
		$info->courseFinished[$info->courseNr] = $allFinished;
		
		$info->steps = $currentSteps;
	}
	
	public function actionUpdateState($recipeNr){
		//TODO: remove echo & return
		echo '{"error":"No machine found..."}';
		return;
		$info = Yii::app()->session['cookingInfo'];
		
		if (isset($info->cookWith[$recipeNr]) && $info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER){
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
				echo '{percent:1, restTime:0' .$additional . ', startTime:'.$_GET['startTime'] . '}';
				return;
			}
			
			$recipe = $info->course->couToRecs[$recipeNr]->recipe;
			$step = $info->recipeSteps[$info->stepNumbers[$recipeNr]];
			$executetTime = time() - $info->stepStartTime[$recipeNr];
			
			$currentTime = time();
			$stepStartTime = $info->stepStartTime[$recipeNr];
			$mealStep->nextStepIn = $stepStartTime - $currentTime + $mealStep->nextStepTotal;
			$mealStep->inTime = $stepStartTime + $mealStep->nextStepTotal < $currentTime;
			$restTime = $mealStep->nextStepIn;
			
			//$restTime = $state->STIME;
			$additional='';
			if ($state->SMODE==self::STANDBY || $state->SMODE==self::CUT || $state->SMODE==self::MOTOR || $state->SMODE==self::COOK || $state->SMODE==self::PRESSHOLD || $state->SMODE==self::COOK_TIMEEND || $state->SMODE==self::RECIPE_END){
				//$percent = 1 - ($state->STIME / $step['STE_STEP_DURATION']);
				$percent = 1 - ($restTime / $mealStep->nextStepTotal);
			} else if ($state->SMODE==self::SCALE || $state->SMODE==self::WEIGHT_REACHED){
				$weight = floor($state->W0);
				$percent = $weight / $step['STE_GRAMS'];
				$additional=', W0:' . $state->W0;
				if ($percent>0.05){ //>5%
					//$restTime = round(($executetTime / $percent) - $executetTime);
					$text = '<span class=\"ingredient\">' . $step['ING_NAME_' . Yii::app()->session['lang']] . '</span> <span class=\"amount\">' . $step['STE_GRAMS'] . 'g' . '</span>: ' . round($percent*100) . '% / ' . round($state->W0) . 'g';
					if ($percent>1.05){
						$text = '<span class=\"toMuch\">' . $text . '</span>';
					}
					$additional .= ', text: "' . $text . '"';
				}
			} else if ($state->SMODE==self::HEADUP || $state->SMODE==self::HOT){
				$percent = $state->T0 / $step['STE_CELSIUS'];
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
			} else if ($state->SMODE==self::COOLDOWN || $state->SMODE==self::COLD){
				$percent = $step['STE_CELSIUS'] / $state->T0; //TODO: correct?
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
			} else if ($state->SMODE==self::PRESSUP || $state->SMODE==self::PRESSURIZED){
				$percent = $state->P0 / $step['STE_KPA'];
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
			} else if ($state->SMODE==self::PRESSDOWN  || $state->SMODE==self::PRESSVENT ||$state->SMODE==self::PRESSURELESS){
				$percent = $step['STE_KPA'] / $state->P0; //TODO: correct?
				if ($percent>0.05){ //>5%
					$restTime = round(($executetTime / $percent) - $executetTime);
				}
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
					if ($percent>=0.95 && $percent<=1.05){
						//Wait 5 Sec with no change
						if ($mealStep->percent == $percent && $mealStep->weightReachedTime != 0){
							if ($currentTime - $mealStep->weightReachedTime >=5){
								$additional.=', gotoNext: true';
							}
						} else {
							$mealStep->weightReachedTime = $currentTime;
						}
					} else {
						$mealStep->weightReachedTime = 0;
					}
				} else {
					$additional.=', gotoNext: true';
				}
			}
			
			$mealStep->percent = $percent;
			$mealStep->nextStepIn = $restTime;
			
			$additional.=', T0:' . $state->T0;
			$additional.=', P0:' . $state->P0;
			echo '{percent:' . $percent . ', restTime:' . $restTime .$additional . ', startTime:'.$_GET['startTime'] . '}';
			
			//{"T0":100,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":30,"SMODE":10,"SID":0}
		}
	}
	
	private function sendActionToFirmware($info, $recipeNr){
		try{
			if (isset($info->cookWith[$recipeNr]) && count($info->cookWith[$recipeNr])>0 && $info->cookWith[$recipeNr][0]!=self::COOK_WITH_OTHER){
				if (isset($info->course->couToRecs[$recipeNr]->recipe->steps[$info->stepNumbers[$recipeNr]])){
					$step = $info->recipeSteps[$recipeNr][$info->stepNumbers[$recipeNr]];
					if ($info->steps[$recipeNr]->endReached){
						$command='{"T0":0,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":0,"SMODE":'.self::RECIPE_END.',"SID":0}';
					} else {
						$command='{"T0":'.$step['STE_CELSIUS'].',"P0":'.$step['STE_KPA'].',"M0RPM":'.$step['STE_RPM'].',"M0ON":'.$step['STE_STIR_RUN'].',"M0OFF":'.$step['STE_STIR_PAUSE'].',"W0":'.$step['STE_GRAMS'].',"STIME":'.$step['STE_STEP_DURATION'].',"SMODE":'.$step['STT_ID'].',"SID":'.$step['detailStepNr'].'}';
					}
					
					$dest = $info->cookWith[$recipeNr];
					//TODO: remove return
					return;
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
						if (is_string($inhalt) && strpos($inhalt, 'ERROR: ') !== false){
							//TODO error an send command...
						}
					}
				}
			}
		} catch(Exception $e) {
			if ($this->debug) echo 'Exception occured in sendActionToFirmware for recipeIndex ' . $recipeNr . ', Exeption was: ' . $e;
		}
	}
	
	private function readActionFromFirmware($info, $recipeNr){
		require_once("remotefileinfo.php");
		$inhalt=remote_file("http://10.0.0.1/db/hw/status");
		
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
		for ($recipeNr=0; $recipeNr<count($info->$course->couToRecs); ++$recipeNr){
			if (isset($info->cookWith[$recipeNr]) && $info->cookWith[$recipeNr]){
				$command='{"T0":0,"P0":0,"M0RPM":0,"M0ON":0,"M0OFF":0,"W0":0,"STIME":0,"SMODE":'.self::RECIPE_END.',"SID":0}';
				
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
		$P_heating = 1000; //W
		$cp_H2O=$info->physics['cp_H2O']; //4.18 J/g*K
		$cp_lipid=$info->physics['cp_lipid']; //1.8 J/g*K
		$cp_prot=$info->physics['cp_prot']; //1.7 J/g*K
		$cp_carb=$info->physics['cp_carb']; //1.3 J/g*K
		
		if ($this->debug) {echo 'calcHeadUpTime: from: ' . $T_start . ', to: ' . $T_end . '...' . "<br>\n";}
		$m_H2O = 0.0;
		$m_lipid = 0.0;
		$m_prot = 0.0;
		$m_carb = 0.0;
		foreach($info->ingredientWeight[$recipeNr] as $ing_id=>$weight){
			$nutrientData = $info->ingredientIdToNutrient[$ing_id];
			
			$m_H2O += $nutrientData->NUT_WATER * $weight / 100.0;
			$m_lipid += $nutrientData->NUT_LIPID * $weight / 100.0;
			$m_prot += $nutrientData->NUT_PROT * $weight / 100.0;
			$m_carb += $nutrientData->NUT_CARB * $weight / 100.0;
			
			if ($this->debug) {echo "\ting_id: " . $ing_id . ', weight: ' . $weight . "<br>\n";}
		}
		if ($this->debug) {echo "\tm_H2O: " . $m_H2O . ', m_lipid: ' . $m_lipid . ', m_prot: ' . $m_prot . ', m_carb: ' . $m_carb . "<br>\n";}
		try {
			$t_heatup=($cp_H2O*$m_H2O+$cp_lipid*$m_lipid+$cp_prot*$m_prot+$cp_carb*$m_carb)*($T_end-$T_start)/$P_heating;
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
		foreach($info->ingredientWeight[$recipeNr] as $ing_id=>$weight){
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
	
	
	
	public function actionNextCourse(){
		$info = Yii::app()->session['cookingInfo'];
		$this->actionGotoCourse($info->courseNr + 1);
	}
	
	public function actionAbort() {
		//TODO abort cooking
		$info = Yii::app()->session['cookingInfo'];
		sendStopToFirmware($info);
		$this->checkRenderAjax('abort');
	}

	public function actionIndex(){
		$info = Yii::app()->session['cookingInfo'];
		$this->loadSteps($info);
		$this->checkRenderAjax('index', array('info'=>$info));
	}
	
	public function actionOverview() {
		$info = Yii::app()->session['cookingInfo'];
		if(isset($_POST['cookwith'])){
			foreach($_POST['cookwith'] as $i=>$val){
			//for($i=0; $i<count($_POST['cookwith']);++$i){
				if ($val === 'remote'){
					$info->cookWith[$i] = array(self::COOK_WITH_IP,self::COOK_WITH_EVERYCOOK_COI,$_POST['remoteip'][$i]);
				} else if ($val == self::COOK_WITH_EVERYCOOK_COI){
					$info->cookWith[$i] = array(self::COOK_WITH_LOCAL,self::COOK_WITH_EVERYCOOK_COI);
				} else {
					$info->cookWith[$i] = array(self::COOK_WITH_OTHER, $val);
				}
			}
		}
		$this->loadSteps($info);
		Yii::app()->session['cookingInfo'] = $info;
		$this->checkRenderAjax('overview', array('info'=>$info));
	}
	
	public function actionPrev() {
		//TODO is a "back" action needed?
		$this->checkRenderAjax('prev');
	}
	
	public function actionEnd(){
		//TODO stop cooking
		$info = Yii::app()->session['cookingInfo'];
		sendStopToFirmware($info);
		$this->checkRenderAjax('end');
	}
	
	public function actionVote($recipeNr, $value){
		$info = Yii::app()->session['cookingInfo'];
		if (isset($info->voted[$recipeNr]) && $info->voted[$recipeNr]>0){
			$vote=RecipesVoting::model()->findByPk($info->voted[$recipeNr]);
		} else {
			$vote = null;
		}
		if ($vote===null){
			$vote = new RecipesVoting();
			$vote->PRF_UID = Yii::app()->user->id;
			$vote->MEA_ID = $info->meal->MEA_ID;
			$vote->COU_ID = $info->course->COU_ID;
			$vote->REC_ID = $info->course->couToRecs[$recipeNr]->recipe->REC_ID;
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
				$reasons = Yii::app()->db->createCommand()->select('RVR_ID,RVR_DESC_'.Yii::app()->session['lang'])->from('recipes_voting_reason')->order('RVR_DESC_'.Yii::app()->session['lang'])->queryAll();
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
		$vote=RecipesVoting::model()->findByPk($RVO_ID);
		if ($vote===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if(isset($_POST['RecipesVoting'])){
			$vote->setScenario('reason');
			$vote->attributes = $_POST['RecipesVoting']; //$vote->RVR_ID / $vote->RVO_REASON
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