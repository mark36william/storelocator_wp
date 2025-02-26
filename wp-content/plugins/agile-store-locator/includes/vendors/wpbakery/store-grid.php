<?php

namespace AgileStoreLocator\Vendors\WPBakery;

use AgileStoreLocator\Frontend\App;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Model\Store;
use AgileStoreLocator\Model\Category;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed direptly.
}

class StoreGrid
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $AgileStoreLocator    The ID of this plugin.
     */
    private $AgileStoreLocator;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


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
        add_shortcode('asl_store_grid', array($this, 'render_shortcode_grid'));
    
        // Create multi dropdown field type
        vc_add_shortcode_param('asl_vc_dropdown', array($this, 'dropdown_multi_settings_field'));

       
    }



    /**
    * [Create multi select field for vc_map() field.]
    * @since 4.8.21 
    * @param array   $field                [description]
    * @param string  $seleted_categories   [description]
    */
     function dropdown_multi_settings_field( $field, $seleted_categories ) {
        
            if ( ! is_array( $seleted_categories ) ) {

                $field_value_arr = explode( ',', $seleted_categories );

            } else {

                $field_value_arr = $seleted_categories;
            }


            // start multi select field for vc_map()
            $multi_select_field  = '';
            $multi_select_field .= '<select multiple name="' . esc_attr( $field['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $field['param_name'] ) . ' ' . esc_attr( $field['type'] ) . '">';

              // Loop over all categories
            foreach ( $field['value'] as $category_name => $category_id ) {

                if ( is_numeric( $category_name ) && ( is_string( $category_id ) || is_numeric( $category_id ) ) ) {
                    $category_name = $category_id;
                }

                $selected = '';

                if ( ! empty( $field_value_arr ) && in_array( $category_id, $field_value_arr ) ) {
                    $selected = ' selected="selected"';
                }

                $multi_select_field .= '<option  value="' . $category_id . '"' . $selected . '>' . $category_name . '</option>';
            }

            $multi_select_field .= '</select>';

            return $multi_select_field;
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

        // All Categories List 
        $all_categories = Category::get_all_categories('asl_vc');
         
        // WPBackery Addon Fields
        vc_map(
            array(
                "name"              => __('Store Grid', 'asl_locator'),
                "description"       => __("Display stores in grid", 'asl_locator'),
                "heading"           => __("Store Grid"),
                "class"             => "vc_admin_label",
                "icon"              => ASL_URL_PATH . 'admin/images/asl_grid.png',
                "base"              => 'asl_store_grid',
                "controls"          => "full",
                "category"          => __('content', 'asl_locator'),

                "params"      => array(
                    // No of Post
                    array(
                        'type'          => 'textfield',
                        "class"         => "",
                        'heading'       => __('Total items', 'asl_locator'),
                        'param_name'    => 'limit',
                        "value"         => __("10", "asl_locator"),
                        'description'   => __('Set max limit for items in grid or empty to display all (limited to 1000).
                    ',  'asl_locator'),
                    ),
                    // Category Filter
                    array(
                        'type'              => 'asl_vc_dropdown',
                        // "class"             => "asl_vc_dropdown",
                        'heading'           => __('Select Categories', 'asl_locator'),
                        'param_name'        => 'category',
                        'value'             => array_unique($all_categories),
                        "std"               => array_unique($all_categories),
                        'description'       => __('Show store state wise', 'asl_locator'),
                    ),
                    array(
                        "type"              => "checkbox",
                        "heading"           => __("Display Option"),
                        "param_name"        => "hide",
                        "admin_label"       => true,
                        "value"             => array(
                            'Hide Address'  => 'address',
                            'Hide Phone'    => 'phone',
                            'Hide Email'    => 'email',
                            'Hide URL Link' => 'url_link',
                        ), //value
                        "std"               => " ",
                        "description"       => __("display setting for stores.")
                    ),
                    // City Filter
                    array(
                        'type'          => 'textfield',
                        "class"         => "",
                        'heading'       => __('City Filter', 'asl_locator'),
                        'param_name'    => 'city',
                        "value"         => __("", "asl_locator"),
                        'description'   => __('Apply the city filter to the grid', 'asl_locator'),
                    ),
                    // State Filter
                    array(
                        'type'          => 'textfield',
                        "class"         => "",
                        'heading'       => __('State Filter', 'asl_locator'),
                        'param_name'    => 'state',
                        "value"         => __("", "asl_locator"),
                        'description'   => __('Apply the state filter to the grid', 'asl_locator'),
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
    public function render_shortcode_grid($atts, $content = null)
    {
        global $wpdb;

        //  FRONTEND Public 
        extract(shortcode_atts(array(
            'limit'         => '',
            'category'      => '',
            'state'         => '',
            'city'          => '',
            'hide'          => ''
        ), $atts)); 

        //  app instance to create a grid
        $app = new App($this->AgileStoreLocator, $this->version);
            
        //  serve a grid
        return $app->storeGrid($atts);
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
