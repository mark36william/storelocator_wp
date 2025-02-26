<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


use AgileStoreLocator\Activator;
use AgileStoreLocator\Deactivator;
use AgileStoreLocator\Frontend\Request;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Admin\Store;
use AgileStoreLocator\Admin\Base;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Manager
 * @author     AgileStoreLocator Team <support@agilelogix.com>
 */
class Manager extends Base {


  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $AgileStoreLocator    The ID of this plugin.
   */
  protected $AgileStoreLocator;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of this plugin.
   */
  protected $version;


  /**
   * [$scripts_data load the scripts]
   * @var array
   */
  protected $scripts_data = array();


  /**
   * [$load_config this configuration is loaded on class initialization to perform rewrite and hook validation]
   * @var [type]
   */
  protected $load_config;
  
  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $AgileStoreLocator       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $AgileStoreLocator, $version ) {

    $this->AgileStoreLocator = $AgileStoreLocator;
    $this->version = time();
    $this->version = $version;

    parent::__construct();

    
    //  Not for the activation
    if(!isset($_REQUEST['action']) || $_REQUEST['action'] != 'activate') {

      //  Fetch the basic configs such as rewrites and hook info
      $this->load_config = \AgileStoreLocator\Helper::get_configs(['rewrite_slug', 'rewrite_id', 'cf7_hook']);

      //  Pretty URL for the Store Locator
      add_action('init', array($this,'rewrite_slug') );

      //  Run the scheduling job
      \AgileStoreLocator\Admin\Schedule::init();

    }
    
    // Whitelist the Variable 
    add_filter( 'query_vars', array($this,'rewrite_query_vars'));

    // Shortcode Button (Classic editor)
    add_action('media_buttons', array($this,'add_shortcode_button'), 15); 

    // Generate shortcode popup 
    add_action('admin_head', array($this,'shortcode_gen_popup'));

    // shortcode registration
    add_action('plugins_loaded', array($this,'shortcode_registration'));

    
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in \AgileStoreLocator\Loader as all of the hooks are defined
     * in that particular class.
     *
     * The \AgileStoreLocator\Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->AgileStoreLocator, ASL_URL_PATH . 'admin/css/bootstrap.min.css', array(), $this->version, 'all' );//$this->version
    wp_enqueue_style( 'asl_chosen_plugin', ASL_URL_PATH . 'admin/css/chosen.min.css', array(), $this->version, 'all' );
    wp_enqueue_style( 'asl_locator', ASL_URL_PATH . 'admin/css/style.css', array(), $this->version, 'all' );
        
    //wp_enqueue_style( 'asl_datatable1', 'http://a.localhost.com/gull/src/assets/styles/vendor/datatables.min.css', array(), $this->version, 'all' );
    wp_enqueue_style( 'asl_datatable1', ASL_URL_PATH . 'admin/datatable/media/css/demo_page.css', array(), $this->version, 'all' );
    wp_enqueue_style( 'asl_datatable2', ASL_URL_PATH . 'admin/datatable/media/css/jquery.dataTables.css', array(), $this->version, 'all' );
    wp_enqueue_style( 'asl_datetimepicker', ASL_URL_PATH . 'admin/css/daterangepicker.css', array(), $this->version, 'all' );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    //  store locator bootstrap
    wp_register_script( 'asl-bootstrap', ASL_URL_PATH . 'admin/js/bootstrap.min.js', array('jquery'), $this->version, false );

    //  Store locator libraries
    wp_register_script( $this->AgileStoreLocator.'-lib', ASL_URL_PATH . 'admin/js/libs.min.js', array('jquery'), $this->version, false );    

    //  Shortcode
    wp_register_script( $this->AgileStoreLocator.'-shortcode', ASL_URL_PATH . 'admin/js/shortcode.js', array('jquery'), $this->version, false );    

    //  CHosen library
    wp_register_script( $this->AgileStoreLocator.'-choosen', ASL_URL_PATH . 'admin/js/chosen.proto.min.js', array('jquery'), $this->version, false );
      
    //  Datatable
    wp_register_script( $this->AgileStoreLocator.'-datatable', ASL_URL_PATH . 'admin/datatable/media/js/jquery.dataTables.min.js', array('jquery'), $this->version, false );
      
    //  Uploader
    wp_register_script( $this->AgileStoreLocator.'-upload', ASL_URL_PATH . 'admin/js/jquery.fileupload.min.js', array('jquery'), $this->version, false );

    //  jscript
    wp_register_script( $this->AgileStoreLocator.'-jscript', ASL_URL_PATH . 'admin/js/jscript.js', array('jquery'), $this->version, false );
      
    //  drawing
    wp_register_script( $this->AgileStoreLocator.'-draw', ASL_URL_PATH . 'admin/js/drawing.js', array('jquery'), $this->version, false );

    //  Datetimepicker
    wp_register_script( $this->AgileStoreLocator.'-datetimepicker', ASL_URL_PATH . 'admin/js/datetimepicker.min.js', array('jquery'), $this->version, false );
    wp_register_script( $this->AgileStoreLocator.'-daterangepicker', ASL_URL_PATH . 'admin/js/unminified/daterangepicker.js', array('jquery'), $this->version, false );
    
    //  leads
    wp_register_script( $this->AgileStoreLocator.'-lead', ASL_URL_PATH . 'admin/js/lead.js', array('jquery', $this->AgileStoreLocator.'-lib', $this->AgileStoreLocator.'-datatable', $this->AgileStoreLocator.'-datetimepicker'), $this->version, false );

    //  Dashboard
    wp_register_script( $this->AgileStoreLocator.'-dashboard', ASL_URL_PATH . 'admin/js/dashboard.js', array('jquery', $this->AgileStoreLocator.'-lib', $this->AgileStoreLocator.'-datetimepicker'), $this->version, false );
  }

  /**
   * [_enqueue_scripts a private enqueue scripts]
   * @return [type] [description]
   */
  public function _enqueue_scripts($all_scripts  = true, $tag = 'jscript') {
    
    $langs = array(
      'select_category'   => esc_attr__('Select Some Options','asl_locator'),
      'no_category'       => esc_attr__('Select Some Options','asl_locator'),
      'geocode_fail'      => esc_attr__('Geocode was not Successful:','asl_locator'),
      'upload_fail'       => esc_attr__('Upload Failed! Please try Again.','asl_locator'),
      'delete_category'   => esc_attr__('Delete Category','asl_locator'),
      'delete_categories' => esc_attr__('Delete Categories','asl_locator'),
      'warn_question'     => esc_attr__('Are you sure you want to ','asl_locator'),
      'delete_it'     => esc_attr__('Delete it!','asl_locator'),
      'duplicate_it'  => esc_attr__('Duplicate it!','asl_locator'),
      'backup_tmpl'   => esc_attr__('Backup Template','asl_locator'),
      'backup_tmpl_msg'   => esc_attr__('Backup of templates is not need if you haven\'t customize the template via plugin editor, are you sure to backup Template into theme root directory?','asl_locator'),
      'backup'            => esc_attr__('Backup','asl_locator'),
      'remove_tmpl'       => esc_attr__('Remove Template','asl_locator'),
      'remove_tmpl_msg'   => esc_attr__('Are you sure to remove Template from the theme root directory?','asl_locator'),
      'remove'          => esc_attr__('Remove','asl_locator'),
      'delete_marker'   => esc_attr__('Delete Marker','asl_locator'),
      'delete_markers'  => esc_attr__('Delete Markers','asl_locator'),
      'delete_logo'     => esc_attr__('Delete Logo','asl_locator'),
      'delete_logos'    => esc_attr__('Delete Selected Logos','asl_locator'),
      'select_special'  => esc_attr__('Select Special','asl_locator'),
      'select_brand'  => esc_attr__('Select Brand','asl_locator'),
      'delete_store'  => esc_attr__('Delete Store','asl_locator'),
      'delete_stores'  => esc_attr__('Delete Stores','asl_locator'),
      'duplicate_stores'  => esc_attr__('Duplicate Selected Store','asl_locator'),
      'start_time'        => esc_attr__('Start Time','asl_locator'),
      'select_logo'       => esc_attr__('Select Logo','asl_locator'),
      'use_image'         => esc_attr__('Use Image','asl_locator'),
      'select_marker'     => esc_attr__('Select Marker','asl_locator'),
      'end_time'          => esc_attr__('End Time','asl_locator'),
      'select_country'    => esc_attr__('Select Country','asl_locator'),
      'delete_all_stores' => esc_attr__('DELETE ALL STORES','asl_locator'),
      'truncate_stores'   => esc_attr__('Truncate Stores Table','asl_locator'),
      'truncate_stores_text'  => esc_attr__('Are you sure to delete all stores of all languages?','asl_locator'),
      'invalid_file_error'    => esc_attr__('Invalid File, Accepts JPG, PNG, GIF or SVG.','asl_locator'),
      'error_try_again'       => esc_attr__('Error Occured, Please try Again.','asl_locator'),
      'delete_all'            => esc_attr__('DELETE ALL','asl_locator'),
      'support_title'             => esc_attr__('Enter Purchase Code','asl_locator'),
      'pur_title'             => esc_attr__('PLEASE VALIDATE PURCHASE CODE!','asl_locator'),
      'support_text'          => __('Re-validate your purchase license to see the support status. <a target="_blank" href="https://agilestorelocator.com/wiki/store-locator-purchase-code/">How to Get My Purchase Code?</a>','asl_locator'),
      'pur_text'              => __('Thank you for purchasing <b>Store Locator for WordPress</b> Plugin, kindly enter your purchase code to unlock the page. <a target="_blank" href="https://agilestorelocator.com/wiki/store-locator-purchase-code/">How to Get My Purchase Code?</a>','asl_locator'),
      'api_key_missing'       => __('Error! Search and Map will not work due to missing API Key','asl_locator'),
      'warn_save_setting'     => __('Save Settings to apply the changes','asl_locator')
    );

    wp_enqueue_script( 'asl-bootstrap');
    
    wp_enqueue_script( $this->AgileStoreLocator.'-lib');
    
    //  These scripts are not need on other pages
    if($all_scripts) {
      wp_enqueue_script( $this->AgileStoreLocator.'-datetimepicker2');
      wp_enqueue_script( $this->AgileStoreLocator.'-daterangepicker');
      wp_enqueue_script( $this->AgileStoreLocator.'-choosen');
      wp_enqueue_script( $this->AgileStoreLocator.'-datatable');
      wp_enqueue_script( $this->AgileStoreLocator.'-upload');
    }


    //  Script for the page
    switch ($tag) {
      
      case 'lead':
        
        $tag = 'lead';

        wp_dequeue_script($this->AgileStoreLocator.'-upload');
        wp_enqueue_script( $this->AgileStoreLocator.'-lead');

        break;
      
      case 'dashboard':
        
        $tag = 'dashboard';

        wp_enqueue_script( $this->AgileStoreLocator.'-dashboard');

        break;


      case 'shortcode':
        
        $tag = 'shortcode';
        wp_enqueue_script( $this->AgileStoreLocator.'-shortcode');
      break;
      

      default:
        
        wp_enqueue_script( $this->AgileStoreLocator.'-draw');
        wp_enqueue_script( $this->AgileStoreLocator.'-jscript');

        break;
    }
    
    // Plugin Validation
    $this->localize_scripts( $this->AgileStoreLocator.'-'.$tag, 'ASL_REMOTE',  array('nounce' => wp_create_nonce('asl-nounce'), 'Com' => get_option('asl-compatible'),  'sl_lang' => $this->lang,  'LANG' => $langs, 'URL' => admin_url( 'admin-ajax.php' )));
    
    //  Inject script with inline_script
    wp_add_inline_script( $this->AgileStoreLocator.'-'.$tag, $this->get_local_script_data(), 'before');
  }


  /**
   * [approve_via_email Approve the store via email link]
   * @return [type] [description]
   */
  public function approve_via_email() {

    $store_id    = isset($_REQUEST['sl-store'])? intval($_REQUEST['sl-store']): null;
    $verify_code = isset($_REQUEST['sl-verify'])? sanitize_text_field($_REQUEST['sl-verify']): null;

    return Store::verify_store_link($store_id, $verify_code);
  }



  /////////////////////////
  //////////Page Methods //
  /////////////////////////

  /**
   * [admin_manage_regions Manage Attribute Page]
   * @return [type] [description]
   */
  public function page_manage_regions() {

    // add scripts
    $this->_enqueue_scripts();

    include ASL_PLUGIN_PATH.'admin/partials/attribute_regions.php';
  }

  /**
   * [admin_manage_brands Manage Attribute Page]
   * @return [type] [description]
   */
  public function page_manage_brands() {

    // add scripts
    $this->_enqueue_scripts();

    include ASL_PLUGIN_PATH.'admin/partials/attribute.php';
  }

  /**
   * [admin_manage_specials Manage Attribute Page]
   * @return [type] [description]
   */
  public function page_manage_specials() {

    // add scripts
    $this->_enqueue_scripts();

    include ASL_PLUGIN_PATH.'admin/partials/attribute_special.php';
  }
  
  /**
   * [admin_ui_customizer ASL Settings Page]
   * @return [type] [description]
   */
  public function page_ui_customizer() {
    
    $this->_enqueue_scripts();

    $all_configs = array();

    include ASL_PLUGIN_PATH.'admin/partials/ui-customizer.php';
  } 

  /**
   * [admin_plugin_settings Admin Plugi]
   * @return [type] [description]
   */
  public function page_plugin_settings() {

    // add scripts
    $this->_enqueue_scripts();


    include ASL_PLUGIN_PATH.'admin/partials/add_store.php';
  }

  /**
   * [page_edit_store Edit a Store]
   * @return [type] [description]
   */
  public function page_edit_store() {

    $this->_enqueue_scripts();

    // For Logo
    wp_enqueue_media();

    global $wpdb;
      
    $store_id = isset($_REQUEST['store_id'])? intval($_REQUEST['store_id']): 0;

    if(!$store_id) {

      die('Invalid Store Id.');
    }

    //  Store Data
    $store  = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."stores WHERE id = $store_id");    


    if(!$store || !$store[0]) {
      die('Invalid Store Id');
    }
  
    //  Take the first store    
    $store = $store[0];


    $storecategory = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."stores_categories WHERE store_id = $store_id");

    //  Current store lang
    $lang      = $store->lang;

    $countries  = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."countries ORDER BY `country`");

    $logos     = $wpdb->get_results( "SELECT `id` as `value`, `name` as `text`, `path` as `imageSrc`  FROM ".ASL_PREFIX."storelogos ORDER BY name");
    $markers   = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."markers");
    $category  = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."categories WHERE lang = '$lang'");

    //  Custom Fields
    $fields       = $this->_get_custom_fields();
    $custom_data  = (isset($store->custom) && $store->custom)? json_decode($store->custom, true): []; 


    //  Get the values of the dropdowns
    /*
    $ddl_fields = \AgileStoreLocator\Model\Attribute::get_fields();

    $ddl_fields_values = [];

    //  Loop over the controls
    foreach($ddl_fields as $ddl_field) {

      $ddl_fields_values['store_'.$ddl_field]   = explode(',', $store->$ddl_field);
    }
    */
    $all_configs = \AgileStoreLocator\Helper::get_configs(['api_key', 'time_format', 'branches']);

    include ASL_PLUGIN_PATH.'admin/partials/edit_store.php';    
  }


  /**
   * [admin_add_new_store Add a New Store]
   * @return [type] [description]
   */
  public function page_add_new_store() {
    
    global $wpdb;

    $this->_enqueue_scripts();

    // For Logo
    wp_enqueue_media();


    //api key
    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'api_key' || `key` = 'time_format' || `key` = 'default_lat' || `key` = 'default_lng'";
    $all_configs_result = $wpdb->get_results($sql);


    $all_configs = array();

    foreach($all_configs_result as $c) {
      $all_configs[$c->key] = $c->value;
    }

    //  Current store lang
    $lang       = $this->lang;

    $logos      = $wpdb->get_results( "SELECT `id` as `value`, `name` as `text`, `path` as `imageSrc`  FROM ".ASL_PREFIX."storelogos ORDER BY name");
    $markers    = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."markers");
    $category   = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."categories WHERE lang = '$lang';");
    $countries  = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."countries ORDER BY `country`");

    $fields = $this->_get_custom_fields();
    
    include ASL_PLUGIN_PATH.'admin/partials/add_store.php';    
  }


  /**
   * [admin_dashboard Plugin Dashboard]
   * @return [type] [description]
   */
  public function page_dashboard() {

    $this->_enqueue_scripts(false, 'dashboard');

    
    global $wpdb;

    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'api_key'";
    $all_configs_result = $wpdb->get_results($sql);

    $all_configs = array('api_key' => $all_configs_result[0]->value);
    $all_stats = array();
    
    $temp = $wpdb->get_results( "SELECT count(*) as c FROM ".ASL_PREFIX."markers");;
    $all_stats['markers']  = $temp[0]->c; 

    $temp = $wpdb->get_results( "SELECT count(*) as c FROM ".ASL_PREFIX."stores");;
    $all_stats['stores']    = $temp[0]->c;

  
    $temp = $wpdb->get_results( "SELECT count(*) as c FROM ".ASL_PREFIX."categories");;
    $all_stats['categories'] = $temp[0]->c;

    $temp = $wpdb->get_results( "SELECT count(*) as c FROM ".ASL_PREFIX."stores_view");;
    $all_stats['searches'] = $temp[0]->c;


    include ASL_PLUGIN_PATH.'admin/partials/dashboard.php';    
  }



  /**
   * [admin_manage_categories Manage Categories]
   * @return [type] [description]
   */
  public function page_manage_categories() {

    $this->_enqueue_scripts();

    include ASL_PLUGIN_PATH.'admin/partials/categories.php';
  }

  /**
   * [admin_store_markers Manage Markers]
   * @return [type] [description]
   */
  public function page_store_markers() {
    
    $this->_enqueue_scripts();

    include ASL_PLUGIN_PATH.'admin/partials/markers.php';
  }


  /**
   * [admin_store_logos Manage Logos]
   * @return [type] [description]
   */
  public function page_store_logos() {

    wp_enqueue_script( $this->AgileStoreLocator.'-datatable');
    $this->_enqueue_scripts(false);
    wp_enqueue_media();

    include ASL_PLUGIN_PATH.'admin/partials/logos.php';
  }
  
  /**
   * [admin_manage_store Manage Stores]
   * @return [type] [description]
   */
  public function page_manage_store() {

    global $wpdb;
    $prefix = ASL_PREFIX;

    $this->_enqueue_scripts();


    $pending_stores = Store::pending_store_count();

    // Field Columns
     $field_columns = array(
      '1' => 'Action',
      '2' => 'Scheduled',
      '3' => 'ID',
      '4' => 'Title',
      '5' => 'Lat',
      '6' => 'Lng',
      '7' => 'Street',
      '8' => 'State',
      '9' => 'City',
      '10' => 'Country',
      '11' => 'Phone',
      '12' => 'Email',
      '13' => 'URL',
      '14' => 'Zip',
      '15' => 'Disabled',
      '16' => 'Categories',
      '17' => 'Marker',
      '18' => 'Logo',
      '19' => 'Created'
    );


    $hidden_fields = $wpdb->get_results("SELECT `content` FROM {$prefix}settings WHERE `type` = 'hidden'");


    if($hidden_fields && isset($hidden_fields[0])) {

      $hidden_fields = $hidden_fields[0]->content;
    }
    else
        $hidden_fields = [];


    // Get all config
    $all_configs = \AgileStoreLocator\Helper::get_configs(); 
    
    include ASL_PLUGIN_PATH.'admin/partials/manage_store.php';
  }

  /**
   * [admin_import_stores Admin Import Store Page]
   * @return [type] [description]
   */
  public function page_import_stores() {

    $this->_enqueue_scripts();

    //Check if ziparhive is installed
    global $wpdb;

    //Get the API KEY
    $sql      = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'server_key'";
    $configs_result = $wpdb->get_results($sql);

    $api_key    = '';

    if(isset($configs_result[0]) && $configs_result[0]->value) {
        $api_key = $configs_result[0]->value;
    } 
    else 
      $api_key = esc_attr__('Google API Key is Missing','asl_locator');



    include ASL_PLUGIN_PATH.'admin/partials/import_store.php';
  }


  /**
   * [admin_customize_map Customize the Map Page]
   * @return [type] [description]
   */
  public function page_customize_map() {

    $this->_enqueue_scripts();

    global $wpdb;

    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'api_key' OR `key` = 'default_lat' OR `key` = 'default_lng' ORDER BY id;";
    $all_configs_result = $wpdb->get_results($sql);

    
    $config_list = array();
    foreach($all_configs_result as $item) {
      $config_list[$item->key] = $item->value;
    }

    $all_configs = array('api_key' => $config_list['api_key'],'default_lat' => $config_list['default_lat'],'default_lng' => $config_list['default_lng']);
    

    $map_customize  = $wpdb->get_results("SELECT content FROM ".ASL_PREFIX."settings WHERE type = 'map' AND id = 1");
    $map_customize  = ($map_customize && $map_customize[0]->content)?$map_customize[0]->content:'[]';


    //add_action( 'init', 'my_theme_add_editor_styles' );
    include ASL_PLUGIN_PATH.'admin/partials/customize_map.php';
  }

  /**
   * [addSlugs Add Slug to existing rows]
   */
  private function addSlugs() {

    global $wpdb;

    $ASL_PREFIX = ASL_PREFIX;
    $query      = "SELECT s.`id`, `title`,  `description`, `street`,  `city`,  `state`, `postal_code`, `lat`,`lng`,`phone`,  `fax`,`email`,`website`,`logo_id`,`marker_id`,`description_2`,`open_hours`, `ordr`, `custom`, `slug` FROM {$ASL_PREFIX}stores as s";

    $all_results = $wpdb->get_results($query);
    
    //  Generate Slug
    
    
    foreach ($all_results as $store ) {
      
      $a_store = (Array) $store;

      // Prevent dublication of slug (update function)
      $slug    = \AgileStoreLocator\Schema\Slug::slugify($a_store, null);

      //update into stores table
      $wpdb->update($ASL_PREFIX."stores", array('slug' => $slug),array('id' => $a_store['id']));
    }
  }



  
  /**
   * [admin_user_settings ASL Settings Page]
   * @return [type] [description]
   */
  public function page_user_settings() {
     
    $this->_enqueue_scripts();

     //  Current store lang
    $lang       = $this->lang;

    // CodeMirror Enqueue
    if(function_exists('wp_enqueue_code_editor'))
      wp_enqueue_code_editor(array('type' => 'text/html'));

    global $wpdb;

    //  Get the Cache Settings
    $cache_settings = \AgileStoreLocator\Helper::getSettings('cache');

    //  make it empty array when not saved
    if(!$cache_settings) {
      $cache_settings = [];
    }

    //  Langs
    $active_langs   = \AgileStoreLocator\Helper::getLangControl(true);

    ///////////////////////////////////////
    //  Check the upgrade is done or not? //
    ///////////////////////////////////////

    \AgileStoreLocator\Activator::validate_configs();

    
    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs";
    $all_configs_result = $wpdb->get_results($sql);

    $query = "SELECT `type`,`content` FROM ".ASL_PREFIX."settings";
    $all_setting_result = $wpdb->get_results($query);
    
    $all_configs = array();
    $all_settings = array();

    foreach ($all_setting_result as $key => $value) {
      $all_settings[$value->type] = $value->content;
    }
    

    foreach($all_configs_result as $config)
    {
      $all_configs[$config->key] = $config->value;  
    }

    $all_configs = array_merge($all_configs,$all_settings);

    ///get Countries
    $countries        = $wpdb->get_results("SELECT country,iso_code_2  as code FROM ".ASL_PREFIX."countries");
    $custom_map_style = \AgileStoreLocator\Helper::get_setting('map_style', 'map_style');

    //  Possible values for the slug
    $slug_attr = array('title' => esc_attr__('Title','asl_locator') , 'city' => esc_attr__('City','asl_locator'), 'postal_code' => esc_attr__('Post Code','asl_locator'), 'state' => esc_attr__('State','asl_locator'), 'description' => esc_attr__('Description', 'asl_locator'));


    // Remove Google Script tags
    $all_configs['remove_maps_script'] = get_option('asl-remove_maps_script');

    //  Get the Custom Fields
    $fields = $this->_get_custom_fields();

    //  Add the fields in the slug
    if($fields && is_array($fields)) {

      foreach($fields as $field) {

        $slug_attr[$field['name']] = $field['label'];
      }

      // Sort the array
      ksort($slug_attr);
    }

    include ASL_PLUGIN_PATH.'admin/partials/user_setting.php';
  }


  /**
   * [page_lead_manager Page Lead Manager]
   * @return [type] [description]
   */
  public function page_lead_manager() {

    // add scripts
    $this->_enqueue_scripts(true, 'lead');

    include ASL_PLUGIN_PATH.'admin/partials/manage_leads.php';
  }



  /**
   * [rewrite_slug ASL Settings Page]
   * @return [type] [description]
   *
   * Pretty URL for the Store Locator
   */
  public function rewrite_slug(){

  global $wpdb;

   $slug      = isset($this->load_config['rewrite_slug'])? $this->load_config['rewrite_slug']: null;
   $page_id   = isset($this->load_config['rewrite_id'])? $this->load_config['rewrite_id']: null;

   // Make sure values exist
   if($slug && $page_id)
    add_rewrite_rule('^'.$slug.'/?([^/]*)/?','index.php?page_id='.$page_id.'&sl-store=$matches[1]','top');
  }


  /**
   * [rewrite_query_vars ASL Settings Page]
   * @return [type] [query_vars]
   * 
   * Whitelist the Variable 
   */
  function rewrite_query_vars($query_vars){
      
      $query_vars[] = 'sl-store';

      return $query_vars;
  }

  /*
  *[add_shortcode_button]
  *Create add shortcode button on admin page 
  *
  */
  public function add_shortcode_button() {
    
    global $post;

    if ($post) {
      if($post->post_type == 'page' )
      {
        echo '<a href="#" id="sl-shortcode-insert" data-toggle="smodal" data-target="#insert-sl-shortcode" class="button">'.__('Add Store Locator Shortcode','asl_locator').'</a>';
      }
    }
  }

  /*
  *[shortcode_gen_popup]
  * shortcode Popup HTML
  *
  */
  public function shortcode_gen_popup() {
    
    global $post;

    if ($post) {
      
      if($post->post_type == 'page') {

        // Add scripts
        $this->_enqueue_scripts(false, 'shortcode');
        
        // Include Add Shortcode admin Popup HTML 
        include ASL_PLUGIN_PATH.'admin/partials/shortcode-popup-html.php';
      }
    }
  }


  /**
   * [asl_logo_uploader] Show the Loader for the Logos
   * Get Gallery HTML
   *
   * @since    0.0.1
   */
  public function asl_logo_uploader( $name, $value = '' ) {

    $html = '<div><ul class="asl_logo_mtb">';
    
    /* array with image IDs for hidden field */
    $hidden = array();

    if( $images = get_posts( array(
      'post_type' => 'attachment',
      'orderby'   => 'post__in', /* we have to save the order */
      'order'     => 'ASC',
      'post__in'  => explode(',',$value), /* $value is the image IDs comma separated */
      'numberposts'    => -1,
      'post_mime_type' => 'image'
    ) ) ) {

      foreach( $images as $image ) {
        $hidden[] = $image->ID;
        $image_src = wp_get_attachment_image_src( $image->ID, 'medium' );
        $html .= '<li data-id="' . $image->ID .  '"><img src="'. $image_src[0] . ')"></li>';
      }

    }

    $html .= '</ul><div style="clear:both"></div></div>';
    $html .= '<input type="hidden" id="'.$name.'" name="data['.$name.']" value="' . join(',',$hidden) . '" /><a class="button btn btn-primary asl_upload_logo_btn">'.esc_attr__('Select Image','asl_locator').'</a>';

    return $html;
  }

  /*
  *[shortcode_registration]
  *
  */
  public function shortcode_registration() {
    
    // For Gutenberg Shortcode button
    require_once ASL_PLUGIN_PATH.'admin/blocks/index.php';

    // For Elementor Shortcode
    //  Add the Elementor
    if ( class_exists( '\Elementor\Plugin' ) ) {
      $ele_addons = new \AgileStoreLocator\Vendors\Elementor\Addon( $this->AgileStoreLocator, $this->version );
    }

    // For WPBakery Shortcode
    if(function_exists('vc_map') && defined('WPB_VC_VERSION')) {
      $vc_addons  = new \AgileStoreLocator\Vendors\WPBakery\Addon( $this->AgileStoreLocator, $this->version );
    }

    //  Initialize the third party hooks to create association
    \AgileStoreLocator\Helper::third_party_hooks($this->load_config);
  }


  /**
   * [add_action_link render the settings button for the plugin]
   * @return [type] $links [description]
   */
  public function add_action_link( $links, $file ) {

    if ( $file !=  ASL_BASE_PATH.'/agile-store-locator.php' ) {
      return $links;
    }

    $settings_url = admin_url( 'admin.php?page=asl-settings' );
    $settings_link = '<a href="' . esc_url( $settings_url ) . '" >' . __( 'Settings', 'asl_locator' ) . '</a>';
    array_unshift( $links, $settings_link );    

    $docs_url = 'https://agilestorelocator.com/wiki/';
    $docs_link = '<a href="' . esc_url( $docs_url ) . '" target="__blank">' . __( 'Docs', 'asl_locator' ) . '</a>';
    array_push( $links, $docs_link );

    return $links;

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

    //  With script tags
    if($with_tags) {

      $scripts = "<script type='text/javascript' id='agile-store-locator-script-js'>".$scripts."</script>";
    }

    //  Clear it
    $this->scripts_data = [];

    return $scripts;
  }

}

