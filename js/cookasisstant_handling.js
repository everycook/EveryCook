var glob = glob || {};

jQuery(function($){
	var startTime = [];
	var getValueTimeoutID=[];
	var getValueStartTime=[];
	var interval = -1;
	var errorCounter=[];
	function initTimer(type, contentParent){
		if (interval != -1){
			window.clearInterval(interval);
		}
		if (typeof(contentParent) === 'undefined') return;
		startTime = [];
		var currentTime = new Date().getTime();
		contentParent.find('.recipeStep').each(function(index){
			startTime[index] = currentTime;
		});
		errorCounter=[];
		interval = window.setInterval(updateTime,500);
		updateTime(true);
	}
	
	$('#page').bind('newContent.ajax_handling', function(e, type, contentParent) {
		initTimer(type, contentParent);
	});
	initTimer('initial', jQuery('#page'));
	
	function cancelFirmwareUpdate(i){
		getValueStartTime[i]=0;
	}
	glob.cancelFirmwareUpdate = cancelFirmwareUpdate;
	
	function showTime(elem, time){
		var timeStr = "";
		if (time<-3600000){
			timeStr += "-";
			time = Math.abs(time+3600000)-3600000;
		}
		//time = Math.round(time/1000)*1000;
		//time = Math.ceil(time/1000)*1000;
		var timeToShow = new Date(time);
		
		var val = timeToShow.getHours();
		if (val>0){
			if (val<10){
				timeStr += "0";
			}
			timeStr += val+":"
		}
		
		val = timeToShow.getMinutes();
		if (val<10){
			timeStr += "0";
		}
		timeStr += val+":"
		
		val = timeToShow.getSeconds();
		if (val<10){
			timeStr += "0";
		}
		timeStr += val;
		//timeStr += "." + timeToShow.getMilliseconds();
		
		elem.text(timeStr);
	}
	
	function updateTime(initialize){
		var currentTime = new Date().getTime();
		var started = jQuery('#started').val();
		var maxFinishedIn = 0;
		//jQuery('.finishTime').add('.nextTime').each(function(){
		jQuery('.recipeStep').each(function(index){
			var recipeStep = jQuery(this);
			var cookWith = recipeStep.find('input[name=cookWith]:first').val()!=0;
			if (started == ''){
				var nextTime = recipeStep.find('.nextTime:first span');
				var input = nextTime.parents('.nextTime:first').next();
				var restTime = parseInt(input.val());
				showTime(nextTime, restTime*1000 -3600000);
				
				var finishedInTime = recipeStep.find('.finishTime:first span');
				input = finishedInTime.parents('.finishTime:first').next();
				var restTime = parseInt(input.val());
				
				var masterTime = parseInt(jQuery('#finishTime').val());
				if (restTime == 0){
					finishedInTime.text('now');
					if (masterTime > maxFinishedIn || maxFinishedIn == 0){
						maxFinishedIn = masterTime;
					}
				} else {
					showTime(finishedInTime, currentTime+(restTime*1000));
					if (restTime+masterTime > maxFinishedIn || maxFinishedIn == 0){
						maxFinishedIn = restTime+masterTime;
					}
				}
			} else
			if (cookWith && !initialize){
				//load state
				//console.log('do update');
				glob.ShowActivity = false;
				var nextTime = recipeStep.find('.nextTime:first span');
				if (typeof(getValueStartTime[index]) ==='undefined' || getValueStartTime[index]==0){
					//if (nextTime.length>0){ //if not, the end step is reached
						getValueTimeoutID[index] = window.setTimeout("glob.cancelFirmwareUpdate("+index+");",3000);
						getValueStartTime[index] = currentTime;
						var url = recipeStep.find('input[name=UpdateCookAssistantLink]:first').val();
						url = glob.urlAddParamStart(url) + 'startTime=' + getValueStartTime[index];
						jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
								if (data.length == 0){
									return;
								}
								eval('var json = ' + data + ';');
								if (typeof(json.error) !== 'undefined'){
									if (errorCounter[index] == undefined){
										errorCounter[index] = 1;
									} else {
										++errorCounter[index];
									}
									if (errorCounter[index]<5){
										alert("Error while load State from EveryCook: " + json.error);
									} else {
										alert("CookAsisstant Terminated, no furter updates, please start again.");
										window.clearInterval(interval);
									}
								} else if (getValueStartTime[index] == json.startTime){
									if (nextTime.length>0){ //if not, the end step is reached
										var elem = recipeStep.find('.action .progress');
										if (json.percent>1){
											json.percent = 1;
										}
										elem.css('width', (json.percent*100) + '%');
										errorCounter[index] = 0;
										window.clearTimeout(getValueTimeoutID[index]);
										
										if (json.text){
											elem = recipeStep.find('.action .actionText');
											elem.html(json.text);
										}
										
										getValueStartTime[index]=0;
										//nextTime.text(json.restTime);
										showTime(nextTime, json.restTime*1000 -3600000);
										if (json.restTime<0){
											nextTime.parents('.nextTime:first').addClass('toLate');
											if (typeof(json.W0) !== 'undefined'){
												
											} else {
												recipeStep.find('.nextStep.mustWait').removeClass('mustWait');
											}
										} else {
											nextTime.parents('.nextTime:first').removeClass('toLate');
										}
										
										var ing_image = recipeStep.find('.action img');
										if (ing_image.length>0){
											ing_image.css('opacity', 1-json.percent);
										}
										
										//if (json.percent >= 1){
										if (json.gotoNext){
											var nextLink = recipeStep.find('.nextStep:first');
											//if (nextLink.is('.autoClick')){
												var link = nextLink.attr('href');
												if (link.indexOf('#') == 0){
													window.location.hash=link;
												} else {
													window.location.pathname=link;
												}
												//console.log(link);
											//}
										}
										
										
									}
									var tempInfo = recipeStep.find('div.temp');
									tempInfo.find('span.temp').text(json.T0);
									tempInfo.find('span.press').text(json.P0);
								}
							},
							'error':function(xhr){
								//ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
							},
							'complete':function(){
							}
						});
					//}
				}
				if (nextTime.length>0){ //if not, the end step is reached
					var input = nextTime.parents('.nextTime:first').next();
					var inputTotal = input.next();
					var stepTotal = parseInt(inputTotal.val());
					//Update finishedIn Time
					var finishedInTime = recipeStep.find('.finishTime:first span');
					input = finishedInTime.parents('.finishTime:first').next();
					var timeDiff = stepTotal - Math.round((currentTime-startTime[index]) / 1000);
					var restTime = parseInt(input.val()) - stepTotal + timeDiff;;
						
					var inputTotal = input.next();
					var inputTotalVal = parseInt(inputTotal.val());
					var inTime = restTime > inputTotalVal;
					if (!inTime){
						finishedInTime.parents('.finishTime:first').addClass('toLate');
						finishedInTime.text();
						showTime(finishedInTime, currentTime+(inputTotalVal*1000));
						nextTime.parents('.nextTime:first').addClass('toLate');
						restTime = inputTotalVal;
					} else {
						finishedInTime.parents('.finishTime:first').removeClass('toLate');
						finishedInTime.text(restTime);
						showTime(finishedInTime, currentTime+(restTime*1000));
						nextTime.parents('.nextTime:first').removeClass('toLate');
					}
					if (restTime > maxFinishedIn || maxFinishedIn == 0){
						maxFinishedIn = restTime;
					}
				}
			} else {
				//Update nextStepIn Time
				var nextTime = recipeStep.find('.nextTime:first span');
				if (nextTime.length>0){ //if not, the end step is reached
					var input = nextTime.parents('.nextTime:first').next();
					var restTime = Math.round(input.val() - ((currentTime-startTime[index]) / 1000));
					//nextTime.text(restTime);
					showTime(nextTime, restTime*1000 -3600000);
					if (restTime<0){
						if (!cookWith){
							recipeStep.find('.nextStep.mustWait').removeClass('mustWait');
						}
						nextTime.parents('.nextTime:first').addClass('toLate');
					}
					
					var inputTotal = input.next();
					var stepTotal = parseInt(inputTotal.val());
					var percent = 1 - (restTime / stepTotal);
					if (percent>1){
						percent=1;
					}
					var elem = recipeStep.find('.action .progress');
					if (!cookWith || recipeStep.find('.nextStep.isWeightStep').length == 0){
						elem.css('width', (percent*100) + '%');
					}
					
					var ing_image = recipeStep.find('.action img');
					if (ing_image.length>0){
						ing_image.css('opacity', 1-percent);
					}
					
					if (restTime <= 0){
						var nextLink = elem.parents('.recipeStep:first').find('.nextStep:first');
						if (nextLink.is('.autoClick')){
							var link = nextLink.attr('href');
							if (link.indexOf('#') == 0){
								window.location.hash=link;
							} else {
								window.location.pathname=link;
							}
							//console.log(link);
						}
					}
					
					//Update finishedIn Time
					var finishedInTime = recipeStep.find('.finishTime:first span');
					input = finishedInTime.parents('.finishTime:first').next();
					var timeDiff = stepTotal - Math.round((currentTime-startTime[index]) / 1000);
					var restTime = parseInt(input.val()) - stepTotal + timeDiff;
					
					var inputTotal = input.next();
					var finishMax = parseInt(inputTotal.val());
					var inTime = true;
					inTime = restTime >= finishMax;
					if (!inTime){
						finishedInTime.parents('.finishTime:first').addClass('toLate');
						showTime(finishedInTime, currentTime+(finishMax*1000));
						restTime = finishMax;
						//finishedInTime.text(finishMax);
					/*} else {
						finishedInTime.parents('.finishTime:first').removeClass('toLate');
						//finishedInTime.text(restTime);
						showTime(finishedInTime, currentTime+(restTime*1000)); //Because of update each 0.5sec the time could change/flip 2 times per second...
					*/
					}
					if (initialize){
						showTime(finishedInTime, currentTime+(restTime*1000));
					}
					/* //TODO: if (not jet started) {
					var masterTime = parseInt(jQuery('#finishTime').val());
					if (masterTime > restTime){
						restTime = masterTime;
					}
					*/
					if (restTime > maxFinishedIn || maxFinishedIn == 0){
						maxFinishedIn = restTime;
					}
				} else if (initialize){
					var input = recipeStep.find('input[name=nextTime]');
					var restTime = Math.round(input.val() - ((currentTime-startTime[index]) / 1000));
					var inputTotal = input.next();
					var stepTotal = parseInt(inputTotal.val());
					
					var finishedInTime = recipeStep.find('.finishTime:first span');
					input = finishedInTime.parents('.finishTime:first').next();
					var timeDiff = stepTotal - Math.round((currentTime-startTime[index]) / 1000);
					var restTime = parseInt(input.val()) - stepTotal + timeDiff;
					showTime(finishedInTime, currentTime+(restTime*1000));
					if (restTime > maxFinishedIn || maxFinishedIn == 0){
						maxFinishedIn = restTime;
					}
				}
			}
		});
		if (maxFinishedIn != 0){
			showTime(jQuery('.meta .finishTime span'), currentTime+(maxFinishedIn*1000));
		}
	}
	
	jQuery('body').undelegate('.cookAssistant .nextStep.mustWait','click').delegate('.cookAssistant .nextStep.mustWait','click', function(){
		//var anchor = jQuery(this);
		var result = window.confirm('Timeend not reached, changing could messup the Recipe, want to finish step anyway?');
		return result;
		/*
		if (result){
			return true;
		} else {
			return false;
		}*/
	});
	
	jQuery('body').undelegate('.cookAssistant .vote','click').delegate('.cookAssistant .vote','click', function(){
		var anchor = jQuery(this);
		var url = anchor.attr('href');
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			if (data.indexOf('{')===0){
				eval('var data = ' + data + ';');
				if (data.sucessfull){
					anchor.parent().children().hide();
					//anchor.parent().append('<span></span>');
					anchor.parent().find('.changeRecipe').show();
					anchor.parents('.recipeStep:first').find('.nextTime').hide();
					return;
				}
			}
			glob.setContentWithImageChangeToFancy(data, {});
			anchor.parent().addClass('activeVote');
		}, 'error': function(){
		}});
		return false;
	});
	
	jQuery('body').undelegate('.cookAssistantVote #reasons select','change').delegate('.cookAssistantVote #reasons select','change', function(){
		var elem = jQuery(this);
		var value = elem.val();
		if (value === 'other'){
			jQuery('.cookAssistantVote #other_reason').show();
		} else {
			jQuery('.cookAssistantVote #other_reason').hide();
		}
	});
	
	jQuery('body').undelegate('.cookAssistantVote #saveReason','click').delegate('.cookAssistantVote #saveReason','click', function(){
		var form = jQuery(this).parents('form:first');
		jQuery.ajax({'type':'post', 'url':form.attr('action'), 'data': form.serialize(),'cache':false,'success':function(data){
			var activeVote = jQuery('.activeVote');
			activeVote.removeClass('.activeVote').children().hide();
			activeVote.find('.changeRecipe').show();
			//activeVote.append('<span></span>');
			activeVote.parents('.recipeStep:first').find('.nextTime').hide();
			jQuery.fancybox.close();
		},
		'error':function(xhr){
			//ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
		},
		});
		return false;
	});
});