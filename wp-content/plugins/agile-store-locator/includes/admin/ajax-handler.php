<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
*
* AjaxHandler Responsible for handling all the AJAX Requests for the admin calls
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/Admin/AjaxHandler
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class AjaxHandler {

  /**
   * [__construct Register the main route to handle AJAX]
   */
  public function __construct() {

    //  Admin request asl ajax handler for all requests
    add_action('wp_ajax_asl_ajax_handler', [$this, 'handle_request']); 
  }

  /**
   * [$ajax_actions All the AJAX actions]
   * @var array
   */
  private $ajax_actions = [];


  /**
   * [add_routes Add all the admin routes that handle the AJAX]
   */
  public function add_routes() {

    /*For Stores*/
    $this->register_route('update_store', 'Store' ,'update_store');
    $this->register_route('add_store', 'Store', 'add_new_store');  
    $this->register_route('delete_all_stores', 'Store', 'admin_delete_all_stores');  

    $this->register_route('get_store_list', 'Store', 'get_store_list');  
    
    $this->register_route('delete_store', 'Store', 'delete_store');  
    $this->register_route('duplicate_store', 'Store', 'duplicate_store');  
    $this->register_route('remove_duplicates', 'Store', 'remove_duplicates');  
    $this->register_route('validate_coords', 'Store', 'validate_coordinates'); 
    $this->register_route('store_status', 'Store', 'store_status');  
    $this->register_route('approve_stores', 'Store', 'approve_stores');

    $this->register_route('schedule_the_store', 'Store', 'schedule_the_store');
    

    /*Categories*/
    $this->register_route('add_categories', 'Category', 'add_category');
    $this->register_route('delete_category', 'Category', 'delete_category');
    $this->register_route('update_category', 'Category', 'update_category');
    $this->register_route('get_category_byid', 'Category', 'get_category_by_id');
    $this->register_route('get_categories', 'Category', 'get_categories');  
    

    /*Attributes*/
    $this->register_route('add_attribute', 'Attribute', 'add_attribute');
    $this->register_route('delete_attribute', 'Attribute', 'delete_attribute');
    $this->register_route('update_attribute', 'Attribute', 'update_attribute');
    $this->register_route('get_attributes', 'Attribute', 'get_attributes');  

    /*Markers*/
    $this->register_route('add_markers', 'Marker', 'add_marker');
    $this->register_route('delete_marker', 'Marker', 'delete_marker');
    $this->register_route('update_marker', 'Marker', 'update_marker');
    $this->register_route('get_marker_byid', 'Marker', 'get_marker_by_id');
    $this->register_route('get_markers', 'Marker', 'get_markers');  

    /*Logo*/
    $this->register_route('get_logos', 'Logo', 'get_logos');  
    $this->register_route('get_logo_byid', 'Logo', 'get_logo_by_id');
    $this->register_route('update_logo', 'Logo', 'update_logo');
    $this->register_route('delete_logo', 'Logo', 'delete_logo');
    $this->register_route('upload_logo', 'Logo', 'upload_logo');

    /*Import and settings*/
    $this->register_route('import_store', 'ImportExport', 'import_store');  
    $this->register_route('delete_import_file', 'ImportExport', 'delete_import_file');  
    $this->register_route('upload_store_import_file', 'ImportExport', 'upload_store_import_file');
    $this->register_route('export_file', 'ImportExport', 'export_store');
    $this->register_route('fill_missing_coords', 'ImportExport', 'fill_missing_coords');
    $this->register_route('validate_api_key', 'ImportExport', 'validate_api_key');   
    $this->register_route('validate_me', 'License', 'validate_code');

    //  Assets
    $this->register_route('backup_assets', 'Asset', 'backup_logo_icons');   
    $this->register_route('import_assets', 'Asset', 'import_assets');
    $this->register_route('migrate_assets', 'Asset', 'migrate_assets');

    //  Settings
    $this->register_route('save_setting', 'Setting', 'save_setting');
    $this->register_route('load_custom_template', 'Setting', 'load_custom_template');
    $this->register_route('save_custom_template', 'Setting', 'save_custom_template');
    $this->register_route('reset_custom_template', 'Setting', 'reset_custom_template');
    $this->register_route('refresh_support_license', 'License', 'refresh_support');

    $this->register_route('save_custom_fields', 'Setting', 'save_custom_fields'); 
    $this->register_route('backup_tmpl', 'Setting', 'backup_template');
    $this->register_route('remove_tmpl', 'Setting', 'remove_template');
    $this->register_route('cache_status', 'Setting', 'manage_cache');
    $this->register_route('load_ui_settings', 'Setting', 'load_ui_settings');
    $this->register_route('save_ui_settings', 'Setting', 'save_ui_settings');
    $this->register_route('expertise_level', 'Setting', 'expertise_level');

    $this->register_route('get_stats', 'Analytics', 'get_stats');
    $this->register_route('export_stats', 'Analytics', 'export_analytics');
    $this->register_route('change_options', 'Setting', 'change_options');

    //  KML files
    $this->register_route('add_kml', 'GoogleMap', 'upload_kml_file');
    $this->register_route('delete_kml', 'GoogleMap', 'remove_kml_file');
    $this->register_route('save_custom_map', 'GoogleMap', 'save_custom_map');

    /*Leads*/
    $this->register_route('export_leads', 'Lead', 'export_leads');
    $this->register_route('export_dealers', 'Lead', 'export_dealers');
    $this->register_route('delete_lead', 'Lead', 'delete_lead');
    $this->register_route('update_lead', 'Lead', 'update_lead');
    $this->register_route('get_lead_byid', 'Lead', 'get_lead_by_id');
    $this->register_route('get_leads', 'Lead', 'get_leads');  

    // Slugs

    $this->register_route('reset_all_slugs', 'Setting', 'reset_all_slugs');  

    // store branch
    $this->register_route('add_store_into_branch', 'Branch', 'add_store_into_branch');  
    $this->register_route('get_store_list_edit', 'Branch', 'get_store_list_edit');  

     // label
    $this->register_route('set_label', 'Label', 'set_label');  
    
    // Store Schedule
    $this->register_route('get_schedule_detail', 'Meta', 'get_schedule_detail');
    $this->register_route('edit_schedule_store_switch', 'Meta', 'edit_schedule_store_switch');  
  }

  /**
   * [register_route Register the AJAX calls for the plugin]
   * @param  [type] $handle        [description]
   * @param  [type] $context_class [description]
   * @param  [type] $action        [description]
   * @return [type]                [description]
   */
  public function register_route($handle, $context_class, $action) {

    $this->ajax_actions[$handle] = [$context_class, $action];
  }


  /**
   * [handle_request Handle the AJAX Request]
   * @return [type] [description]
   */
  public function handle_request() {

    //  sl-action
    $route = isset($_REQUEST['sl-action'])? $_REQUEST['sl-action']: ''; 

    //  nouce validation for CSRF
    if(!wp_verify_nonce($_REQUEST['asl-nounce'], 'asl-nounce')) {

      return $this->json_response(['nouce' => $_REQUEST['asl-nounce'], 'error' => esc_attr__('Error! request verification fail.','asl_locator')]);
    }


    //  validate the route
    if(isset($this->ajax_actions[$route])) {

      $sl_request = $this->ajax_actions[$route];
      

      $class_name = '\\'.__NAMESPACE__ . '\\' .$sl_request[0];
      $class_inst = new $class_name;
      
      //  is callable method?
      if(!is_callable([$class_inst, $sl_request[1]])) {
        return $this->json_response(['error' => esc_attr__('Error! method not exist!','asl_locator')]);
      }

      //  Result of the execution
      $results  = null;

      try {
          
        //  Execute the method
        $results = call_user_func([$class_inst, $sl_request[1]]);

      } 
      //  Caught in exception
      catch (\Exception $e) {
          
        $results = ['msg' => $e->getMessage()];
      }

      $this->json_response($results);
    }

    //  route not found
    $this->json_response(['error' => esc_attr__('Error! route not found.','asl_locator')]);
  }


  /**
   * [json_response Send the $data as JSON]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function json_response($data) {

    echo wp_json_encode($data);
    die;
  }
}

