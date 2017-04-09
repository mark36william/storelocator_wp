var store_wpress_map;
var store_wpress_markers = [];
var store_wpress_infoWindow;
var store_wpress_panorama;

jQuery('#geocode_address_btn').live('click', function(event) {
	event.preventDefault();
	var address = jQuery('#address2geocode').val();
	
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var lat = results[0].geometry.location.lat();
			var lng = results[0].geometry.location.lng();
			jQuery('#add_store_form').hide();
			jQuery('#add_store_form2').show();
			jQuery('#lat').val(lat);
			jQuery('#lng').val(lng);
			jQuery('#address').val(address);
			jQuery('#address_display').html(address);
			var img = '<img src="http://maps.google.com/maps/api/staticmap?center='+lat+','+lng+'&zoom=15&size=300x200&markers=color:red|'+lat+','+lng+'&sensor=false">';
			jQuery('#map_display').html(img);
		}
	});
});

jQuery('#edit_geocode_address').live('click', function(event) {
	event.preventDefault();
	jQuery('#add_store_form').show();
	jQuery('#add_store_form2').hide();
	jQuery('#address2geocode').val(jQuery('#address').val());
});

// ###################
// START Widget stores
function display_widget_closest_stores() {
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(closest_stores_detectionSuccess, closest_stores_detectionError, {maximumAge:Infinity});
	}
}

function closest_stores_detectionSuccess(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	
	var page_number = 1;
	
	//loading icon
	if(jQuery('#widget_store_locator_list').html()=='') {
		var img = '<img src="' + Store_wpress.plugin_url + 'graph/ajax-loader.gif">';
		jQuery('#widget_store_locator_list').html(img);
	}
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_list&page_number=' + page_number + '&lat=' + lat + '&lng=' + lng + '&nb_display=' + Store_wpress.widget_nb_display + '&no_info_links=1&widget_display=1',
		success: function(msg) {
			var stores = msg.stores;
			jQuery('#widget_store_locator_list').html(stores);
		}
	});
}

function closest_stores_detectionError() {
	jQuery('#widget_store_locator_list').html('You need to share your location in order to view the locations list. <a href="javascript:window.location.reload();">Reload the page?</a>');
}
// ##################
// END Closest stores

// ####################
// START closest stores
function search_closest_locations() {
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(search_closest_locations_success, search_closest_locations_error, {maximumAge:Infinity});
	}
}

function search_closest_locations_success(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	Store_wpress.current_lat = lat;
	Store_wpress.current_lng = lng;
	search_locations2();
}

function search_closest_locations_error() {
	search_locations2();
}
// ##################
// END closest stores

function init_basic_map(lat, lng, marker_text, marker_icon) {
	store_wpress_map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(lat, lng),
		zoom: Store_wpress.zoom_detail,
		scrollwheel: false,
		mapTypeId: Store_wpress.map_type,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	});
	
	var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
	
	createMarker(latlng, lat, lng, marker_text, marker_icon);
	
	if(Store_wpress.streetview=='on') streetView(lat,lng);
	
	store_wpress_infoWindow = new google.maps.InfoWindow();
}

function store_locator_load(type) {
	if(type=='map') {
		jQuery('body').data('type', 'map');
		init_stores_map();
	}
	else if(type=='both') {
		jQuery('body').data('type', 'both');
		init_stores_map_list();
	}
	else {
		jQuery('body').data('type', 'list');
		init_stores_list();
	}
}

function init_stores_map_list() {
	init_stores_map();
	init_stores_list();
}

function init_stores_list() {
	//loading icon
	if(jQuery('#store_locator_list').html()=='') {
		var img = '<img src="' + Store_wpress.plugin_url + 'graph/ajax-loader.gif">';
		jQuery('#store_locator_list').html(img);
	}
	
	//page number setup
	var page_number = jQuery('body').data("page_number");
	if(page_number==null) {
		page_number=1;
		jQuery('body').data("page_number", page_number);
	}
	
	//lat & lng setup
	var lat = '';
	var lng = '';
	if(Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='') {
		lat = Store_wpress.searched_lat;
		lng = Store_wpress.searched_lng;
	}
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_list&page_number=' + page_number + '&lat=' + lat + '&lng=' + lng + '&category_id=' + Store_wpress.category_id + '&category2_id=' + Store_wpress.category2_id + '&radius_id=' + Store_wpress.radius_id + '&nb_display=' + Store_wpress.nb_display + '&display_type=' + jQuery('body').data('type'),
		success: function(msg) {
			jQuery('#stores_locator_title').html(msg.title);
			jQuery('#previousNextButtons').html(msg.previousNextButtons);
			if (jQuery('#previousNextButtons2').length > 0) jQuery('#previousNextButtons2').html(msg.previousNextButtons);
			//alert(msg.stores);
			if(msg.stores=='') jQuery('#store_locator_list').html('No results found');
			else jQuery('#store_locator_list').html(msg.stores);
		}
	});
}

function init_stores_map() {
	
	if(Store_wpress.current_lat=='') {
		store_wpress_map = new google.maps.Map(document.getElementById("map"), {
			center: new google.maps.LatLng(Store_wpress.lat, Store_wpress.lng),
			zoom: Store_wpress.zoom,
			scrollwheel: false,
			mapTypeId: Store_wpress.map_type,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		});
		store_wpress_infoWindow = new google.maps.InfoWindow();
	}
	
	if(Store_wpress.search=='on' || jQuery('#store_wpress_address').val()!='') {
		search_locations();
	}
}

function search_locations() {
	if(Store_wpress.closest_stores=='on') {
		if(Store_wpress.current_lat!='' && Store_wpress.current_lng!='') {
			search_locations2();
		}
		else {
			search_closest_locations();
		}
	}
	else {
		search_locations2();
	}
}

jQuery(".displayStoreMap").live('click', function(event) {
	event.preventDefault();
	var id = jQuery(this).attr('id');
	var lat = jQuery(this).attr('lat');
	var lng = jQuery(this).attr('lng');
	
	var content = jQuery('#infowindow_'+id).html();
	var marker_icon = jQuery('#marker_icon_'+id).html();
	
	var latlng = new google.maps.LatLng(
		parseFloat(lat),
		parseFloat(lng)
	);
	
	init_basic_map(lat, lng, '', '');
	
	clearLocations();
	createMarker(latlng, lat, lng, content, marker_icon, 1);
});

function search_locations2() {
	
	//page number init
	var page_number = jQuery('body').data("page_number");
	if(page_number==null) {
		page_number=1;
		jQuery('body').data("page_number", page_number);
	}
	
	//lat & lng setup
	var lat = '';
	var lng = '';
	if(Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='') {
		lat = Store_wpress.searched_lat;
		lng = Store_wpress.searched_lng;
	}
	
	var nb_display;
	nb_display = Store_wpress.nb_display;
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_map&page_number=' + page_number + '&lat=' + lat + '&lng=' + lng + '&category_id=' + Store_wpress.category_id + '&category2_id=' + Store_wpress.category2_id + '&radius_id=' + Store_wpress.radius_id + '&nb_display=' + nb_display,
		success: function(msg) {
			var locations = msg.locations;
			var markersContent = msg.markersContent;
			var bounds = new google.maps.LatLngBounds();
			
			jQuery('#stores_locator_title').html(msg.title);
			jQuery('#previousNextButtons').html(msg.previousNextButtons);
			if (jQuery('#previousNextButtons2').length > 0) jQuery('#previousNextButtons2').html(msg.previousNextButtons);
			clearLocations();
			
			//alert(locations.length);
			
			for (var i=0; i<locations.length; i++) {
				var name = locations[i]['name'];
				var address = locations[i]['address'];
				var distance = parseFloat(locations[i]['distance']);
				var latlng = new google.maps.LatLng(
					parseFloat(locations[i]['lat']),
					parseFloat(locations[i]['lng'])
				);
				//category custom marker
				var marker_icon = locations[i]['marker_icon'];
				
				//if no category marker, set custom marker
				//if(marker_icon==null) marker_icon = Store_wpress.custom_marker;
				
				//createOption(name, distance, i);
				createMarker(latlng, locations[i]['lat'], locations[i]['lng'], markersContent[i], marker_icon);
				
				bounds.extend(latlng);
	       	}
	       	
	       	if(locations.length>1) {
	       		store_wpress_map.fitBounds(bounds);
	       	}
	       	else {
				store_wpress_map.setCenter(bounds.getCenter());
				store_wpress_map.setZoom(15);
	       	}
		}
	});
}

function clearLocations() {
	store_wpress_infoWindow.close();
	for (var i = 0; i < store_wpress_markers.length; i++) {
		store_wpress_markers[i].setMap(null);
	}
	store_wpress_markers.length = 0;
}

function store_wpress_setAddress(address, display_type) {
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			Store_wpress.current_lat = results[0].geometry.location.lat();
			Store_wpress.current_lng = results[0].geometry.location.lng();
			
			Store_wpress.searched_lat = results[0].geometry.location.lat();
			Store_wpress.searched_lng = results[0].geometry.location.lng();
			
			store_locator_load(display_type);
		}
	});
}

jQuery("#store_wpress_category_filter").live('change', function(event) {
	event.preventDefault();
	var category_id = jQuery(this).val();
	jQuery('body').data('page_number', 1);
	Store_wpress.category_id = category_id;
	
	if(jQuery('body').data('type')=='map') search_locations();
	else if(jQuery('body').data('type')=='both') init_stores_map_list();
	else init_stores_list();
});

jQuery("#store_wpress_category2_filter").live('change', function(event) {
	event.preventDefault();
	var category2_id = jQuery(this).val();
	jQuery('body').data('page_number', 1);
	Store_wpress.category2_id = category2_id;
	
	if(jQuery('body').data('type')=='map') search_locations();
	else if(jQuery('body').data('type')=='both') init_stores_map_list();
	else init_stores_list();
});

jQuery("#store_wpress_distance_filter").live('change', function(event) {
	event.preventDefault();
	var radius_id = jQuery(this).val();
	jQuery('body').data('page_number', 1);
	Store_wpress.radius_id = radius_id;
	
	if(jQuery('body').data('type')=='map') search_locations();
	else if(jQuery('body').data('type')=='both') init_stores_map_list();
	else init_stores_list();
});

jQuery("#store_wpress_search_btn").live('click', function(event) {
	event.preventDefault();
	var address = jQuery("#store_wpress_address").val();
	jQuery('body').data("page_number", 1);
	
	//suffix
	//address += ', Australia';
	
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			
			Store_wpress.searched_lat = results[0].geometry.location.lat();
			Store_wpress.searched_lng = results[0].geometry.location.lng();
			
			if(jQuery('body').data('type')=='map') search_locations();
			else if(jQuery('body').data('type')=='both') init_stores_map_list();
			else init_stores_list();
		}
		else {
			Store_wpress.searched_lat = '';
			Store_wpress.searched_lng = '';
			if(jQuery('body').data('type')=='map') search_locations();
			else if(jQuery('body').data('type')=='both') init_stores_map_list();
			else init_stores_list();
		}
	});
});

function setStreetView(latlng) {
    store_wpress_panorama = store_wpress_map.getStreetView();
    store_wpress_panorama.setPosition(latlng);
    store_wpress_panorama.setPov({
      heading: 265,
      zoom:1,
      pitch:0}
    );
}

jQuery("#displayStreetView").live('click', function(event) {
	event.preventDefault();
	//alert('cool');
	store_wpress_panorama.setVisible(true);
});

jQuery("#store_locator_next").live('click', function(event) {
	event.preventDefault();
	var page_number = jQuery('body').data("page_number");
	jQuery('body').data("page_number", (page_number+1));
	if(jQuery('body').data('type')=='map') search_locations();
	else if(jQuery('body').data('type')=='both') init_stores_map_list();
	else init_stores_list();
});

jQuery("#store_locator_previous").live('click', function(event) {
	event.preventDefault();
	var page_number = jQuery('body').data("page_number");
	jQuery('body').data("page_number", (page_number-1));
	if(jQuery('body').data('type')=='map') search_locations();
	else if(jQuery('body').data('type')=='both') init_stores_map_list();
	else init_stores_list();
});

function createMarker(latlng, lat, lng, html, marker_icon, window_flag) {
	
	if(marker_icon===null || marker_icon===undefined || marker_icon==='') marker_icon=Store_wpress.custom_marker;
	
	var marker = new google.maps.Marker({
		map: store_wpress_map,
		position: latlng,
		icon: marker_icon,
		animation: google.maps.Animation.DROP
	});
	
	if(window_flag==1) {
		store_wpress_infoWindow.setContent(html);
		store_wpress_infoWindow.open(store_wpress_map, marker);
		setStreetView(latlng);		
	}
	else {
		google.maps.event.addListener(marker, 'click', function() {
			store_wpress_infoWindow.setContent(html);
			store_wpress_infoWindow.open(store_wpress_map, marker);
			setStreetView(latlng);
		});
	}
	
	store_wpress_markers.push(marker);
}

function streetView(lat,lng) {
	var dom = 'streetview';
	panorama = new google.maps.StreetViewPanorama(document.getElementById(dom));
	displayStreetView(lat,lng, dom);
}

function displayStreetView(lat,lng, dom) {
	var latlng = new google.maps.LatLng(lat,lng);
	
	var panoramaOptions = {
	  position: latlng,
	  panControl: true,
	  linksControl: true,
	  enableCloseButton: true,
	  disableDoubleClickZoom: true,
	  addressControl: false,
	  visible: true,
	  pov: {
	    heading: 270,
	    pitch: 0,
	    zoom: 1
	  }
	};
	store_wpress_panorama = new google.maps.StreetViewPanorama(document.getElementById(dom),panoramaOptions);
	store_wpress_map.setStreetView(store_wpress_panorama);
}
