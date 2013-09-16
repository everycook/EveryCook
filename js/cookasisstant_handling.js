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

var glob = glob || {};

jQuery(function($){
	var currentTime = new Date().getTime();
	var startTime = [];
	var getValueTimeoutID=[];
	var getValueStartTime=[];
	var gotoNextTime=[];
	var interval = -1;
	var errorCounter=[];
	var lastError=[];
	var connections=[];
	var socketToConnections=[];
	var lastWebsocketConnectTime=[];
	var lastUpdateTime=[];
	var lastPercent=[];
	var MIN_STATUS_INTERVAL=40000; //40sec
	var finishedInTimeLastValue = 9999999;
	
	function initTimer(type, contentParent){
		if (type !== 'fancy'){
			if (interval != -1){
				window.clearInterval(interval);
			}
			if (typeof(contentParent) === 'undefined') return;
			if (jQuery('.recipeStep').length > 0){
				startTime = [];
				currentTime = new Date().getTime();
				contentParent.find('.recipeStep').each(function(index){
					startTime[index] = currentTime;
					lastPercent[index] = 0;
				});
				errorCounter=[];
				initialize(type);
				interval = window.setInterval(updateTime,500);
				updateTime(true);
			}
		}
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
	
	function showError(message){
		var content = '<div class="message">' + message + '</div><div class="buttons"><div class="button closeFancy">OK</div></div>'
		jQuery.fancybox({
			'content':content
		});
	}
	
	jQuery('body').undelegate('#fancybox-content .closeFancy','click').delegate('#fancybox-content .closeFancy','click',function(){
		jQuery.fancybox.close();
	});
	
	function updateTimeNotStarted(recipeStep, maxFinishedIn){
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
		return maxFinishedIn;
	}
	
	function updateFinishedTime(recipeStep, nextTime, stepTotal, maxFinishedIn, index, updateAlways, initialize){
		//Update finishedIn Time
		var finishedInTime = recipeStep.find('.finishTime:first span');
		input = finishedInTime.parents('.finishTime:first').next();
		var timeDiff = stepTotal - Math.round((currentTime-startTime[index]) / 1000);
		var restTime = parseInt(input.val()) - stepTotal + timeDiff;
			
		var inputTotal = input.next();
		var inputTotalVal = parseInt(inputTotal.val());
		var inTime = restTime > inputTotalVal;
		if (!inTime){
			finishedInTime.parents('.finishTime:first').addClass('toLate');
			finishedInTime.text();
			showTime(finishedInTime, currentTime+(inputTotalVal*1000));
			//TODO?: nextTime.parents('.nextTime:first').addClass('toLate');
			restTime = inputTotalVal;
		} else if (updateAlways){
			finishedInTime.parents('.finishTime:first').removeClass('toLate');
			showTime(finishedInTime, currentTime+(restTime*1000));  //Because of update each 0.5sec the time could change/flip 2 times per second...
			nextTime.parents('.nextTime:first').removeClass('toLate');
		} else if (initialize){
			showTime(finishedInTime, currentTime+(restTime*1000));
		}
		if (restTime > maxFinishedIn || maxFinishedIn == 0){
			maxFinishedIn = restTime;
		}
		return maxFinishedIn;
	}
	
	function updateTimePoll(recipeStep, nextTime, index, maxFinishedIn){
		var input = nextTime.parents('.nextTime:first').next();
		var inputTotal = input.next();
		var stepTotal = parseInt(inputTotal.val());
		return updateFinishedTime(recipeStep, nextTime, stepTotal, maxFinishedIn, index, true, false);
	}
	
	function updatePercent(recipeStep, percent){
		var elem = recipeStep.find('.action .progress');
		if (percent>1){
			percent = 1;
		}
		//if (cookWith=='' || recipeStep.find('.nextStep.isWeightStep').length == 0){ // why should this be needed?
		elem.css('width', (percent*100) + '%');
		var ing_image = recipeStep.find('.action img');
		if (ing_image.length>0){
			ing_image.css('opacity', 1-percent);
		}
	}
	
	function handleUpdate(recipeStep, data, index, nextTime, currentTime){
		if (data.length == 0){
			return false;
		}
		var json;
		try {
			json = JSON.parse(data);
		} catch (exception){
			eval('var json = ' + data + ';');
		}
		lastUpdateTime[index] = currentTime;
		if (typeof(json.error) !== 'undefined'){
			var stepNrField = recipeStep.find("input[name=stepNr]");
			if (recipeStep.parents('body').length == 0){
				//is not on assistant site.
				return true;
			} else if (stepNrField.length == 0 || stepNrField.val() == -1){
				return true;
			} else {
				if (errorCounter[index] == undefined){
					errorCounter[index] = 1;
				} else {
					++errorCounter[index];
				}
				if (errorCounter[index]<5){
					//TODO: reload?
					showError("Error while load State from EveryCook: " + json.error);
				} else {
					showError("CookAsisstant Terminated, no furter updates, please start again(Press F5 or go to nextstep to reinitialize updater)");
					connections[index]['active'] = false;
					if (connections[index]['connected'] === true){
						try {
							var socket = connections[index]['socket'];
							socket.close();
						} catch (e){}
					}
					//window.clearInterval(interval);
				}
				return false;
			}
		} else if (connections[index]['type'] != 'poll' || getValueStartTime[index] == json.startTime){
			var stepNr = recipeStep.find('input[name=stepNr]').val();
			if (json.SID == stepNr){
				if (nextTime.length>0){ //if not, the end step is reached
					//Only update view if percent is greater
					if (lastPercent[index] < json.percent){
						updatePercent(recipeStep, json.percent);
						lastPercent[index] = json.percent;
					}
					
					errorCounter[index] = 0;
					
					if (json.text){
						elem = recipeStep.find('.action .actionText');
						elem.html(json.text);
					}
					//nextTime.text(json.restTime);
					
					//Update nextTime field for timeupdate between recived data
					var input = recipeStep.find('input[name=nextTime]');
					input.val(json.restTime + ((currentTime-startTime[index]) / 1000));
					
					//show time
					showTime(nextTime, json.restTime*1000 -3600000);
					if (json.restTime<0){
						nextTime.parents('.nextTime:first').addClass('toLate');
						if (typeof(json.W0) !== 'undefined'){
							//TODO
						} else {
							recipeStep.find('.nextStep.mustWait').removeClass('mustWait');
						}
					} else {
						nextTime.parents('.nextTime:first').removeClass('toLate');
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
					} else if (json.gotoNextTime){
						gotoNextTime[index] = currentTime + (json.gotoNextTime * 1000);
					}
				}
				var tempInfo = recipeStep.find('div.temp');
				tempInfo.find('span.temp').text(json.T0);
				tempInfo.find('span.press').text(json.P0);
				return true;
			} else {
				//TODO return false;?
				return true;
			}
		} else {
			//TODO return false;?
			return true;
		}
	}
	
	function updateTime(initialize){
		currentTime = new Date().getTime();
		var started = jQuery('#started').val();
		var maxFinishedIn = 0;	
		//jQuery('.finishTime').add('.nextTime').each(function(){
		//jQuery('.recipeStep').each(function(index){
		//	var connection = connections[index];
		for(var i=0; i<connections.length; ++i){
			var connection = connections[i];
			var index = connection['index'];
			if (connection['active'] && connection['recipeStep'].parents('body').length>0){
				var recipeStep = connection['recipeStep'];
				var pollActivated = false;
				if (connection['type'] === 'poll'){
					pollActivated = true;
				} else if (connection['type'] === 'websocket' && connection['connected'] !== true){
					pollActivated = true;
				}
				if (pollActivated){
					if (currentTime - lastWebsocketConnectTime[index] > 10000){ //10 sec
						connectToWebsocket(recipeStep, connection['ip'], connection['port'], index, currentTime);
					}
				}
				if (started == ''){
					maxFinishedIn = updateTimeNotStarted(recipeStep, maxFinishedIn);
				} else if (pollActivated && !initialize){
					//load state
					glob.ShowActivity = false;
					var nextTime = recipeStep.find('.nextTime:first span');
					if (typeof(getValueStartTime[index]) === 'undefined' || getValueStartTime[index]==0){
						//if (nextTime.length>0){ //if not, the end step is reached
							getValueTimeoutID[index] = window.setTimeout("glob.cancelFirmwareUpdate("+index+");",5000);
							getValueStartTime[index] = currentTime;
							var url = recipeStep.find('input[name=UpdateCookAssistantLink]:first').val();
							url = glob.urlAddParamStart(url) + 'startTime=' + getValueStartTime[index];
							jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){				
									window.clearTimeout(getValueTimeoutID[index]);
									getValueStartTime[index]=0;
									handleUpdate(recipeStep, data, index, nextTime, currentTime);
								},
								'error':function(xhr){
									if (console && console.log){
										console.log("Error on load state: " + xhr.status);
									}
									//ajaxResponceHandler(xhr.responseText, 'ajax'); //xhr.status //xhr.statusText
								},
								'complete':function(){
								}
							});
						//}
					}
					if (nextTime.length>0){ //if not, the end step is reached
						maxFinishedIn = updateTimePoll(recipeStep, nextTime, index, maxFinishedIn);
					}
				} else {
					//Update nextStepIn Time
					var nextTime = recipeStep.find('.nextTime:first span');
					if (initialize){
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
					} else if (nextTime.length>0){ //if not, the end step is reached
						//console.log("lastUpdateTime: " + lastUpdateTime[index] + ", currentTime: " + currentTime);
						if (lastUpdateTime[index]+MIN_STATUS_INTERVAL < currentTime){
							if (connection['type'] === 'websocket' && connection['connected'] === true){
								//ask from middleware
								connection['socket'].send('getState?reload=true');
							} else {
								//poll update
								glob.ShowActivity = false;
								var nextTime = recipeStep.find('.nextTime:first span');
								if (typeof(getValueStartTime[index]) === 'undefined' || getValueStartTime[index]==0){
									//if (nextTime.length>0){ //if not, the end step is reached
										getValueTimeoutID[index] = window.setTimeout("glob.cancelFirmwareUpdate("+index+");",5000);
										getValueStartTime[index] = currentTime;
										var url = recipeStep.find('input[name=UpdateCookAssistantLink]:first').val();
										url = glob.urlAddParamStart(url) + 'startTime=' + getValueStartTime[index];
										jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){				
												window.clearTimeout(getValueTimeoutID[index]);
												getValueStartTime[index]=0;
												handleUpdate(recipeStep, data, index, nextTime, currentTime);
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
									maxFinishedIn = updateTimePoll(recipeStep, nextTime, index, maxFinishedIn);
								}
							}
						} else {
							var input = nextTime.parents('.nextTime:first').next();
							var restTime = Math.round(input.val() - ((currentTime-startTime[index]) / 1000));
							showTime(nextTime, restTime*1000 -3600000);
							
							var nextLink = recipeStep.find('.nextStep:first');
							
							if (restTime<=0){
								if(connection['type'] === 'browser'){
									nextLink.removeClass('mustWait');
								}
								if (restTime<0){
									nextTime.parents('.nextTime:first').addClass('toLate');
								}
							}
							
							var inputTotal = input.next();
							var stepTotal = parseInt(inputTotal.val());
							
							if (!nextLink.is('.isWeightStep') || connection['type'] === 'browser'){
								var percent = 1 - (restTime / stepTotal);
								//Only update view if percent is greater
								if (lastPercent[index] < percent){
									updatePercent(recipeStep, percent);
									lastPercent[index] = percent;
								}
								
								if (restTime <= 0){
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
							}
							
							if (gotoNextTime[index]>0 && currentTime>=gotoNextTime[index]){
								nextLink.removeClass('mustWait');
								gotoNextTime[index] = 0;
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
							
							//Update finishedIn Time
							maxFinishedIn = updateFinishedTime(recipeStep, nextTime, stepTotal, maxFinishedIn, index, false, initialize);
						}
					}
				}
			}
		}
		if (maxFinishedIn != 0){
			var valToShow = currentTime+(maxFinishedIn*1000);
			//if (finishedInTimeLastValue > valToShow){
				showTime(jQuery('.meta .finishTime span'), valToShow);
				finishedInTimeLastValue = valToShow;
			//}
		}
	}
	
	jQuery('body').undelegate('.cookAssistant .nextStep.mustWait','click').delegate('.cookAssistant .nextStep.mustWait','click', function(){
		var result = window.confirm('Timeend not reached, changing could mess up the Recipe, want to finish step anyway?');
		return result;
	});
	
	jQuery('body').undelegate('.cookAssistant .vote','click').delegate('.cookAssistant .vote','click', function(){
		var anchor = jQuery(this);
		var url = anchor.attr('href');
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			if (data.indexOf('{')===0){
				eval('var data = ' + data + ';');
				if (data.sucessfull){
					anchor.parent().children().hide();
					anchor.parent().append('<span></span>');
					//anchor.parent().find('.changeRecipe').show();
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
		jQuery.ajax({'type':'post', 'url':form.attr('action'), 'data': form.serialize(),'cache':false,
			'success':function(data){
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
	
	function createConnection (serverUrl){
		var socket = null;
		if (window.MozWebSocket) {
			socket = new MozWebSocket(serverUrl);
		} else if (window.WebSocket) {
			socket = new WebSocket(serverUrl);
		}
		if (socket == null){
			return null;
		}
		
		socket.binaryType = 'blob';
		socket.onopen = function(msg) {
			var connectionIndex = socketToConnections[this];
			connections[connectionIndex]['connected'] = true;
			var recipeStep = connections[connectionIndex]['recipeStep'];
			recipeStep.find('.middleware').addClass('connected');
			recipeStep.find('.middleware > div').attr('title', glob.trans.COOKASISSTANT_MIDDLEWARE_ONLINE);
		};
		socket.onmessage = function(msg) {
			var connectionIndex = socketToConnections[this];
			var nextTime = connections[connectionIndex]['recipeStep'].find('.nextTime:first span');
			if (connections[connectionIndex]['active'] != false){
				currentTime = new Date().getTime();
				var index = connections[connectionIndex]['index'];
				var timeSinceLastUpdate = currentTime - lastUpdateTime[index];
				if (!handleUpdate(connections[connectionIndex]['recipeStep'], msg.data, index, nextTime, currentTime)){
					if (timeSinceLastUpdate > 10000){
						this.send('getState');
					} else {
						var json;
						try {
							json = JSON.parse(data);
						} catch (exception){
							eval('var json = ' + data + ';');
						}
						if (lastError[index] != json.error){
							this.send('getState');
						}
					}
				}
			}
		};
		socket.onclose = function(msg) {
			var connectionIndex = socketToConnections[this];
			connections[connectionIndex]['connected'] = false;
			var recipeStep = connections[connectionIndex]['recipeStep'];
			recipeStep.find('.middleware').removeClass('connected');
			recipeStep.find('.middleware > div').attr('title', glob.trans.COOKASISSTANT_MIDDLEWARE_OFFLINE);
		};
		return socket;
	}
	
	function connectToWebsocket(recipeStep, ip, port, index, currentTime) {
		lastWebsocketConnectTime[index] = currentTime;
		var serverUrl = ip + ':' + port + '/everycook';
		serverUrl = serverUrl + '?recipeNr=' + index;
		if (location.protocol == "https:"){
			serverUrl = 'wss://' + serverUrl;
		} else {
			serverUrl = 'ws://' + serverUrl;
		}
		
		var socket = null;
		var connected = false;
		if (typeof(connections[index]) !== 'object'  || connections[index]['connected'] !== true){
			socket = createConnection(serverUrl);
		} else if (connections[index]['ip'] != ip){
			try {
				connections[index]['socket'].close();
			} catch (err){}
			socket = createConnection(serverUrl);
		} else if (typeof(connections[index]) === 'object'){
			socket = connections[index]['socket'];
			connected = connections[index]['connected'];
			if (!connected && typeof(socket) !== 'undefined' && socket != null){
				connected = socket.readyState == socket.OPEN;
			}
		}
		if (socket != null){
			connections[index] = {'index':index, 'active': true, 'type':'websocket', 'recipeStep': recipeStep, 'ip':ip, 'port':port, 'socket':socket, 'connected':connected};
			socketToConnections[socket] = index;
			if (connected){
				recipeStep.find('.middleware').addClass('connected');
				recipeStep.find('.middleware > div').attr('title', glob.trans.COOKASISSTANT_MIDDLEWARE_ONLINE);
			}
		} else {
			connections[index] = {'index':index, 'active': true, 'type':'poll', 'recipeStep': recipeStep, 'ip':ip, 'port':port};
		}
	}
	
	function initialize(type) {
		if (type == 'initial'){
			//TODO: close sockets
			
			connections=[];
			socketToConnections={};
		} else {
			if (typeof(connections) !== 'object' || connections.length == 0){
				connections=[];
			}
			if (typeof(socketToConnections) !== 'object' || socketToConnections.length == 0){
				socketToConnections={};
			}
		}
		
		currentTime = new Date().getTime();
		
		var recipeSteps = jQuery('.recipeStep');
		for(var i=0; i<recipeSteps.length; ++i){
			gotoNextTime[i] = 0;
			var recipeStep = jQuery(recipeSteps[i]);
			var cookWith = recipeStep.find('input[name=cookWith]');
			var ip = cookWith.val();
			if (ip != ''){
				connectToWebsocket(recipeStep, ip, 8000, i, currentTime);
			} else {
				connections[i] = {'index':i, 'active': true, 'type':'browser', 'recipeStep': recipeStep};
			}
		}
		
		/*
		$('#send').click(function() {
			var payload;
			payload = new Object();
			payload.action = $('#action').val();
			payload.data = $('#data').val();
			return socket.send(JSON.stringify(payload));
		});
		$('#sendText').click(function() {
			return socket.send($('#text').val());
		});
		return $('#sendfile').click(function() {
			var data, payload;
			data = document.binaryFrame.file.files[0];
			if (data) {
				payload = new Object();
				payload.action = 'setFilename';
				payload.data = $('#file').val();
				socket.send(JSON.stringify(payload));
				socket.send(data);
			}
			return false;
		});
		*/
	}

});