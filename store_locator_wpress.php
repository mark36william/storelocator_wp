<?php
/*
Plugin Name: Store Locator for WordPress
Plugin URI: http://yougapi.com/products/wp/store_locator/
Description: Integrate an Advanced and fully featured Store Locator into your WordPress
Version: 1.9.7
Author: Yougapi Technology LLC
Author URI: http://yougapi.com
*/

require_once dirname( __FILE__ ).'/class/wpress_framework/WPress_framework.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_settings.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_admin.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_db.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_shortcode.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_display.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_widget.php';

/*
- Support of a second category
- Support on list display and Map in the same time
- Support of distance filters
- Centralised keywords for a very easy translation
- Client side geocoding to avoid reaching Google quotas limits
*/

$GLOBALS['store_locator_lang']['store'] = 'store';
$GLOBALS['store_locator_lang']['stores'] = 'stores';
$GLOBALS['store_locator_lang']['store_name'] = 'Store name';
$GLOBALS['store_locator_lang']['address'] = 'Address';
$GLOBALS['store_locator_lang']['url'] = 'Url';
$GLOBALS['store_locator_lang']['tel'] = 'Tel';
$GLOBALS['store_locator_lang']['email'] = 'Email';
$GLOBALS['store_locator_lang']['description'] = 'Description';
$GLOBALS['store_locator_lang']['more_information'] = 'More information';
$GLOBALS['store_locator_lang']['related_post'] = 'Related post';
$GLOBALS['store_locator_lang']['more_details'] = 'More details';
$GLOBALS['store_locator_lang']['get_directions'] = 'Get directions';
$GLOBALS['store_locator_lang']['to_here'] = 'To here';
$GLOBALS['store_locator_lang']['from_here'] = 'From here';
$GLOBALS['store_locator_lang']['streetview'] = 'Streetview';
$GLOBALS['store_locator_lang']['view_all_stores'] = 'View all stores';
$GLOBALS['store_locator_lang']['next'] = 'Next';
$GLOBALS['store_locator_lang']['previous'] = 'Previous';
$GLOBALS['store_locator_lang']['search_by_address'] = 'Search by address';
$GLOBALS['store_locator_lang']['search'] = 'Search';
$GLOBALS['store_locator_lang']['distance'] = 'Distance';
$GLOBALS['store_locator_lang']['category'] = 'Category';
$GLOBALS['store_locator_lang']['all_categories'] = 'All categories';

$GLOBALS['store_locator_lang']['category2'] = 'Practice';
$GLOBALS['store_locator_lang']['all_categories2'] = 'All practices';

$GLOBALS['store_locator_settings']['distance'] = array('1', '5', '10', '25', '50', '100');
$GLOBALS['store_locator_settings']['category2_flag'] = 0;


class Store_locator_wpress {
	
	function Store_locator_wpress() {
		add_action('plugins_loaded', array(__CLASS__, 'add_scripts'));
		add_action('wp_footer', array(__CLASS__, 'add_onload'));
		
		//AJAX
		add_action( 'wp_ajax_nopriv_store_wpress_listener', array(__CLASS__, 'store_wpress_listener') );
		add_action( 'wp_ajax_store_wpress_listener', array(__CLASS__, 'store_wpress_listener') );
		
		if(is_admin()) {
			register_activation_hook(__FILE__, array(__CLASS__, 'on_plugin_activation'));
		}
	}
	
	function add_onload() {
	    ?>
	    <script type="text/javascript">
	    my_onload_callback = function() {
	    	<?php echo $GLOBALS['store_wpress_js_on_ready']; ?>
	    };
		
	    if( typeof jQuery == "function" ) { 
	        jQuery(my_onload_callback); // document.ready
	    }
	    else {
	        document.getElementsByTagName('body')[0].onload = my_onload_callback; // body.onload
	    }
	    
	    </script>
	    <?php
	}
	
	function add_scripts() {
		if (!is_admin()) {
			
		}
	}
	
	//AJAX calls
	function store_wpress_listener() {
		
		$method = $_POST['method'];
		
		//display stores Map
		if($method=='display_map') {
			$lat = $_POST['lat'];
			$lng = $_POST['lng'];
			$page_number = $_POST['page_number'];
			$category_id = $_POST['category_id'];
			$category2_id = $_POST['category2_id'];
			$radius_id = $_POST['radius_id'];
			$nb_display = $_POST['nb_display'];
			
			$sdb1 = new Store_locator_wpress_db();
			$ss1 = new Store_locator_wpress_shortcode();
			
			$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
			if($nb_display=='') $nb_display = $options['nb_stores'];
			$distance_unit = $options['distance'];
			
			if($page_number=='') $page_number = 1; //default value just in case
			if($nb_display=='') $nb_display = 20; //default value just in case
			
			$locations =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 'page_number'=>$page_number, 'nb_display'=>$nb_display, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			
			//calculate number total of stores
			$stores2 =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng,
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			$nb_stores = count($stores2);
			
			//previous/next buttons
			$previousNextButtons = $ss1->displayPreviousNextButtons($page_number, $nb_stores, $nb_display);
			
			if($nb_stores==1) $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['store'];
			else $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['stores'];
			
			$results['title'] = $title;
			$results['previousNextButtons'] = $previousNextButtons;
			$results['locations'] = $locations;
			$results['markersContent'] = $ss1->displayMarkersContent($locations);
			$results = json_encode($results);
			
			echo $results;
			exit;
		}
		
		//display stores list
		else if($method=='display_list') {
			$page_number = $_POST['page_number'];
			$lat = $_POST['lat'];
			$lng = $_POST['lng'];
			$category_id = $_POST['category_id'];
			$category2_id = $_POST['category2_id'];
			$radius_id = $_POST['radius_id'];
			$nb_display = $_POST['nb_display'];
			$no_info_links = $_POST['no_info_links']; //activate or no the links display
			$widget_display = $_POST['widget_display'];
			$display_type = $_POST['display_type'];
			
			$sdb1 = new Store_locator_wpress_db();
			$ss1 = new Store_locator_wpress_shortcode();
			
			$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
			if($nb_display=='') $nb_display = $options['nb_stores'];
			$distance_unit = $options['distance'];
			
			if($page_number=='') $page_number = 1; //default value just in case
			if($nb_display=='') $nb_display = 20; //default value just in case
			
			$stores =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 'page_number'=>$page_number, 'nb_display'=>$nb_display, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			
			//calculate number total of stores
			$stores2 =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			$nb_stores = count($stores2);
			
			//previous/next buttons
			$previousNextButtons = $ss1->displayPreviousNextButtons($page_number, $nb_stores, $nb_display);
			
			if($lat!=''&&$lng!='') $distance_display=1;
			
			$sd1 = new Store_locator_wpress_display();
			if($nb_stores>0) {
				if($display_type=='both') $content = $sd1->display_stores_list($stores, array('distance_display'=>$distance_display, 'no_info_links'=>$no_info_links, 'widget_display'=>$widget_display));
				else $content = $sd1->display_stores_list($stores, array('distance_display'=>$distance_display, 'no_info_links'=>$no_info_links, 'widget_display'=>$widget_display));
			}
			else $content='';
			
			if($nb_stores==1) $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['store'];
			else $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['stores'];
			
			$results['title'] = $title;
			$results['previousNextButtons'] = $previousNextButtons;
			$results['stores'] = $content;
			$results = json_encode($results);
			
			echo $results;
			exit;
		}
	}
	
	function on_plugin_activation() {
		if(self::notify_verification()) {
			//create the plugin table if it doesn't exist
			$sdb1 = new Store_locator_wpress_db();
			$sdb1->setup_tables();
			//set default settings
			$ss1 = new Store_locator_wpress_settings();
			$ss1->set_default_values();		
		}
	}
	
	function notify_verification() {
		$url = 'http://yougapi.com/updates/?item=locator_wpress&s='.site_url();
		wp_remote_get($url);
		return 1;
	}
}

new Store_locator_wpress();

?>