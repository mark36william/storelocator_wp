<?php

namespace AgileStoreLocator\Frontend;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Frontend
 * @author     AgileLogix <support@agilelogix.com>
 */
class App {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $AgileStoreLocator    The ID of this plugin.
	 */
	private $AgileStoreLocator;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;



	/**
	 * [$scripts_data load the scripts]
	 * @var array
	 */
	private $scripts_data = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $AgileStoreLocator       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $AgileStoreLocator, $version ) {

		$this->AgileStoreLocator = $AgileStoreLocator;
		$this->version = time();
		$this->version = $version;


		$this->script_name = '';

    //	Match & redirect
		if(isset($_GET['sl-addr']) && $_GET['sl-addr']) {

			$sanitize_text = esc_attr($_GET['sl-addr']);
			//$this->have_matching_address($sanitize_text);	
    }
	}

	/**
	 * [register_styles Load the very basic style]
	 * @return [type] [description]
	 */
	public function register_styles() {
		
		wp_enqueue_style( $this->AgileStoreLocator.'-init',  ASL_URL_PATH.'public/css/init.css', array(), $this->version, 'all' );
	}


	/**
	 * [get_public_config Get the configuration in key/list form]
	 * @return [type] [description]
	 */
	private function get_public_config() {

		global $wpdb;

		//	Fetch All Configs
		$configs = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."configs WHERE `key` NOT IN ('server_key', 'notify_email') AND `type` NOT IN ('label', 'priv');");


		$all_configs = array();
		
		foreach($configs as $_config)
			$all_configs[$_config->key] = $_config->value;

		return $all_configs;
	}

	/**
	 * [register_scripts Register all the scripts]
	 * @return [type] [description]
	 */
	public function register_scripts() {
		
		// ASL libraries
		wp_register_script( $this->AgileStoreLocator.'-lib', ASL_URL_PATH . 'public/js/asl_libs.min.js', array('jquery'), $this->version, true );
		
		//	New cluster library
		wp_register_script( $this->AgileStoreLocator.'-cluster', ASL_URL_PATH . 'public/js/asl_cluster.min.js', array('jquery', $this->AgileStoreLocator.'-lib'), $this->version, true );

		//	Search Widget
		wp_register_script( $this->AgileStoreLocator.'-search', ASL_URL_PATH . 'public/js/asl_search.js', array('jquery'), $this->version, true );
		
		//	Form
		wp_register_script( $this->AgileStoreLocator.'-form-libs', ASL_URL_PATH . 'public/js/sl-form-libs.js', array('jquery'), $this->version, true );
		
		//	Default Script
		wp_register_script( $this->AgileStoreLocator.'-script', ASL_URL_PATH . 'public/js/site_script.js', array('jquery'), $this->version, true );

		//	Template 3
		wp_register_script( $this->AgileStoreLocator.'-tmpl-3', ASL_URL_PATH . 'public/js/tmpl_3_script.js', array('jquery'), $this->version, true );

		//	Template 4
		wp_register_script( $this->AgileStoreLocator.'-tmpl-4', ASL_URL_PATH . 'public/js/site_script.js', array('jquery'), $this->version, true );

		//	Template list
		wp_register_script( $this->AgileStoreLocator.'-tmpl-list', ASL_URL_PATH . 'public/js/list_script.js', array('jquery'), $this->version, true );
		
		//	Store Detail page
		wp_register_script( $this->AgileStoreLocator.'-tmpl-detail', ASL_URL_PATH . 'public/js/sl_detail.js', array('jquery'), $this->version, true );

		//	Store Form
		wp_register_script( $this->AgileStoreLocator.'-form', ASL_URL_PATH . 'public/js/asl-form.js', array('jquery'), $this->version, true );
		
		//	Lead Form
		wp_register_script( $this->AgileStoreLocator.'-lead', ASL_URL_PATH . 'public/js/asl-lead-form.js', array('jquery'), $this->version, true );

	}


	/**
	 * [register_google_maps Register the Google Maps]
	 * @return [type] [description]
	 */
	public function register_google_maps($atts = array()) {

		global $wpdb;

		$sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'api_key' OR `key` = 'map_language' OR `key` = 'map_region' ORDER BY id ASC;";
		
		$all_result = $wpdb->get_results($sql);
		
		$map_url = '//maps.googleapis.com/maps/api/js?libraries=places,drawing';

		//	Set the API Key
		if(isset($all_result[0]) && $all_result[0]->value) {
			
			$api_key 	= $all_result[0]->value;
			$map_url .= '&key='.$api_key;
		}


		//	Add the callback, jan-25-2023
		$map_cb_func = isset($atts['lib_callback'])? $atts['lib_callback']: 'asl_init_map';
		$map_url 		.= '&callback='.$map_cb_func;

		//	Set the map language
		$map_country = null;

		if(isset($atts['map_language']) && $atts['map_language'])
			$map_country = $atts['map_language'];
		else if(isset($all_result[1]) && $all_result[1]->value) {
			$map_country = $all_result[1]->value;
		}

		//	When we have a country
		if($map_country) {
			$map_url .= '&language='.$map_country;
		}

		//	Set the map region
		$map_region   = null;

		if(isset($atts['map_region']))
			$map_region = $atts['map_region'];
		else if(isset($all_result[2]) && $all_result[2]->value) {
			$map_region = $all_result[2]->value;
		}
			
		if($map_region)
			$map_url   .= '&region='.$map_region;

		//	Register the Google Maps
		wp_register_script('asl_google_maps', $map_url, array('jquery'), null, true );

		//	Enqueue the Google Maps
		wp_enqueue_script('asl_google_maps');

		$this->initBorlabsCookies();
	}

	/**
	 * Enqueue the Store Locator Scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($type = '', $atts = array()) {

		//	Register Before Enqueue
		$this->register_scripts();
	
		//	enqueue the libs
		if($type != 'wc') {
			wp_enqueue_script( $this->AgileStoreLocator.'-lib');		
		}

		//	Register the Google Maps
		$this->register_google_maps($atts);

		//	We only want the Google Maps
		if($type == 'wc') {
			return;
		}

		switch ($type) {

			case 'search':

				wp_enqueue_script( $this->AgileStoreLocator.'-search');
				break;

			case 'list':

				wp_enqueue_script( $this->AgileStoreLocator.'-tmpl-list');
				break;

			case 'form':

				wp_enqueue_script( $this->AgileStoreLocator.'-form');
				break;


			case 'lead':

				wp_enqueue_script( $this->AgileStoreLocator.'-form-libs');
				wp_enqueue_script( $this->AgileStoreLocator.'-lead');
				break;
			
			case '3':
				
				wp_enqueue_script( $this->AgileStoreLocator.'-tmpl-3');
				break;


			case 'detail':

				wp_enqueue_script( $this->AgileStoreLocator.'-tmpl-detail');
				break;


			case '4':
					
				wp_enqueue_script( $this->AgileStoreLocator.'-form-libs');
				wp_enqueue_script( $this->AgileStoreLocator.'-tmpl-4');
				break;

			default:
					
				wp_enqueue_script( $this->AgileStoreLocator.'-script');
				break;
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($template = '') {


		$media = 'all'; //screen, all


		switch ($template) {


			case '2':

				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

				//	CSS file
				wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-0',  ASL_URL_PATH.'public/css/tmpl-2/tmpl-2.css', array(), $this->version, $media );
				//wp_enqueue_style( $this->AgileStoreLocator.'-list',  'http://127.0.0.1:8080/main.scss/custom.css', array(), $this->version, $media );
				
			break;

			case '1':
			
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );


				//	Add the CSS for the Template 1
				wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-1',  ASL_URL_PATH.'public/css/tmpl-1/tmpl-1.css', array(), $this->version, $media );
				

				break;

			case '3':
			
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

				//	Add the CSS for the Template 3
				wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-3',  ASL_URL_PATH.'public/css/tmpl-3/tmpl-3.css', array(), $this->version, $media );
				//wp_enqueue_style( $this->AgileStoreLocator.'-list',  'http://127.0.0.1:8080/main.scss/custom.css', array(), $this->version, $media );

				break;


			case 'list':
			
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

				//	Add the CSS for the Template 3
				wp_enqueue_style( $this->AgileStoreLocator.'-list',  ASL_URL_PATH.'public/css/tmpl-list.css', array(), $this->version, $media );
				//wp_enqueue_style( $this->AgileStoreLocator.'-list',  'http://127.0.0.1:8080/main.scss/custom.css', array(), $this->version, $media );

				break;

			case 'form':
				
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

				//	Add the CSS for the Template 3
				wp_enqueue_style( $this->AgileStoreLocator.'-form',  ASL_URL_PATH.'public/css/asl-form.css', array(), $this->version, $media );

				break;

			case '4':
				
				//	fonts	
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );

				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );


				// wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-4',  'http://localhost:8080/main.scss/custom-grid.css', array(), $this->version, $media );
				wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-4',  ASL_URL_PATH.'public/css/tmpl-4/tmpl-4.css', array(), $this->version, $media );

				break;




			case 'lead':
				
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );


				wp_enqueue_style( $this->AgileStoreLocator.'-lead',  ASL_URL_PATH.'public/css/asl-lead-form.css', array(), $this->version, $media );

				break;


			case 'page':
				
				
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );


				//	Add the CSS for the Template 3
				wp_enqueue_style( $this->AgileStoreLocator.'-page',  ASL_URL_PATH.'public/css/store-page.css', array(), $this->version, $media );

			break;

			case 'grid':
				
				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

			break;

			case 'search':
					

				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );
				
				//	Add the CSS for the asl_search
				wp_enqueue_style( $this->AgileStoreLocator.'-asl-search',  ASL_URL_PATH.'public/css/asl_search.css', array(), $this->version, $media );
				break;


			default:


				//	Icons
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-icons',  ASL_URL_PATH.'public/css/icons/fontello.css', array(), $this->version, $media );
				
				//	Bootstrap
				wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap',  ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, $media );

				//	Add the CSS for the Template 0
				wp_enqueue_style( $this->AgileStoreLocator.'-tmpl-0',  ASL_URL_PATH.'public/css/tmpl-0/tmpl-0.css', array(), $this->version, $media );
				//wp_enqueue_style( $this->AgileStoreLocator.'-list',  'http://192.168.100.6:8080/main.scss/custom.css', array(), $this->version, $media );
				break;
		}

		// todo, remove it
		//wp_enqueue_style( $this->AgileStoreLocator.'-mess-up',  'http://asl.localhost.com/style-mess.css', array(), $this->version, $media );
	}



  /**
   * [initBorlabsCookies use Borlabs Cookies if plugin is installed]
   * @return [type] [description]
   */
  public function initBorlabsCookies() {

    if (function_exists('BorlabsCookieHelper')) {
      
      $borlabs = new \AgileStoreLocator\Vendors\Borlabs;

      $borlabs->initialize();
    }
  }


	/**
	 * [searchBox Display the Search box for the Store locator Shortcode :: ASL_SEARCH]
	 * @param  [type] $atts [description]
	 * @return [type]       [description]
	 */
	public function searchBox($atts) {

		global $wpdb;

		//Load the Style
		$this->enqueue_styles('search');

		if(!$atts) {
			$atts = array();
		}

		//	Fetch All Configs
		$all_configs = $this->get_public_config();
		// echo "<pre>";
		// print_r($atts);

		//	Language
		$lang   =  (isset($all_configs['locale']) && $all_configs['locale'] == '1')? get_locale(): '';
		
		//	Lang override by attribute
		if(isset($atts['lang']) && strlen($atts['lang'] <= 13)) {
			$lang = $atts['lang'];
		}

		//	en_US is default
		if($lang == 'en' || $lang == 'en_US')
			$lang = '';

		//	Clean the language code
		$lang   	 = esc_sql($lang);

		$lang_code = ($lang == '')? 'en_US': $lang;
		
		//Load the Scripts
		$this->enqueue_scripts('search', $atts);

		
		$all_configs['URL'] 				= ASL_UPLOAD_URL;
		$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;


		$all_configs = shortcode_atts( $all_configs, $atts );

		//add the missing attributes into settings
		$all_configs = array_merge($all_configs, $atts);

		
		//ADD The missing parameters
		$default_options = array(
			'show_categories' => '1'
		);

		$all_configs  = array_merge($default_options, $all_configs);
		
		
		//	filter all the attribute values, escape values
		foreach ($all_configs as $config_key => $config_value) {
			$all_configs[$config_key] = esc_attr($config_value);
		}
		
		//	Get the categories
		$all_categories = array();
		$results = $wpdb->get_results("SELECT id,category_name as name,icon, ordr FROM ".ASL_PREFIX."categories WHERE lang = '$lang' ORDER BY category_name ASC");

		foreach($results as $_result) {
			$all_categories[$_result->id] = $_result;
		}

		

		//For Translation	
		$words = array(
			'detail' 			=> asl_esc_lbl('website'),
			'select_option' 	=> asl_esc_lbl('select_option'),
			'search' 			=> asl_esc_lbl('search'),
			'all_selected' 		=> asl_esc_lbl('all_selected'),
			'none' 				=> asl_esc_lbl('none'),
			'all_categories'	=> asl_esc_lbl('all_categories'),
			'none_selected' 	=> asl_esc_lbl('none_selected'),
			'selected' 			=> asl_esc_lbl('selected'),
			'current_location' 	=> asl_esc_lbl('current_location'),
			'select_category' 	=> asl_esc_lbl('select_category'),
			'brand'				=> asl_esc_lbl('brand'),
			'region'			=> asl_esc_lbl('region'),
			'geo'				=> asl_esc_lbl('geo'),
			'category'			=> asl_esc_lbl('category')
		);

		$all_configs['words'] 	  = $words;

		//	apply the filter, ticket #6933
		$all_configs['words']     = apply_filters( 'asl_filter_search_widget_words', $words);
		
		ob_start();

		$template_file = 'asl-search.php';

		//	Additional Attributes
		$filter_ddl   = (isset($all_configs['filter_ddl']) && $all_configs['filter_ddl'])? $all_configs['filter_ddl']: null;


		if($filter_ddl) {
			
			$filter_ddl = explode(',', $filter_ddl);
		}


		////////////////////////
		// Get the Attributes //
		////////////////////////
		$all_attributes = \AgileStoreLocator\Model\Attribute::get_all_attributes_list($lang, $atts);

		//Customization of Template
		if($template_file) {

			if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
        $template_path = $theme_file;
      }
      else {
        $template_path = ASL_PLUGIN_PATH.'public/partials/'.$template_file;
      }

      include $template_path;
		}
        

		$sl_output = ob_get_contents();
		
		ob_end_clean();

		$title_nonce = wp_create_nonce( 'asl_remote_nonce' );
		
		$this->localize_scripts( $this->AgileStoreLocator.'-search', 'ASL_SEARCH', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' ),
		    'nonce'    => $title_nonce,
		));
		
		$this->localize_scripts( $this->AgileStoreLocator.'-search', 'asl_attributes', $all_attributes);
		$this->localize_scripts( $this->AgileStoreLocator.'-search', 'asl_search_configuration', $all_configs);
		$this->localize_scripts( $this->AgileStoreLocator.'-search', 'asl_search_categories', $all_categories);
		
		//	Inject script with inline_script
		wp_add_inline_script( $this->AgileStoreLocator.'-search', $this->get_local_script_data(), 'before');

		return $sl_output;
	}


	/**
	 * [storeForm Frontend Store Form]
	 * @param  [type] $attr [description]
	 * @return [type]       [description]
	 */
	public function storeForm($atts) {

		global $wpdb;

		//	Fetch All Configs
		$all_configs = $this->get_public_config();

		//	Language
		$lang   =  (isset($all_configs['locale']) && $all_configs['locale'] == '1')? get_locale(): '';

		//	en_US is default
		if($lang == 'en' || $lang == 'en_US')
			$lang = '';

		//	Clean the language code
		$lang   = esc_sql($lang);

		//	filter all the attribute values, escape values
		foreach ($all_configs as $config_key => $config_value) {
			$all_configs[$config_key] = esc_attr($config_value);
		}

		//	for the localization script
		$this->script_name = '-form';
	
		//Load the Scripts
		$this->enqueue_scripts('form');

		
		//Load the Style
		$this->enqueue_styles('form');

    $markers   	= $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."markers");
		$countries 	= $wpdb->get_results("SELECT id, country FROM ".ASL_PREFIX."countries");

		$ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();

		$all_ddl_controls = [];

		//  Loop over the controls
    foreach($ddl_controls as $control_key => $ddl_control) {

    	$all_ddl_controls['all_'.$ddl_control['field']] 	= \AgileStoreLocator\Model\Attribute::get_list($control_key, $lang);
    }

    //	to keep backward compatibility
    extract($all_ddl_controls);

		// Get the Categories
		$all_categories = array();
		$results 				= $wpdb->get_results("SELECT id, category_name FROM ".ASL_PREFIX."categories WHERE lang = '$lang' ORDER BY category_name ASC");


		foreach($results as $_result) {
			$all_categories[$_result->id] = $_result;
		}

		//	The Upload Directory
		$all_configs['URL'] 				= ASL_UPLOAD_URL;
		$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;
		$all_configs['map'] 				= '1';

		
		if(!$atts) {

			$atts = array();
		}
		

		$all_configs = shortcode_atts( $all_configs, $atts );

		//add the missing attributes into settings
		$all_configs = array_merge($all_configs, $atts);

		//	unregister the Google Maps
		/*
		if($all_configs['map'] == '0') {
			wp_deregister_script('asl_google_maps');
		}
		*/

		//For Translation	
		$words = array(
			'detail' 		=> asl_esc_lbl('website'),
			'none_selected' => asl_esc_lbl('none_selected'),
			'fill_form' 	=> asl_esc_lbl('fill_form')
		);

		$all_configs['words'] 	  = $words;

		/**
		 * Render the form
		 */
		
		ob_start();

		//	Template file
		$template_file = 'asl-store-form.php';


		$fields = \AgileStoreLocator\Helper::get_custom_fields();


		//Customization of Template
		if($template_file) {

			if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
        $template_path = $theme_file;
      }
      else {
        $template_path = ASL_PLUGIN_PATH.'public/partials/'.$template_file;
      }

      include $template_path;
		}

		$sl_output = ob_get_contents();


		ob_end_clean();

		$title_nonce = wp_create_nonce( 'asl_store_form_nonce' );
		
		$this->localize_scripts( $this->AgileStoreLocator.'-form', 'ASL_FORM', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' ),
		    'vkey'    => $title_nonce,
		));
		
		
		$this->localize_scripts( $this->AgileStoreLocator.'-form', 'asl_form_configuration', $all_configs);
		
		//	Inject script with inline_script
		wp_add_inline_script( $this->AgileStoreLocator.'-form', $this->get_local_script_data(), 'before');

		return $sl_output;
	}


	/**
	 * [leadForm Lead Store Form]
	 * @param  [type] $attr [description]
	 * @return [type]       [description]
	 */
	public function leadForm($atts) {

		global $wpdb;

		//	for the localization script
		$this->script_name = '-lead';
	
		//Load the Scripts
		$this->enqueue_scripts('lead');

		
		//Load the Style
		$this->enqueue_styles('lead');

   	
   	$all_configs = array(
   		'radius' => 25
   	);

		//	The Upload Directory
		$all_configs['URL'] 				= ASL_UPLOAD_URL;
		$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;

		
		if(!$atts) {

			$atts = array();
		}
		

		$all_configs = shortcode_atts( $all_configs, $atts );

		//add the missing attributes into settings
		$all_configs = array_merge($all_configs, $atts);

		//	filter all the attribute values, escape values
		foreach ($all_configs as $config_key => $config_value) {
			$all_configs[$config_key] = esc_attr($config_value);
		}

		//For Translation	
		$words = array(
			'detail' 	=> asl_esc_lbl('website'),
			'fill_form' => asl_esc_lbl('fill_form')
		);

		$all_configs['words'] 	  = $words;

		/**
		 * Render the form
		 */
		
		ob_start();

		//	Template file
		$template_file = 'asl-lead-form.php';


		// Check for Local Version
		if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
        $template_path = $theme_file;
    }
    else {
        $template_path = 'partials/'.$template_file;
    }

    include ASL_PLUGIN_PATH.'public/'.$template_path;

		$sl_output = ob_get_contents();


		ob_end_clean();

		$title_nonce = wp_create_nonce( 'asl_store_form_nonce' );
		
		$this->localize_scripts( $this->AgileStoreLocator.'-form', 'ASL_FORM', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' ),
		    'vkey'    => $title_nonce,
		));
		
		
		$this->localize_scripts( $this->AgileStoreLocator.'-lead', 'asl_lead_configuration', $all_configs);
		
		//	Inject script with inline_script
		wp_add_inline_script( $this->AgileStoreLocator.'-lead', $this->get_local_script_data(), 'before');

		return $sl_output;
	}
 
 
	/**
	 * [storePage Store Page]
	 * @param  [type] $atts [description]
	 * @return [type]       [description]
	 */
	public function storePage($atts) {

		global $wpdb;

		$this->enqueue_styles('page');

		if(!$atts) {
			$atts = array();
		}
		

		/////////////////////////
		///	Store Id Attribute //
		/////////////////////////
		
		// Try to get from the attributes
		$where_clause = 's.`id` = %d'; 
		$q_param 		  = null;

		//	Get value by attribute
		$q_param 		= isset($atts['sl-store'])? intval($atts['sl-store']): null;

		//	Get value by the $_GET
		if(!$q_param) {
			$q_param   = (isset($_GET['sl-store']) && $_GET['sl-store'])? $_GET['sl-store']: null;
		}

		//	Check for the slug when store id is missing
		if(!$q_param) {
			
			//	For the Slug
			$q_param   = get_query_var('sl-store');
			
			if($q_param) {

				// Clear the Slug for SQL injection
				$q_param = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $q_param), '-'));
				$q_param = preg_replace('/-+/', '-', $q_param);

				$where_clause = 's.`slug` = %s';
			}
		}
		
		if($q_param) {

			$ASL_PREFIX = ASL_PREFIX;


			// ddl_fields in the query
    	$ddl_fields_str = \AgileStoreLocator\Model\Attribute::sql_query_fields();


			//	Run the main query
			$query   = "SELECT s.`id`, `title`,  `description`, `street`,  `city`,  `state`, `postal_code`, `country`, `lat`,`lng`,`phone`,  `fax`,`email`,`website`,`logo_id`,{$ASL_PREFIX}storelogos.`path`,`marker_id`,`description_2`,`open_hours`, `ordr`,$ddl_fields_str, `custom`,
					group_concat(category_id) as categories, lang FROM {$ASL_PREFIX}stores as s 
					LEFT JOIN {$ASL_PREFIX}storelogos ON logo_id = {$ASL_PREFIX}storelogos.id
					LEFT JOIN {$ASL_PREFIX}stores_categories ON s.`id` = {$ASL_PREFIX}stores_categories.store_id
					WHERE {$where_clause}";

			$results  = $wpdb->get_results($wpdb->prepare($query, array($q_param)));		

			//	Only for the correct record
			if($results && isset($results[0]) && $results[0]->id) {


				//	Template file
				$template_file = 'asl-store-page.php';

				$store_data    = $results[0];


				//	Get the Country
				$country = $wpdb->get_results("SELECT country FROM ".ASL_PREFIX."countries WHERE id = ".$store_data->country);

				$store_data->country = ($country && isset($country[0]))? $country[0]->country: '';

			
				//	Custom Field
				if(isset($store_data->custom) && $store_data->custom) {

					$custom_fields = json_decode($store_data->custom, true);

					if($custom_fields && is_array($custom_fields) && count($custom_fields) > 0) {

						foreach ($custom_fields as $custom_key => $custom_value) {
							
							$store_data->$custom_key = $custom_value;
						}
					}
				}

				//	Make the address
				$locality = trim(implode(', ', array_filter(array($store_data->city, $store_data->state, $store_data->postal_code, $store_data->country))), ', ');
				$address  = [$store_data->street, $locality];
				$store_data->address = (trim(implode(', ', $address)));

				

				//	To display only one parameter
				if(isset($atts['field'])) {

					$display_column = $atts['field'];

					//	render the open hours
					$store_data->open_hours = \AgileStoreLocator\Helper::openHours($store_data, '1');
					
					if(isset($store_data->$display_column)) {

						return $store_data->$display_column;
					}
				}

				//Load the Scripts
				$this->enqueue_scripts('detail', $atts);


				////////////////////
				///Get Categories //
				////////////////////
				$store_categories = null;

        if(isset($store_data->categories) && $store_data->categories) {

        	//	filter the numbers
        	$ids = explode(',', $store_data->categories);
        	$ids = array_map( 'absint', $ids );
        	$ids = implode(',', $ids);

        	$lang = $store_data->lang;

          $categories = $wpdb->get_results("SELECT `id`,`category_name`,`icon` FROM `{$ASL_PREFIX}categories` WHERE id IN ($ids) AND lang = '$lang'");          

          if($categories) {

            foreach($categories as $b) {
              $store_categories[] = $b->category_name;
            }

            //	Fill the categories for Schema
        		$store_data->all_categories = $store_categories;

        		//	Keep the full instance
        		$store_data->categories 		= $categories;
          }
        }

				/////////////////////////////
				///Get the Attribute Names //
				/////////////////////////////


				$ddl_controls  = \AgileStoreLocator\Model\Attribute::get_controls();

				$all_ddl_names = [];


				//  Loop over the controls
		    foreach($ddl_controls as $control_key => $ddl_control) {

		    	$ctrl_field = $ddl_control['field'];

		    	if(isset($store_data->$ctrl_field) && $store_data->$ctrl_field) {

		    		$ctrl_field_values = explode(',', $store_data->$ctrl_field);

		    		$names = \AgileStoreLocator\Model\Attribute::get_names_by_ids($control_key, $ctrl_field_values);

		    		$all_ddl_names['store_'.$ctrl_field]  = implode(', ', $names);
		    	}
		    	else
		    			$all_ddl_names['store_'.$ctrl_field] = null;

		    }

		    
		    //	make them variables, for backward compatibility
		    extract($all_ddl_names);

				//	Open hours
				$store_data->hours = $store_data->open_hours;

				//	Map will appear or not?
		    $store_data->map 	= (isset($atts['map']) && $atts['map'] == '0')? false: true;
		    

				//	All the configuration
		    $all_configs = \AgileStoreLocator\Helper::get_configs(['store_schema', 'zoom', 'map_layout', 'week_hours']);

		    //	Show the closed or not?
		    $show_close_label 			= (isset($atts['closed_label']) && $atts['closed_label'] == '1')? true: false;
		    $store_data->open_hours = \AgileStoreLocator\Helper::openHours($store_data, $all_configs['week_hours'], $show_close_label);


				$all_configs['URL'] 				= ASL_UPLOAD_URL;
				$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;
		  	
		  	

		    //	When we have a map!
		    if($store_data->map) {

			    $all_configs['default_lat'] = $store_data->lat;
			    $all_configs['default_lng'] = $store_data->lng;

			    
			    $all_configs = shortcode_atts( $all_configs, $atts );

					// Add the missing attributes into settings
					$all_configs = array_merge($all_configs, $atts);

					//	filter all the attribute values, escape values
					foreach ($all_configs as $config_key => $config_value) {
						$all_configs[$config_key] = esc_attr($config_value);
					}

					//	Get the JSON for the Map layout
					$all_configs['map_layout'] 	= $this->_map_layout($all_configs['map_layout']);
					$all_configs['icon'] 				= \AgileStoreLocator\Helper::getMarkerPath($store_data->marker_id);
				}
				else {

					unset($all_configs['zoom']);
					unset($all_configs['map_layout']);
					unset($all_configs['week_hours']);
					unset($all_configs['URL']);
					unset($all_configs['PLUGIN_URL']);

				}


				//  apply filter to make the store detail accessible
				$store_data     = apply_filters( 'asl_filter_store_detail', $store_data);

				//	Generate the Google Schema
				$google_schema 	= ($all_configs['store_schema'] == '1')? \AgileStoreLocator\Helper::googleSchema($store_data):'';

				ob_start();

				// Check for Local Version
				if($template_file) {

					if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
		        $template_path = $theme_file;
		      }
		      else {
		        $template_path = ASL_PLUGIN_PATH.'public/partials/'.$template_file;
		      }

		      include $template_path;
				}

				$sl_output = ob_get_contents();

				ob_end_clean();

				return $sl_output;
			}
		}

		return '';
	}

	/**
	 * [storeGrid Render the Store Grid]
	 * @param  [type] $attrs [description]
	 * @return [type]        [description]
	 */
	public function storeGrid($atts) {


		// echo "in func storeGrid";
		// echo "<pre>";
		// print_r($atts);
		// die();
		global $wpdb;

		//	Enqueue the CSS of it
		$this->enqueue_styles('grid');

		if(!$atts) {
			$atts = array();
		}

		// Limit of the Grid
		$limit 	= isset($atts['limit'])? intval($atts['limit']): 10;
		$offset = isset($atts['offset'])? intval($atts['offset']): 0;
		$hide_fields = isset($atts['hide']) ? esc_attr($atts['hide']) : '';
		$hide_fields = explode(',', $hide_fields);

		//	Get the stores
		$stores 			= \AgileStoreLocator\Model\Store::get_stores($atts, $limit, $offset);
		
		//	Grid Template file
		$template_file = 'asl-store-grid.php';

		//	get the config
		$all_configs = \AgileStoreLocator\Helper::get_configs(['rewrite_slug', 'week_hours']);

		if(isset($all_configs['rewrite_slug']) && $all_configs['rewrite_slug']) {

			$all_configs['rewrite_slug'] = apply_filters( 'wpml_home_url', home_url('/')).'/'.$all_configs['rewrite_slug'];

			// replace the double slash
			$all_configs['rewrite_slug'] = preg_replace('#(?<!:)/+#im', '/', $all_configs['rewrite_slug']);
		}

		$all_configs['URL'] 				= ASL_UPLOAD_URL;
		$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;
  	
		// Check for Local Version
		if($template_file) {

			if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
        $template_path = $theme_file;
      }
      else {
        $template_path = ASL_PLUGIN_PATH.'public/partials/'.$template_file;
      }


      $sl_output = '<section class="asl-cont asl-store-grid sl-opacity-1">
    									<div class="sl-container">
												<div class="sl-row">';
      
      // Add the loop to the stores
			foreach ($stores as $store_data) {

				$locality = trim(implode(', ', array_filter(array($store_data->city, $store_data->state, $store_data->postal_code, $store_data->country))), ', ');
				$address  = [$store_data->street, $locality];
				// Complete Store Address
				$store_data->address = (trim(implode(', ', $address)));
				//	URL of the page
				$store_data->url     =  $all_configs['rewrite_slug'].'/'.$store_data->slug;
				
				$direction_path = urlencode($store_data->address);

				if(isset($atts['coords_direction'])) {

				    $direction_path = $store_data->lat.','.$store_data->lng;
				    $direction_path = urlencode(trim($direction_path));
				}

				if(in_array('address', $hide_fields)){

					unset($store_data->address);
					unset($store_data->street);
					unset($store_data->city);
					unset($store_data->state);
					unset($store_data->postal_code);
				}
				
				if(in_array('phone', $hide_fields))
					unset($store_data->phone);
				
				if(in_array('email', $hide_fields))
					unset($store_data->email );
				
				if(in_array('url_link', $hide_fields))
					unset($store_data->url);

				//	prepare the direction link
				$store_data->direction = "https://www.google.com/maps/dir/?api=1&destination=".$direction_path;

				// echo "<pre>";
				// print_r($store_data);
				// echo "</pre>";
				// die();
				//	open the stream
		      	ob_start();
		      
		      	include $template_path;

		      	// get the stream
				$sl_output .= ob_get_contents();

				ob_end_clean();
			}

			//	When there are no stores
			if(count($stores) < 1) {
				$sl_output .= '<p class="text-center w-100 alert-warning alert sl-grid-no-stores">'.esc_attr__('Sorry! there are no stores found!','asl_locator').'</p>';
			}

      //	Closing div
      $sl_output .= '</div></div></section>';
		}

		// echo "1231232";
		// print_r($sl_output);
		// die();
		return $sl_output;
	}

	/**
	 * [frontendStoreLocator Frontend of Plugin]
	 * @param  [type] $atts [description]
	 * @return [type]       [description]
	 */
	public function frontendStoreLocator($atts) {
		
		global $wpdb, $post;

		$all_configs = $this->get_public_config();


		//	The Upload Directory
		$all_configs['URL'] 				= ASL_UPLOAD_URL;
		$all_configs['PLUGIN_URL'] 	= ASL_URL_PATH;
		$all_configs['site_lang'] 	= get_locale();


		//	Language
		$lang   =  (isset($all_configs['locale']) && $all_configs['locale'] == '1')? get_locale(): '';
		

		if(!$atts) {
			$atts = array();
		}
		else {

			//	apply filter to change the attributes
			$atts     = apply_filters( 'asl_filter_locator_attrs', $atts);
		}

		//	Lang override by attribute
		if(isset($atts['lang']) && strlen($atts['lang'] <= 13)) {
			$lang = $atts['lang'];
		}


		//	en_US is default
		if($lang == 'en' || $lang == 'en_US')
			$lang = '';

		//	Clean the language code
		if($lang)
			$lang   	  = esc_sql($lang);

		$lang_code 		= ($lang == '')? 'en_US': $lang;

		//	Merge the shortcodes
		$all_configs  = shortcode_atts( $all_configs, $atts );

		//	Add the missing attributes into settings
		$all_configs  = array_merge($all_configs, $atts);

		//	Language code in config, latest
		if($lang)
			$all_configs['lang'] = $lang_code;

		//	Check the template to load
		$template = (isset($all_configs['template']))? $all_configs['template']: '0';

		//	Load the secondary cluster library
		if($template != 'list' && $all_configs['cluster'] == '2') {

			wp_enqueue_script($this->AgileStoreLocator.'-cluster');
		}

		//Load the Scripts
		$this->enqueue_scripts($template, $atts);

		//	for the localization script
		$this->script_name = ($template == '3' || $template == '4' || $template == 'list')? '-tmpl-'.$template: '-script';
		
		//Load the Style
		$this->enqueue_styles($template);

		//	If the GDPR is enabled, dequeue the Google Maps
		if(isset($all_configs['gdpr']) && $all_configs['gdpr'] != '0') {
			wp_deregister_script('asl_google_maps');
		}


		$category_clause = "";

		//	select category
		if(isset($atts['select_category'])) {
			$all_configs['select_category'] = $atts['select_category'];
		}

		//$all_configs['select_category'] = '9004111222276004';

		////////////////////////////////////////
		////////The Redirect Attribute Params //
		////////////////////////////////////////

		$filter_ddls = \AgileStoreLocator\Model\Attribute::get_fields();

		//	the category filter
		$filter_ddls[] = 'category';

		foreach ($filter_ddls as $attr_key) {
				
				$attr_name = 'sl-'.$attr_key;
				if(isset($_GET[$attr_name]) && $_GET[$attr_name]) {

				if(preg_match('/^[0-9,]+$/', $_GET[$attr_name])) {

					$all_configs['select_'.$attr_key] = $_GET[$attr_name];
		    }
			}
		}



		////////////////////////////
		// Add the address filter //
		////////////////////////////
		$address_filters = ['state', 'city', 'postal_code'];

		foreach ($address_filters as $addr_filter) {
			
			if(isset($atts[$addr_filter]) && $atts[$addr_filter]) {

				$all_configs[$addr_filter] = $atts[$addr_filter];
			}
			else if(isset($_GET['sl-'.$addr_filter]) && $_GET['sl-'.$addr_filter]) {

				$all_configs[$addr_filter] = strip_tags($_GET['sl-'.$addr_filter]);
			}
		}



    if(isset($_GET['sl-addr']) && $_GET['sl-addr']) {

      //$all_configs['default-addr'] = \str_replace( strip_tags($_GET['sl-addr']), "\"", "");
      $all_configs['default-addr'] = esc_attr($_GET['sl-addr']);
    }
		elseif(isset($atts['sl-addr'])) {

			$all_configs['default-addr'] = $atts['sl-addr'];
			$all_configs['req_coords'] = true;
		}
		


		if(isset($_GET['lat']) && $_GET['lng']) {

			$all_configs['default_lat'] = $_GET['lat'];
			$all_configs['default_lng'] = $_GET['lng'];
		}
		//	Get the Coordinates
		else if(isset($all_configs['default-addr']) && $all_configs['default-addr']) {

			$all_configs['req_coords'] = true;
		}

		////////////////////////////////////////
		////////The Redirect Attribute ENDING //
		////////////////////////////////////////

		//	Only show Valid Categories		
		if(isset($atts['category'])) {

			$all_configs['category'] = $atts['category'];

			$load_categories = explode(',', $all_configs['category']);

			$the_categories  = array();

			foreach($load_categories as $_c) {

				if(is_numeric($_c)) {

					$the_categories[] = $_c;
				}
			}

			$the_categories  = implode(',', $the_categories);
			$category_clause = " AND id IN (".$the_categories.')';
			$all_configs['category'] = $the_categories;
		}


	
		//	Min and Max zoom
		if(isset($atts['maxZoom']) || isset($atts['maxzoom'])) {
			
			$all_configs['maxzoom'] = isset($atts['maxZoom'])?$atts['maxZoom']:$atts['maxzoom'];
		}

		if(isset($atts['minZoom']) || isset($atts['minzoom'])) {
			
			$all_configs['minzoom'] = isset($atts['minZoom'])?$atts['minZoom']:$atts['minzoom'];
		}
		


		//	For limited markers
		if(isset($atts['stores'])) {
			
			$all_configs['stores'] = $atts['stores'];
		}

		//	Search 2, Template 0
		if(!isset($atts['search_2'])) {
			
			$all_configs['search_2'] = false;
		}


		//	Mobile stores limit
		if(isset($atts['mobile_stores_limit']) && is_numeric($atts['mobile_stores_limit'])) {
			
			$all_configs['mobile_stores_limit'] = $atts['mobile_stores_limit'];
		}
		
		

		//	For a fixed radius
		if(isset($atts['fixed_radius']) && is_numeric($atts['fixed_radius'])) {
			
			$all_configs['fixed_radius'] = $atts['fixed_radius'];
		}



		if(isset($all_configs['rewrite_slug']) && $all_configs['rewrite_slug']) {

			$all_configs['rewrite_slug'] = apply_filters( 'wpml_home_url', home_url('/')).'/'.$all_configs['rewrite_slug'];

			// replace the double slash
			$all_configs['rewrite_slug'] = preg_replace('#(?<!:)/+#im', '/', $all_configs['rewrite_slug']);
		}


		//ADD The missing parameters
		$default_options = array(
			'debug' => '1',
			'pickup' => '0',
			'ship_from' => '0',
			'cluster' => '1',
			'prompt_location' => '2',
			'map_type' => 'roadmap',
			'distance_unit' => 'Miles',
			'zoom' => '9',
			'show_categories' => '1',
			'additional_info' => '1',
			'distance_slider' => '1',
			'layout' => '0',
			'default_lat' => '-33.947128',
			'default_lng' => '25.591169',
			'map_layout' => '0',
			'infobox_layout' => '0',
			'advance_filter' => '1',
			'color_scheme' => '0',
			'time_switch' => '0',
			'category_marker' => '0',
			'load_all' => '1',
			'head_title' => 'Number Of Shops',
			'font_color_scheme' => '1',
			'template' => '0',
			'color_scheme_1' => '0',
			'api_key' => '',
			'display_list' => '1',
			'full_width' => '0',
			'time_format' => '0',
			'category_title' => 'Category',
			'no_item_text' => 'No Item Found',
			'zoom_li' => '13',
			'single_cat_select' => '0',
			'country_restrict' => '',
			'google_search_type' => '',
			'color_scheme_2' => '0',
			'analytics' => '0',
			'sort_by_bound' => '0',
			'scroll_wheel' => '0',
			'mobile_optimize' 	=> null,
			'mobile_load_bound' => null,
			'search_type' => '0',
			'search_destin' => '0',
			'full_height' => '',
			'map_language' => '',
			'map_region' => '',
			'sort_by' => '',
			'distance_control' => '0',
			'dropdown_range' => '20,40,60,80,*100',
			'target_blank' => '1',
			'fit_bound' => '1',
			'info_y_offset' => '',
			'cat_sort' => 'name_',
			'direction_btn' => '1',
			'print_btn' => '1',
			'tabs_layout' => false,
			'filter_ddl' => '',
			'branches' => '0',
			'store_schedule' => '0'
		);

		$all_configs  = array_merge($default_options, $all_configs);
		

		if($all_configs['sort_by'] == 'distance') {
			
			$all_configs['sort_by'] = '';
		}

		if(isset($atts['user_center'])) {
			
			$all_configs['user_center'] = $atts['user_center'];
		}

		
		//	filter all the attribute values, escape values
		foreach ($all_configs as $config_key => $config_value) {
			$all_configs[$config_key] = esc_attr($config_value);
		}

		// KML Files
		if(isset($atts['kml']) && $atts['kml'] == '1') {

			//	Get the KML files
			$kml_files = \AgileStoreLocator\Helper::get_kml_files();


			if($kml_files && !empty($kml_files)) {

				$all_configs['kml_files'] = $kml_files;
				//$all_configs['kml_files'] = implode(',', $kml_files);
			}
		}

		//	Filter for the config
		$all_configs    = apply_filters( 'asl_filter_locator_config', $all_configs);




		//Get the categories
		$all_categories = array();
		$results = $wpdb->get_results("SELECT id,category_name as name,icon, ordr FROM ".ASL_PREFIX."categories WHERE lang = '$lang' ".$category_clause.' ORDER BY category_name ASC');

		foreach($results as $_result) {

			$all_categories[$_result->id] = $_result;
		}
		


		////////////////////////
		// Get the Attributes //
		////////////////////////
		$all_attributes = \AgileStoreLocator\Model\Attribute::get_all_attributes_list($lang, $atts);


		/////////////////////
		// Get the Markers //
		/////////////////////
		$all_markers = array();
		$results = $wpdb->get_results("SELECT id, marker_name as name,icon FROM ".ASL_PREFIX."markers");

		foreach($results as $_result) {
			
			$all_markers[$_result->id] = $_result;
		}

		//	Get the active Marker
		$active_marker = $wpdb->get_results("SELECT icon FROM ".ASL_PREFIX."markers WHERE marker_name = 'Active' ORDER BY id DESC LIMIT 1");		
		
		if($active_marker && $active_marker[0]) {
			$all_configs['active_marker'] = $active_marker[0]->icon;
		}

		//	Override with shortcode
		if(isset($atts['active_marker'])) {
			
			$all_configs['active_marker'] = $atts['active_marker'];
		}

		
		//	Get the JSON for the Map layout
		$all_configs['map_layout'] = $this->_map_layout($all_configs['map_layout']);


		//Load the map customization
		$map_customize  = $wpdb->get_results("SELECT content FROM ".ASL_PREFIX."settings WHERE type = 'map' AND id = 1");
		$map_customize  = ($map_customize && $map_customize[0]->content)?$map_customize[0]->content:'[]';
			
		
		//For Translation	
		$words = array(
			'label_country' 	=> asl_esc_lbl('label_country'),
			'label_state' 		=> asl_esc_lbl('label_state'),
			'label_city' 		=> asl_esc_lbl('label_city'),
			'ph_countries' 		=> asl_esc_lbl('ph_countries'),
			'ph_states' 		=> asl_esc_lbl('ph_states'),
			'ph_cities' 		=> asl_esc_lbl('ph_cities'),
			'pickup' 			=> asl_esc_lbl('pickup'),
			'ship_from' 		=> asl_esc_lbl('ship_from'),
			'direction' 		=> asl_esc_lbl('direction'),
			'zoom' 				=> asl_esc_lbl('zoom'),
			'detail' 			=> asl_esc_lbl('website'),
			'select_option' 	=> asl_esc_lbl('select_option'),
			'search' 			=> asl_esc_lbl('search'),
			'all_selected' 		=> asl_esc_lbl('all_selected'),
			'none' 				=> asl_esc_lbl('none'),
			'all_categories'	=> asl_esc_lbl('all_categories'),
			'all_brand'			=> asl_esc_lbl('all_brand'),
			'all_special'		=> asl_esc_lbl('all_special'),
			'none_selected' 	=> asl_esc_lbl('none_selected'),
			'reset_map' 		=> asl_esc_lbl('reset_map'),
			'reload_map' 		=> asl_esc_lbl('reload_map'),
			'selected' 			=> asl_esc_lbl('selected'),
			'current_location'  => asl_esc_lbl('current_location'),
			'your_cur_loc' 		=> asl_esc_lbl('your_cur_loc'),
			
			/*Template words*/
			'Miles' 	 		=> asl_esc_lbl('miles'),
			'Km' 	 	 		=> asl_esc_lbl('km'),
			'phone' 	 		=> asl_esc_lbl('phone'),
			'fax' 		 		=> asl_esc_lbl('fax'),
			'directions' 		=> asl_esc_lbl('app_directions'),
			'distance' 	 		=> asl_esc_lbl('distance_title'),
			'read_more'  		=> asl_esc_lbl('read_more'),
			'hide_more'  		=> asl_esc_lbl('hide_more'),
			'select_distance' 	=> asl_esc_lbl('select_distance'),
			'none_distance'  	=> asl_esc_lbl('none'),
			'cur_dir'  			=> asl_esc_lbl('cur_dir'),
			'radius_circle' 	=> asl_esc_lbl('radius_circle'),

			//	Tmpl-3
			'back_to_store' 	=> asl_esc_lbl('back_to_store'),
			'categories_title' 	=> asl_esc_lbl('all_categories'),
			'categories_tab' 	=> asl_esc_lbl('categories_tab'),
			'distance_title' 	=> asl_esc_lbl('distance_title'),
			'distance_tab' 		=> asl_esc_lbl('distance_tab'),
			'geo_location_error'=> asl_esc_lbl('geo_location_error'),
			'no_found_head' 	=> asl_esc_lbl('no_found_head'),
			'select_category' 	=> asl_esc_lbl('select_category'),
			'brand'				=> asl_esc_lbl('brand'),
			'special'			=> asl_esc_lbl('special'),
			'region'			=> asl_esc_lbl('region'),
			'category'			=> asl_esc_lbl('category'),
			'within'			=> asl_esc_lbl('within'),
			'country'			=> asl_esc_lbl('country'),
			'state'				=> asl_esc_lbl('state'),
			'in'				=> asl_esc_lbl('in'),
			'desc_title'		=> asl_esc_lbl('desc_title'),
			'add_desc_title'	=> asl_esc_lbl('add_desc_title'),
			'closed'			=> asl_esc_lbl('closed'),
			'opened'			=> asl_esc_lbl('opened'),
			'perform_search'	=> asl_esc_lbl('perform_search')
		);

		$all_configs['words'] 	  = $words;

		//	apply filter to change the locator words, ticket #6933
		$all_configs['words']     = apply_filters( 'asl_filter_locator_words', $words);

		$all_configs['version']   = $this->version;
		$all_configs['days']   	  = array('sun'=> asl_esc_lbl('sun'), 'mon'=> asl_esc_lbl('mon'), 'tue'=> asl_esc_lbl('tue'), 'wed'=> asl_esc_lbl('wed'),'thu'=> asl_esc_lbl('thu'), 'fri'=> asl_esc_lbl('fri'), 'sat'=> asl_esc_lbl('sat'));

		
		//	Additional Attributes
		$filter_ddl   = (isset($all_configs['filter_ddl']) && $all_configs['filter_ddl'])? $all_configs['filter_ddl']: null;

		if($filter_ddl) {
			
			$filter_ddl = explode(',', $filter_ddl);
		}


		//	SHOW/Hide Custom CSS
		$css_code = '';


		//	Code codes for the CSS
		$css_code .= \AgileStoreLocator\Helper::generate_tmpl_css($all_configs['template']);

		//	Hide the direction button
		if($all_configs['direction_btn'] == '0') {
			$css_code .= '.asl-cont .sl-direction, .asl-buttons .directions {display: none !important;}';
		}

		//	Hide the direction button
		if(isset($all_configs['zoom_btn']) && $all_configs['zoom_btn'] == '0') {
			$css_code .= '.asl-buttons .zoomhere {display: none !important;}';
		}

		//	Hide the Print button
		if(isset($all_configs['print_btn']) && $all_configs['print_btn'] == '0') {
			$css_code .= '.asl-p-cont .asl-print-btn,.asl-cont .asl-print-btn {display: none !important;}';
		}


		//	Only show stores when marker is clicked
		if($all_configs['template'] != 'list' && $all_configs['first_load'] == '7') {
			
			$all_configs['first_load'] = '1';
			$css_code .= '.asl-p-cont .sl-item,.asl-cont .sl-item {display: none !important;}.asl-p-cont .sl-item.highlighted,.asl-cont .sl-item.highlighted {display: flex !important;}';
		}


		///////////////////////////////////
		// Is Cache Enabled for Language //
		///////////////////////////////////
		$cache_settings = \AgileStoreLocator\Helper::getSettings('cache');

		//  make it empty array when not saved
    if(!$cache_settings) {
      $cache_settings = [];
    }


    //	When enabled
    if(isset($cache_settings[$lang_code]) && $cache_settings[$lang_code] == '1') {

    	$all_configs['cache'] 		= '1';
    	$all_configs['cache_ver'] = $cache_settings[$lang_code.'-ver'];
    }
    else 
    	$all_configs['cache'] 		= null;


		ob_start();

		$template_file = null;

		switch($all_configs['template']) {

      case '4':

        if($all_configs['color_scheme_3'] < 0 && $all_configs['color_scheme_3'] > 9)
          $all_configs['color_scheme_3'] = 0;


          $template_file = 'template-frontend-4.php';
          break;


      case '3':

        if($all_configs['color_scheme_3'] < 0 && $all_configs['color_scheme_3'] > 9)
          $all_configs['color_scheme_3'] = 0;

          $template_file = 'template-frontend-3.php';
          break;


      case '2':
        if($all_configs['color_scheme_2'] < 0 && $all_configs['color_scheme_2'] > 9)  
          $all_configs['color_scheme_2'] = 0;

          $template_file = 'template-frontend-2.php';
          break;

      case '1':
        if($all_configs['color_scheme_1'] < 0 && $all_configs['color_scheme_1'] > 9)
          $all_configs['color_scheme_1'] = 0;

          $template_file = 'template-frontend-1.php';
          break;

      case 'list':

        if($all_configs['color_scheme'] < 0 && $all_configs['color_scheme'] > 9)
          $all_configs['color_scheme'] = 0;


        $atts['no_script'] = 0;
        $template_file 		 = 'template-frontend-list.php';

          break;


      case 'deal':
        if($all_configs['color_scheme'] < 0 && $all_configs['color_scheme'] > 9)
          $all_configs['color_scheme'] = 0;

          $template_file = 'template-frontend-deal.php';
          break;


      case 'realestate':
      if($all_configs['color_scheme_2'] < 0 && $all_configs['color_scheme_2'] > 9)
        $all_configs['color_scheme_2'] = 0;

        $template_file = 'template-frontend-realestate.php';
        break;


      default:
        
        if($all_configs['color_scheme'] < 0 && $all_configs['color_scheme'] > 9)
          $all_configs['color_scheme'] = 0;

          $template_file = 'template-frontend-0.php';

          break;
    }

		// Customization of Template file
		if($template_file) {

			if ( $theme_file   = locate_template( array ( $template_file ) ) ) {
        $template_path = $theme_file;
      }
      else {
        $template_path = ASL_PLUGIN_PATH.'public/partials/'.$template_file;
      }

      include $template_path;
		}
    
		$sl_output = ob_get_contents();

		ob_end_clean();


		$title_nonce = wp_create_nonce( 'asl_remote_nonce');

		//	Get the template infobox & infobar
    $asl_tmpls = \AgileStoreLocator\Helper::get_template_views($all_configs['template']);

    //	Save the templates
    $this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'asl_tmpls', $asl_tmpls);

    //	Inject the template
		wp_add_inline_script($this->AgileStoreLocator.'-lib', $this->get_local_script_data(), 'before');

		//	Start Localizing again
		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'ASL_REMOTE', array(
	    'ajax_url' => admin_url( 'admin-ajax.php' ),
	    'nonce'    => $title_nonce,
	    'default_lang' 	=> get_locale(),
	    'lang'					=> $lang
		));			

		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'asl_configuration', $all_configs);
		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'asl_categories', $all_categories);
		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'asl_attributes', $all_attributes);
		
		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, 'asl_markers', $all_markers);
		$this->localize_scripts( $this->AgileStoreLocator.$this->script_name, '_asl_map_customize', (($map_customize)? array($map_customize):[]));
			
		//	Inject script with inline_script
		wp_add_inline_script( $this->AgileStoreLocator.$this->script_name, $this->get_local_script_data(), 'before');

		//	For some reason, if the configuration is not loading up
		if(isset($all_configs['load_vars'])) {
			$sl_output = $sl_output.$this->get_local_script_data(true);
		}

		return $sl_output;
	}



  /**
   * [_map_layout Return the JSON for the Map layout]
   * @param  [type] $layout_code [description]
   * @return [type]              [description]
   */
  private function _map_layout($layout_code) {

  	global $wpdb;


  	/// Get the map configuration
		switch($layout_code) {

			//
			case '-1':
				return '[]';
			break;

			//25-blue-water
			case '0':
				return '[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#46bcec"},{visibility:"on"}]}]';
			break;

			//Flat Map
			case '1':
				return '[{"featureType": "poi.business","stylers": [{"visibility": "off"}]},{featureType:"landscape",elementType:"all",stylers:[{visibility:"on"},{color:"#f3f4f4"}]},{featureType:"landscape.man_made",elementType:"geometry",stylers:[{weight:.9},{visibility:"off"}]},{featureType:"poi.park",elementType:"geometry.fill",stylers:[{visibility:"on"},{color:"#83cead"}]},{featureType:"road",elementType:"all",stylers:[{visibility:"on"},{color:"#ffffff"}]},{featureType:"road",elementType:"labels",stylers:[{visibility:"off"}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"on"},{color:"#fee379"}]},{featureType:"road.arterial",elementType:"all",stylers:[{visibility:"on"},{color:"#fee379"}]},{featureType:"water",elementType:"all",stylers:[{visibility:"on"},{color:"#7fc8ed"}]}]';
			break;

			//Icy Blue
			case '2':
				return '[{stylers:[{hue:"#2c3e50"},{saturation:250}]},{featureType:"road",elementType:"geometry",stylers:[{lightness:50},{visibility:"simplified"}]},{featureType:"road",elementType:"labels",stylers:[{visibility:"off"}]}]';
			break;


			//Pale Dawn
			case '3':
				return '[{featureType:"administrative",elementType:"all",stylers:[{visibility:"on"},{lightness:33}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2e5d4"}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#c5dac6"}]},{featureType:"poi.park",elementType:"labels",stylers:[{visibility:"on"},{lightness:20}]},{featureType:"road",elementType:"all",stylers:[{lightness:20}]},{featureType:"road.highway",elementType:"geometry",stylers:[{color:"#c5c6c6"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#e4d7c6"}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#fbfaf7"}]},{featureType:"water",elementType:"all",stylers:[{visibility:"on"},{color:"#acbcc9"}]}]';
			break;


			//cladme
			case '4':
				return '[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#4f595d"},{visibility:"on"}]}]';
			break;


			//light monochrome
			case '5':
				return '[{featureType:"administrative.locality",elementType:"all",stylers:[{hue:"#2c2e33"},{saturation:7},{lightness:19},{visibility:"on"}]},{featureType:"landscape",elementType:"all",stylers:[{hue:"#ffffff"},{saturation:-100},{lightness:100},{visibility:"simplified"}]},{featureType:"poi",elementType:"all",stylers:[{hue:"#ffffff"},{saturation:-100},{lightness:100},{visibility:"off"}]},{featureType:"road",elementType:"geometry",stylers:[{hue:"#bbc0c4"},{saturation:-93},{lightness:31},{visibility:"simplified"}]},{featureType:"road",elementType:"labels",stylers:[{hue:"#bbc0c4"},{saturation:-93},{lightness:31},{visibility:"on"}]},{featureType:"road.arterial",elementType:"labels",stylers:[{hue:"#bbc0c4"},{saturation:-93},{lightness:-2},{visibility:"simplified"}]},{featureType:"road.local",elementType:"geometry",stylers:[{hue:"#e9ebed"},{saturation:-90},{lightness:-8},{visibility:"simplified"}]},{featureType:"transit",elementType:"all",stylers:[{hue:"#e9ebed"},{saturation:10},{lightness:69},{visibility:"on"}]},{featureType:"water",elementType:"all",stylers:[{hue:"#e9ebed"},{saturation:-78},{lightness:67},{visibility:"simplified"}]}]';
			break;
			

			//mostly grayscale
			case '6':
				return '[{featureType:"administrative",elementType:"all",stylers:[{visibility:"on"},{lightness:33}]},{featureType:"administrative",elementType:"labels",stylers:[{saturation:"-100"}]},{featureType:"administrative",elementType:"labels.text",stylers:[{gamma:"0.75"}]},{featureType:"administrative.neighborhood",elementType:"labels.text.fill",stylers:[{lightness:"-37"}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#f9f9f9"}]},{featureType:"landscape.man_made",elementType:"geometry",stylers:[{saturation:"-100"},{lightness:"40"},{visibility:"off"}]},{featureType:"landscape.natural",elementType:"labels.text.fill",stylers:[{saturation:"-100"},{lightness:"-37"}]},{featureType:"landscape.natural",elementType:"labels.text.stroke",stylers:[{saturation:"-100"},{lightness:"100"},{weight:"2"}]},{featureType:"landscape.natural",elementType:"labels.icon",stylers:[{saturation:"-100"}]},{featureType:"poi",elementType:"geometry",stylers:[{saturation:"-100"},{lightness:"80"}]},{featureType:"poi",elementType:"labels",stylers:[{saturation:"-100"},{lightness:"0"}]},{featureType:"poi.attraction",elementType:"geometry",stylers:[{lightness:"-4"},{saturation:"-100"}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#c5dac6"},{visibility:"on"},{saturation:"-95"},{lightness:"62"}]},{featureType:"poi.park",elementType:"labels",stylers:[{visibility:"on"},{lightness:20}]},{featureType:"road",elementType:"all",stylers:[{lightness:20}]},{featureType:"road",elementType:"labels",stylers:[{saturation:"-100"},{gamma:"1.00"}]},{featureType:"road",elementType:"labels.text",stylers:[{gamma:"0.50"}]},{featureType:"road",elementType:"labels.icon",stylers:[{saturation:"-100"},{gamma:"0.50"}]},{featureType:"road.highway",elementType:"geometry",stylers:[{color:"#c5c6c6"},{saturation:"-100"}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{lightness:"-13"}]},{featureType:"road.highway",elementType:"labels.icon",stylers:[{lightness:"0"},{gamma:"1.09"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#e4d7c6"},{saturation:"-100"},{lightness:"47"}]},{featureType:"road.arterial",elementType:"geometry.stroke",stylers:[{lightness:"-12"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{saturation:"-100"}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#fbfaf7"},{lightness:"77"}]},{featureType:"road.local",elementType:"geometry.fill",stylers:[{lightness:"-5"},{saturation:"-100"}]},{featureType:"road.local",elementType:"geometry.stroke",stylers:[{saturation:"-100"},{lightness:"-15"}]},{featureType:"transit.station.airport",elementType:"geometry",stylers:[{lightness:"47"},{saturation:"-100"}]},{featureType:"water",elementType:"all",stylers:[{visibility:"on"},{color:"#acbcc9"}]},{featureType:"water",elementType:"geometry",stylers:[{saturation:"53"}]},{featureType:"water",elementType:"labels.text.fill",stylers:[{lightness:"-42"},{saturation:"17"}]},{featureType:"water",elementType:"labels.text.stroke",stylers:[{lightness:"61"}]}]';
			break;


			//turquoise water
			case '7':
				return '[{stylers:[{hue:"#16a085"},{saturation:0}]},{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{featureType:"road",elementType:"labels",stylers:[{visibility:"off"}]}]';
			break;


			//unsaturated browns
			case '8':
				return '[{elementType:"geometry",stylers:[{hue:"#ff4400"},{saturation:-68},{lightness:-4},{gamma:.72}]},{featureType:"road",elementType:"labels.icon"},{featureType:"landscape.man_made",elementType:"geometry",stylers:[{hue:"#0077ff"},{gamma:3.1}]},{featureType:"water",stylers:[{hue:"#00ccff"},{gamma:.44},{saturation:-33}]},{featureType:"poi.park",stylers:[{hue:"#44ff00"},{saturation:-23}]},{featureType:"water",elementType:"labels.text.fill",stylers:[{hue:"#007fff"},{gamma:.77},{saturation:65},{lightness:99}]},{featureType:"water",elementType:"labels.text.stroke",stylers:[{gamma:.11},{weight:5.6},{saturation:99},{hue:"#0091ff"},{lightness:-86}]},{featureType:"transit.line",elementType:"geometry",stylers:[{lightness:-48},{hue:"#ff5e00"},{gamma:1.2},{saturation:-23}]},{featureType:"transit",elementType:"labels.text.stroke",stylers:[{saturation:-64},{hue:"#ff9100"},{lightness:16},{gamma:.47},{weight:2.7}]}]';
			break;


			case '9':
				
			$custom_map_style = \AgileStoreLocator\Helper::get_setting('map_style', 'map_style');

			if($custom_map_style) {
				return $custom_map_style;
			}

			break;

			//turquoise water
			default:
				return '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}]';
			break;
		}

		return '[]';
  }

  /**
   * [have_matching_address Get all the matching from the description_2 zipcodes]
   * @param  [type] $zip_code [description]
   * @return [type]           [description]
   */
  private function have_matching_address($zip_code) {

  	global $wpdb;

  	$zip_code 			= sanitize_text_field($zip_code);
  	
  	$selected_store = \AgileStoreLocator\Helper::get_store(null, "s.description_2 LIKE '%".$wpdb->esc_like( $zip_code ) ."%'");

  	// When we have a store perform redirection
  	if($selected_store && $selected_store->website) {

  		header('Location:'.$selected_store->website);
  		die;
  	}
  }





  /**
   * [localize_scripts description]
   * @param  [type] $script_name [description]
   * @param  [type] $variable    [description]
   * @param  [type] $data        [description]
   * @return [type]              [description]
   */
  private function localize_scripts($script_name, $variable, $data) {

  	$this->scripts_data[] = [$variable, $data]; 
  }


  /**
   * [get_local_script_data Render the scripts data]
   * @return [type] [description]
   */
  private function get_local_script_data($with_tags = false) {

  	$scripts = '';

  	foreach ($this->scripts_data as $script_data) {
  			
  		$scripts .= 'var '.$script_data[0].' = '.(($script_data[1] && !empty($script_data[1]))?json_encode($script_data[1]): "''").';';
  	}

  	//	With script tags
  	if($with_tags) {

  		$scripts = "<script type='text/javascript' id='agile-store-locator-script-js'>".$scripts."</script>";
  	}

  	//	Clear it
  	$this->scripts_data = [];

  	return $scripts;
  }
}

//  Create the Alias for the ASL-WC
class_alias('\AgileStoreLocator\Frontend\App', 'AgileStoreLocator_Public');