<?php

define('STORE_LOCATOR_WPRESS', 'store_locator_wpress');

// register the widgets
add_action("plugins_loaded",array('Store_locator_wpress_widget', 'widget_registration'));

class Store_locator_wpress_widget extends WPress_framework_store_locator {
	
	function widget_registration() {
		//stores list
		wp_register_sidebar_widget(STORE_LOCATOR_WPRESS, 'Store Locator WPress', array(__CLASS__, 'widget_display_stores_list'));
		wp_register_widget_control(STORE_LOCATOR_WPRESS, 'Store Locator WPress', array(__CLASS__, 'widget_control_stores_list'));
	}
	
	/*
	Widgets front display
	*/
	
	function widget_display_stores_list() {
		$options = get_option(STORE_LOCATOR_WPRESS);
		
		/*
		$nb_stores = $options['nb_stores'];
		if($nb_stores=='') $nb_stores=4;
		*/
		
		//include the JS files
		add_action('wp_footer', array('Store_locator_wpress_shortcode', 'add_js_map'));
		
		$s1 = new Store_locator_wpress_shortcode();
		$s1->js_wpress_declaration();
		
		//execute on dom ready
		$GLOBALS['store_wpress_js_on_ready'] .= 'display_widget_closest_stores();';
		
		if($options['title']!='') echo '<h3 class="widget-title" style="margin-bottom:5px;">'.$options['title'].'</h3>';
		echo '<p id="widget_store_locator_list"></p>';
	}
	
	/*
	Widgets controls
	*/
	
	function widget_control_stores_list() {
		$criteria['key'] = STORE_LOCATOR_WPRESS;
		$criteria['fields'][] = array('name'=>'title', 'title'=>'Widget title:');
		$criteria['fields'][] = array('name'=>'nb_stores', 'title'=>'Number of stores:');
		parent::display_widget_control($criteria);
	}
}

new Store_locator_wpress();

?>