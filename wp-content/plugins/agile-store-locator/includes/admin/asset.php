<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;


/**
 * Handling all the assets to migrate from the previous version
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Asset
 */

class Asset extends Base {

  /**
   * [__construct description]
   */
  public function __construct() {

    parent::__construct();
  }

  /**
   * [backup_logo_icons Backup of Logos]
   * @return [type] [description]
   */
  public function backup_logo_icons() {

    global $wpdb;


    $zip_name = 'store-locator-logo-icons-'.time().'.zip';
    $zip_path = ASL_PLUGIN_PATH.'public/export/'.$zip_name;

    $response  = new \stdclass();
    $response->success = false;


    //  Array for all the Assets
    $all_assets = array();

    ///////////Backup Logo Folder/////////
    $logos     = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."storelogos ORDER BY name");
    
    foreach($logos as $logo) {

      $asset_file   = ASL_UPLOAD_DIR.'Logo/'.$logo->path;
      
      //Check if File Exist

      $all_assets[] = $asset_file;
    }

    ///////////Backup Marker Folder/////////
    $markers   = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."markers");
    
    foreach($markers as $m) {

      $asset_file   = ASL_UPLOAD_DIR.'icon/'.$m->icon;
      
      //Check if File Exist

      $all_assets[] = $asset_file;
    }

    ///////////Backup Logo Folder//////////
    $categories  = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."categories");
    
    foreach($categories as $c) {

      $asset_file   = ASL_UPLOAD_DIR.'svg/'.$c->icon;
      
      //  Check if File Exist
      $all_assets[] = $asset_file;
    }

      //  Created successfull backup
    if(\AgileStoreLocator\Helper::create_zip($all_assets, $zip_path)) {

      $response->success  = true;
      $response->msg      = esc_attr__('Assets Backup Successfully, Download the Zip File.','asl_locator');
      $response->zip      = ASL_URL_PATH.'public/export/'.$zip_name;
    }
    else
      $response->error = esc_attr__('Failed to Create the Backup','asl_locator');

    echo json_encode($response);die;
  }

  /**
   * [import_assets Import Assets such as Logo, Icons, Markers]
   * @return [type] [description]
   */
  public function import_assets() {

    $response = new \stdclass();
    $response->success = false;

    //  Validate Admin?
    if(!current_user_can('administrator')) {

      $response->error = esc_attr__('Please login with Administrator Account.','asl_locator');
      echo json_encode($response);die;
    }

    $target_dir  = ASL_PLUGIN_PATH."public/export/";
    $target_file = 'assets_'.uniqid().'.zip';

    
    //  Move the File to the Import Folder
    if(move_uploaded_file($_FILES["files"]["tmp_name"], $target_dir.$target_file)) {

      $response->success = true;
      
      if(\AgileStoreLocator\Helper::extract_assets($target_dir.$target_file)) {

        $response->msg = esc_attr__('Assets Imported Successfully.','asl_locator');
      }
      else
        $response->msg = esc_attr__('Failed to Imported Assets.','asl_locator');  
    }
    //error
    else {

      $response->error = esc_attr__('Error, file not moved, check permission.','asl_locator');
    }

    echo json_encode($response);die;
  }

  

  /**
   * [migrate_assets Migrate the Assets from the Older versions to the newer]
   * @return [type] [description]
   */
  public function migrate_assets() {

    $is_valid  = \AgileStoreLocator\Helper::migrate_assets();


    $message   = ($is_valid)? esc_attr__('Assets moved successfully.'): esc_attr__('No assets to migrate.');

    echo json_encode(['msg' => $message, 'success' => $is_valid]);die;
  }

}