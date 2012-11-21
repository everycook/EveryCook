var initialLocation;
var lastLocation;
var locationBasel;
var browserSupportFlag =  new Boolean();
var lastCords = null;
var gettingCached = false;
var fallbackDone = false;
var map;

var doLoadStores = false;
var doPlaceMarker = false;
var doInitialize = false;
var doLoadPlaces = false;

var markerList = new Array();
var markerIcons = new Array();
var markerClustererList = new Array();
var groupStyles = new Array();
var markerTotalCount = 0;
var MIN_DETAIL_ZOOM = 9;
var MAX_GROUP_ZOOM = 13;
var infoBubble;
var infoBubbleStoreId;
var infoBubbleStoreIdNext;
var lastZoom=99;
var lastBounds=null;

var distanceMarker;
var distanceText;

var homeTitle = 'Your Home';
var currentTitle = 'your Position';
var homeMarker;
var currentMarker;
var locateAddressCallback;

var geocodeTitle = 'geocode / change Marker (draggable)';
var geocoder;
var lastGeocodeMarker;

var placesService;
var infowindow;
var placesResult = [];
var placesLastDetail;
var placesDetailRequestStartedFor;
var weekdayNames = ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'];
var autocomplete;
var searchBox;

//Initialize functions
function loadScript(sensor, region, https, loadStores, placeMarker, runInitialize, loadPlaces) {
	doLoadStores = loadStores;
	doPlaceMarker = placeMarker;
	doInitialize = runInitialize;
	doLoadPlaces = loadPlaces;
	
	var url;
	if (https){
		url = "https://maps-api-ssl.google.com/maps/api/js";
	} else {
		url = "https://maps.google.com/maps/api/js";
	}
	url = url + "?sensor="+((sensor)?"true":"false") + ((region)?"&region="+region:"") + "&libraries=places" + "&callback=initialize"; //"&language=" + lang
	var check = jQuery('script[src="' + url + '"]');
	if(check.length == 0){
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = url;
		document.body.appendChild(script);
		
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble-compiled.js";
		document.body.appendChild(script);
		
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer_compiled.js";
		document.body.appendChild(script);
	} else {
		initialize();
	}
}
	
function addRelocateButton(){
	var controlDiv = document.createElement('div');
	controlDivJ = jQuery(controlDiv);
	controlDivJ.css('margin','5px');
	controlDivJ.css('box-shadow', '0 2px 4px rgba(0, 0, 0, 0.4)');
	controlDivJ.css('border', '1px solid #717B87');
	
	var controlUI = document.createElement('img');
	controlUI.src = glob.prefix + 'pics/locate.png';
	controlUI.alt = 'find current Possition';
	controlUI.title = 'find current Possition';
	controlUI.width = 24;
	controlUI.height = 24;
	controlDiv.appendChild(controlUI);

	google.maps.event.addDomListener(controlUI, 'click', function() {
		lastLocation = initialLocation;
		locateUser(locateCallback, undefined, false);
	});

	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
}

function checkMapInitialized(){
	if (typeof(map) === 'undefined' || jQuery(map.getDiv()).filter(':visible').length == 0 ){
		var myOptions = {
			zoom: 14,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var container = jQuery('#map_canvas');
		if (container.length > 0){
			container.show();
			map = new google.maps.Map(container.get(0), myOptions);
		}
		//map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
		if (doLoadStores){
			google.maps.event.addListener(map, 'dragend', mapChanged);
			google.maps.event.addListener(map, 'bounds_changed', function(){
				google.maps.event.addListenerOnce(map, 'idle', mapChanged);
			});
			google.maps.event.addListener(map, 'zoom_changed', function(){
				google.maps.event.addListenerOnce(map, 'idle', mapChanged);
			});
			google.maps.event.addListenerOnce(map, 'idle', mapChanged);
		}
		
		addRelocateButton();
	}
}

function initialize() {
	if (typeof(google) === 'undefined'){
		return;
	}
	
	if (!locationBasel){
		locationBasel = new google.maps.LatLng(47.557473,7.592926);
	}
	
	if (doInitialize){
		if (typeof(distanceMarker) !== 'undefined'){
			distanceMarker.setMap(null);
			distanceMarker = undefined;
		}
		
		lastZoom=99;
		lastBounds=null;
		loadDataCallback = loadDataStores;
		checkMapInitialized();
		distanceText = 'distance to home:';
		if (doLoadStores){
			locateUser(locateCallback, doFallback, true);
		} else if (!doPlaceMarker){
			locateUser(locateCallback, doFallback, true);
		}
		if (jQuery('#home_gps_lat').val() != '' && jQuery('#home_gps_lng').val() != ''){
			homeMarker = new google.maps.Marker({
				position: new google.maps.LatLng(jQuery('#home_gps_lat').val(), jQuery('#home_gps_lng').val()), 
				map: map,
				title: homeTitle,
			});
		}
	}
	initializeGeocoder();
	initializePlaces();
}

function destinationPoint(start, brng, dist) {
  dist = dist/6371.01;  // convert dist to angular distance in radians
  brng = brng.toRad();  // 
  var lat1 = start.lat().toRad(), lng1 = start.lng().toRad();

  var lat2 = Math.asin( Math.sin(lat1)*Math.cos(dist) + 
                        Math.cos(lat1)*Math.sin(dist)*Math.cos(brng) );
  var lng2 = lng1 + Math.atan2(Math.sin(brng)*Math.sin(dist)*Math.cos(lat1), 
                               Math.cos(dist)-Math.sin(lat1)*Math.sin(lat2));
  lng2 = (lng2+3*Math.PI) % (2*Math.PI) - Math.PI;  // normalise to -180..+180º

  return new google.maps.LatLng(lat2.toDeg(), lng2.toDeg());
}

function reinitialize(lat, lng, zoom, radius, dataCallback, isCurrent){
	lastZoom=99;
	lastBounds=null;
	
	if (typeof(lastGeocodeMarker) !== 'undefined'){
		lastGeocodeMarker.setMap(null);
		lastGeocodeMarker=undefined;
	}
	
	if (typeof(distanceMarker) !== 'undefined'){
		distanceMarker.setMap(null);
		distanceMarker = undefined;
	}
	
	loadDataCallback = dataCallback;
	
	if (isCurrent){
		markerTitle = currentTitle;
	} else {
		markerTitle = homeTitle;
	}
	if (dataCallback == loadDataProduct){
		distanceText = 'distance to ' + markerTitle + ':';
	} else {
		distanceText = 'distance to home:';
	}
	
	var center = new google.maps.LatLng(lat, lng);
	checkMapInitialized();
	map.setCenter(center);
	
	if (typeof(radius) !== 'undefined'){
		var bounds = new google.maps.LatLngBounds();
		bounds.extend(destinationPoint(center, 0, radius)); //top
		bounds.extend(destinationPoint(center, 90, radius)); //right
		bounds.extend(destinationPoint(center, 180, radius)); //bottom
		bounds.extend(destinationPoint(center, 270, radius)); //left
		
		map.fitBounds(bounds);
	} else if (typeof(zoom) !== 'undefined'){
		map.setZoom(zoom);
	}
	
	distanceMarker  = new google.maps.Marker({
		position: center, 
		map: map,
		title: markerTitle,
	});
	if (isCurrent){
		if (typeof(homeMarker) !== 'undefined'){
			homeMarker.setMap(null);
			homeMarker = undefined;
		}
		if (jQuery('#home_gps_lat').val() != '' && jQuery('#home_gps_lng').val() != ''){
			homeMarker = new google.maps.Marker({
				position: new google.maps.LatLng(jQuery('#home_gps_lat').val(), jQuery('#home_gps_lng').val()), 
				map: map,
				title: homeTitle,
			});
		}
	} else {
		if (typeof(currentMarker) !== 'undefined'){
			currentMarker.setMap(null);
			currentMarker = undefined;
		}
		if (jQuery('#current_gps_lat').val() != '' && jQuery('#current_gps_lng').val() != ''){
			if(jQuery('#current_gps_time').val() > (new Date().getTime())/1000){
				currentMarker  = new google.maps.Marker({
					position: new google.maps.LatLng(jQuery('#current_gps_lat').val(), jQuery('#current_gps_lng').val()), 
					map: map,
					title: currentTitle,
				});
			}
		}
	}
	
	google.maps.event.addListenerOnce(map, 'idle', mapChanged);
}

function UpdateSessionLocation(){
	//if it's in a radius more than 500 meters, it seams to be a unexact location, save it in html/session so don't need to ask again and to show distances.
	if (lastCords == null  || lastCords.accuracy > 500){
		jQuery('#current_gps_lat').val(initialLocation.lat());
		jQuery('#current_gps_lng').val(initialLocation.lng());
		var time = (new Date().getTime())/1000 + 2*60*60;//this un accurate location is valid for 2 hours, no need to ask the same multiple times.
		jQuery('#current_gps_time').val(time); 
		var url = jQuery('#markCurrentGPS').val();
		jQuery.post(url ,{
			"lat": initialLocation.lat(), 
			"lng": initialLocation.lng(),
			"time": time,
		}, null);
	} else { // if (lastCords.accuracy <= 500)
	//if it's a real location (less than 500 meters), ask again if older than 10min. Also save it in session to show distances.
		jQuery('#current_gps_lat').val(initialLocation.lat());
		jQuery('#current_gps_lng').val(initialLocation.lng());
		//this one is valid for up to 10min, so you see your changed location.
		var time = (new Date().getTime())/1000 + 10*60;
		jQuery('#current_gps_time').val(time);
		var url = jQuery('#markCurrentGPS').val();
		jQuery.post(url ,{
			"lat": initialLocation.lat(), 
			"lng": initialLocation.lng(),
			"time": time,
		}, null);
	}
}

function loadDataStores(southWest, northEast, zoom){
	var url = $("#StoreLocationsLink").val();
	jQuery.post(url ,{
	   "southWestLat": southWest.lat(),
	   "southWestLng": southWest.lng(),
	   "northEastLat": northEast.lat(),
	   "northEastLng": northEast.lng(),
	   "zoom": zoom
	 }, function(xml) {
	   updateMarkers(xml);
	});
}

function loadDataProduct(southWest, northEast, zoom){
	if (typeof(distanceMarker) !== 'undefined'){
		startLat = distanceMarker.getPosition().lat();
		startLng = distanceMarker.getPosition().lng();
	} else {
		startLat = '';
		startLng = '';
	}
	var url = $("#ProductStoreLocationsLink").val();
	jQuery.post(url ,{
	   "southWestLat": southWest.lat(),
	   "southWestLng": southWest.lng(),
	   "northEastLat": northEast.lat(),
	   "northEastLng": northEast.lng(),
	   "zoom": zoom,
	   "product_id": jQuery('.selectedProduct').val(),
	   "startLat": startLat,
	   "startLng": startLng,
	 }, function(xml) {
	   updateMarkers(xml);
	});
}

function locateCallback(status){
	if (status === -1){
		//alert("Geolocation service failed. We've placed you in Basel, Schweiz.");
		//initialLocation = locationBasel;
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
		locateAddressCallback = locateCallback;
	} else if (status === -2){
		//alert("Your browser doesn't support geolocation. We've placed you in Basel, Schweiz.");
		//initialLocation = locationBasel;
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
		locateAddressCallback = locateCallback;
	} else {
		if (status !== 0){
			UpdateSessionLocation();
		}
		map.setCenter(initialLocation);
		if (typeof(currentMarker) !== 'undefined'){
			currentMarker.setMap(null);
			currentMarker = undefined;
		}
		currentMarker  = new google.maps.Marker({
			position: initialLocation, 
			map: map,
			title: currentTitle,
		});
		if (doLoadStores){
			google.maps.event.addListenerOnce(map, 'idle', mapChanged);
		}
	}
}

function doFallback(){
	if (!fallbackDone){
		map.setCenter(locationBasel);
		if (doLoadStores){
			google.maps.event.addListenerOnce(map, 'idle', mapChanged);
		}
		fallbackDone = true;
	}
}

function locateUser(callback, fallbackCallback, useCached){
	if (typeof(fallbackCallback) !== 'undefined'){
		fallbackDone = false;
	}
	if (useCached){
		if (jQuery('#current_gps_lat').val() != '' && jQuery('#current_gps_lng').val() != ''){
			if(jQuery('#current_gps_time').val() > (new Date().getTime())/1000){
				initialLocation = new google.maps.LatLng(jQuery('#current_gps_lat').val(), jQuery('#current_gps_lng').val());
				callback(0);
				return;
			}
		}
	}
	
	// Try W3C Geolocation (Preferred)
	if(navigator.geolocation) {
		browserSupportFlag = true;
		
		var successCallback = function(position) {
			window.clearTimeout(fallbackTimeout);
			lastCords = position.coords;
			initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			//alert('accuracy: ' + position.coords.accuracy + ', heading: ' + position.coords.heading + ', speed: ' + position.coords.speed);
			callback(1);
		};
		var errorCallback = function(error) {
			window.clearTimeout(fallbackTimeout);
			switch(error.code) {
				case error.TIMEOUT:
					//alert('timeout');
					if (gettingCached){
						if (typeof(fallbackCallback) !== 'undefined'){
							fallbackCallback();
						}
						gettingCached = false;
						navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {timeout:10000}); //, enableHighAccuracy:true
					} else {
						callback(-1);
					}
					break;
				default:
					callback(-1);
			}
		}
		
		if (typeof(fallbackCallback) !== 'undefined'){
			var fallbackTimeout = window.setTimeout(fallbackCallback, 5000);
		}
		if (useCached){
			gettingCached = true;
			//get cached value if not older than 10 min
			navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {maximumAge:600000, timeout:0});
		} else {
			gettingCached = false;
			navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {timeout:10000}); //, enableHighAccuracy:true
		}
	// Try Google Gears Geolocation
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
			callback(2);
		}, function() {
			callback(-1);
		});
	// Browser doesn't support Geolocation
	} else {
		browserSupportFlag = false;
		callback(-2);
	}
}




//show/hide functions
function clearMarkers(){
	hideMarkers();
}

function hideMarkers(){
	for (var i = 0; i < markerList.length; i++) {
		if (typeof(markerList[i]) !== 'undefined'){
			for (var j = 0; j < markerList[i].length; j++) {
				markerList[i][j].setMap(null);
			}
		}
	}

	for (var i = 0; i < markerClustererList.length; i++) {
		if (typeof(markerClustererList[i]) !== 'undefined'){
			markerClustererList[i].clearMarkers();
		}
	}
}

function showOnlySupplierMarkers(supplierId){
	for (var i = 0; i < markerList.length; i++) {
		if (typeof(markerList[i]) !== 'undefined'){
			if (i != supplierId){
				for (var j = 0; j < markerList[i].length; j++) {
					markerList[i][j].setMap(null);
				}
				if (typeof(markerClustererList[i]) !== 'undefined'){
					markerClustererList[i].clearMarkers();
				}
			} else {
				if (markerTotalCount <= 20 || map.getZoom() >= 11){
					for (var j = 0; j < markerList[i].length; j++) {
						markerList[i][j].setMap(map);
					}
				} else {
					if (typeof(markerClustererList[i]) !== 'undefined'){
						markerClustererList[i].addMarkers(markerList[i]);
					}
				}
			}
		}
	}
}

function showMarkers(){
	for (var i = 0; i < markerList.length; i++) {
		if (markerTotalCount <= 20 || map.getZoom() >= MAX_GROUP_ZOOM){
			if (typeof(markerList[i]) !== 'undefined'){
				for (var j = 0; j < markerList[i].length; j++) {
					markerList[i][j].setMap(map);
				}
			}
		} else {
			if (typeof(markerClustererList[i]) !== 'undefined'){
				markerClustererList[i].addMarkers(markerList[i]);
			}
		}
	}
}



//Data functions
function mapChanged() {
	var bounds = map.getBounds();
	if (typeof(bounds) !== 'undefined'){
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		var zoom = map.getZoom();
		
		if (lastBounds != null){
			if (lastBounds.getNorthEast().lat()>=northEast.lat() && lastBounds.getSouthWest().lat()<=southWest.lat() &&
				lastBounds.getNorthEast().lng()>=northEast.lng() && lastBounds.getSouthWest().lng()<=southWest.lng()){
				//part is smaller than last loaded one, loading not needed.
				return false;
			}
		}
		
		lastZoom = zoom;
		
		//always load a bit more then visible(20%), so draging is possible
		var latDiff = (southWest.lat()-northEast.lat()) * 0.2;
		var lngDiff = (southWest.lng()-northEast.lng()) * 0.2;
		southWest = new google.maps.LatLng(southWest.lat()+latDiff,southWest.lng()+lngDiff);
		northEast = new google.maps.LatLng(northEast.lat()-latDiff,northEast.lng()-lngDiff);
		lastBounds = new google.maps.LatLngBounds(southWest,northEast);
		
		loadDataCallback(southWest, northEast, zoom);
	}
 	return false; 
};

function updateMarkers(xml) {
	var newMarkerList = new Array();
	var storeOptionValues = '';
	var storeCount = 0;
	if ($("status",xml).text() != "success") {
		lastZoom = 99;
		lastBounds = null;
	} else {
		storeCount = $("storeCount",xml);
		
		var storeList = $("store",xml);
		for (var i = 0; i < storeList.length; i++) {
			var store = storeList[i];
			var lat = $("lat", store).text();
			var lng = $("lng", store).text();
			var storeId = $("storeId", store).text();
			var supplierId = $("supplierId", store).text();
			var distance = $("distance", store).text();
			
			if (map.getZoom() > MIN_DETAIL_ZOOM){
				var name = $("name", store).text();
				var street = $("street", store).text();
				var houseNr = $("housenumber", store).text();
				var zip = $("zip", store).text();
				var city = $("city", store).text();
				var supplier = $("supplier", store).text();
				var type = $("type", store).text();
				var typeId = $("typeId", store).text();
				var imageUrl = $("imageUrl", store).text();
				/*
				if (typeId == 0){
					typeId = '';
				}
				*/
			}
			
			var cords = new google.maps.LatLng(lat,lng);
			var marker;
			if (map.getZoom() > MIN_DETAIL_ZOOM){
				var html = infoWindowHtml(name, street, houseNr, zip, city, supplier, imageUrl, distance);
				marker = addMarker(cords, name, html, storeId, supplierId, typeId);
				
				
				if (name != ''){
					storeOptionValues = storeOptionValues + '<option value="' + storeId + '_' + supplierId + '_' + typeId + '">' + supplier + ' ' + name + '</option>';
				} else {
					storeOptionValues = storeOptionValues + '<option value="' + storeId + '_' + supplierId + '_' + typeId + '">' + supplier + ' ' + city + '</option>';
				}
			} else {
				marker = addMarker(cords, name, null, storeId, supplierId, typeId);
			}
			if (typeof(newMarkerList[supplierId]) === 'undefined'){
				newMarkerList[supplierId] = new Array();
			}
			newMarkerList[supplierId].push(marker);
		}
	}
	
	if (typeof(infoBubble) !== 'undefined'){
		infoBubble.close();
		infoBubble.setMap(null);
	}
	infoBubbleStoreId = -1;
	clearMarkers();
	
	markerList = newMarkerList;
	markerTotalCount = storeCount.get(0).textContent;
	
	if (markerTotalCount <= 20 || map.getZoom() >= MAX_GROUP_ZOOM){
		showMarkers();
	} else {
		for (var i = 0; i < markerList.length; i++) {
			if (typeof(markerList[i]) !== 'undefined'){
				if (typeof(markerClustererList[i]) === 'undefined'){
					groupStyles[i] = [{
						url: glob.prefix + 'pics/supplier_' + i + '_group1.png',
						height: 47,
						width: 53,
						anchor: [0, 0],
						textColor: '#ff00ff',
						textSize: 10
					}, {
						url: glob.prefix + 'pics/supplier_' + i + '_group2.png',
						height: 47,
						width: 53,
						anchor: [0, 0],
						textColor: '#ff0000',
						textSize: 11
					}, {
						url: glob.prefix + 'pics/supplier_' + i + '_group3.png',
						width: 47,
						height: 53,
						anchor: [0, 0],
						textSize: 12
					}];
					
					var mcOptions = {gridSize: 50, maxZoom: MAX_GROUP_ZOOM, styles: groupStyles[i]};
					markerClustererList[i] = new MarkerClusterer(map, markerList[i], mcOptions);
				} else {
					markerClustererList[i].addMarkers(markerList[i]);
				}
				markerClustererList[i].redraw();
			}
		}
	}
	jQuery('#STO_MAP option').remove();
	jQuery('#STO_MAP').append(jQuery(storeOptionValues));
};

infoWindowHtml = function(name, street, houseNr, zip, city, supplier, imageUrl, distance) {
	distance = Math.round(distance*10) / 10; //round to 1 decimal digit
	return '<div class="store-popup">'
		   + ((imageUrl.trim().length>0)?'<img src="' + imageUrl + '">':'')
		   + '<div class="store-popup-adresse">'
		   + supplier + ' ' + name + '<br>'
		   + ((zip==0)?'':street + ' ' + houseNr + ', ' + zip + ' ' + city)
		   + '</div><br>'
		   + distanceText + distance + ' km'
		   + '</div>'
};

function addMarker(latLng, name, html, storeId, supplierId, typeId) {
	if (typeof(markerIcons[supplierId]) === 'undefined'){
		markerIcons[supplierId] = new google.maps.MarkerImage(glob.prefix + 'pics/supplier_' + supplierId + '.png',
			new google.maps.Size(79, 48),
			new google.maps.Point(0,0),
			new google.maps.Point(0, 48)
		);
	}
	var marker = new google.maps.Marker({
		//title: name,
		//map: map,
		position: latLng,
		icon: markerIcons[supplierId]
	});
	
	if (html != null){
		google.maps.event.addListener(marker, 'mouseover', function() {
			infoBubbleStoreIdNext = storeId;
			window.setTimeout(function() {
				showInfoBubble(storeId, html, marker);
			}, 1);
		});

		google.maps.event.addListener(marker, 'mouseout', function() {
			if (typeof(infoBubble) !== 'undefined'){
				infoBubble.close();
			}
		});
		if (jQuery('#ProToSto_SUP_ID').length > 0){
			google.maps.event.addListener(marker, 'click', function() {
				jQuery('#ProToSto_SUP_ID :selected').removeAttr('selected');
				jQuery('#ProToSto_SUP_ID option[value=' + supplierId + ']').attr('selected','selected');
				jQuery('#ProToSto_STY_ID :selected').removeAttr('selected');
				jQuery('#ProToSto_STY_ID option[value=' + typeId + ']').attr('selected','selected');
			});
		}
	}
	return marker;
};

function showInfoBubble(storeId, html, marker){
	if (infoBubbleStoreIdNext == storeId){
		if (infoBubbleStoreId != storeId){
			if (typeof(infoBubble) !== 'undefined'){
				infoBubble.close();
				infoBubble.setMap(null);
			}
			infoBubbleStoreId = storeId;
			infoBubble = new InfoBubble({
				map: map,
				content: html,
				//position: latLng,
				shadowStyle: 1,
				padding: '3px',
				//backgroundColor: 'rgb(57,57,57)',
				borderRadius: 4,
				borderWidth: 1,
				borderColor: '#2c2c2c',
				disableAutoPan: true,
				hideCloseButton: true,
				arrowPosition: 50,
				arrowSize: 10,
				arrowStyle: 1
			});
			infoBubble.open(map, marker);
		} else {
			infoBubble.open(map, marker);
		}
	}
}


//Geocode functions
//var infowindow;
function initializeGeocoder() {
	if (typeof(geocoder) === 'undefined'){
		geocoder = new google.maps.Geocoder();
	}
	//infowindow = new google.maps.InfoWindow();
	
	if (typeof(lastGeocodeMarker) !== 'undefined'){
		lastGeocodeMarker.setMap(null);
		lastGeocodeMarker=undefined;
	}
	
	if (doPlaceMarker){
		google.maps.event.addListener(map, 'click', function(event) {
			jQuery('.cord_lat').val(event.latLng.lat());
			jQuery('.cord_lng').val(event.latLng.lng());
			setGeocodeMarker(event.latLng);
			
			map.setCenter(event.latLng);
		});
		
		//google.maps.event.addListenerOnce(map, 'idle', function(event) {
			var lat = jQuery('.cord_lat').val();
			var lng = jQuery('.cord_lng').val();
			if (lat != '' && lng != ''){
				lat = parseFloat(lat);
				lng = parseFloat(lng);
				var latLng = new google.maps.LatLng(lat, lng);
				map.setCenter(latLng);
				setGeocodeMarker(latLng);
			} else if (!doLoadStores){
				locateUser(locateCallback, doFallback, true);
			}
		//});
	}
}

function setGeocodeMarker(latLng, latField, lngField){
	if (typeof(lastGeocodeMarker) !== 'undefined'){
		lastGeocodeMarker.setMap(null);
		lastGeocodeMarker=undefined;
	}
	lastGeocodeMarker = new google.maps.Marker({
		position: latLng, 
		map: map,
		draggable: true,
		title: geocodeTitle,
	});
	if (typeof(latField) === 'undefined' || latField == null){
		var latField = jQuery('.cord_lat');
	}
	if (typeof(lngField) === 'undefined' || lngField == null){
		var lngField = jQuery('.cord_lng');
	}
	latField.val(latLng.lat());
	lngField.val(latLng.lng());
	latField.change();
	lngField.change();
	google.maps.event.addListener(lastGeocodeMarker, 'dragend', function(event) {
		//map.setCenter(event.latLng);
		latField.val(event.latLng.lat());
		lngField.val(event.latLng.lng());
		latField.change();
		lngField.change();
	});
	google.maps.event.addListener(lastGeocodeMarker, 'dblclick', function(event) {
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		url = glob.urlAddParamStart(url) + 'lat=' + latField.val();
		url = glob.urlAddParamStart(url) + 'lng=' + lngField.val();
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
		locateAddressCallback = MarkerCurrentGPSCallback;
	});
}

jQuery(function($){
	jQuery('body').undelegate('#Address_to_GPS','click').delegate('#Address_to_GPS','click',function(){
		var form = jQuery('#address-form');
		if (form.length == 0){
			form = jQuery('#stores-form');
		}
		street = form.find('#Stores_STO_STREET').val();
		no = form.find('#Stores_STO_HOUSE_NO').val();
		zip = form.find('#Stores_STO_ZIP').val();
		city = form.find('#Stores_STO_CITY').val();
		state = form.find('#Stores_STO_STATE').val();
		country = form.find('#Stores_STO_COUNTRY').val();
		
		var address = '';
		if (street != ''){
			address = address + street;
			if (no != '') {
				address = address + ' ' + no;
			}
			address = address + ',';
		}
		if (zip != '' || city != ''){
			if (zip != ''){
				address = address + zip;
			}
			if (city != '') {
				address = address + ' ' + city;
			}
			address = address + ',';
		}
		if (state != ''){
			address = address + state + ',';
		}
		if (country != ''){
			address = address + country + ',';
		}

		codeAddress(address, form.find('#Stores_STO_GPS_LAT'), form.find('#Stores_STO_GPS_LNG'));
		return false;
	});

	jQuery('body').undelegate('#GPS_to_Address','click').delegate('#GPS_to_Address','click',function(){
		var form = jQuery('#address-form');
		if (form.length == 0){
			form = jQuery('#stores-form');
		}
		decodeAddress(form.find('#Stores_STO_GPS_LAT').val(), form.find('#Stores_STO_GPS_LNG').val(), form.find('#Stores_STO_STREET'), form.find('#Stores_STO_HOUSE_NO'), form.find('#Stores_STO_ZIP'), form.find('#Stores_STO_CITY'), form.find('#Stores_STO_STATE'), form.find('#Stores_STO_COUNTRY'));
		return false;
	});
});

function codeAddress(address, latField, lngField) {
	geocoder.geocode( {'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (map) {
				map.setCenter(results[0].geometry.location);
				if(jQuery('#address-form').length == 0){
					setGeocodeMarker(results[0].geometry.location, latField, lngField);
				}
			}
			
			latField.val(results[0].geometry.location.lat());
			lngField.val(results[0].geometry.location.lng());
		} else {
			if (typeof(lastGeocodeMarker) !== 'undefined'){
				lastGeocodeMarker.setMap(null);
				lastGeocodeMarker=undefined;
			}
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}

function decodeAddress(lat, lng, streetField, noField, zipField, cityField, stateField, countryField) {
    var lat = parseFloat(lat);
    var lng = parseFloat(lng);
    var latLng = new google.maps.LatLng(lat, lng);

	geocoder.geocode({'latLng': latLng}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				if (map) {
					if(jQuery('#address-form').length == 0){
						setGeocodeMarker(results[0].geometry.location);
					}
					map.setCenter(results[0].geometry.location);
				}
				
				var parts = results[0].address_components;
				for(var i=0; i<parts.length; ++i){
					for (var j=0; j<parts[i].types.length; ++j){
						if (parts[i].types[j] == 'route'){
							streetField.val(parts[i].long_name);
						} else if (parts[i].types[j] == 'street_number'){
							noField.val(parts[i].long_name);
						} else if (parts[i].types[j] == 'postal_code'){
							zipField.val(parts[i].long_name);
						} else if (parts[i].types[j] == 'locality'){
							cityField.val(parts[i].long_name);
						} else if (parts[i].types[j] == 'administrative_area_level_1'){
							stateField.val(parts[i].long_name);
						} else if (parts[i].types[j] == 'country'){
							countryField.find('option :selected').removeAttr('selected');
							countryField.find('option[value=' + parts[i].short_name + ']').attr('selected','selected');
						}
					}
				}
				/*
				infowindow.setContent(results[0].formatted_address);
				infowindow.open(map, lastGeocodeMarker);
				*/
			}
		} else {
			alert("Geocoder failed due to: " + status);
		}
    });
}

function MarkerCurrentGPSCallback(status){
	if (status === -1){
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
		locateAddressCallback = MarkerCurrentGPSCallback;
		//alert("Geolocation service failed. Please set your location on Map.");
	} else if (status === -2){
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
		locateAddressCallback = MarkerCurrentGPSCallback;
		//alert("Your browser doesn't support geolocation. Please set your location on Map.");
	} else if (status !== 0){
		map.setCenter(initialLocation);
		setGeocodeMarker(initialLocation);
		
		UpdateSessionLocation();
	}
}

function UpdateCurrentGPSCallback(status){
	if (status === -1){
		//alert("Geolocation service failed.");
		locateAddressCallback = UpdateCurrentGPSCallback;
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
	} else if (status === -2){
		//alert("Your browser doesn't support geolocation.");
		locateAddressCallback = UpdateCurrentGPSCallback;
		var url = glob.urlAddParamStart(jQuery('#addressFormLink').val()) + 'errorCode=' + status;
		jQuery.ajax({'type':'get', 'url':url,'cache':false,'success':function(data){
			glob.setContentWithImageChangeToFancy(data, {});
		}});
	} else if (status !== 0){
		if (map) {
			map.setCenter(initialLocation);
		}
		//setGeocodeMarker(initialLocation);
		
		UpdateSessionLocation();
		if (typeof(lastLocation) === 'undefined' || lastLocation.lat()  != initialLocation.lat() || lastLocation.lng()  != initialLocation.lng()){
			/*
			if (lastCords != null){
				alert('DEBUG: geolocation sucessfull (accuracy: ' + lastCords.accuracy + 'm), press F5 to reload data (will be done automatically in future...)'); //TODO
			} else {
				alert('DEBUG: setting address GPS  sucessfull , press F5 to reload data (will be done automatically in future...)'); //TODO
			}
			*/
			glob.reloadPage();
		} else {
			if (typeof(currentMarker) !== 'undefined'){
				currentMarker.setMap(null);
				currentMarker = undefined;
			}
			currentMarker  = new google.maps.Marker({
				position: initialLocation, 
				map: map,
				title: currentTitle,
			});
		}
	}
}


function initializePlaces(){
	if (typeof(google) === 'undefined'){
		return;
	}
	if (doLoadPlaces){
		infowindow = new google.maps.InfoWindow();
		placesService = new google.maps.places.PlacesService(map);
		
		/*
		var input = document.getElementById('placesQuery');
		var options = {
			types: ['establishment'],
		};

		autocomplete = new google.maps.places.Autocomplete(input, options);
		autocomplete.bindTo('bounds', map);
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
          infowindow.close();
          //marker.setVisible(false);
          input.className = '';
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // Inform the user that the place was not found and return.
            input.className = 'notfound';
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          var image = new google.maps.MarkerImage(
              place.icon,
              new google.maps.Size(71, 71),
              new google.maps.Point(0, 0),
              new google.maps.Point(17, 34),
              new google.maps.Size(35, 35));
          marker.setIcon(image);
          marker.setPosition(place.geometry.location);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });
		*/
		
		/* //error google.maps.places.SearchBox is undefined...
		var input = document.getElementById('placesQuery');
		
		var searchBox = new google.maps.places.SearchBox(input);x
		searchBox.bindTo('bounds', map);
		
		google.maps.event.addListener(searchbox, 'places_changed', function() {
		  var places = searchbox.getPlaces();

		  for (var i = 0, marker; marker = markers[i]; i++) {
			marker.setMap(null);
		  }

		  markers = [];
		  var bounds = new google.maps.LatLngBounds();
		  for (var i = 0, place; place = places[i]; i++) {
			var image = new google.maps.MarkerImage(
				place.icon, new google.maps.Size(71, 71),
				new google.maps.Point(0, 0), new google.maps.Point(17, 34),
				new google.maps.Size(25, 25));

			var marker = new google.maps.Marker({
			  map: map,
			  icon: image,
			  title: place.name,
			  position: place.geometry.location
			});

			markers.push(marker);

			bounds.extend(place.geometry.location);
		  }

		  map.fitBounds(bounds);
		});
		*/
	}
}

function placesSearchBounds(){
	var request = {
		/*location: pyrmont,
		radius: '500',*/
		bounds: map.getBounds(),
		types: ['store', 'grocery_or_supermarket', 'shopping_mall'] //, 'establishment'
	};
	placesService.search(request, showPlacesCallback);
}

function placesSearchQuery(queryString){
	var request = {
		/*location: pyrmont,
		radius: '500',*/
		bounds: map.getBounds(),
		query: queryString
	};
	placesService.textSearch(request, showPlacesCallback);
}

function showPlacesCallback(results, status, pagination) {
	jQuery('#places_results').get(0).scrollTop = 0;
	for(var i=0; i< placesResult.length; ++i){
		placesResult[i].marker.setMap(null);
		placesResult[i].searchResult.remove();
		placesResult[i].marker = undefined;
	}
	placesResult = [];
	if (status == google.maps.places.PlacesServiceStatus.OK) {
		for (var i = 0; i < results.length; ++i) {
			showPlacesResult(results[i]);
		}
	}
	/* //dont work... why???
	if (pagination.hasNextPage) {
		var nextResults = jQuery('<div class="places_moreResult"><div class="button">show next results</div></div>');
		jQuery('#places_results').append(nextResults);
		nextResults.find('.button').get(0).onClick = pagination.nextPage;
	}
	*/
}

function showPlacesResult(place) {
	var placeLoc = place.geometry.location;
	var marker = new google.maps.Marker({
		map: map,
		position: placeLoc,
		icon: new google.maps.MarkerImage(
              place.icon,
              new google.maps.Size(71, 71),
              new google.maps.Point(0, 0),
              new google.maps.Point(17, 34),
              new google.maps.Size(35, 35)),
	});
	var searchResult = jQuery('<div class="places_result"><img src="' + place.icon + '" style="float:left"/><div class="details"><div class="name">' + place.name + '</div><div class="address">' + ((place.formatted_address)?place.formatted_address:place.vicinity) + '</div><a href="' + placesResult.length + '" class="button">show on Map</a></div><div class="clearfix"></div></div>');
	jQuery('#places_results').append(searchResult);
	place.marker = marker;
	place.searchResult = searchResult;
	placesResult.push(place);

	google.maps.event.addListener(marker, 'click', function() {
		showPlacesDetails(place);
	});
}

function showPlacesDetails(place){
	//var html = '<div class="places_infoblock"><img src="' + place.icon + '" />' + '<div class="places_info">' + place.name + '<br>' + ((place.formatted_address)?place.formatted_address:place.vicinity) + '</div>';
	//infowindow.setContent(html);
	//infowindow.open(map, place.marker);
		
	var request = {
		reference: place.reference
	};
	placesDetailRequestStartedFor = place;
	placesService.getDetails(request, showPlaceDetailCallback);	
}

function showPlaceDetailCallback(place, status) {
	if (status == google.maps.places.PlacesServiceStatus.OK) {
		if (placesDetailRequestStartedFor.id != place.id){
			var placeLoc = place.geometry.location;
			var marker = new google.maps.Marker({
				map: map,
				position: placeLoc
			});
			place.marker = marker;
		} else {
			place.marker = placesDetailRequestStartedFor.marker;
		}
		
		placesLastDetail = place;
		
		var openTimes="";
		if (place.opening_hours){
			var periods = place.opening_hours.periods;
			openTimes+= '<div class="places_openTimes">';
			for(var i=0; i<periods.length; ++i){
				openTimes += '<span class="timeLine">' + weekdayNames[periods[i].open.day] + ": " + periods[i].open.time +  " - " + periods[i].close.time + "</span>";
			}
			openTimes+= '</div>';
		}
		
		var html = '<div class="places_infoblock"><img src="' + place.icon + '" />' + '<div class="places_info"><div class="name">' + place.name + '</div><div class="address">' + ((place.formatted_address)?place.formatted_address:place.vicinity) + '</div>' + openTimes + '<div class="button">use address for new Store</div></div>';
		infowindow.setContent(html);
		infowindow.open(map, place.marker);
	} else {
		placesLastDetail = undefined;
	}
}

jQuery(function($){
	jQuery('body').undelegate('#setMarkerCurrentGPS','click').delegate('#setMarkerCurrentGPS','click',function(){
		locateUser(MarkerCurrentGPSCallback, undefined, false);
		return false;
	});
	
	jQuery('body').undelegate('#updateCurrentGPS','click').delegate('#updateCurrentGPS','click',function(){
		lastLocation = initialLocation;
		locateUser(UpdateCurrentGPSCallback, undefined, false);
		return false;
	});
	
	jQuery('body').undelegate('#useLocation','click').delegate('#useLocation','click', function(){
		var form = jQuery('#address-form');
		if (form.length != 0){
			var lat = form.find('#Stores_STO_GPS_LAT').val();
			var lng = form.find('#Stores_STO_GPS_LNG').val();
			if (lat != '' && lng != ''){
				initialLocation = new google.maps.LatLng(lat, lng);
				lastCords = null;
				locateAddressCallback(3);
			} else {
				alert('You need coordinates, please press the "Address to GPS" button to translate address to coordinates.')
				return false;
			}
		}
		jQuery.fancybox.close();
		return false;
	});
	
	jQuery('body').undelegate('#placesByQuery','click').delegate('#placesByQuery','click', function(){
		placesSearchQuery(jQuery('#placesQuery').val());
		return false;
	});
	jQuery('body').undelegate('#placesQuery','keyup').delegate('#placesQuery','keyup', function(event){
		if(event.keyCode == 13){
			placesSearchQuery(jQuery('#placesQuery').val());
		}
	});
	jQuery('body').undelegate('#placesByRange','click').delegate('#placesByRange','click', function(){
		placesSearchBounds();
		return false;
	});
	
	jQuery('body').undelegate('#places_results .places_result .button','click').delegate('#places_results .places_result .button','click', function(){
		var index = jQuery(this).attr('href');
		map.setCenter(placesResult[index].geometry.location);
		showPlacesDetails(placesResult[index]);
		return false;
	});
	
	jQuery('body').undelegate('.places_infoblock .button','click').delegate('.places_infoblock .button','click', function(){
		parts = placesLastDetail.address_components;
		
		var form = jQuery('#address-form');
		if (form.length == 0){
			form = jQuery('#stores-form');
		}
		
		var nameField = form.find('#Stores_STO_NAME');
		nameField.val(placesLastDetail.name);
		
		var telField = form.find('#Stores_STO_PHONE');
		if (placesLastDetail.international_phone_number){
			telField.val(placesLastDetail.international_phone_number);
		} else if (placesLastDetail.formatted_phone_number){
			telField.val(placesLastDetail.formatted_phone_number);
		} else {
			telField.val('');
		}
		
		var latField = form.find('#Stores_STO_GPS_LAT');
		var lngField = form.find('#Stores_STO_GPS_LNG');
		setGeocodeMarker(placesLastDetail.geometry.location, latField, lngField);
		
		
		var streetField = form.find('#Stores_STO_STREET');
		var noField = form.find('#Stores_STO_HOUSE_NO');
		var zipField = form.find('#Stores_STO_ZIP');
		var cityField = form.find('#Stores_STO_CITY');
		var stateField = form.find('#Stores_STO_STATE');
		var countryField = form.find('#Stores_STO_COUNTRY');
		
		streetField.val('');
		noField.val('');
		zipField.val('');
		cityField.val('');
		stateField.val('');
		countryField.val('');
		
		for(var i=0; i<parts.length; ++i){
			for (var j=0; j<parts[i].types.length; ++j){
				if (parts[i].types[j] == 'route'){
					streetField.val(parts[i].long_name);
				} else if (parts[i].types[j] == 'street_number'){
					noField.val(parts[i].long_name);
				} else if (parts[i].types[j] == 'postal_code'){
					zipField.val(parts[i].long_name);
				} else if (parts[i].types[j] == 'locality'){
					cityField.val(parts[i].long_name);
				} else if (parts[i].types[j] == 'administrative_area_level_1'){
					stateField.val(parts[i].long_name);
				} else if (parts[i].types[j] == 'country'){
					countryField.find('option :selected').removeAttr('selected');
					countryField.find('option[value=' + parts[i].short_name + ']').attr('selected','selected');
				}
			}
		}
		return false;
	});
});


/** Converts numeric degrees to radians */
if (typeof(Number.prototype.toRad) === "undefined") {
  Number.prototype.toRad = function() {
    return this * Math.PI / 180;
  }
}

/** Converts numeric radians to degrees */
if (typeof(Number.prototype.toDeg) === "undefined") {
  Number.prototype.toDeg = function() {
    return this / Math.PI * 180;
  }
}