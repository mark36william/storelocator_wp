<?php

namespace AgileStoreLocator\Admin;



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
 * @subpackage AgileStoreLocator/Admin/Schedule
 */

class Schedule {


  /**
   * [init Initialize & add the action]
   * @return [type] [description]
   */
  public static function init() {
    

    try {
      
       // Add the cron action    
      add_action( 'asl_schedule_store', [Schedule::class, 'run_service']);
      
    } catch (Exception $e) { }
      
    //  testing only
    // Schedule::run_service();
  }


  /**
   * [run_service Run the schedule service every hour to enable and disable the store]
   * @return [type] [description]
   */
  public static function run_service() {

    // dd(121);

    // Enable stores  
    \AgileStoreLocator\Model\Store::stores_to_enable_by_schedule();
    
    // Disable stores 
    \AgileStoreLocator\Model\Store::stores_to_disable_by_schedule();
  }

  /**
   * [schedule_sync_job schedule a cron job to process the XML file]
   * @param  boolean $status [description]
   * @return [type]          [description]
   */
  public static function schedule_stores_job($status = false, $config = []) {

  
    $args = array();
    
    //  Add the cron
    if($status) {

      //  Add it if not added
      if (! wp_next_scheduled ( 'asl_schedule_store', $args )) {
        wp_schedule_event( time(), 'hourly', 'asl_schedule_store', $args );
      }
    }    
    else {

      //  Remove the task
      wp_clear_scheduled_hook('asl_schedule_store');
    }

  }


}
