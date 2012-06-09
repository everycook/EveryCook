var initialLocation;
var locationBasel;
var locationWaldenburg;
var browserSupportFlag =  new Boolean();
var lastCords = null;
var gettingCached = false;
var fallbackDone = false;
var map;

var doLoadStores = false;
var doPlaceMarker = false;

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


var geocoder;
var lastGeocodeMarker;

//Initialize functions
function loadScript(sensor, region, https, loadStores, placeMarker) {
	doLoadStores = loadStores;
	doPlaceMarker = placeMarker;
	
	var url;
	if (https){
		url = "https://maps-api-ssl.google.com/maps/api/js";
	} else {
		url = "https://maps.google.com/maps/api/js";
	}
	url = url + "?sensor="+((sensor)?"true":"false") + ((region)?"&region="+region:"") + "&callback=initialize"; //"&language=" + lang
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

function initialize() {
	if (!locationBasel){
		locationBasel = new google.maps.LatLng(47.557473,7.592926);
	}
	if (!locationWaldenburg){
		locationWaldenburg = new google.maps.LatLng(47.37836,7.746305);
	}
	
	var myOptions = {
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	lastZoom=99;
	lastBounds=null;
	if (doLoadStores){
		google.maps.event.addListener(map, 'dragend', mapChanged);
		google.maps.event.addListener(map, 'zoom_changed', function(){
			google.maps.event.addListenerOnce(map, 'idle', mapChanged);
		});
		google.maps.event.addListenerOnce(map, 'idle', mapChanged);
		locateUser(locateCallback);
	} else if (!doPlaceMarker){
		locateUser(locateCallback);
	}
	
	initializeGeocoder();
}

function locateCallback(status){
	if (status === -1){
		alert("Geolocation service failed.");
		initialLocation = locationWaldenburg;
	} else if (status === -2){
		alert("Your browser doesn't support geolocation. We've placed you in Basel, Schweiz.");
		initialLocation = locationBasel;
	} else if (status !== 0){
		//if it's in a radius more than 500 meters, it seams to be a unexact location, save it in html/session so don't need to ask again and to show distances.
		if (!lastCords || lastCords.accuracy > 500){
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
	map.setCenter(initialLocation);
	if (doLoadStores){
		google.maps.event.addListenerOnce(map, 'idle', mapChanged);
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

function locateUser(callback){
	fallbackDone = false;
	if (jQuery('#current_gps_lat').val() != '' && jQuery('#current_gps_lng').val() != ''){
		if(jQuery('#current_gps_time').val() > (new Date().getTime())/1000){
			initialLocation = new google.maps.LatLng(jQuery('#current_gps_lat').val(), jQuery('#current_gps_lng').val());
			callback(0);
			return;
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
		var errorCallback = function() {
			window.clearTimeout(fallbackTimeout);
			switch(error.code) {
				case error.TIMEOUT:
					alert('timeout');
					if (gettingCached){
						doFallback();
						gettingCached = false;
						navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {timeout:60000}); //, enableHighAccuracy:true
					} else {
						callback(-1);
					}
					break;
				default:
					callback(-1);
			}
		}
		
		var fallbackTimeout = window.setTimeout(doFallback, 5000);
		gettingCached = true;
		//get cached value if not older than 10 min
		navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {maximumAge:600000, timeout:0});
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

function waldenburg() {
	map.setCenter(locationWaldenburg);
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
		
		//always load a bit more then visible, so draging is possible
		var latDiff = (southWest.lat()-northEast.lat()) * 0.2;
		var lngDiff = (southWest.lng()-northEast.lng()) * 0.2;
		southWest = new google.maps.LatLng(southWest.lat()+latDiff,southWest.lng()+lngDiff);
		northEast = new google.maps.LatLng(northEast.lat()-latDiff,northEast.lng()-lngDiff);
		lastBounds = new google.maps.LatLngBounds(southWest,northEast);
		
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
				
				if (typeId == 0){
					typeId = '';
				}
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
	return '<div class="store-popup">'
		   + ((imageUrl.trim().length>0)?'<img src="' + imageUrl + '">':'')
		   + '<div class="store-popup-adresse">'
		   + supplier + ' ' + name + '<br>'
		   + ((zip==0)?'':street + ' ' + houseNr + ', ' + zip + ' ' + city)
		   + '</div><br>'
		   + 'distance to home:' + distance
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
		
		google.maps.event.addListener(marker, 'click', function() {
			jQuery('#ProToSto_SUP_ID :selected').removeAttr('selected');
			jQuery('#ProToSto_SUP_ID option[value=' + supplierId + ']').attr('selected','selected');
			jQuery('#ProToSto_STY_ID :selected').removeAttr('selected');
			jQuery('#ProToSto_STY_ID option[value=' + typeId + ']').attr('selected','selected');
		});
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
				locateUser(locateCallback);
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
		draggable: true
	});
	if (typeof(latField) === 'undefined' || latField == null){
		var latField = jQuery('.cord_lat');
	}
	if (typeof(lngField) === 'undefined' || lngField == null){
		var lngField = jQuery('.cord_lng');
	}
	google.maps.event.addListener(lastGeocodeMarker, 'dragend', function(event) {
		//map.setCenter(event.latLng);
		latField.val(event.latLng.lat());
		lngField.val(event.latLng.lng());
	});
}

jQuery(function($){
	jQuery('body').undelegate('#Address_to_GPS','click').delegate('#Address_to_GPS','click',function(){
		street = jQuery('#Stores_STO_STREET').val();
		no = jQuery('#Stores_STO_HOUSE_NO').val();
		zip = jQuery('#Stores_STO_ZIP').val();
		city = jQuery('#Stores_STO_CITY').val();
		state = jQuery('#Stores_STO_STATE').val();
		country = jQuery('#Stores_STO_COUNTRY').val();
		
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

		codeAddress(address, jQuery('#Stores_STO_GPS_LAT'), jQuery('#Stores_STO_GPS_LNG'));
		return false;
	});

	jQuery('body').undelegate('#GPS_to_Address','click').delegate('#GPS_to_Address','click',function(){
		decodeAddress(jQuery('#Stores_STO_GPS_LAT').val(), jQuery('#Stores_STO_GPS_LNG').val(), jQuery('#Stores_STO_STREET'), jQuery('#Stores_STO_HOUSE_NO'), jQuery('#Stores_STO_ZIP'), jQuery('#Stores_STO_CITY'), jQuery('#Stores_STO_STATE'), jQuery('#Stores_STO_COUNTRY'));
		return false;
	});
});

function codeAddress(address, latField, lngField) {
	geocoder.geocode( {'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			setGeocodeMarker(results[0].geometry.location, latField, lngField);
			
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
				setGeocodeMarker(results[0].geometry.location);
				map.setCenter(results[0].geometry.location);
				
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
