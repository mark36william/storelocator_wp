<?php

namespace AgileStoreLocator\Vendors\WPBakery;

use AgileStoreLocator\Frontend\App;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Model\Store;
use AgileStoreLocator\Model\Category;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed direptly.
}

class SearchWidget
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
        add_shortcode('asl_search_widget', array($this, 'render_shortcode_search_widget'));
       
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
                "name"              => __('Store Search Widget', 'asl_locator'),
                "description"       => __("Display stores search widget", 'asl_locator'),
                "heading"           => __("Store Search Widget"),
                "class"             => "vc_admin_label",
                "icon"              => ASL_URL_PATH . 'admin/images/asl_grid.png',
                "base"              => 'asl_search_widget',
                "controls"          => "full",
                "category"          => __('content', 'asl_locator'),

                "params"      => array(
                    array(
                        "type"              => "checkbox",
                        "heading"           => __("Display Option"),
                        "param_name"        => "category_control",
                        "admin_label"       => true,
                        "value"             => array(
                            'Show Category' => '1',
                        ), //value
                        "description"       => __("Display setting for stores category."),
                        "std"               => '', // default unchecked
                    ),

                    

                    array(
                        'type'          => 'textfield',
                        "class"         => "",
                        'heading'       => __('Redirect', 'asl_locator'),
                        'param_name'    => 'redirect',
                        "value"         => __("", "asl_locator"),
                        'description'   => __('Paste URL for redirect of your store locator page',  'asl_locator'),
                    ),

                    array(
                          "type"        => "colorpicker",
                          "class"       => "",
                          "heading"     => __( "Background color", "asl_locator" ),
                          "param_name"  => "bg_color",
                          "value"       => '#f5f5f5', //Default Red color
                          "description" => __( "Choose Background color", "asl_locator" )
                    ),

                    array(
                          "type"        => "colorpicker",
                          "class"       => "",
                          "heading"     => __( "Button color", "asl_locator" ),
                          "param_name"  => "btn_color",
                          "value"       => '#0473aa', //Default Red color
                          "description" => __( "Choose Button color", "asl_locator" )
                    ),        
                                       
                  
                  
                   
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
    public function render_shortcode_search_widget($atts, $content = null)
    {
        global $wpdb;

        //  FRONTEND Public 
        $shortcode_attr = array();
        
        extract(shortcode_atts(array(
            'category_control'   =>    ((!empty($atts['category_control']) ) ? $shortcode_attr['category_control'] = 'category_control="'.$atts['category_control'].'"' : $shortcode_attr['category_control'] = 'category_control=0' ),
            'redirect'   => ((!empty($atts['redirect']) ) ? $shortcode_attr['redirect'] = 'redirect="'.$atts['redirect'].'"' : '' ),
            'bg_color'   => ((!empty($atts['bg_color']) ) ? $shortcode_attr['bg_color'] = 'bg-color="'.$atts['bg_color'].'"' : '' ),
            'btn_color'  => ((!empty($atts['btn_color']) ) ? $shortcode_attr['btn_color'] = 'btn-color="'.$atts['btn_color'].'"' : '' ),
        ), $atts));


        $shortcode_attr = implode(' ', $shortcode_attr);
        $shortcode = '[ASL_SEARCH '.$shortcode_attr.' ]';


        echo do_shortcode ($shortcode);
      
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
