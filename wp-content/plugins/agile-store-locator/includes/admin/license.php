<?php

namespace AgileStoreLocator\Admin;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}



/**
 * The validation of the purchase code
 *
 * @link       https://agilestorelocator.com
 * @since      4.8.8
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/License
 */


class License {

  
  /**
   * [$server_url description]
   * @var string
   */
  protected $server_url = 'https://wc.agilestorelocator.com/validate/index.php';


  /**
   * [__construct license constructor]
   */
  public function __construct() {

    $this->site_url = preg_replace( '(^https?://)', '', home_url() );
  }



  /**
   * [prepare_request prepare the query for validation]
   * @param  [type] $request_type [purpose of the request]
   * @return [type]               [description]
   */
  public function prepare_request( $request_type ) {

    return add_query_arg(
      urlencode_deep(
        [
          'v-key'  => $this->purchase_key,
          'v-hash' => $this->site_url,
          'type'   => $request_type
        ]
      ),
      $this->server_url
    );
  }


  /**
   * [send_request description]
   * @param  [type] $url [description]
   * @return [type]      [description]
   */
  public function send_request( $url ) {
    
    //  The main response
    $response = new \stdClass();

    $result   = wp_safe_remote_get($url, ['timeout'   => 10, 'sslverify' => false]);

    //  Get request details
    $response->code          = wp_remote_retrieve_response_code( $result );
    $response->code_message  = wp_remote_retrieve_response_message( $result );


    //  No response from server?
    if(!$result || empty($result)) {
      
      $response->error_code = $response->code;
      $response->error      = $response->message;
    }
    //  is a WP error?
    else if (is_wp_error($result)) {

      $response->error_code = $result->get_error_code();
      $response->error      = $result->get_error_message( $response->error_code );
    }

    //  is a valid request?
    if($response->code == 200) {

      $response->result = (array) json_decode(wp_remote_retrieve_body($result));
    }

    return $response;
  }


  /**
   * [supported_info Return the supported until status]
   * @return [type] [description]
   */
  public static function supported_info() {

    $details = get_option('asl-compatible');

    if($details) {

      //  When we have details
      if(is_array($details) && isset($details['supported_until'])) {

        $supported_until = strtotime($details['supported_until']);

        if($supported_until < time()) {
          return '<span class="red">'.esc_attr__('Expired','asl_locator').'</span>';
        }
        else
          return date('d M Y', $supported_until);
      }

      return false;
    }

    return null;
  }

  /**
   * [get_crc get the crc of the code]
   * @return [type] [description]
   */
  public function get_crc($purchase_code) {

    return crc32($purchase_code);
  }

  /**
   * [validate_code Validate the purchase code]
   * @return [type] [description]
   */
  public function validate_code($type = 'activate') {

    error_reporting(0);
    ini_set('display_errors', '0');

    //  Main response
    $response = new \stdClass();

    //  the purchase code
    $this->purchase_key = isset($_REQUEST['value'])? $_REQUEST['value']: null;

    $validate_response  = $this->send_request($this->prepare_request($type));


    if($type == 'activate' && strpos($this->purchase_key, '|')) {

      $codes = explode('|', $this->purchase_key);

      if($codes[1]  == crc32($codes[0])) {

        update_option('asl-compatible', ['purchase-code' => $this->purchase_key]);

        $response->success  = true;
        $response->message  = 'true';

        echo json_encode($response); die;
      }
    }

    
    //  if valid results?
    if(isset($validate_response->result) && is_array($validate_response->result) && !empty($validate_response->result)) {

      if($validate_response->result['success']) {

        update_option('asl-compatible', array('purchase-code' => $this->purchase_key, 'supported_until' => $validate_response->result['supported_until']));

        $response->success = true;
      }

      //  Add the message
      if($validate_response->result['message']) {

        $response->message = $validate_response->result['message'];
      }
    }
    //  when no results
    else if(isset($validate_response->error) && $validate_response->error) {
      
      $response->message = $validate_response->error.', ID:'.crc32($this->purchase_key);
    }
    else {

      $response->message = esc_attr__('Error in validation! Contact us at support@agilelogix.com with purchase code and number: ', 'asl_locator').$this->get_crc($this->purchase_key);
    }

    echo wp_json_encode($response);die;
  }


  /**
   * [refresh_support Re-validate the support license]
   * @return [type] [description]
   */
  public function refresh_support() {

    return $this->validate_code('refresh');
  }


}
