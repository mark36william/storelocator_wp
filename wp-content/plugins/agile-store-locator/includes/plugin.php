<?php

namespace AgileStoreLocator;

use AgileStoreLocator\Loader;
use AgileStoreLocator\Admin\Manager;
use AgileStoreLocator\Frontend\Request;
use AgileStoreLocator\Frontend\App;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://agilelogix.com
 * @since      1.0.0
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 * @author     AgileLogix <support@agilelogix.com>
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      \AgileStoreLocator\Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $AgileStoreLocator    The string used to uniquely identify this plugin.
	 */
	protected $AgileStoreLocator;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->AgileStoreLocator 	= ASL_PLUGIN;
		$this->version 				= ASL_CVERSION;

		$this->load_dependencies();
		$this->set_locale();
		
		//	ADMIN HOOKS
		$this->plugin_admin   = new Manager( $this->get_AgileStoreLocator(), $this->get_version() );

		//	FRONTEND Public
		$this->plugin_public  = new App( $this->get_AgileStoreLocator(), $this->get_version() );

		//	FRONTEND Request
		$this->public_request = new Request();
		
		add_action('wp_ajax_asl_load_stores', array($this->public_request, 'load_stores'));	
		add_action('wp_ajax_nopriv_asl_load_stores', array($this->public_request, 'load_stores'));

		add_action('wp_ajax_asl_reg_store', array($this->public_request, 'register_store'));	
		add_action('wp_ajax_nopriv_asl_reg_store', array($this->public_request, 'register_store'));

		add_action('wp_ajax_asl_search_log', array($this->public_request, 'search_log'));	
		add_action('wp_ajax_nopriv_asl_search_log', array($this->public_request, 'search_log'));	

		add_action('wp_ajax_asl_lead_request', array($this->public_request, 'lead_request'));	
		add_action('wp_ajax_nopriv_asl_lead_request', array($this->public_request, 'lead_request'));

		add_action('wp_ajax_asl_store_ratings', array($this->public_request, 'store_ratings'));	
		add_action('wp_ajax_nopriv_asl_store_ratings', array($this->public_request, 'store_ratings'));

		add_action('wp_ajax_asl_approve_store', array($this->plugin_admin, 'approve_via_email'));	
		add_action('wp_ajax_nopriv_asl_approve_store', array($this->plugin_admin, 'approve_via_email'));


		if (is_admin())
			$this->define_admin_hooks();
		else
			$this->define_public_hooks();


		//	Remove Google Maps scripts
		if(get_option('asl-remove_maps_script') == '1') {

			add_filter('script_loader_tag', array($this, 'removeGoogleMapsTag'), 9999999, 3);
		}

		//	Feeds
		add_action( 'init', array($this, 'add_stores_feed') );

		//	Add the crons
		$this->all_cron_jobs();
	}


	/**
	 * [all_cron_jobs All the cronjob of the Store locator]
	 * @return [type] [description]
	 */
	private function all_cron_jobs() {

		//	Add the Cron job action for import
    $this->loader->add_action( 'asl_import_cron', \AgileStoreLocator\Cron\StoreCron::class, 'execute_the_cron');

		//	Add the cron job action for lead
    $this->loader->add_action( 'asl_lead_cron', \AgileStoreLocator\Cron\LeadCron::class, 'execute_the_cron');
	}

	/**
	 * [add_stores_feed Add the Stores Feed, URL/?feed=stores-feed]
	 */
	public function add_stores_feed() {

		add_feed( 'stores-feed', array($this, 'generateFeeds') );
	}


	/**
	 * Remove all the other google maps scripts
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function removeGoogleMapsTag($tag, $handle, $src)
	{
	
		if(preg_match('/maps\.google/i', $src))
		{
			if($handle != 'asl_google_maps')
				return '';
		}

		return $tag;
	}


	/**
	 * [generateFeeds Generate the Feeds]
	 * @param  string $output [description]
	 * @return [type]         [description]
	 */
	public function generateFeeds($output = 'xml') {

		// Instantiate the class object
		$ASLFeed = \AgileStoreLocator\Schema\Feed::getInstance();

		$ASLFeed->displayStores();die;	
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - \AgileStoreLocator\Loader. Orchestrates the hooks of the plugin.
	 * - \AgileStoreLocator\i18n. Defines internationalization functionality.
	 * - \AgileStoreLocator\Admin. Defines all hooks for the admin area.
	 * - \AgileStoreLocator\Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for autoloading the classes 
		 */
		require_once ASL_PLUGIN_PATH . '/includes/autoloader.php';

		Autoloader::run();

		$this->loader = new Loader();

		//	Register the type
		add_action( 'init', ['\AgileStoreLocator\StoreType',  'init'] );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the \AgileStoreLocator\i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new \AgileStoreLocator\i18n();
		$plugin_i18n->set_domain( $this->get_AgileStoreLocator() );

		//$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		add_action( 'plugins_loaded', array($this, 'load_plugin_textdomain') );
		//$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function load_plugin_textdomain() {

		$domain 				= 'asl_locator';
		$admin_domain 	= 'asl_locator';


		$mo_file  			= WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . get_locale() . '.mo';
		$mo_admin_file  = WP_LANG_DIR . '/' . $admin_domain . '/' . $admin_domain . '-' . get_locale() . '.mo';
		
		//	$mo_file, /wp-content/languages/asl_locator/asl_locator-en_US.mo
		
		//Plugin Frontend
		load_textdomain( $domain, $mo_file ); 
		load_plugin_textdomain( $domain, false, ASL_BASE_PATH . '/languages/');

		//Load the Admin Language File
		/*if (is_admin()) {
			load_textdomain( $admin_domain, $mo_admin_file ); 
			load_plugin_textdomain( $admin_domain, false, ASL_BASE_PATH . '/languages/');
		}*/

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		//	Initialize the Admin Ajax Request handler
		$admin_router = new \AgileStoreLocator\Admin\AjaxHandler();
		
		//	Add all the admin routes
		$admin_router->add_routes();

		//ad menu if u r an admin
		add_action('admin_menu', array($this,'add_admin_menu'));
		
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );

		add_filter( 'plugin_action_links_'.ASL_BASE_PATH.'/agile-store-locator.php', array( $this->plugin_admin, 'add_action_link' ), 10, 2 );  
	}


	/**
	 * [add_admin_menu description]
	 */
	public function add_admin_menu() {
		
		//delete_posts, edit_pages, add_users
		//activate_plugins
		if (current_user_can(ASL_PERMISSION)) {

			$level_mode = \AgileStoreLocator\Helper::expertise_level();
			
			$svg = 'dashicons-location';
			add_Menu_page('Agile Store Locator', esc_attr__('A Store Locator','asl_locator'), ASL_PERMISSION, 'asl-plugin', array($this->plugin_admin,'page_plugin_settings'),$svg);
			add_submenu_page( 'asl-plugin', esc_attr__('Dashboard','asl_locator'), esc_attr__('Dashboard','asl_locator'), ASL_PERMISSION, 'agile-dashboard', array($this->plugin_admin,'page_dashboard'));
			add_submenu_page( 'asl-plugin', esc_attr__('Create New Store','asl_locator'), esc_attr__('Add New Store','asl_locator'), ASL_PERMISSION, 'create-agile-store', array($this->plugin_admin,'page_add_new_store'));
			add_submenu_page( 'asl-plugin', esc_attr__('Manage Stores','asl_locator'), esc_attr__('Manage Stores','asl_locator'), ASL_PERMISSION, 'manage-agile-store', array($this->plugin_admin,'page_manage_store'));
			add_submenu_page( 'asl-plugin', esc_attr__('Manage Categories','asl_locator'), esc_attr__('Manage Categories','asl_locator'), ASL_PERMISSION, 'manage-asl-categories', array($this->plugin_admin,'page_manage_categories'));
			
			if(!$level_mode) {
				
				//  ddl controls
    		$ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();
    		
    		foreach($ddl_controls as $control_key => $control_page) {

    			add_submenu_page( 'asl-plugin', esc_attr__('Manage '.$control_page['plural'],'asl_locator'), esc_attr__('Manage '.$control_page['plural'],'asl_locator'), ASL_PERMISSION, 'manage-asl-'.$control_key, array($this->plugin_admin, 'page_manage_'.$control_key));
    		}
			}

			add_submenu_page( 'asl-plugin', esc_attr__('Manage Markers','asl_locator'), esc_attr__('Manage Markers','asl_locator'), ASL_PERMISSION, 'manage-store-markers', array($this->plugin_admin,'page_store_markers'));
			add_submenu_page( 'asl-plugin', esc_attr__('Manage Logos','asl_locator'), esc_attr__('Manage Logos','asl_locator'), ASL_PERMISSION, 'manage-store-logos', array($this->plugin_admin,'page_store_logos'));
			
			if(!$level_mode) {
				add_submenu_page( 'asl-plugin', esc_attr__('Customize Map','asl_locator'), esc_attr__('Customize Map','asl_locator'), ASL_PERMISSION, 'customize-map', array($this->plugin_admin,'page_customize_map'));
			}
			
			add_submenu_page( 'asl-plugin', esc_attr__('Import/Export Stores','asl_locator'), esc_attr__('Import/Export Stores','asl_locator'), ASL_PERMISSION, 'import-store-list', array($this->plugin_admin,'page_import_stores'));
			add_submenu_page( 'asl-plugin', esc_attr__('ASL Settings','asl_locator'), esc_attr__('ASL Settings','asl_locator'), ASL_PERMISSION, 'asl-settings', array($this->plugin_admin,'page_user_settings'));
			add_submenu_page( 'options-writing.php', esc_attr__('Agile Store Locator UI Customizer','asl_locator'), esc_attr__('Agile Store Locator UI Customizer','asl_locator'), ASL_PERMISSION, 'sl-ui-customizer', array($this->plugin_admin, 'page_ui_customizer'));
			add_submenu_page( 'options-writing.php', esc_attr__('Leads Manager','asl_locator'), esc_attr__('Leads Manager','asl_locator'), ASL_PERMISSION, 'sl-lead-manager', array($this->plugin_admin, 'page_lead_manager'));

			add_submenu_page('asl-plugin-edit', esc_attr__('Edit Store','asl_locator'), esc_attr__('Edit Store','asl_locator'), ASL_PERMISSION, 'edit-agile-store', array($this->plugin_admin,'page_edit_store'));
			remove_submenu_page( "asl-plugin", "asl-plugin" );
			remove_submenu_page( "asl-plugin", "asl-plugin-edit" );
			//edit-agile-store
    }
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'register_styles' );

		//	29-Jan-2022 Registered through Public file
		//$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'register_scripts' );

    add_shortcode( 'ASL_STORELOCATOR', array($this->plugin_public, 'frontendStoreLocator'));	
    add_shortcode( 'ASL_GRID', array($this->plugin_public, 'storeGrid'));
    add_shortcode( 'ASL_SEARCH', array($this->plugin_public, 'searchBox'));
    add_shortcode( 'ASL_STORE', array($this->plugin_public, 'storePage'));
    add_shortcode( 'ASL_FORM', array($this->plugin_public, 'storeForm'));
    add_shortcode( 'ASL_LEADFORM', array($this->plugin_public, 'leadForm'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_AgileStoreLocator() {
		return $this->AgileStoreLocator;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    \AgileStoreLocator\Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
