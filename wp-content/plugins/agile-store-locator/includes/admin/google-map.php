<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The map manager functionality of the admin
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/GoogleMap
 */

class GoogleMap extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }

  
  /**
   * [save_custom_map save customize map]
   * @return [type] [description]
   */
  public function save_custom_map() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;


    //Check for asl-p-cont infbox html
    if(isset($_POST['data_map'])) {

      $data_map = stripslashes_deep($_POST['data_map']);

        $wpdb->update(ASL_PREFIX."settings",
        array('content' => stripslashes($data_map)),
        array('id' => 1,'type'=> 'map'));

      $response->msg     = esc_attr__("Map has been updated successfully.",'asl_locator');
      $response->success = true;
    }
    else
      $response->error   = esc_attr__("Error Occured saving Map.",'asl_locator');

        
    echo json_encode($response);die;  
  }

  
  /**
   * [kml_file_filter Allow the KML file]
   * @param  [type] $mimes [description]
   * @return [type]        [description]
   */
  public function kml_file_filter( $mimes ) {
 
    // New allowed mime types.
    $mimes['kmz']  = 'application/vnd.google-earth.kmz';
    $mimes['kml']  = 'application/vnd.google-earth.kml+xml';
    
    return $mimes;
  }

  /**
   * [upload_kml_file Upload s new KML File]
   * @return [type] [description]
   */
  public function upload_kml_file() {

    //  Only for the administrator
    if(current_user_can('administrator') ) {
      
      //  Temporarily define to pass the KML file
      if (!defined('ALLOW_UNFILTERED_UPLOADS'))
      define( 'ALLOW_UNFILTERED_UPLOADS', true );
    }
    
    //  All the KML file
    add_filter( 'upload_mimes', array($this, 'kml_file_filter') );

    //  Upload the KML File
    $kml_upload  = $this->_file_uploader($_FILES["files"], 'kml');

    //  When the file is uploaded successfully
    if(isset($kml_upload['success']) && $kml_upload['success']) {

      echo json_encode(['msg' => esc_attr__("KML File uploaded successfully.",'asl_locator'), 'success' => true]);
    }
    else
      echo json_encode(['error' => $kml_upload['error']]);
    
    die;
  }


   /**
   * [remove_kml_file Delete the KML file]
   * @return [type] [description]
   */
  public function remove_kml_file() {

    $file_name  = sanitize_text_field($_REQUEST['data_']);
    $response   = \AgileStoreLocator\Helper::removeFile($file_name, ASL_UPLOAD_DIR.'kml/');

    echo json_encode($response);die;
  }
  
  
}