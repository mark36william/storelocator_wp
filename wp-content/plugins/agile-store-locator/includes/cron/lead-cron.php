<?php

namespace AgileStoreLocator\Cron;

/**
*
* Lead cron events handling
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/cron/Lead
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class LeadCron {


  /**
   * [execute_the_cron Execute the lead cron job]
   * @return [type] [description]
   */
  public static function execute_the_cron() {

    \AgileStoreLocator\Model\Lead::send_follow_up_emails();
  }


  /**
   * [schedule_cron Add/Remove the cron job for lead follow up]
   * @param  boolean $status [description]
   * @return [type]          [description]
   */
  public static function schedule_cron($status = false, $config = []) {

    $args = array();
    
    //  Add the cron
    if($status) {

      //  Add it if not added
      if (! wp_next_scheduled ( 'asl_lead_cron', $args )) {
        wp_schedule_event( time(), 'hourly', 'asl_lead_cron', $args );
      }
    }    
    else {

      //  Remove the cron 
      wp_clear_scheduled_hook('asl_lead_cron');
    }
    
  }
}

