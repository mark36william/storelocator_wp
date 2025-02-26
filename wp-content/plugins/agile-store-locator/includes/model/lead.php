<?php

namespace AgileStoreLocator\Model;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
*
* Lead Object
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/elements/lead
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class Lead {

  /**
   * [get_follow_up_leads Get all those leads that needs a follow up]
   * @return [type] [description]
   */
  public static function get_follow_up_leads() {

    global $wpdb;

    $follow_ups = $wpdb->get_results('SELECT * FROM `'.ASL_PREFIX.'leads` WHERE ((created_on + INTERVAL 1 HOUR) < NOW()) AND follow_up = 1');

    return $follow_ups;
  }

  /**
   * [send_follow_up_emails send the follow up emails to the store owners]
   * @return [type] [description]
   */
  public static function send_follow_up_emails() {

    global $wpdb;

    $leads = self::get_follow_up_leads();

    //  Get the mail configs
    $mail_configs  = \AgileStoreLocator\Helper::get_configs(['admin_notify', 'notify_email', 'lead_follow_up']);

    //  Loop over and send emails
    foreach ($leads as $lead) {
      
      //  Get the closest store
      $closest_store = \AgileStoreLocator\Helper::get_store($lead->store_id);

      
      //  when notification is enabled
      if($mail_configs['admin_notify']) {

        $subject  = esc_attr__("Store Locator Lead Follow-up",'asl_locator');


        $full_name    = $lead->name;
        $user_phone   = $lead->phone;
        $msg_text     = $lead->message;
        $user_email   = $lead->email;
        $postal_code  = $lead->postal_code;

        $content  = '<p>'.esc_attr__('This is a follow-up email for the store: ', 'asl_locator').(($closest_store)? $closest_store->title:''). '</p><br>'.
                    '<p>'.esc_attr__('Full Name: ','asl_locator'). $full_name.'</p>'.
                    '<p>'.esc_attr__('Email Address: ','asl_locator').'<a href="mailto:'.$user_email.'">'.$user_email.'</a>'.'</p>'.
                    '<p>'.esc_attr__('Phone: ','asl_locator').'<a href="tel:'.$user_phone.'">'.$user_phone.'</a>'.'</p>'.
                    '<p>'.esc_attr__('Postal Code: ','asl_locator'). $postal_code.'</p>';

        //  When we have message text
        if($msg_text) {
          $content  .= '<p>'.esc_attr__('Message: ','asl_locator'). $msg_text.'</p>';
        }


        // CC headers
        $headers  = [];

        //  main email sent to the email
        $to_email = ($closest_store && $closest_store->email)? $closest_store->email: $mail_configs['notify_email'];


        //  Add CC to the admin email when we have a two recievers
        if($mail_configs['notify_email'] && $to_email != $mail_configs['notify_email']) {
          $headers[] = 'Cc: '.$mail_configs['notify_email'];
        }

        //  Send a email notification
        \AgileStoreLocator\Helper::send_email($to_email, $subject, $content, $headers);
      }

      //  Mark as follow-up completed
      $wpdb->update(ASL_PREFIX.'leads', array('follow_up' => 0), array('id' => $lead->id));
    }
  }
}

