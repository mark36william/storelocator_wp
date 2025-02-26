<?php

namespace AgileStoreLocator\Vendors\Elementor;


use AgileStoreLocator\Vendors\Elementor\Widget;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Agile Store Locator Elementor Addon
 */
class Addon {


    /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $AgileStoreLocator    The ID of this plugin.
   */
    protected $AgileStoreLocator;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of this plugin.
   */
    protected $version;
    
    /**
     * Agile Store Locator Elementor Addon constructor.
     */
    public function __construct() {

        add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );

        add_action( 'elementor/frontend/after_enqueue_styles', array($this, 'asl_ele_editor_style' ) );
    }

    /**
     * Register widget
     */
    public function widgets_registered() {

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new StoreLocator());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new StoreGrid());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new SearchWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new StoreDetail());
    }

    /**
     * Register bootstrap file for elementor Editor view
     */
    public function asl_ele_editor_style() {

        wp_register_style( $this->AgileStoreLocator.'-sl-bootstrap', ASL_URL_PATH.'public/css/sl-bootstrap.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->AgileStoreLocator.'-sl-bootstrap' );

    }
}
