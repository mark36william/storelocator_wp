<?php

namespace AgileStoreLocator\Model;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
*
* To access the Stores database table
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/elements/Meta
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class Meta {


  /**
   * [get_branch_meta Get all the meta of the store]
   * @param  [type] $store_id [description]
   * @return [type]           [description]
   */
  public static function get_branch_meta($option_value) {

    global $wpdb;

    //  When store is null or empty
    if(!$option_value) {
      return [];
    }

    $prefix   = $wpdb->prefix."asl_";
    $all_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$prefix}stores_meta WHERE option_name = 'p_id' AND option_value = %d", $option_value ));

    return $all_rows;    
  }

  /**
   * [get_schedule_store Get schedule  store]
   * @param  [type] $store_id [description]
   * @return [type]           [description]
   */
  public static function get_schedule_store($store_id) {

    global $wpdb;

    //  When store is null or empty
    if(!$store_id) {
      return [];
    }
    
    $prefix   = $wpdb->prefix."asl_";

    $a_store = $wpdb->get_results("SELECT option_name,option_value FROM {$prefix}stores_meta WHERE store_id = $store_id AND (option_name = 's_date' OR option_name = 'e_date')");

    return $a_store;
  }


}