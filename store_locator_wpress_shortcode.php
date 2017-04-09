<?php

class Store_locator_wpress_shortcode {
	
	static $js_object;
	static $js_map;
	
	function Store_locator_wpress_shortcode() {
		add_shortcode( 'store_wpress', array(__CLASS__, 'display_stores') );
		add_action('wp_footer', array(__CLASS__, 'add_scripts'));
	}
	
	function add_scripts() {
		if(self::$js_map) {
			self::add_js_map();
		}
	}
	
	function add_js_map() {
		//Google Map API
		wp_register_script('gmap_api', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
		wp_print_scripts('gmap_api');
		//Store locator
		wp_register_script('store_locator_js', plugin_dir_url( __FILE__ ).'class/store_locator/include/js/script.js', array('jquery'));
		wp_print_scripts('store_locator_js');
	}
	
	function js_wpress_declaration($criteria=array()) {
		$display = $criteria['display'];
		$category = $criteria['category'];
		$nb_display = $criteria['nb_display'];
		
		if(self::$js_object!=1) {
			$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
			$options2 = get_option(STORE_LOCATOR_WPRESS_SETTINGS_store_details);
			
			$options3 = get_option(STORE_LOCATOR_WPRESS);
			$widget_nb_display = $options3['nb_stores'];
			
			echo '<script>
			/* <![CDATA[ */
			var Store_wpress = {
				"ajaxurl": "'.admin_url('admin-ajax.php').'", "plugin_url": "'.plugin_dir_url( __FILE__ ).'", 
				"category_id": "'.$category.'", "category2_id": "", "radius_id": "", 
				"nb_display": "'.$nb_display.'", "widget_nb_display": "'.$widget_nb_display.'", 
				"map_type": "'.$options['map_type'].'", "zoom": '.(int)$options['zoom'].', 
				"lat": '.(int)$options['lat'].', "lng": '.(int)$options['lng'].', 
				"current_lat": "", "current_lng": "", 
				"searched_lat": "", "searched_lng": "", 
				"custom_marker": "'.$options['custom_marker'].'",
				"search": "'.$options['default_stores_search'].'", "closest_stores": "'.$options['closest_stores'].'", 
				"zoom_detail": '.(int)$options2['zoom'].', "streetview": "'.$options2['streetview'].'"
			};
			/* ]]> */
			</script>';
		}
		
		self::$js_object=1;
	}
	
	function display_stores($atts, $content = null, $code) {
		extract(shortcode_atts(array(
		'display' => '',
		'category' => '',
		'nb_display' => '',
		'category_filter' => '',
		'distance_filter' => ''
		), $atts));
		
		self::js_wpress_declaration(array('category'=>$category, 'nb_display'=>$nb_display));
		
		$content .= '<style type="text/css">#map img { max-width: none; }</style>';
		
		self::$js_map = true;
		
		//display store details
		if($_GET['store_id']>0) {
			
			$sdb1 = new Store_locator_wpress_db();
			$stores = $sdb1->return_stores(array('id'=>$_GET['store_id']));
			
			$sb1 = new Store_locator_wpress_display();
			$store_details = $sb1->get_store_details_display($stores);
			$content = $store_details;
			
			$id = $stores[0]['id'];
			$name = $stores[0]['name'];
			$logo = $stores[0]['logo'];
			$address = $stores[0]['address'];
			$url = $stores[0]['url'];
			$marker_icon = $stores[0]['marker_icon'];
			
			$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
			
			//get infowindow display
			$sd = new Store_locator_wpress_display();
			$marker_text = $sd->getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url, 'streetview'=>$options['streetview'], 'directions'=>$options['directions']));
			
			$GLOBALS['store_wpress_js_on_ready'] = 'init_basic_map(\''.$stores[0]['lat'].'\',\''.$stores[0]['lng'].'\', \''.addslashes($marker_text).'\', \''.$marker_icon.'\');';
		}
		
		//display stores (map or list)
		else {
			
			if($_GET['address']!='') {
				$GLOBALS['store_wpress_js_on_ready'] = "store_wpress_setAddress('".$_GET['address']."', '$display');";
			}
			else {
				if($display=='list') $GLOBALS['store_wpress_js_on_ready'] .= 'store_locator_load("list");';
				elseif($display=='both') $GLOBALS['store_wpress_js_on_ready'] .= 'store_locator_load("both");';
				else $GLOBALS['store_wpress_js_on_ready'] .= 'store_locator_load("map");';
			}
			
			//search box
			$search_box = self::displayAddressSearchBox(array('category_filter'=>$category_filter, 'distance_filter'=>$distance_filter));
			$content .= $search_box;
			
			$sdb1 = new Store_locator_wpress_db();
			$nb_stores_tab = $sdb1->return_nb_stores(array('category_id'=>$category));
			$nb_stores = $nb_stores_tab['nb'];
			
			//number of store & previous/next buttons
			$content .= '<div style="width:100%; padding-bottom:5px; border-bottom: 1px solid #e7e7e7; margin-bottom:10px;">';
				
				$content .= '<span id="stores_locator_title">';
				if($nb_stores==1) $content .= $nb_stores.' '.$GLOBALS['store_locator_lang']['store'];
				elseif ($nb_stores>1) $content .= $nb_stores.' '.$GLOBALS['store_locator_lang']['stores'];
				$content .= '</span>';
				
				$content .= '<div style="float:right;" id="previousNextButtons"></div>';
				
			$content .= '</div>';
			
			if($display=='list') {
				$content .= self::get_stores_display_list();
			}
			elseif($display=='both') {
				$content .= self::get_stores_display_map_list();
			}
			else {
				$content .= self::get_stores_display_map();
			}
		}
		
		$content = '<p>'.$content.'</p>';
		return $content;
	}
	
	/*
	More display
	*/
	
	function displayPreviousNextButtons($page_number, $nb_stores, $nb_display) {
		if($page_number>1) {
			$display .= '<a href="#" id="store_locator_previous">'.$GLOBALS['store_locator_lang']['previous'].'</a> ';
			$display .= ' - <b>'.$page_number.'</b>';
			$previous_flag=1;
		}
		if($nb_stores>($nb_display*$page_number)) {
			if($previous_flag==1) $display .= ' - ';
			$display .= '<a href="#" id="store_locator_next">'.$GLOBALS['store_locator_lang']['next'].'</a>';
		}
		return $display;
	}
	
	function displayMarkersContent($locations) {
		
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
		
		$sd = new Store_locator_wpress_display();
		
		for($i=0; $i<count($locations);$i++) {
			$id = $locations[$i]['id'];
			$name = $locations[$i]['name'];
			$logo = $locations[$i]['logo'];
			$address = $locations[$i]['address'];
			$url = $locations[$i]['url'];
			
			$markers[$i] .= $sd->getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url, 'streetview'=>$options['streetview'], 'directions'=>$options['directions'], 'more_details'=>1));
		}
		return $markers;
	}
	
	function format_marker_content() {
		
	}
	
	/*
	Start display functions
	*/
	
	function displayAddressSearchBox($criteria=array()) {
		$category_filter = $criteria['category_filter'];
		$distance_filter = $criteria['distance_filter'];
		
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
		$distance = $options['distance'];
		
		$display = '<div>'.$GLOBALS['store_locator_lang']['search_by_address'].'</div>';
		
		$display .= '<form method="GET">';
			$display .= '<input type="text" id="store_wpress_address" name="store_wpress_address" style="width:440px;" value="'.$_GET['address'].'" />';
			$display .= ' <input type="submit" id="store_wpress_search_btn" value="'.$GLOBALS['store_locator_lang']['search'].'" style="padding:2px;"/>';
			
			if($category_filter==1 || $distance_filter==1) {
				
				$display .= '<div style="margin-bottom:20px; margin-top:10px;">';

				if($GLOBALS['store_locator_settings']['category2_flag']==1) {
					$db1 = new Store_locator_wpress_db();
					$categories = $db1->return_categories2();
					
					$nb_stores_by_cat = $db1->return_nb_stores_by_category2();
					
					$display .= $GLOBALS['store_locator_lang']['category2'].': ';
					$display .= '<select id="store_wpress_category2_filter">';
					$display .= '<option value="">'.$GLOBALS['store_locator_lang']['all_categories2'].'</option>';
					for($i=0; $i<count($categories); $i++) {
						
						$nb_stores = $nb_stores_by_cat[$categories[$i]['id']];
						if($nb_stores=='') $nb_stores=0;
						
						$display .= '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$nb_stores.')</option>';
					}
					$display .= '</select>&nbsp;&nbsp;&nbsp;';
				}
				
				if($category_filter==1) {
					$db1 = new Store_locator_wpress_db();
					$categories = $db1->return_categories();
					
					$nb_stores_by_cat = $db1->return_nb_stores_by_category();
					
					$display .= $GLOBALS['store_locator_lang']['category'].': ';
					$display .= '<select id="store_wpress_category_filter">';
					$display .= '<option value="">'.$GLOBALS['store_locator_lang']['all_categories'].'</option>';
					for($i=0; $i<count($categories); $i++) {
						
						$nb_stores = $nb_stores_by_cat[$categories[$i]['id']];
						if($nb_stores=='') $nb_stores=0;
						
						$display .= '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$nb_stores.')</option>';
					}
					$display .= '</select>';
				}
				
				if($distance_filter==1) {
					
					$display .= '&nbsp;&nbsp;&nbsp;'.$GLOBALS['store_locator_lang']['distance'].': ';
					$display .= '<select id="store_wpress_distance_filter">';
					$display .= '<option value=""></option>';
					for($i=0; $i<count($GLOBALS['store_locator_settings']['distance']); $i++) {
						$display .= '<option value="'.$GLOBALS['store_locator_settings']['distance'][$i].'">'.$GLOBALS['store_locator_settings']['distance'][$i].' '.$distance.'</option>';
					}
					$display .= '</select>';
				}
				
				$display .= '</div>';
			}
			
		$display .= '</form>';
		
		return $display;
	}
	
	function get_stores_display_map() {
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
		$width = $options['width'];
		$height = $options['height'];
		$content .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.';"></div>';
		return $content;
	}
	
	function get_stores_display_list() {
		$content .= '<div id="store_locator_list"></div>';
		return $content;
	}

	function get_stores_display_map_list() {
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
		$width = $options['width'];
		$height = $options['height'];
		$content .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.';"></div>';
		$content .= '<br>';
		$content .= '<div id="store_locator_list"></div>';
		$content .= '<div id="previousNextButtons2"></div>';
		return $content;
	}
}

new Store_locator_wpress_shortcode();

?>