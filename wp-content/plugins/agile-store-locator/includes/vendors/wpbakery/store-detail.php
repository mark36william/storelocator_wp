<?php

namespace AgileStoreLocator\Vendors\WPBakery;

use AgileStoreLocator\Frontend\App;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Model\Store;
use AgileStoreLocator\Model\Category;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed direptly.
}

class StoreDetail
{


   
    /**
     * Initialize the class and set its properties.
     *
     * @since      4.8.21
     */
    
    public function __construct()
    {

          // We safely integrate with VC 
        $this->integrate_with_vc();

        // Render shortcode hook
        add_shortcode('asl_store_detail', array($this, 'render_shortcode_store_detail'));
       
    }


    /**
    * [Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.]
    * @since 4.8.21 
    */
    public function integrate_with_vc()
    {
        // Check if Visual Composer is installed
        if (!defined('WPB_VC_VERSION')) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array($this, 'show_vc_version_notice'));
            return;
        }

      
        // WPBackery Addon Fields
        vc_map(
            array(
                "name"              => __('Store Detail', 'asl_locator'),
                "description"       => __("Display store detail", 'asl_locator'),
                "heading"           => __("Store Detail"),
                "class"             => "vc_admin_label",
                "icon"              => ASL_URL_PATH . 'admin/images/asl_grid.png',
                "base"              => 'asl_store_detail',
                "controls"          => "full",
                "category"          => __('content', 'asl_locator'),

                "params"      => array(
                  


                    array(
                          "type" => "textfield",
                          "holder" => "div",
                          "class" => "",
                          "heading" => __( "Store ID", "asl_locator" ),
                          "param_name" => "asl_store_id",
                          "value" => __( "", "asl_locator" ),
                          "description" => __( "Set store id", "asl_locator" )
                    ),

                    array(
                        "type"          => 'dropdown',
                        "heading"       => __( 'Display Option', 'asl_locator' ),
                        "param_name"    => 'field',
                        "value"         => array(
                          __( 'Select', 'asl_locator' )      => '',
                          __( 'Title', 'asl_locator' )      => 'title',
                          __( 'Address', 'asl_locator' )    => 'address',
                          __( 'City', 'asl_locator' )       => 'city',
                          __( 'State', 'asl_locator' )      => 'state',
                          __( 'Country', 'asl_locator' )    => 'country',
                          __( 'Open Hours', 'asl_locator' ) => 'open_hours',
                          __( 'Latitude', 'asl_locator' )   => 'lat',
                          __( 'longitude', 'asl_locator' )  => 'lng',
                        ),
                        "description"   => __( 'Select display option', 'asl_locator' ),
                    )

                         
                                       
                  
                  
                   
                )
            )
        );


    }

    /*
    
    */
       
    /**
    * [Shortcode render vc_map]
    * @since 4.8.21 
    * @param [type] $atts                [Get filter data from vc_map]
    * @param [type] $content             [description]
    */
    public function render_shortcode_store_detail($atts, $content = null)
    {
        global $wpdb;

        //  FRONTEND Public 
        $shortcode_attr = array();
        
        
       extract(shortcode_atts(
        array(
            'asl_store_id'  => ((!empty($atts['asl_store_id']) ) ? $shortcode_attr['asl_store_id'] = 'sl-store="'.$atts['asl_store_id'].'"' : '' ),
            'field'         => ((!empty($atts['field']) ) ? $shortcode_attr['field'] = 'field="'.$atts['field'].'"' : '' )
        ), 
        $atts)); 


        $shortcode_attr = implode(' ', $shortcode_attr);
        // echo "<pre>";
        // print_r($shortcode_attr);
        // die();

        $shortcode = '[ASL_STORE  '.$shortcode_attr.']';


        echo do_shortcode($shortcode);
      
    }


    /**
    * [Show notice if your plugin is activated]
    * @since 4.8.21 
    */
    public function show_vc_version_notice()
    {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']) . '</p>
        </div>';
    }
}
