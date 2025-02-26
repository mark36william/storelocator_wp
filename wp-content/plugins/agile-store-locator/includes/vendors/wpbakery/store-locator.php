<?php

namespace AgileStoreLocator\Vendors\WPBakery;

use AgileStoreLocator\Frontend\App;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Model\Store;
use AgileStoreLocator\Model\Category;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed direptly.
}

class StoreLocator
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
        add_shortcode('asl_store_locator', array($this, 'render_shortcode_store_locator'));
       
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
                "name"              => __('Store Locator Widget', 'asl_locator'),
                "description"       => __("Display store locator widget", 'asl_locator'),
                "heading"           => __("Store Locator"),
                "class"             => "vc_admin_label",
                "icon"              => ASL_URL_PATH . 'admin/images/asl_grid.png',
                "base"              => 'asl_store_locator',
                "controls"          => "full",
                "category"          => __('content', 'asl_locator'),

                "params"      => array(

                    array(
                        "type"          => 'dropdown',
                        "heading"       => __( 'Select Template', 'asl_locator' ),
                        "param_name"    => 'template',
                        "value"         => array(
                           __('Template 0','asl_locator')     => '0',
                           __('Template 1','asl_locator')     => '1',
                           __('Template 2','asl_locator')     => '2',
                           __('Template 3','asl_locator')     => '3',
                           __('Template 4','asl_locator')     => '4',
                           __('Template List','asl_locator')  => 'list',
                        ),
                        "std"           => '', 
                    ),

                    array(
                        "type"          => 'dropdown',
                        "heading"       => __( 'Search Type', 'asl_locator' ),
                        "param_name"    => 'search_type',
                        "value"         => array(
                           __('Search By Address (Google)','asl_locator')                     => '0',
                           __('Search By Store Name (Database)','asl_locator')                => '1',
                           __('Search By Stores Cities, States (Database)','asl_locator')     => '2',
                           __('Geocoding on Enter key (Google Geocoding API)','asl_locator')  => '3',
                        ),
                        "std"           => '', 
                    ),

                    array(
                        "type"          => 'dropdown',
                        "heading"       => __( 'Select Layout', 'asl_locator' ),
                        "param_name"    => 'layout',
                        "value"         => array(
                           __('List Format','asl_locator')                            => '0',
                           __('Accordion (States, Cities, Countries)','asl_locator')  => '1',
                           __('Accordion (Categories)','asl_locator')                 => '2',
                        ),
                        "std"           => '', 
                    ),

                    array(
                        "type"          => 'dropdown',
                        "heading"       => __( 'Distance Control', 'asl_locator' ),
                        "param_name"    => 'distance_control',
                        "value"         => array(
                           __('Slider','asl_locator')       => '0',
                           __('Dropdown','asl_locator')     => '1',
                           __('Boundary Box','asl_locator') => '2',
                        ),
                        "std"           => '', 
                    ),

                    array(
                        "type"          => 'textfield',
                        "heading"       => __( 'Head Title', 'asl_locator' ),
                        "param_name"    => 'head_title',
                        "value"         => __( 'No. of Stores', 'asl_locator' ),
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
    public function render_shortcode_store_locator($atts, $content = null)
    {
        global $wpdb;

       //  //  FRONTEND Public 
        $shortcode_attr = array();
        
       extract(shortcode_atts(
        array(
            'template'          => ((!empty($atts['template']) || $atts['template'] == '0') ? $shortcode_attr['template'] = 'template="'.$atts['template'].'"' : '' ),
            'search_type'       => ((!empty($atts['search_type']) || $atts['search_type'] == '0') ? $shortcode_attr['search_type'] = 'search_type="'.$atts['search_type'].'"' : '' ),
            'layout'            => ((!empty($atts['layout'])  || $atts['layout'] == '0') ? $shortcode_attr['layout'] = 'layout="'.$atts['layout'].'"' : '' ),
            'distance_control'  =>  ((!empty($atts['distance_control']) || $atts['distance_control'] == '0') ? $shortcode_attr['distance_control'] = 'distance_control="'.$atts['distance_control'].'"' : '' ),
            'head_title'        => ''
        ), 
        $atts)); 


        $shortcode_attr = implode(' ', $shortcode_attr);
        $shortcode = '[ASL_STORELOCATOR  '.$shortcode_attr.']';


        echo'<div class="elementor-shortcode asl-free-addon">';
        echo do_shortcode($shortcode);
        echo'</div>';

      
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
