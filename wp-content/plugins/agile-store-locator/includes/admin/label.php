<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The functionality to save the labels
 *
 * @link       https://agilestorelocator.com
 * @since      4.8.28
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Label
 */

class Label extends Base {

    /**
     * [__construct description]
     */  
    public function __construct() {

        parent::__construct();
        
    }

    /**
    * [set_label GET List of Stores]
    * @return [type] [description]
    */
    public function set_label() {

        global $wpdb;
        
        $prefix       = $wpdb->prefix."asl_";
        
        $response  = new \stdclass();
        $response->success = false;


        //  Settings data
        $key     = $this->clean_input($_POST['_key']);
        $value   = $this->clean_input($_POST['value']);

        // Check if key exist?
        $get_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".ASL_PREFIX."configs WHERE `type` = %s  AND `key` = %s", 'label' , $key ));

        if (empty($get_row)) {

            $wpdb->insert(ASL_PREFIX.'configs', 
                     array(
                       'key'    => $key,
                       'value' => $value,
                       'type' => 'label'
                     ),
                     array('%s','%s','%s'));  
          
        } 
        else{

            $wpdb->update( ASL_PREFIX.'configs', array('value' => $value),array('key'=> $key ));
        
        }


        $response->msg     = esc_attr__("label has been updated",'asl_locator');
        $response->success = true;

        echo json_encode($response);die;
    }
}