<?php

namespace AgileStoreLocator\Admin;

use AgileStoreLocator\Admin\Base;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * The store manager functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Meta
 */

class Meta extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }

  /**
   * [get_schedule_detail Get schedule  store]
   * @return [type]           [description]
   */
  public function get_schedule_detail() {

    global $wpdb;
    $prefix = ASL_PREFIX;

    $response           = new \stdclass();
    $response->success  = false;

    $store_id = isset($_POST['store_id'])? stripslashes_deep($_POST['store_id']): null;


    $a_store = $wpdb->get_results("SELECT store_id,option_name,option_value FROM {$prefix}stores_meta WHERE store_id = $store_id AND (option_name = 's_date' OR option_name = 'e_date')");

    //  
    if (!empty($a_store)) {

      $response->store_schedule = $a_store;
      $response->success        = true;
      
    }

    return $response;
  }

  /**
   * [edit_schedule_store_switch ]
   * @return [type]           [description]
   */
  public function edit_schedule_store_switch() {

      global $wpdb;
      $prefix = ASL_PREFIX;

      $response           = new \stdclass();
      $response->success  = false;

      $store_id = isset($_POST['store_id'])? stripslashes_deep($_POST['store_id']): null;


      //  Get the store 
      $a_store  = $wpdb->get_results("SELECT id,title,is_disabled FROM {$prefix}stores WHERE id = $store_id");

      // dd($asl_schedule_store);  

      if (!empty($a_store)) {

        $response->store_schedule = $a_store;
        $response->success        = true;
      }


      return $response;
    }





}
