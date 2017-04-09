<?php
define('STORE_LOCATOR_WPRESS_SETTINGS', 'store_locator_wpress_settings');
define('STORE_LOCATOR_WPRESS_SETTINGS_posts', 'store_locator_wpress_settings_posts');
define('STORE_LOCATOR_WPRESS_SETTINGS_store_details', 'store_locator_wpress_settings_store_details');

add_filter( 'plugin_action_links', array('Store_locator_wpress_settings', 'plugin_action_links'), 10, 2);
add_action( 'admin_menu', array('Store_locator_wpress_settings', 'config_page_init') );

add_action('admin_print_scripts', array('Store_locator_wpress_settings','config_page_scripts'));


class Store_locator_wpress_settings extends WPress_framework_store_locator {
	
	function config_page_scripts() {
		//wp_deregister_script('dashboard');
		//wp_enqueue_script('dashboard');
	}
	
	function config_page_init() {
		if (function_exists('add_submenu_page'))
			add_submenu_page('plugins.php', 'Store locator', 'Store locator', 'manage_options', 'store-locator-wpress-settings', array('Store_locator_wpress_settings', 'store_locator_settings'));
	}
	
	function store_locator_settings() {
		
		$options = get_option(STORE_LOCATOR_WPRESS_ADMIN);
		$distance = $options['distance'];
		
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		
			<br>
			
			<?php
			echo '<a href="http://yougapi.com"><img src="'.plugin_dir_url( __FILE__ ).'graph/store-locator-wpress-mini.png" align="left" style="margin-right:15px;" /></a>';
			?>
			<h2>Store Locator WPress configuration</h2>
			<hr style="background:#ddd;color:#ddd;height:1px;border:none;">
			<br><br>
			
			<table width="100%"><tr>
			<td valign="top" width="50%" style="padding-right:10px;">
				
				<?php
				$map_types_tab = array('roadmap'=>'Roadmap', 'satellite'=>'Satellite', 'hybrid'=>'Hybrid', 'terrain'=>'Terrain');
				$distance_tab = array('km'=>'Kilometer', 'miles'=>'Miles');
				
				$criteria['key'] = STORE_LOCATOR_WPRESS_SETTINGS;
				$criteria['box_title'] = 'Your Store Locator Settings';
				$criteria['fields'][] = array('name'=>'width', 'title'=>'Map width <small>(100% or any value in pixels - Ex: 480px)</small>');
				$criteria['fields'][] = array('name'=>'height', 'title'=>'Map height <small>(Any value in pixels - Ex: 380px)</small>');
				$criteria['fields'][] = array('name'=>'map_type', 'title'=>'Type of Map', 'type'=>'select', 'select_values'=>$map_types_tab);
				$criteria['fields'][] = array('name'=>'distance', 'title'=>'Distance', 'type'=>'select', 'select_values'=>$distance_tab);
				$criteria['fields'][] = array('name'=>'zoom', 'title'=>'Zoom level <small>(0 to 20)</small>');
				$criteria['fields'][] = array('name'=>'lat', 'title'=>'Latitude <small>(Default map latitude)</small>');
				$criteria['fields'][] = array('name'=>'lng', 'title'=>'Longitude <small>(Default map longitude)</small>');
				$criteria['fields'][] = array('name'=>'nb_stores', 'title'=>'Number of stores to display by page or Map');
				$criteria['fields'][] = array('name'=>'custom_marker', 'title'=>'URL of the custom market to use');
				$criteria['fields'][] = array('name'=>'store_locator_link', 'title'=>'URL of the store locator (where the main locator shortcode has been added)');
				$criteria['fields'][] = array('name'=>'default_stores_search', 'title'=>'Check to load the Stores on the Map by default', 'type'=>'checkbox');
				$criteria['fields'][] = array('name'=>'closest_stores', 'title'=>'Check to load the closest Stores by default', 'type'=>'checkbox');
				$criteria['fields'][] = array('name'=>'streetview', 'title'=>'Street view display (as a Map overlay - includes a link in the marker InfoWindow)', 'type'=>'checkbox');
				$criteria['fields'][] = array('name'=>'directions', 'title'=>'Display the directions (in the marker InfoWindow)', 'type'=>'checkbox');
				parent::display_admin_control($criteria);
				?>
				
				<small>Plugin created by <a href="http://yougapi.com">Yougapi Technology</a></small>
			</td>
			
			<td valign="top" style="padding-left:10px;">
				
				<?php
				$position_tab = array('top'=>'Top', 'bottom'=>'Bottom');
				
				$criteria2['key'] = STORE_LOCATOR_WPRESS_SETTINGS_posts;
				$criteria2['box_title'] = 'Display of linked Stores in posts';
				$criteria2['fields'][] = array('name'=>'linked_stores_display', 'title'=>'Activate the display of the linked stores in posts', 'type'=>'checkbox');
				$criteria2['fields'][] = array('name'=>'linked_stores_position', 'title'=>'Where to display the linked store', 'type'=>'select', 'select_values'=>$position_tab);
				parent::display_admin_control($criteria2);
				
				$criteria3['key'] = STORE_LOCATOR_WPRESS_SETTINGS_store_details;
				$criteria3['box_title'] = 'Display of Stores details options';
				$criteria3['fields'][] = array('name'=>'zoom', 'title'=>'Map zoom level <small>(0 to 20)</small>');
				$criteria3['fields'][] = array('name'=>'width', 'title'=>'Map and/or Street View width <small>(100% or any value in pixels - Ex: 480px)</small>');
				$criteria3['fields'][] = array('name'=>'height', 'title'=>'Map and/or Street View height <small>(Any value in pixels - Ex: 380px)</small>');
				$criteria3['fields'][] = array('name'=>'streetview', 'title'=>'Display the street view', 'type'=>'checkbox');
				parent::display_admin_control($criteria3);
				?>
				
				<?php
				?>
				
			</td>
			</tr></table>
			
		</div></div>
			
		<?php
	}
	
	function plugin_action_links($links, $file) {
		if ( $file == plugin_basename( dirname(__FILE__).'/store_locator_wpress.php' ) ) {
			$links[] = '<a href="plugins.php?page=store-locator-wpress-settings">Settings</a>';
		}
		return $links;
	}
	
	function set_default_values() {
		//app setup
		$criteria['key'] = STORE_LOCATOR_WPRESS_SETTINGS;
		$criteria['default_values'] = array('width'=>'100%', 'height'=>'380px', 'map_type'=>'roadmap', 'zoom'=>'5', 'lat'=>'40', 'lng'=>'-100', 
		'nb_stores'=>'20', 'distance'=>'miles', 'default_stores_search'=>'on', 'streetview'=>'on');
		parent::set_default_values($criteria);
		//Linked stores
		$criteria2['key'] = STORE_LOCATOR_WPRESS_SETTINGS_posts;
		$criteria2['default_values'] = array('linked_stores_display'=>'on', 'linked_stores_position'=>'bottom');
		parent::set_default_values($criteria2);
		//Store details
		$criteria3['key'] = STORE_LOCATOR_WPRESS_SETTINGS_store_details;
		$criteria3['default_values'] = array('zoom'=>'16', 'width'=>'100%', 'height'=>'280px');
		parent::set_default_values($criteria3);
	}
	
}

?>